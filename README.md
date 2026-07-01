# Sistem Informasi Manajemen Distribusi MBG
## Badan Gizi Nasional - Kelurahan Kerasaan I

### 📋 Deskripsi
Aplikasi web untuk manajemen distribusi program MBG (Makanan Bergizi) di tingkat kelurahan. Sistem ini dirancang untuk memudahkan pencatatan data penerima, petugas, jadwal distribusi, dan laporan.

### 🎨 Konsep Desain
- **Tema**: Navy Blue (#0F4C81), Hijau, Putih
- **Framework UI**: Bootstrap 5, AdminLTE
- **Efek**: Glassmorphism, Gradient Background
- **Icons**: FontAwesome
- **Font**: Poppins
- **Charts**: Chart.js
- **Alerts**: SweetAlert2

### ✨ Fitur Utama
✅ Dashboard dengan statistik real-time
✅ Master Data (Penerima & Petugas)
✅ Manajemen Distribusi
✅ Jadwal Distribusi
✅ Laporan & Export (PDF, Excel)
✅ Upload Foto Dokumentasi
✅ Sistem Login dengan Hak Akses
✅ Search & Pagination
✅ Notifikasi SweetAlert

### 📁 Struktur Folder
```
mbg-system/
├── index.php
├── login.php
├── dashboard.php
├── logout.php
├── master/
│   ├── penerima.php
│   └── petugas.php
├── transaksi/
│   ├── distribusi.php
│   └── jadwal.php
├── laporan/
│   └── laporan.php
├── config/
│   └── koneksi.php
├── assets/
│   ├── css/
│   ├── js/
│   ├── img/
│   └── vendor/
├── database/
│   └── mbg.sql
└── README.md
```

### 🚀 Cara Instalasi
1. Clone repository ini
2. Import file `database/mbg.sql` ke MySQL
3. Ubah konfigurasi database di `config/koneksi.php`
4. Akses aplikasi melalui browser: `http://localhost/mbg-system`
5. Login dengan akun: `admin` / `admin123`

### 💡 Teknologi
- PHP 7.4+
- MySQL 5.7+
- Bootstrap 5
- Chart.js
- FontAwesome
- SweetAlert2

### 📝 Catatan
Aplikasi ini dirancang sebagai "Premium Minimalist Dashboard" dengan tampilan mewah namun interface yang sederhana dan user-friendly.