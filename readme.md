# Struktur Dasar

website_services/
├── application/
│ ├── controllers/
│ ├── models/
│ ├── views/
│ │ ├── templates/ <-- layout header/footer
│ │ ├── admin/ <-- halaman admin (dashboard, katalog, dst)
│ │ └── user/ <-- halaman user (home, katalog, ulasan, dst)
│ ├── config/
│ └── ... (autoload, routes, dsb)
├── system/
├── uploads/
│ ├── catalogues/
│ └── logos/
└── index.php

# Konfigurasi Awal

1. buka file:
   `/application/config/config.php`

2. edit bagian:
   `$config['base_url'] = 'http://localhost/website_services/';`

3. Lalu, di /application/config/autoload.php, autoload helper dan library:

```
$autoload['libraries'] = array('database', 'session');
$autoload['helper'] = array('url', 'form');

```
