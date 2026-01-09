# SIMASERV - Sistem Manajemen Service

Aplikasi web untuk manajemen service komputer dan tracking tiket pelanggan.

## Fitur Utama

- **Public Tracking System**: Pelanggan dapat melacak status service menggunakan Kode Tiket.
- **Admin Dashboard**: Overview statistik harian, bulanan, dan performa teknisi.
- **WhatsApp & Email Notifications**: Notifikasi otomatis saat status service berubah (Selesai, Diambil).
- **Service Management**: CRUD data service, update status, dan manajemen sparepart.
- **Print Invoice**: Cetak nota tanda terima dan invoice pembayaran.

## Instalasi Lokal

1. **Clone Repository**
   ```bash
   git clone https://github.com/Mrmarc-bit/simaserv.git
   cd simaserv
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Setup Environment**
   - Copy `.env.example` ke `.env`
   - Atur database dan kredensial lainnya.
   ```bash
   php artisan key:generate
   ```

4. **Database Migration**
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Jalankan Server**
   ```bash
   npm run dev
   # Terminal baru:
   php artisan serve
   ```

## Catatan Deployment (GitHub Pages)

**PENTING**: Aplikasi ini dibangun menggunakan **Laravel (PHP)** dan **MySQL**. Oleh karena itu, aplikasi ini **TIDAK BISA** dijalankan di **GitHub Pages** karena GitHub Pages hanya mendukung website statis (HTML/CSS/JS).

Untuk mengonlinekan aplikasi ini, Anda memerlukan hosting yang mendukung PHP & Database, seperti:
- **Shared Hosting** (cPanel/Niagahoster/Hostinger/dll)
- **VPS** (DigitalOcean/Linode)
- **PaaS** (Railway.app, Heroku, Fly.io)

### Cara Publish ke GitHub
Codebase ini sudah aman untuk di-push ke GitHub. File sensitif seperti `.env` (berisi password database) sudah otomatis diabaikan oleh `.gitignore` sehingga tidak akan bocor ke publik.
