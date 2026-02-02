<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Ingredients extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'purchase_price',
        'package_size',
        'unit',
        'yield',
        'user_id',
        'org_id',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'package_size' => 'decimal:2',
        'yield' => 'decimal:2',
    ];

    protected function netUnitCost(): Attribute
    {
        return Attribute::make(
            get: function () {
                $netWeight = ($this->package_size * $this->yield) / 100;
                return $netWeight > 0 ? ($this->purchase_price / $netWeight) : 0;
            },
        );
    }

    /**
     * Get the Chef (User) that owns the ingredient.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
