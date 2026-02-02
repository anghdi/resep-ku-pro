<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Recipes extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'outlet',
        'image_url',
        'category',
        'method',
        'selling_price',
        'user_id',
        'org_id',
    ];

    /**
     * Casting agar harga jual selalu diperlakukan sebagai angka desimal.
     */
    protected $casts = [
        'selling_price' => 'decimal:2',
    ];

    /**
     * Relasi ke Chef (User) yang membuat resep ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(
            Ingredients::class,      // Model tujuan
            'recipe_ingredients',   // Tabel pivot koki
            'recipe_id',            // Foreign key di pivot untuk resep
            'ingredient_id'         // Foreign key di pivot untuk bahan
        )->withPivot('amount_needed')->withTimestamps();
    }
}
