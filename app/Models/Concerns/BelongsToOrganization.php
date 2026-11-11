<?php

namespace App\Models\Concerns;

use App\Models\Organization;
use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait BelongsToOrganization
{
    /**
     * Boot the BelongsToOrganization trait for a model.
     */
    protected static function bootBelongsToOrganization(): void
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            if (Auth::check() && method_exists(Auth::user(), 'isUmkmAdmin') && Auth::user()->isUmkmAdmin()) {
                $model->organization_id = Auth::user()->organization_id;
            } elseif (empty($model->organization_id) && Auth::check() && !empty(Auth::user()->organization_id)) {
                $model->organization_id = Auth::user()->organization_id;
            }
        });
    }

    /**
     * Relasi ke Organization induk.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Scope query untuk mengabaikan TenantScope (mis. untuk angka agregat vendor).
     */
    public function scopeWithoutTenant(Builder $query): Builder
    {
        return $query->withoutGlobalScope(TenantScope::class);
    }
}

