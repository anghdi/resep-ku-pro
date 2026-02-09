<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ingredients;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class IngredientsMaster extends Component
{
    use WithPagination;

    // Properti sesuai skema koki
    public $name, $purchase_price, $package_size, $unit = 'gr', $yield = 100;
    public $search = '';
    public $selected_id;
    public $is_editing = false;

    protected $rules = [
        'name' => 'required|min:2',
        'purchase_price' => 'required|numeric|min:0',
        'package_size' => 'required|numeric|min:0.01',
        'unit' => 'required|in:gr,ml,pcs',
        'yield' => 'required|numeric|between:0,100',
    ];

    private function writeLog($action, $details)
    {
        DB::table('activity_logs')->insert([
            'id' => (string) Str::uuid(), // Sesuai skema UUID
            'org_id' => auth()->user()->org_id,
            'user_email' => auth()->user()->email,
            'action' => $action,
            'details' => $details,
            'created_at' => now(), // timestamptz
        ]);
    }

    public function save()
    {

        if (!auth()->user()->hasAccess('ingredients', 'add')) {
            session()->flash('error', 'Access Denied: You do not have permission to add new ingredients.');
            return;
        }

        if (!auth()->user()->hasAccess('ingredients', 'edit')) {
            session()->flash('error', 'Access Denied: You do not have permission to update ingredients.');
            return;
        }

        $this->validate();

        $isNew = !$this->is_editing;
        $ingredientName = $this->name;

        $data = [
            'name' => $this->name,
            'purchase_price' => $this->purchase_price,
            'package_size' => $this->package_size,
            'unit' => $this->unit,
            'yield' => $this->yield,
            'user_id' => auth()->id(),
            'org_id' => auth()->user()->org_id,
        ];

        if ($this->is_editing) {
            Ingredients::find($this->selected_id)->update($data);

            // LOG: Update Bahan
            $this->writeLog('Update Ingredient', "Modified details for: {$ingredientName}");
        } else {
            Ingredients::create($data);

            // LOG: Tambah Bahan
            $this->writeLog('Create Ingredient', "Added new ingredient: {$ingredientName} (Price: Rp " . number_format($this->purchase_price) . " per {$this->package_size}{$this->unit})");
        }

        $this->resetForm();
        session()->flash('success', 'Ingredient saved and logged!');
    }

    public function edit($id)
    {
        $item = Ingredients::findOrFail($id);
        $this->selected_id = $id;
        $this->name = $item->name;
        $this->purchase_price = $item->purchase_price;
        $this->package_size = $item->package_size;
        $this->unit = $item->unit;
        $this->yield = $item->yield;
        $this->is_editing = true;
    }

    public function delete($id)
    {

        if (!auth()->user()->hasAccess('ingredients', 'delete')) {
            session()->flash('error', 'Access Denied: You do not have permission to delete ingredients.');
            return;
        }

        $item = Ingredients::find($id);
        if ($item) {
            $name = $item->name;
            $item->delete();

            // LOG: Hapus Bahan
            $this->writeLog('Delete Ingredient', "Permanently removed ingredient: {$name} from the master list");
        }
    }

    public function resetForm()
    {
        $this->reset(['name', 'purchase_price', 'package_size', 'yield', 'is_editing', 'selected_id']);
        $this->unit = 'gr';
    }

    public function render()
    {
        $query = Ingredients::where('org_id', auth()->user()->org_id);

        if ($this->search) {
            $query->where('name', 'REGEXP', $this->search);
        }

        return view('livewire.pages.ingredients-master', [
            'ingredients' => $query->latest()->paginate(10)
        ])->layout('layouts.app');
    }
}
