<?php

namespace Database\Seeders;

use App\Models\Inquiry;
use App\Models\Organization;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed basis data aplikasi dengan data dummy sesuai spesifikasi PRD.
     */
    public function run(): void
    {
        // 1. Password diambil dari environment (.env lokal), tidak hardcoded di repo
        $seedPassword = env('SEED_USER_PASSWORD', 'PasswordDemo2026!');
        $hashedPassword = Hash::make($seedPassword);

        // 2. Organisasi 1: Toko ABC
        $orgABC = Organization::withoutTenant()->updateOrCreate(
            ['slug' => 'toko-abc'],
            [
                'name' => 'Toko ABC',
                'category' => 'Kuliner',
                'description' => 'Pusat oleh-oleh aneka kue dan camilan khas berkualitas.',
                'address' => 'Jl. Jendral Sudirman No. 10, Kuningan',
                'phone' => '081234567891',
                'opening_hours' => 'Setiap Hari, 08:00 - 20:00 WIB',
                'is_active' => true,
            ]
        );

        // 3. Organisasi 2: Peyeum Khas Kuningan
        $orgPeyeum = Organization::withoutTenant()->updateOrCreate(
            ['slug' => 'peyeum-kuningan'],
            [
                'name' => 'Peyeum Khas Kuningan',
                'category' => 'Kuliner',
                'description' => 'Produsen peyeum tape ketan asli daun jambu khas Kuningan dengan rasa manis legit alami.',
                'address' => 'Jl. Veteran No. 45, Kuningan, Jawa Barat',
                'phone' => '081234567892',
                'opening_hours' => 'Senin - Minggu, 07:00 - 21:00 WIB',
                'is_active' => true,
            ]
        );

        // 4. Akun Dummy (3 Akun)
        // 4a. Vendor
        User::updateOrCreate(
            ['email' => 'vendor@platformkamu.test'],
            [
                'name' => 'Vendor Pengelola Platform',
                'password' => $hashedPassword,
                'role' => 'vendor',
                'organization_id' => null,
            ]
        );

        // 4b. Admin Toko ABC
        User::updateOrCreate(
            ['email' => 'admin@tokoabc.test'],
            [
                'name' => 'Admin Toko ABC',
                'password' => $hashedPassword,
                'role' => 'umkm_admin',
                'organization_id' => $orgABC->id,
            ]
        );

        // 4c. Admin Peyeum
        User::updateOrCreate(
            ['email' => 'admin@peyeum.test'],
            [
                'name' => 'Admin Peyeum Kuningan',
                'password' => $hashedPassword,
                'role' => 'umkm_admin',
                'organization_id' => $orgPeyeum->id,
            ]
        );

        // 5. Produk Dummy
        // 5a. Produk Toko ABC (termasuk produk Sembunyi untuk uji filter)
        Product::withoutTenant()->updateOrCreate(
            ['organization_id' => $orgABC->id, 'name' => 'Kue Pia Manis ABC'],
            [
                'category' => 'Kuliner',
                'price' => 35000,
                'description' => 'Kue pia isi kacang hijau lembut dengan kulit renyah.',
                'status' => 'Tampil',
            ]
        );

        Product::withoutTenant()->updateOrCreate(
            ['organization_id' => $orgABC->id, 'name' => 'Kue Bolu Kukus ABC'],
            [
                'category' => 'Kuliner',
                'price' => 45000,
                'description' => 'Bolu kukus mekar aneka rasa manis pas.',
                'status' => 'Tampil',
            ]
        );

        Product::withoutTenant()->updateOrCreate(
            ['organization_id' => $orgABC->id, 'name' => 'Resep Rahasia Pia ABC'],
            [
                'category' => 'Kuliner',
                'price' => 100000,
                'description' => 'Paket khusus resep eksklusif (arsip internal).',
                'status' => 'Sembunyi', // Untuk uji filter
            ]
        );

        // 5b. Produk Peyeum Khas Kuningan (JANGAN SEED Peyeum Keju!)
        Product::withoutTenant()->updateOrCreate(
            ['organization_id' => $orgPeyeum->id, 'name' => 'Peyeum Ember Kecil'],
            [
                'category' => 'Kuliner',
                'price' => 75000,
                'description' => 'Peyeum tape ketan dalam ember kecil isi 50 bungkus daun jambu.',
                'status' => 'Tampil',
            ]
        );

        Product::withoutTenant()->updateOrCreate(
            ['organization_id' => $orgPeyeum->id, 'name' => 'Peyeum Ember Besar'],
            [
                'category' => 'Kuliner',
                'price' => 90000,
                'description' => 'Peyeum tape ketan dalam ember besar isi 100 bungkus daun jambu.',
                'status' => 'Tampil',
            ]
        );

        Product::withoutTenant()->updateOrCreate(
            ['organization_id' => $orgPeyeum->id, 'name' => 'Paket Oleh-oleh Peyeum'],
            [
                'category' => 'Kuliner',
                'price' => 150000,
                'description' => 'Paket komplit oleh-oleh peyeum + keripik khas Kuningan.',
                'status' => 'Sembunyi', // Untuk uji filter
            ]
        );

        // 6. Pertanyaan Dummy
        // 6a. Pertanyaan Toko ABC
        Inquiry::withoutTenant()->updateOrCreate(
            ['organization_id' => $orgABC->id, 'visitor_name' => 'Rina Wijaya'],
            [
                'visitor_contact' => '081987654321',
                'message' => 'Apakah bisa kirim paket pia ke Bandung dan berapa ongkirnya?',
                'status' => 'Baru',
            ]
        );

        Inquiry::withoutTenant()->updateOrCreate(
            ['organization_id' => $orgABC->id, 'visitor_name' => 'Budi Pratama'],
            [
                'visitor_contact' => 'budi@gmail.com',
                'message' => 'Berapa lama ketahanan kue pia jika disimpan pada suhu ruang?',
                'status' => 'Dibalas',
            ]
        );

        // 6b. Pertanyaan Peyeum Khas Kuningan
        Inquiry::withoutTenant()->updateOrCreate(
            ['organization_id' => $orgPeyeum->id, 'visitor_name' => 'Dewi Lestari'],
            [
                'visitor_contact' => 'dewi@mail.com',
                'message' => 'Apakah peyeum ember kecil sudah siap langsung dikonsumsi saat sampai?',
                'status' => 'Baru',
            ]
        );

        Inquiry::withoutTenant()->updateOrCreate(
            ['organization_id' => $orgPeyeum->id, 'visitor_name' => 'Ahmad Fauzi'],
            [
                'visitor_contact' => '081322334455',
                'message' => 'Bisa pesan 10 ember besar untuk acara syukuran minggu depan?',
                'status' => 'Dibalas',
            ]
        );

        Inquiry::withoutTenant()->updateOrCreate(
            ['organization_id' => $orgPeyeum->id, 'visitor_name' => 'Citra Kirana'],
            [
                'visitor_contact' => 'citra@mail.com',
                'message' => 'Paket peyeum sudah sampai, tape sangat manis dan airnya melimpah. Terima kasih!',
                'status' => 'Selesai',
            ]
        );
    }
}
