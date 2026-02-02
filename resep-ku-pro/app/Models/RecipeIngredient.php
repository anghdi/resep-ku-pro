<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Ingredients;
use App\Models\Recipes;

class RecipeIngredient extends Model
{
    protected $table = 'recipe_ingredients';

    protected $fillable = [
        'recipe_id',
        'ingredient_id',
        'amount_needed',
    ];

    protected $casts = [
        'amount_needed' => 'decimal:2',
    ];

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipes::class);
    }

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredients::class);
    }

    public function getCostEstimationAttribute()
    {
        if (!$this->ingredient) return 0;

        $pricePerUnit = $this->ingredient->purchase_price / $this->ingredient->package_size;
        return $pricePerUnit * $this->amount_needed;
    }
}
