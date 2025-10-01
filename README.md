# RUN DEV - Event Lari untuk Developer

Aplikasi web untuk event lari khusus developer yang dibangun dengan Laravel dan Firebase.

## Deskripsi Aplikasi

RUN DEV adalah platform pendaftaran event lari yang dirancang khusus untuk komunitas developer. Aplikasi ini menyediakan:

- **Pendaftaran Online**: Form pendaftaran dengan berbagai kategori lomba (5K, 10K, Half Marathon)
- **Dashboard Peserta**: Portal untuk peserta melihat status pendaftaran dan pembayaran
- **Admin Panel**: Interface untuk mengelola peserta dan event
- **Firebase Integration**: Autentikasi dan database real-time menggunakan Firebase

## Tech Stack

- **Backend**: Laravel 12 dengan PHP 8.2+
- **Frontend**: Blade Templates dengan Vite dan Tailwind CSS
- **Database**: Firebase Realtime Database
- **Authentication**: Firebase Auth
- **Build Tool**: Vite
- **Styling**: Tailwind CSS

## Fitur Utama

- ✅ Pendaftaran peserta dengan validasi lengkap
- ✅ Sistem autentikasi Firebase
- ✅ Dashboard peserta dan admin
- ✅ Manajemen pembayaran
- ✅ Email notifikasi
- ✅ Responsive design
- ✅ Firebase real-time database integration

## Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+ dan NPM
- Firebase Project dengan Realtime Database

### Installation Steps

1. **Clone repository**
```bash
git clone <repository-url>
cd run-dev-event
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install Node.js dependencies**
```bash
npm install
```

4. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configure Firebase**
- Update `.env` dengan Firebase credentials:
```env
FIREBASE_CREDENTIALS=/path/to/firebase-admin-sdk.json
FIREBASE_DATABASE_URL=https://your-project-default-rtdb.firebaseio.com

MIX_FIREBASE_API_KEY=your_api_key
MIX_FIREBASE_AUTH_DOMAIN=your-project.firebaseapp.com
MIX_FIREBASE_DATABASE_URL=https://your-project-default-rtdb.firebaseio.com/
MIX_FIREBASE_PROJECT_ID=your-project-id
MIX_FIREBASE_STORAGE_BUCKET=your-project.appspot.com
MIX_FIREBASE_MESSAGING_SENDER_ID=your_sender_id
MIX_FIREBASE_APP_ID=your_app_id
```

6. **Build assets**
```bash
npm run build
# or for development
npm run dev
```

7. **Start development server**
```bash
php artisan serve
```

## Deployment

Lihat file `TUTORIAL_DEPLOY_HOSTINGER.md` untuk panduan lengkap deployment ke Hostinger.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
