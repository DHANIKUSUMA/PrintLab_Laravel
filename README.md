# 🖨️ PrintLab - Aplikasi Manajemen Pesanan Digital Printing (Laravel + Flowbite)

Aplikasi web lengkap untuk mengelola pesanan, inventaris kertas, pengguna, dan laporan keuangan bisnis digital printing. Sistem terbagi menjadi dua antarmuka: Admin dan User (Karyawan/Pelanggan).

## Fitur Utama

### 🛡️ Role Admin (Full Access)
- **Dashboard**: Visualisasi statistik (Pesanan masuk, Selesai, Pending, Stok Kertas).
- **Kelola Pesanan**: CRUD pesanan lengkap dengan status tracking (pending → diterima → selesai).
- **Kelola Pengeluaran**: Pencatatan biaya operasional (gaji, ATK, listrik, dll) dengan kategori.
- **Kelola User**: Manajemen akun karyawan dan pengguna sistem.
- **Kelola Jenis Kertas**: Manajemen inventaris dan harga kertas.
- **Laporan Keuangan**: Rekap harian/bulanan/tahunan pemasukan & pengeluaran, laba bersih, buku kas, dan grafik tren.

### 👤 Role User (Employee/Customer)
- **Dashboard**: Ringkasan pesanan pribadi.
- **Buat Pesanan**: Form input detail order (ukuran, jumlah, finishing).
- **Histori Pesanan**: Melihat status pesanan yang dibuat.

## 🛠️ Teknologi yang Digunakan

- **Framework**: Laravel 11
- **Database**: MySQL
- **Frontend**: HTML5, Tailwind CSS v4
- **UI Components**: Flowbite
- **Icons**: Lucide React via CDN
- **Data Visualization**: Chart.js

## 🚀 Instalasi & Setup

### 1. Clone Repository
```bash
git clone <url-repo-anda>
cd PrintLab_Laravel
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Konfigurasi Environment
Salin file `.env.example` menjadi `.env` dan konfigurasikan kredensial database:
```env
DB_CONNECTION=mysql
DB_HOST=[IP_ADDRESS]
DB_PORT=3306
DB_DATABASE=nama_db_printlab
DB_USERNAME=root
DB_PASSWORD=""
```

### 4. Generate Key & Run Migrations
```bash
php artisan key:generate
php artisan migrate --seed
```
*(Migrations sudah mencakup role seeding dan demo data awal.)*

### 5. Serve the Application
```bash
php artisan serve
```
Akses aplikasi melalui browser di: `http://localhost:8000`

---

*Aplikasi ini dikembangkan sebagai proyek akademik untuk memahami alur bisnis percetakan dan implementasi database relasional.*