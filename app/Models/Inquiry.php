<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory, BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'visitor_name',
        'visitor_contact',
        'message',
        'status',
    ];

    /**
     * Hitung total pertanyaan secara agregat tanpa filter tenant (untuk dashboard vendor).
     */
    public static function aggregateCount(): int
    {
        return static::withoutGlobalScope(TenantScope::class)->count();
    }
}
