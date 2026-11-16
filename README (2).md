# Platform Website Profil dan Katalog Siap Pakai untuk UMKM

**Judul:** Pengembangan Platform Website Profil dan Katalog Siap Pakai Berbasis Web dengan Model SaaS untuk UMKM

**Tugas Besar Cyber Security (individu, Pra-UTS)** — satu platform web berbasis SaaS tempat satu UMKM mendapat satu halaman publik profil dan katalog siap pakai (contoh: `/u/toko-abc`) dan mengelolanya sendiri dari dashboard, tanpa harus membangun dan meng-host websitenya sendiri.

**URL live utama:** https://app.sjaglobalcosmindo.web.id/
**URL live cadangan:** https://umkm-saas.onrender.com/
**Repository:** https://github.com/jaybim2/umkm-saas

> Render Free tidur setelah 15 menit tanpa traffic dan bangun sekitar 30–60 detik. Untuk demo, buka URL sekitar 2 menit sebelumnya.

## Fitur Utama

1. Registrasi UMKM dan login dengan peran Vendor dan Admin UMKM.
2. Dashboard Vendor dan Dashboard UMKM.
3. CRUD Profil UMKM.
4. CRUD Produk, dengan status Tampil dan Sembunyi.
5. Halaman publik UMKM (`/u/{slug}`) yang hanya menampilkan produk Tampil.
6. Form pertanyaan pengunjung (inquiry), dengan status Baru, Dibalas, Selesai.
7. Pesan WhatsApp per produk (Pesan WA). Tidak ada payment gateway, keranjang belanja, booking, atau template kedua, agar keamanan tetap sederhana.
8. Laporan: total vendor dan total produk / pertanyaan per UMKM, dengan ekspor CSV.

Studi kasus yang digunakan adalah data contoh (dummy), bukan kemitraan nyata: Toko ABC dan Peyeum Khas Kuningan.

## Teknologi

- Laravel 12, PHP 8.4
- Database: PostgreSQL di Neon (Free), database `umkm_saas`
- Hosting aplikasi: Render (Free), service `umkm-saas`
- Domain / DNS / HTTPS: Cloudflare (proxied), subdomain `app.sjaglobalcosmindo.web.id`
- Sertifikat SSL/TLS valid untuk `app.sjaglobalcosmindo.web.id`, diterbitkan Google Trust Services, berlaku 12 Nov 2026 – 10 Feb 2027.

Alur singkat:
Pengunjung / Admin UMKM -> Cloudflare (DNS, proxy, HTTPS) -> Render (aplikasi Laravel) -> Neon (database PostgreSQL)

Tabel utama (4 tabel): `organizations`, `users`, `products`, `inquiries`.
Peran: `vendor` (`organization_id` NULL), `umkm_admin`, pengunjung (tanpa login).

## Cara Menjalankan di Lokal

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Lalu buka http://127.0.0.1:8000

## Konfigurasi Environment

