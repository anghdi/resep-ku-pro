<?php

namespace App\Livewire\Pages;

use App\Models\Ingredients;
use App\Models\RecipeIngredient;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Category;
use App\Models\Outlets;
use App\Models\Recipes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class AddNewMenu extends Component
{
    use WithFileUploads;

    // Properti Section 1 (Create)
    public $menu_name, $category, $outlet, $selling_price = 0, $food_photo, $method;

    // Properti Section 2 (Manage)
    public $filter_outlet, $selected_recipe_id;
    public $edit_name, $edit_category, $edit_outlet, $update_photo, $edit_image_url;
    public $selected_ingredient, $amount_needed;
    public $edit_method;

    /**
     * Otomatis memuat data saat resep dipilih di dropdown
     */
    public function updatedSelectedRecipeId($id)
    {
        $recipe = Recipes::find($id);
        if ($recipe) {
            $this->edit_name = $recipe->name;
            $this->edit_category = $recipe->category;
            $this->edit_outlet = $recipe->outlet;
            $this->edit_image_url = $recipe->image_url;
            $this->edit_method = $recipe->method; // Load instruksi dari DB
        }
    }

    public function saveRecipe()
    {
        $this->validate([
            'menu_name' => 'required|min:3',
            'selling_price' => 'required|numeric',
            'category' => 'required',
            'outlet' => 'required',
        ]);

        try {
            $path = $this->food_photo ? $this->food_photo->store('menu-photos', 'public') : null;

            Recipes::create([
                'name' => $this->menu_name,
                'selling_price' => $this->selling_price,
                'category' => $this->category,
                'outlet' => $this->outlet,
                'image_url' => $path,
                'user_id' => auth()->id(),
                'org_id' => auth()->user()->org_id,
            ]);

            session()->flash('success', 'Recipe created successfully!');
            $this->reset(['menu_name', 'category', 'outlet', 'food_photo', 'selling_price']);
        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong while saving.');
        }
    }

    public function addIngredient()
    {
        $this->validate(['selected_ingredient' => 'required', 'amount_needed' => 'required|numeric']);

        RecipeIngredient::create([
            'recipe_id' => $this->selected_recipe_id,
            'ingredient_id' => $this->selected_ingredient,
            'amount_needed' => $this->amount_needed,
        ]);

        $this->reset(['amount_needed', 'selected_ingredient']);
    }

    public function render()
    {
        return view('livewire.pages.add-new-menu', [
            'categories' => Category::where('org_id', auth()->user()->org_id)->get(),
            'outlets' => Outlets::where('org_id', auth()->user()->org_id)->get(),
            'all_ingredients' => Ingredients::where('org_id', auth()->user()->org_id)->get(),
            'recipes' => Recipes::where('org_id', auth()->user()->org_id)
                        ->when($this->filter_outlet, fn($q) => $q->where('outlet', $this->filter_outlet))
                        ->get(),
            'recipe_ingredients' => RecipeIngredient::where('recipe_id', $this->selected_recipe_id)->with('ingredient')->get()
        ])->layout('layouts.app');
    }

    public function updateInfo()
    {
        $this->validate([
            'edit_name' => 'required|min:3',
            'edit_category' => 'required',
            'edit_outlet' => 'required',
        ]);

        $recipe = Recipes::findOrFail($this->selected_recipe_id);

        $data = [
            'name' => $this->edit_name,
            'category' => $this->edit_category,
            'outlet' => $this->edit_outlet,
        ];

        // Jika ada foto baru yang diunggah
        if ($this->update_photo) {
            $data['image_url'] = $this->update_photo->store('menu-photos', 'public');
        }

        $recipe->update($data);

        // Catat ke CCTV
        $this->writeLog('Update Recipe', "Updated basic information for recipe: {$recipe->name}");

        session()->flash('success', 'Recipe updated successfully!');
    }

    public function duplicateRecipe()
    {
        $original = Recipes::findOrFail($this->selected_recipe_id);

        $newRecipe = $original->replicate();
        $newRecipe->name = $original->name . ' (Copy)';
        $newRecipe->save();

        $ingredients = DB::table('recipe_ingredients')
            ->where('recipe_id', $original->id)
            ->get();

        foreach ($ingredients as $item) {
            DB::table('recipe_ingredients')->insert([
                'recipe_id' => $newRecipe->id,
                'ingredient_id' => $item->ingredient_id,
                'amount_needed' => $item->amount_needed,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('activity_logs')->insert([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'org_id' => auth()->user()->org_id,
            'user_email' => auth()->user()->email,
            'action' => 'Duplicate Recipe',
            'details' => "Duplicated {$original->name} to {$newRecipe->name}",
            'created_at' => now(),
        ]);

        session()->flash('success', 'Recipe and all ingredients duplicated!');

        $this->selected_recipe_id = $newRecipe->id;
        $this->updatedSelectedRecipeId($newRecipe->id);
    }

    public function deleteRecipe()
    {
        $recipe = Recipes::findOrFail($this->selected_recipe_id);
        $name = $recipe->name;

        $recipe->delete();

        $this->writeLog('Delete Recipe', "Permanently removed recipe: {$name}");

        // Reset pilihan agar form hilang
        $this->selected_recipe_id = null;
        $this->reset(['edit_name', 'edit_category', 'edit_outlet', 'edit_image_url']);

        session()->flash('success', 'Recipe deleted.');
    }

    private function writeLog($action, $details) {
    DB::table('activity_logs')->insert([
        'id' => (string) Str::uuid(),
        'org_id' => auth()->user()->org_id,
        'user_email' => auth()->user()->email,
        'action' => $action,
        'details' => $details,
        'created_at' => now(),
        ]);
    }

    public function saveSOP()
    {
        $this->validate([
            'edit_method' => 'required|min:10',
        ]);

        $recipe = Recipes::findOrFail($this->selected_recipe_id);
        $recipe->update(['method' => $this->edit_method]);

        DB::table('activity_logs')->insert([
            'id' => (string) Str::uuid(),
            'org_id' => auth()->user()->org_id,
            'user_email' => auth()->user()->email,
            'action' => 'Update SOP',
            'details' => "Updated cooking method for recipe: {$recipe->name}",
            'created_at' => now(),
        ]);

        session()->flash('success', 'Cooking Method updated successfully!');
    }

}
