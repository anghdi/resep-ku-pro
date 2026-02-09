<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Recipes;
use App\Models\Outlets;

class CostingAnalysis extends Component
{
    public $selectedOutlet;
    public $selectedRecipeId;
    public $recipe;
    public $newSellingPrice;

    // Properti Hasil Perhitungan
    public $rawCost = 0;
    public $finalCost = 0; // Cost + 5% Fluk + 10% Tax
    public $suggestedPrice = 0;
    public $foodCostPercent = 0;

    /**
     * Listener saat resep dipilih
     */
    public function updatedSelectedRecipeId($id)
    {
        if (!$id) return;

        $this->recipe = Recipes::find($id);
        $this->newSellingPrice = $this->recipe->selling_price;
        $this->calculateCosting();
    }

    private function calculateCosting()
    {
        $this->rawCost = 0;
        foreach ($this->recipe->ingredients as $item) {
            // Logika: Harga Beli / Ukuran Kemasan * Jumlah yang Dibutuhkan
            $pricePerUnit = $item->ingredient->purchase_price / $item->ingredient->package_size;
            $this->rawCost += ($pricePerUnit * $item->amount_needed);
        }

        // Rumus Koki: (Raw Cost * 1.05) * 1.10
        $this->finalCost = ($this->rawCost * 1.05) * 1.10;

        // Suggested Price (Target 30% Food Cost)
        $this->suggestedPrice = $this->finalCost > 0 ? ($this->finalCost / 0.3) : 0;

        // Food Cost Percentage
        $this->foodCostPercent = ($this->recipe->selling_price > 0)
            ? ($this->finalCost / $this->recipe->selling_price) * 100
            : 0;
    }

    public function updateSellingPrice()
    {
        // Proteksi Akses Edit (Update)
        if (!auth()->user()->hasAccess('menus', 'edit')) {
            session()->flash('error', 'Unauthorized: You do not have permission to update selling prices.');
            return;
        }

        $this->recipe->update(['selling_price' => $this->newSellingPrice]);
        $this->calculateCosting();
        session()->flash('success', 'Selling price updated successfully!');
    }

    public function render()
    {
        return view('livewire.pages.costing-analysis', [
            'outlets' => Outlets::where('org_id', auth()->user()->org_id)->get(),
            'recipes' => Recipes::where('org_id', auth()->user()->org_id)->get(),
        ])->layout('layouts.app');
    }
}
