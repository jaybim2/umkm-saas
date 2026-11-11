<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'name',
        'category',
        'price',
        'description',
        'photo_url',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    /**
     * Hitung total produk secara agregat tanpa filter tenant (untuk dashboard vendor).
     */
    public static function aggregateCount(): int
    {
        return static::withoutGlobalScope(TenantScope::class)->count();
    }
}
