<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

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

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
#   k a s i r _ a p p 
 
 


Clone the repository to your local environment: git clone https://github.com/lepresk/filament-reactive-bug.git

Install all dependencies and Filament: Run composer install and php artisan filament:install --panels


# .env

APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:kAhCRnDnNQdWk0aEwil4F3iVq3qPrelpetlvZd0ArL8=
APP_DEBUG=true
APP_URL=http://kasir_app.test

APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID

APP_MAINTENANCE_DRIVER=file
# APP_MAINTENANCE_STORE=database

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=    
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
# CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
"# kasir_app"  git init git add README.md git commit -m "first commit" git branch -M main git remote add origin https://github.com/Aston-Delfan/kasir_app.git git push -u origin main
#   k a s i r _ a p p 
 
 




1.Aktifkan extension php pada laragon yaitu intl, zip opcache

2.Install Laravel menggunakan quick app->Laravel

3.Install filament dan panelsnya:
composer require filament/filament -W
php artisan filament:install --panels

3.buat user:
php artisan make:filament-user

4.buat model:
contoh:php artisan make:model Pelanggan -m

5.buka providers/AppServiceProvider.php masukkan:
"use Illuminate\Database\Eloquent\Model;"
masukkan "Model::unguard();" pada boot()

6.buat resource untuk modelnya:
contoh:
php artisan make:filament-resource Pelanggan

untuk formya:
Forms\Components\TextInput::make('name')
untuk tablenya:
Tables\Columns\TextColumn::make('name'),

custom for edit or create:
protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}

7.pengaturan Bahasa, pada file env ganti en menjadi id
-dan jalankan command ini:
php artisan vendor:publish --tag=filament-panels-translations



php artisan vendor:publish --tag=filament-actions-translations

php artisan vendor:publish --tag=filament-forms-translations

php artisan vendor:publish --tag=filament-infolists-translations

php artisan vendor:publish --tag=filament-notifications-translations

php artisan vendor:publish --tag=filament-tables-translations

php artisan vendor:publish --tag=filament-translations


-benerin navigasi, form dan table: label,required,unique(ignoreRecord: true),searchable()

Laravel lang:
composer require laravel-lang/common
Soft-wrap

-jalankan ini untuk meningkatkan performa Laravel:
php artisan icons:cache


8.install plugins
composer require joaopaulolndev/filament-edit-profile
-setting php di laragon cari php.ini-> cari upload_tmp_dir = C:/laragon/tmp
-translation:
php artisan vendor:publish --tag="filament-edit-profile-translations"
-migration:
php artisan vendor:publish --tag="filament-edit-profile-migrations"
php artisan migrate

-tambahkan di adminpanelprovider.php:
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
 
->plugins([
    FilamentEditProfilePlugin::make()
        ->setIcon('heroicon-o-user')
])

-menambahkan bahasa indonesia folder lang->vendor->filament-edit-profile->copy folder en dan ganti jadi id lalu ganti menjadi ini:

    'title' => 'Edit Profil',
    'profile_information' => 'Informasi Profil',
    'profile_information_description' => 'Perbarui informasi profil dan alamat email Anda.',
    'name' => 'Nama',
    'email' => 'Email',
    'avatar' => 'Foto',
    'password' => 'Kata Sandi',
    'update_password' => 'Perbarui Kata Sandi',
    'current_password' => 'Kata Sandi Saat Ini',
    'new_password' => 'Kata Sandi Baru',
    'confirm_password' => 'Konfirmasi Kata Sandi',
    'ensure_your_password' => 'Pastikan akun Anda menggunakan kata sandi yang panjang dan acak untuk keamanan.',
    'delete_account' => 'Hapus Akun',
    'delete_account_description' => 'Hapus akun Anda secara permanen.',
    'yes_delete_it' => 'Ya, hapus!',
    'are_you_sure' => 'Apakah Anda yakin ingin menghapus akun Anda? Ini tidak dapat dibatalkan!',
    'incorrect_password' => 'Kata sandi yang Anda masukkan salah. Silakan coba lagi.',
    'user_load_error' => 'Objek pengguna yang terautentikasi harus merupakan model Eloquent agar halaman profil dapat memperbaruinya.',
    'delete_account_card_description' => 'Setelah akun Anda dihapus, semua data dan sumber daya Anda akan dihapus secara permanen. Sebelum menghapus akun, unduh data atau informasi yang ingin Anda simpan.',
    'saved_successfully' => 'Informasi profil Anda telah berhasil disimpan.',
    'custom_fields' => 'Bidang Kustom',
    'custom_fields_description' => 'Perbarui bidang kustom Anda.',
    'save' => 'Simpan',
    'token_name' => 'Nama Token',
    'token_abilities' => 'Kemampuan',
    'token_created_at' => 'Dibuat pada',
    'token_expires_at' => 'Berlaku hingga',
    'token_section_title' => 'Token API',
    'token_section_description' => 'Kelola token API yang memungkinkan layanan pihak ketiga mengakses aplikasi ini atas nama Anda.',
    'token_action_label' => 'Buat Token',
    'token_modal_heading' => 'Buat',
    'token_create_notification' => 'Token berhasil dibuat!',
    'token_helper_text' => 'Token Anda hanya ditampilkan sekali saat pembuatan. Jika Anda kehilangan token, Anda perlu menghapusnya dan membuat yang baru.',
    'token_modal_heading_2' => 'Salin Token Akses Pribadi',
    'token_empty_state_heading' => 'Buat token pertama Anda',
    'token_empty_state_description' => 'Buat token akses pribadi untuk memulai.',
    'browser_section_title' => 'Sesi Browser',
    'browser_section_description' => 'Kelola dan keluar dari sesi aktif Anda di browser dan perangkat lain.',
    'browser_sessions_content' => 'Jika perlu, Anda dapat keluar dari semua sesi browser Anda di semua perangkat. Beberapa sesi terbaru Anda terdaftar di bawah; daftar ini mungkin tidak lengkap. Jika Anda merasa akun Anda telah dikompromikan, perbarui kata sandi Anda.',
    'browser_sessions_device' => 'Perangkat ini',
    'browser_sessions_last_active' => 'Terakhir aktif',
    'browser_sessions_log_out' => 'Keluar dari Sesi Browser Lain',
    'browser_sessions_confirm_pass' => 'Masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin keluar dari sesi browser lain di semua perangkat.',
    'browser_sessions_logout_success_notification' => 'Semua sesi browser lainnya telah berhasil keluar.',



