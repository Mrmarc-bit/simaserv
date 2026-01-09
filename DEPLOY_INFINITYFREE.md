# Panduan Deployment ke InfinityFree

Panduan ini akan membantu Anda meng-upload aplikasi **SIMASERV** ke hosting gratis **InfinityFree**.

## 1. Persiapan File
Codebase Anda sudah saya siapkan agar mudah di-upload.
1. Download file project Anda dari GitHub (klik **Code** -> **Download ZIP**) atau gunakan file di folder komputer Anda.
2. Pastikan file project di-zip (misal: `simaserv.zip`). **PENTING**: Jangan zip foldernya, tapi zip **isi dalam foldernya**.

## 2. Persiapan Database
Anda perlu mengambil data dari komputer Anda untuk dipindahkan ke hosting.
1. Buka **Laragon**.
2. Klik tombol **Database** (biasanya akan membuka HeidiSQL atau phpMyAdmin).
3. Pilih database `karircom` (atau nama database yang Anda pakai).
4. Klik kanan pada nama database -> **Export database as SQL**.
   - Pastikan opsi **Create** dicentang (untuk `Data` dan `Structure`).
5. Simpan file sebagai `database.sql`.

## 3. Upload ke InfinityFree
1. Login ke akun InfinityFree Anda.
2. Masuk ke **Control Panel** (cPanel) atau **File Manager**.
3. Buka folder `htdocs`.
4. Hapus file `index2.html` atau file bawaan lainnya jika ada.
5. Upload file `simaserv.zip` yang sudah Anda siapkan tadi.
6. **Ekstrak** (Unzip) file tersebut di dalam folder `htdocs`.
   - *Catatan*: Jika tidak bisa upload zip, Anda mungkin perlu upload file satu per satu menggunakan aplikasi FTP seperti **FileZilla**. (Host, User, Pass ada di dashboard InfinityFree).

## 4. Setup Database Online
1. Di Control Panel InfinityFree, cari menu **MySQL Databases**.
2. Buat database baru, misal: `simaserv_db`.
3. Catat detail berikut yang muncul:
   - **MySQL Hostname** (biasanya `sql123.infinityfree.com`)
   - **MySQL User** (misal: `epiz_123456`)
   - **MySQL Password** (biasanya sama dengan password akun vPanel)
   - **MySQL Database Name** (misal: `epiz_123456_simaserv_db`)
4. Buka menu **phpMyAdmin** di Control Panel.
5. Pilih database yang baru dibuat.
6. Klik tab **Import** -> Pilih file `database.sql` yang tadi Anda export -> Klik **Go**.

## 5. Konfigurasi Aplikasi (.env)
1. Kembali ke **File Manager** (di folder `htdocs`).
2. Cari file bernama `.env.example`.
3. Rename (ganti nama) menjadi `.env`.
4. Edit file `.env` tersebut, dan ubah bagian ini sesuai data database InfinityFree Anda (Langkah 4):
   ```
   APP_NAME=SIMASERV
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=http://nama-domain-anda.infinityfreeapp.com

   DB_CONNECTION=mysql
   DB_HOST=sql123.infinityfree.com  <-- Ganti dengan MySQL Hostname
   DB_PORT=3306
   DB_DATABASE=epiz_xxxx_db       <-- Ganti dengan Database Name
   DB_USERNAME=epiz_xxxx          <-- Ganti dengan User
   DB_PASSWORD=password_anda      <-- Ganti dengan Password
   ```
5. Simpan file.

## 6. Selesai!
Buka website Anda. Seharusnya aplikasi sudah berjalan.

---
### Masalah Umum (Troubleshooting)
- **Error 500**: Pastikan versi PHP di InfinityFree diatur ke versi **8.1** atau **8.2**. (Cari menu "Select PHP Version").
- **Gambar tidak muncul**: Pastikan folder `public/storage` sudah benar. Di hosting shared, kadang perlu menghapus folder `public/storage` lama dan membuat symlink baru (tapi di hosting gratisan fitur symlink sering diblokir). Solusi alternatif: edit `config/filesystems.php`, ubah `'root' => storage_path('app/public')` menjadi `'root' => public_path('storage')` (kurang aman tapi jalan).