`.env` dibuat dari `.env.example`, jangan di-commit ke repo. Variabel yang dipakai: `APP_NAME`, `APP_ENV`, `APP_DEBUG=false`, `APP_URL`, `APP_KEY`, `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `DB_SSLMODE=require`. Nilai rahasia (`APP_KEY`, `DB_PASSWORD`) hanya diisi di dashboard Render / Neon, tidak ditulis di README.

## Keamanan yang Diterapkan

- Password disimpan sebagai hash (bcrypt), bukan teks biasa.
- Login memakai pesan gagal umum "Email atau password salah".
- Password lemah ditolak saat registrasi, dan login dibatasi (rate limiting) setelah 5 kali gagal.
- Semua query memakai parameter binding / Eloquent, bukan string concatenation.
- Semua output deskripsi produk dan pesan pertanyaan di-escape di Blade (`{{ }}`).
- Setiap query produk dan pertanyaan selalu difilter berdasarkan `organization_id` milik user yang login (tenant isolation).
- Rute `/vendor` hanya boleh dibuka oleh peran Vendor.
- Form memakai token CSRF (`@csrf`, `_token`).
- Secret (`APP_KEY`, `DB_PASSWORD`) hanya di environment variable di dashboard Render / Neon, tidak di-commit ke repo.

## Hasil Self-Testing (Ringkasan)

Pengujian dilakukan penulis sendiri (self-testing) pada aplikasi sendiri, di URL live, 14–15 November 2026, sesuai PDF Tugas Besar poin 3f. Bukti lengkap (screenshot ST01–ST14) ada di laporan PDF.

| No | Skenario | Hasil |
|---|---|---|
| U01 | SQL Injection di login | Lolos — login ditolak |
| U02 | SQL Injection di parameter URL | Lolos — diblokir WAF, 403 Forbidden, Request ID a5a46cda6978b618-MRS. Tidak ada error database dan tidak ada data yang bocor. Catatan: yang memblokir adalah WAF di depan, perlindungan utama tetap parameter binding Laravel. |
| U03 | XSS di deskripsi produk | Lolos — tidak ada alert, payload tampil sebagai teks biasa |
| U04 | XSS di pesan pertanyaan | Lolos — tidak ada alert |
| U05 | IDOR produk lintas UMKM | Lolos — 404 Not Found, data produk UMKM lain tidak tampil |
| U06 | Akses `/vendor` sebagai admin UMKM / pengunjung | Lolos — tanpa login dialihkan ke `/login`, Admin UMKM ditolak 403, Vendor berhasil masuk. Kategori: Broken Access Control / RBAC (OWASP A01). |
| U07 | Password lemah ditolak | Lolos — `123456`, `password`, `abc123` ditolak; password kuat diterima |
| U08 | Rate limiting login | Lolos — setelah 5 kali gagal muncul pembatasan sementara |
| U09 | Logout dan masa berlaku sesi | Lihat laporan lengkap |
| U10 | Produk Sembunyi dan UMKM nonaktif | Lolos (bagian produk Sembunyi) — `/u/toko-abc` hanya tampil 3 produk Tampil, "Paket Hampers Lebaran" tidak muncul; `/u/peyeum-kuningan` tampil 5 kartu, "Paket Oleh-oleh Peyeum" tidak muncul. Bagian UMKM nonaktif belum diuji, jangan diklaim. |
| U11 | HTTPS dan security headers | Lolos (Grade B) — securityheaders.com, 14 Nov 2026 08:01 UTC. Header yang ada: Permissions-Policy, Referrer-Policy, X-Content-Type-Options, X-Frame-Options. Yang belum ada: Strict-Transport-Security dan Content-Security-Policy. |
| U12 | Secret tidak ter-commit dan dependency aman | Lihat laporan lengkap |
| U13 | Rate limiting form pertanyaan | Lihat laporan lengkap |
| U14 | CSRF | Lihat laporan lengkap |

Catatan jujur: HSTS dan CSP belum ada saat pengujian (Grade B sudah cukup untuk U11). Rotasi password Neon adalah tindak lanjut U12, jangan ditulis sudah dilakukan kalau belum.

## Penggunaan AI

AI yang digunakan: Muse (Duck), sebagai asisten pribadi.

Yang dibantu AI:
- Membantu menyusun judul dan scope, serta merapikan PRD menjadi tugas satu per satu untuk Antigravity.
- Membantu menyiapkan file migrasi, panduan deploy Render + Neon, file self-test U01–U14, dan template laporan Word.
- Membantu menjelaskan langkah pentest dengan Burp Suite Community dan membaca hasil pengujian.

Yang TIDAK dilakukan AI:
- AI tidak menguji aplikasi menggantikan penulis. Semua hasil U01–U14 adalah hasil uji nyata penulis sendiri di URL live.
- AI tidak membuat keputusan akhir. Judul, stack Laravel, scope, dan deploy Render + Neon diputuskan oleh penulis.

Cara penulis memverifikasi hasil AI:
- Setiap file dan langkah yang diberikan AI dicek langsung: migrasi dicek dengan `php artisan migrate`, deploy dicek dengan membuka URL live, dan setiap hasil pentest dicek dengan screenshot dan Response Burp.
- Tidak ada secret atau data nyata yang dimasukkan ke dalam prompt AI, sesuai aturan di PDF.
