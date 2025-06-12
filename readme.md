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

# Buat Template Halaman Dasar (Bootstrap 5)

1. application/views/templates/header.php

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $title ?? 'Jasa Website' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="<?= base_url() ?>">Jasa Website</a>
  </div>
</nav>
<div class="container mt-4">

2. application/views/templates/footer.php

</div> <!-- end container -->
<footer class="bg-light text-center p-3 mt-5">
  <small>&copy; <?= date('Y') ?> Jasa Website</small>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

# Buat Controller Awal & Landing Page

1. application/controllers/User.php:

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

  public function index() {
    $data['title'] = 'Beranda';
    $this->load->view('templates/header', $data);
    $this->load->view('user/home');
    $this->load->view('templates/footer');
  }
}

2. application/views/user/home.php:

<h1 class="text-center">Pembuatan Website Profesional</h1>
<div class="row text-center mt-4">
  <div class="col-md-4"><div class="border p-3">Katalog 1</div></div>
  <div class="col-md-4"><div class="border p-3">Katalog 2</div></div>
  <div class="col-md-4"><div class="border p-3">Katalog 3</div></div>
</div>

# Setting database

1. buka /application/config/database.php:

```$db['default'] = array(
    'dsn'   => '',
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',                 // XAMPP default biasanya kosong
    'database' => 'jasa_website',     // ← ganti dengan nama database kamu
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    ...
);

```
# Routing halaman awal

1. Buka: /application/config/routes.php:
```
$route['default_controller'] = 'user';
```
# Halaman Login Admin

Admin bisa login menggunakan username & password
Hanya admin yang bisa mengakses halaman dashboard
Data login disimpan di tabel tb_users

## Buat view form login
application/views/auth/login.php:

<div class="row justify-content-center">
  <div class="col-md-4">
    <h3 class="text-center">Login Admin</h3>
    <?php if ($this->session->flashdata('error')): ?>

      <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>
    <form method="post" action="<?= base_url('auth/login') ?>">
      <div class="mb-3">
        <label for="username">Username</label>
        <input type="text" class="form-control" name="username" required>
      </div>
      <div class="mb-3">
        <label for="password">Password</label>
        <input type="password" class="form-control" name="password" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

  </div>
</div>

## Buatk controller auth.php

application/controllers/Auth.php:

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('User_model');
    $this->load->library('session');
  }

  public function index() {
    $this->load->view('templates/header', ['title' => 'Login']);
    $this->load->view('auth/login');
    $this->load->view('templates/footer');
  }

  public function login() {
    $username = $this->input->post('username');
    $password = $this->input->post('password');
    $user = $this->User_model->get_by_username($username);

    if ($user && password_verify($password, $user->password)) {
      $this->session->set_userdata('user_id', $user->user_id);
      redirect('admin');
    } else {
      $this->session->set_flashdata('error', 'Username atau Password salah!');
      redirect('auth');
    }
  }

  public function logout() {
    $this->session->sess_destroy();
    redirect('auth');
  }
}

## buat model user_model.php
application/models/User_model.php:

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

  public function get_by_username($username) {
    return $this->db->get_where('tb_users', ['username' => $username])->row();
  }
}


## tambahkan admin dummy ke database
Buka phpMyAdmin, jalankan query ini:

INSERT INTO tb_users (username, password)
VALUES ('admin', '$2y$10$GpZKcLskHZutTD5boIRfV.CraV.rEB53erVrxCjVR59dJD72HJ82G'); 

Password-nya adalah: admin123
(Dienkripsi dengan password_hash())

## routing

application/config/routes.php:
Tambahkan ini:

$route['login'] = 'auth/index';
$route['logout'] = 'auth/logout';


## hasil
Buka http://localhost/website_services/login

Masukkan:
Username: admin
Password: admin123
>> akan error kalau .htaccess nya belumm dibuat :http://localhost/website_services/index.php/login

## hilangkan index.php

1. bikin file di root folder : .htaccess

2. isi dengan :

RewriteEngine On
RewriteBase /website_services/
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L]


3. edit file application/config/config.php:

cari baris : 
$config['index_page'] = 'index.php';

ubah:
$config['index_page'] = '';


4. Pastikan Apache mod_rewrite Aktif
Buka XAMPP → Config (Apache) → httpd.conf

Pastikan baris berikut tidak dikomentari (tidak ada # di depannya):
LoadModule rewrite_module modules/mod_rewrite.so

Simpan file dan restart Apache dari XAMPP Control Panel


# masalah login salah password

1. bikin file hash.php di root folder isi hash.php:

<?php
echo password_hash('admin123', PASSWORD_DEFAULT);

2. jalankan : http://localhost/website_services/hash.php

3. kemudian masukkan copy hasil nya dan edit password di table mysql
