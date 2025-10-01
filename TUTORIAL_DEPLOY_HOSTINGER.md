# Tutorial Deploy Aplikasi Laravel ke Hostinger

## Deskripsi Aplikasi
Aplikasi ini adalah aplikasi web berbasis Laravel 12 dengan integrasi Firebase. Aplikasi menggunakan:
- **Backend**: Laravel 12 dengan PHP 8.2+
- **Frontend**: Blade Templates dengan Vite dan Tailwind CSS
- **Database**: Firebase Realtime Database
- **Services**: Firebase untuk authentication dan real-time features

## Persyaratan Sistem

### Hosting Requirements
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js 18+ dan NPM
- Firebase project dengan Realtime Database aktif
- SSL Certificate (HTTPS)

### Hostinger Plan Requirements
Pastikan paket hosting Anda mendukung:
- PHP 8.2+
- Akses internet untuk koneksi Firebase
- SSH Access (untuk Business plan ke atas)
- Cron Jobs
- File Manager dengan akses ke root directory

## Langkah 1: Persiapan Lokal

### 1.1 Build Assets Production
```bash
# Install dependencies
npm install

# Build untuk production
npm run build

# Install PHP dependencies
composer install --optimize-autoloader --no-dev
```

### 1.2 Konfigurasi Environment
```bash
# Copy environment file
cp .env.example .env
```

Edit file `.env` untuk production:
```env
APP_NAME="Run Dev Event"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database - Firebase Realtime Database (tidak perlu MySQL)
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=

# Session dan Cache
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# Mail (opsional - konfigurasi sesuai Hostinger)
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=your_email@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls

# Firebase Configuration
FIREBASE_CREDENTIALS=/path/to/your/firebase-admin-sdk.json
FIREBASE_DATABASE_URL=https://run-event-cbb9c-default-rtdb.firebaseio.com

# Firebase Web Config untuk Frontend
MIX_FIREBASE_API_KEY=AIzaSyBIyxGNsw7w0YjwhTQq3O6Hjw_0d2jIWAQ
MIX_FIREBASE_AUTH_DOMAIN=run-event-cbb9c.firebaseapp.com
MIX_FIREBASE_DATABASE_URL=https://run-event-cbb9c-default-rtdb.firebaseio.com/
MIX_FIREBASE_PROJECT_ID=run-event-cbb9c
MIX_FIREBASE_STORAGE_BUCKET=run-event-cbb9c.appspot.com
MIX_FIREBASE_MESSAGING_SENDER_ID=779259991666
MIX_FIREBASE_APP_ID=1:779259991666:web:af0cce1eb30fa4712b0ab5
```

### 1.3 Generate Application Key
```bash
php artisan key:generate
```

### 1.4 Optimasi untuk Production
```bash
# Clear dan cache konfigurasi
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

## Langkah 2: Setup Firebase Database

### 2.1 Verifikasi Firebase Project
1. Login ke **Firebase Console**: https://console.firebase.google.com
2. Pastikan project `run-event-cbb9c` sudah aktif
3. Pilih **Realtime Database** dari menu
4. Pastikan database URL: `https://run-event-cbb9c-default-rtdb.firebaseio.com`
5. Atur **Rules** sesuai kebutuhan aplikasi

### 2.2 Download Firebase Admin SDK
1. Di Firebase Console, pilih **Project Settings** (⚙️)
2. Tab **Service accounts**
3. Klik **Generate new private key**
4. Download file JSON (contoh: `run-event-cbb9c-firebase-adminsdk-xxx.json`)
5. **Simpan file ini dengan aman** - jangan commit ke Git!

## Langkah 3: Upload Files ke Hostinger

### 3.1 Struktur Upload
```
public_html/
├── public/          # Semua file dari folder public Laravel
│   ├── index.php
│   ├── build/       # Asset hasil build Vite
│   └── ...
├── app/             # Upload ke luar public_html
├── bootstrap/
├── config/
├── database/
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env
├── artisan
├── composer.json
├── run-event-cbb9c-firebase-adminsdk-xxx.json  # Firebase Admin SDK
└── ...
```

### 3.2 Upload via File Manager
1. **Compress** semua file project (kecuali `node_modules`, `.git`)
2. Login ke **hPanel** → **File Manager**
3. Upload file zip ke **home directory** (bukan public_html)
4. Extract file zip
5. **Pindahkan** isi folder `public/` Laravel ke `public_html/`
6. **Edit** `public_html/index.php`:

```php
<?php
// Ubah path ini sesuai struktur Hostinger
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
```

### 3.3 Upload via SSH (Untuk Business Plan+)
```bash
# Compress project locally
tar -czf project.tar.gz . --exclude=node_modules --exclude=.git

# Upload via SCP
scp project.tar.gz username@your-server:/path/to/home

# SSH ke server
ssh username@your-server

# Extract dan setup
tar -xzf project.tar.gz
mv public/* public_html/
```

## Langkah 4: Konfigurasi Permissions

