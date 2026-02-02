<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class UserPermission extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'org_id',
        'module',
        'can_create',
        'can_read',
        'can_update',
        'can_delete',
    ];

    /**
     * Casting boolean agar data dikembalikan sebagai true/false murni,
     * bukan angka 1/0 di Livewire.
     */
    protected $casts = [
        'can_create' => 'boolean',
        'can_read' => 'boolean',
        'can_update' => 'boolean',
        'can_delete' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn ($m) => $m->id = $m->id ?: (string) \Illuminate\Support\Str::uuid());
    }

    /**
     * Relasi ke User (Siapa pemilik ijin ini)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
