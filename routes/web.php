<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterUmkmController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PublicUmkmController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UmkmDashboardController;
use App\Http\Controllers\UmkmProfileController;
use App\Http\Controllers\VendorDashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Layar 1: Landing Page Platform (Publik, redirect ke dashboard jika sudah login)
Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->isVendor()
            ? redirect()->route('vendor.dashboard')
            : redirect()->route('dashboard');
    }
    return view('landing');
})->name('landing');

// Layar 2: Login & Logout
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

// Layar 3: Registrasi UMKM
Route::get('/register-umkm', [RegisterUmkmController::class, 'create'])->name('register-umkm');
Route::post('/register-umkm', [RegisterUmkmController::class, 'store']);

// Layar 6 & Layar 7 & Layar 8: Dashboard, Profil, dan Produk UMKM (Hanya Admin UMKM yang terotentikasi)
Route::middleware(['auth', 'role:umkm_admin'])->group(function () {
    Route::get('/dashboard', [UmkmDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/profil', [UmkmProfileController::class, 'edit'])->name('umkm.profile.edit');
    Route::get('/dashboard/profil/{organization}', [UmkmProfileController::class, 'editById'])->name('umkm.profile.edit.id');
    Route::put('/dashboard/profil', [UmkmProfileController::class, 'update'])->name('umkm.profile.update');

    // Layar 8: Daftar & Form Produk (CRUD Produk UMKM)
    Route::resource('/dashboard/produk', ProductController::class)
        ->names('products')
        ->parameters(['produk' => 'product']);

    // Layar 9: Daftar & Detail Pertanyaan Masuk (T13 / Slice 3a)
    Route::get('/dashboard/pertanyaan', [InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/dashboard/pertanyaan/{inquiry}', [InquiryController::class, 'show'])->name('inquiries.show');
    Route::patch('/dashboard/pertanyaan/{inquiry}/status', [InquiryController::class, 'updateStatus'])->name('inquiries.update-status');
    Route::delete('/dashboard/pertanyaan/{inquiry}', [InquiryController::class, 'destroy'])->name('inquiries.destroy');

    // Layar 10: Laporan & Ekspor UMKM (T14 / Slice 3b)
    Route::get('/dashboard/laporan', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/dashboard/laporan/ekspor-produk', [ReportController::class, 'exportProductsCsv'])->name('reports.export-products');
    Route::get('/dashboard/laporan/ekspor-pertanyaan', [ReportController::class, 'exportInquiriesCsv'])->name('reports.export-inquiries');
});

// Layar 4 & 5: Dashboard Vendor & Daftar UMKM (Hanya Vendor yang terotentikasi)
Route::middleware(['auth', 'role:vendor'])->group(function () {
    Route::get('/vendor', [VendorDashboardController::class, 'index'])->name('vendor.dashboard');
    Route::get('/vendor/umkm', [VendorDashboardController::class, 'umkmIndex'])->name('vendor.umkm.index');
    Route::post('/vendor/umkm', [VendorDashboardController::class, 'storeUmkm'])->name('vendor.umkm.store');
    Route::put('/vendor/umkm/{organization}', [VendorDashboardController::class, 'updateUmkm'])->name('vendor.umkm.update');
    Route::patch('/vendor/umkm/{organization}/status', [VendorDashboardController::class, 'toggleStatus'])->name('vendor.umkm.status');
    Route::delete('/vendor/umkm/{organization}', [VendorDashboardController::class, 'destroyUmkm'])->name('vendor.umkm.destroy');
    Route::get('/vendor/ekspor', [VendorDashboardController::class, 'exportCsv'])->name('vendor.export');
});

// Layar 11 & Formulir Pertanyaan Publik (/u/{slug})
Route::get('/u/{slug}', [PublicUmkmController::class, 'show'])->name('public.umkm.show');
Route::post('/u/{slug}/pertanyaan', [PublicUmkmController::class, 'storeInquiry'])
    ->name('public.umkm.inquiry')
    ->middleware('throttle:10,1');