### 4.1 Set File Permissions
```bash
# Via File Manager atau SSH
chmod 755 bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 4.2 Storage Link (Jika diperlukan)
```bash
# Via SSH atau Terminal Hostinger
php artisan storage:link
```

## Langkah 5: Setup Firebase Database Connection

### 5.1 Test Firebase Connection
```bash
# Via SSH atau Terminal Hostinger
cd /path/to/your/project

# Test koneksi Firebase (jika ada artisan command custom)
php artisan firebase:test
```

### 5.2 Initial Data Setup (Opsional)
1. **Manual**: Tambah data awal via Firebase Console
2. **Seeder**: Jika ada Laravel seeder yang menggunakan Firebase
```bash
php artisan db:seed --force
```

## Langkah 6: Konfigurasi Firebase di Server

### 6.1 Upload Firebase Admin SDK
1. Upload file `run-event-cbb9c-firebase-adminsdk-xxx.json` ke root project 
2. Update path di `.env`:
```env
FIREBASE_CREDENTIALS=/home/username/domains/yourdomain.com/run-event-cbb9c-firebase-adminsdk-xxx.json
```

### 6.2 Set File Permissions untuk Firebase
```bash
# Via SSH atau File Manager
chmod 600 run-event-cbb9c-firebase-adminsdk-xxx.json
```

### 6.3 Verify Firebase Configuration
```bash
# Test apakah Laravel bisa membaca Firebase config
php artisan config:cache
php artisan config:clear
```

## Langkah 7: Setup Domain dan SSL

### 7.1 Domain Configuration
1. **hPanel** → **Domains** → **DNS Zone**
2. Arahkan A record ke IP Hostinger
3. Setup **www** CNAME ke domain utama

### 7.2 SSL Certificate
1. **hPanel** → **SSL** → **Manage**
2. Enable **Force HTTPS Redirect**

## Langkah 8: Optimasi Production

### 8.1 Caching
```bash
# Clear semua cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 8.2 Setup Cron Jobs (Untuk Queue Jobs)
1. **hPanel** → **Advanced** → **Cron Jobs**
2. Tambahkan job:
```bash
# Jalankan setiap menit
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

### 8.3 Setup Queue Worker
Untuk queue jobs yang menggunakan database:
```bash
# Manual run (via SSH)
php artisan queue:work --daemon
```

## Langkah 9: Testing dan Troubleshooting

### 9.1 Test Basic Functionality
- ✅ Homepage loading
- ✅ Firebase Realtime Database connection
- ✅ Firebase authentication
- ✅ Asset loading (CSS, JS)
- ✅ Forms dan CSRF protection
- ✅ Blade templates dengan Firebase integration

### 9.2 Common Issues

#### Issue: 500 Internal Server Error
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Common fixes:
chmod -R 775 storage bootstrap/cache
php artisan key:generate
php artisan config:cache
```

#### Issue: Assets tidak loading
```bash
# Regenerate asset manifest
npm run build
php artisan config:cache
```

#### Issue: Firebase connection error
- Verifikasi Firebase credentials dan path file JSON
- Pastikan `FIREBASE_DATABASE_URL` benar di `.env`
- Check Firebase Console untuk status project
- Pastikan Firebase Rules mengizinkan akses aplikasi


## Langkah 10: Monitoring dan Maintenance

### 10.1 Setup Error Monitoring
```env
# .env - Log ke file untuk monitoring
LOG_CHANNEL=single
LOG_LEVEL=error
```

### 10.2 Regular Maintenance
```bash
# Weekly tasks
php artisan cache:clear
php artisan queue:restart
php artisan config:cache

# Check logs
tail -n 100 storage/logs/laravel.log
```

### 10.3 Backup Strategy
- **Firebase Database**: Export data via Firebase Console secara berkala
- **Files**: Download via File Manager bulanan
- **Code**: Backup repository Git
- **Firebase Admin SDK**: Backup file JSON credential dengan aman

## Security Checklist

- ✅ `APP_DEBUG=false` di production
- ✅ `APP_ENV=production`
- ✅ Strong database password
- ✅ Firebase credentials aman
- ✅ SSL certificate aktif
- ✅ File permissions benar (755/775)
- ✅ `.env` tidak accessible via web

## URLs Penting

- **Homepage**: https://yourdomain.com
- **hPanel**: https://hpanel.hostinger.com
- **Firebase Console**: https://console.firebase.google.com
- **File Manager**: Via hPanel → File Manager

## Kontak Support

Jika mengalami masalah:
1. **Hostinger Support**: 24/7 live chat
2. **Laravel Documentation**: https://laravel.com/docs
3. **Firebase Console**: https://console.firebase.google.com

---

**Selamat! Aplikasi Laravel Anda telah berhasil di-deploy ke Hostinger! 🎉**

> **Catatan**: Tutorial ini dibuat untuk deployment ke shared hosting Hostinger. Untuk VPS, langkah-langkahnya mungkin sedikit berbeda terutama pada bagian server configuration dan permissions.