9.role for users
php artisan make:migration add_role_to_users --table=users

-up
$table->string('role')->nullable();
-down
$table->dropColumn('role');

-tambahkan role pada models users

-buat resource:
php artisan make:filament-resource User

-di resource table column tambahkan name, email, role, created_at
-di resource form tambahkan name, email, password ->password() ->revealable()
Select::make('role')->options(['admin' => 'Admin', 'user' => 'User'])

pada text input password tambahkan ini:
->dehydrated(fn ($state) => filled($state))
->required(fn (string $context): bool => $context === 'create'),


10.Mengatur hak akses untuk users
php artisan make:policy UserPolicy --model=User
ganti // dengan return true;

menambahkan hak akses:
return $user-> == 'admin';

untuk deleteAny copy delete dan hapus User $model

buat untuk yang lain juga.


11.Buat data pelanggan
buat model dan resource supplier dan produks

pada produk jika foreign key maka:
$table->foreign('category_id')
        ->references('id')
        ->on('produks')
        ->onDelete('cascade');

pada stok edit:
->disabledOn('edit')

12. Buat data pembelian dan detail pembelian:
buat model dan resourcenya.
pada foreignId('idnya')->constrained('tablenya')

buat form untuk pembeliannya yang berisi tanggal dengan ->default(now()) menggunakan select untuk nama perusahaanya dan akan reactiv kepada text input setelahnya

ubah create pembeliannya agar menjadi selanjutnya dengan mencari getFormActions dan getRedirectUrl di CreateRecord

13. buat detail pembeliannya
salin resource detail pembeliannya
buat relasi di modelnya

14.menampilkan detail pembelian menggunakan widget
copy getFormActions dan getRedirectUrl pada create pembelian, ganti id nya dengan pembelian_id

buat widgetnya:
php artisan make:filament-widget DetailPembelianWidget --table
kemudian ketik DetailPembelianResource dan admin

buat relasi di detail pembeliannya ke produk

kemudian di create detail pembelian pastekan getFooterWidgets()

15.buat total harga di widgetnya
buat juga edit actionnya

16. grid untuk merapikan tampilan form

17.menampilkan data relasi di pembelian table


18.observer:
php artisan make:observer NamaObserver --model=NamaModel
tambahkan:
DetailPembelian::observe(DetailPembelianObserver::class); pada AppServiceProvider


19. membuat view
-Update actions PembelianResource.php untuk menambahkan ViewAction
-Buat ViewPembelian page
-Update getPages method di PembelianResource
-Konfigurasi Widget

20. tambahkan widget di edit pembelian
-copy dari viewPembelian lalu ubah nilai 'isViewOnly' => true menjadi false



untuk mengatur navigation pakai:
->navigationGroups([
    'Transaksi',
    'Data Pelanggan & Supplier',
    'Laporan',
])

1.Untuk generate pdf
composer require barryvdh/laravel-dompdf


memperbaiki migration:
php artisan make:migration rename_saya_to_users --table=users

php artisan migrate:status
php artisan migrate:rollback --step=1





clone kasir_app:
https://github.com/Aston-Delfan/kasir_app.git


login git:
-Tekan Windows + R, lalu ketik:
control /name Microsoft.CredentialManager
-Akan terbuka Windows Credential Manager
-Cari entri bernama:
git:https://github.com
-Klik → Remove / Delete

push ke repo:
-cd /path/ke/proyekmu
-Inisialisasi Git (jika belum)
git init
-Tambah Semua File dan Commit
git add .
git commit -m "Initial commit"
-Hubungkan ke GitHub Remote
git remote add origin https://github.com/Aston-Delfan/ukk_kasir.git
-Push ke GitHub
git branch -M main
git push -u origin main

username:Aston-Delfan
password:Asuton8923Derufan@
