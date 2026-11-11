<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantScope implements Scope
{
    /**
     * Terapkan filter tenant (organization_id) pada query builder Eloquent.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Jika tidak ada user login (misal: pengunjung publik atau cli/seeder tanpa auth),
        // scope tidak membatasi query.
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();

        // 1. Admin UMKM: selalu difilter ke organization_id milik user yang login
        if ($user->role === 'umkm_admin' || !empty($user->organization_id)) {
            if ($model->getTable() === 'organizations') {
                $builder->where($model->qualifyColumn('id'), $user->organization_id);
            } else {
                $builder->where($model->qualifyColumn('organization_id'), $user->organization_id);
            }
            return;
        }

        // 2. Vendor:
        // - organizations: bebas melihat daftar semua UMKM (tidak difilter)
        // - inquiries: dilarang membaca isi pesan pertanyaan UMKM
        // - products: tidak boleh mengelola produk UMKM individu (difilter organization_id)
        if ($user->role === 'vendor' || is_null($user->organization_id)) {
            if ($model->getTable() === 'organizations') {
                return; // Bebas melihat daftar UMKM
            }

            if ($model->getTable() === 'inquiries') {
                // Cegah vendor membaca isi pertanyaan UMKM
                $builder->whereRaw('1 = 0');
                return;
            }

            // Untuk products, filter ke organization_id vendor (null -> menghasilkan 0 baris)
            $builder->where($model->qualifyColumn('organization_id'), $user->organization_id);
        }
    }
}

