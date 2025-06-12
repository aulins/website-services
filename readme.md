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


# halaman dashboard admin

Sidebar kiri: navigasi admin (Dashboard, Katalog, Pesanan, Laporan, Ulasan)
Ringkasan pesanan selesai & pendapatan bulan ini
(opsional nanti) Chart statistik sederhana

## struktur folder
application/
├── controllers/
│   └── Admin.php
├── views/
│   ├── admin/
│   │   └── dashboard.php
│   ├── templates/
│   │   ├── admin_header.php
│   │   └── admin_footer.php

## buat template header admin
application/views/templates/admin_header.php:

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $title ?? 'Admin Dashboard' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      display: flex;
    }
    aside {
      width: 220px;
      min-height: 100vh;
      background: #f8f9fa;
    }
    main {
      flex: 1;
      padding: 20px;
    }
    .nav-link.active {
      font-weight: bold;
      color: #0d6efd !important;
    }
  </style>
</head>
<body>
  <aside class="p-3">
    <h4>Admin Panel</h4>
    <nav class="nav flex-column">
      <a class="nav-link <?= uri_string() == 'admin' ? 'active' : '' ?>" href="<?= base_url('admin') ?>">Dashboard</a>
      <a class="nav-link" href="#">Katalog</a>
      <a class="nav-link" href="#">Pesanan</a>
      <a class="nav-link" href="#">Laporan</a>
      <a class="nav-link" href="#">Ulasan</a>
      <a class="nav-link text-danger" href="<?= base_url('logout') ?>">Logout</a>
    </nav>
  </aside>
  <main>

## buat template footer admin

application/views/templates/admin_footer.php:

  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

## buat controller Admin.php

application/controllers/Admin.php:

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

  public function __construct() {
    parent::__construct();
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }

    $this->load->database();
  }

  public function index() {
    // Jumlah pesanan selesai bulan ini
    $this->db->where('status', 'completed');
    $this->db->where('MONTH(created_at)', date('m'));
    $this->db->where('YEAR(created_at)', date('Y'));
    $pesanan_selesai = $this->db->count_all_results('tb_orders');

    // Total pendapatan bulan ini
    $this->db->select_sum('tb_catalogues.price');
    $this->db->from('tb_orders');
    $this->db->join('tb_catalogues', 'tb_catalogues.catalogue_id = tb_orders.catalogue_id');
    $this->db->where('tb_orders.status', 'completed');
    $this->db->where('MONTH(tb_orders.created_at)', date('m'));
    $this->db->where('YEAR(tb_orders.created_at)', date('Y'));
    $result = $this->db->get()->row();

    $pendapatan = $result->price ?? 0;

    $data['title'] = 'Dashboard Admin';
    $data['pesanan_selesai'] = $pesanan_selesai;
    $data['pendapatan'] = $pendapatan;

    $this->load->view('templates/admin_header', $data);
    $this->load->view('admin/dashboard', $data);
    $this->load->view('templates/admin_footer');
  }
}

## buat view dashboard.php
application/views/admin/dashboard.php:

<h2>Ringkasan dan Statistik Pesanan</h2>

<div class="row my-4">
  <div class="col-md-6">
    <div class="bg-primary text-white p-4 rounded">
      <h5>Pesanan Selesai Bulan Ini</h5>
      <h2><?= $pesanan_selesai ?></h2>

    </div>

  </div>
  <div class="col-md-6">
    <div class="bg-success text-white p-4 rounded">
      <h5>Total Pendapatan Bulan Ini</h5>
      <h2>Rp <?= number_format($pendapatan, 0, ',', '.') ?></h2>
    </div>
  </div>
</div>

## hasil

akses: http://localhost/website_services/admin

# fitur CRUD katalog admin

Admin dapat:

Melihat daftar katalog jasa
Menambah katalog
Mengedit katalog
Menghapus katalog

## struktur folder:

application/
├── controllers/
│ └── Catalogue.php ← controller CRUD katalog
├── models/
│ └── Catalogue_model.php ← model untuk akses db
├── views/
│ └── admin/
│ ├── katalog_list.php ← daftar katalog
│ └── katalog_form.php ← form tambah/edit katalog

## buat model Catalogue_model.php

application/models/Catalogue_model.php:

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogue_model extends CI_Model {

  public function get_all() {
    return $this->db->order_by('created_at', 'DESC')->get('tb_catalogues')->result();
  }

  public function get($id) {
    return $this->db->get_where('tb_catalogues', ['catalogue_id' => $id])->row();
  }

  public function insert($data) {
    return $this->db->insert('tb_catalogues', $data);
  }

  public function update($id, $data) {
    return $this->db->where('catalogue_id', $id)->update('tb_catalogues', $data);
  }

  public function delete($id) {
    return $this->db->delete('tb_catalogues', ['catalogue_id' => $id]);
  }
}

## buat controller Catalogue.php
application/controllers/Catalogue.php:

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogue extends CI_Controller {

  public function __construct() {
    parent::__construct();
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }
    $this->load->model('Catalogue_model');
  }

  public function index() {
    $data['title'] = 'Kelola Katalog';
    $data['catalogues'] = $this->Catalogue_model->get_all();
    $this->load->view('templates/admin_header', $data);
    $this->load->view('admin/katalog_list', $data);
    $this->load->view('templates/admin_footer');
  }

  public function create() {
    $data['title'] = 'Tambah Katalog';
    if ($this->input->post()) {
      $insert = [
        'package_name' => $this->input->post('package_name'),
        'categories'   => $this->input->post('categories'),
        'description'  => $this->input->post('description'),
        'price'        => $this->input->post('price'),
        'status_publish' => $this->input->post('status_publish'),
        'created_at'   => date('Y-m-d H:i:s')
      ];
      $this->Catalogue_model->insert($insert);
      redirect('catalogue');
    }
    $this->load->view('templates/admin_header', $data);
    $this->load->view('admin/katalog_form');
    $this->load->view('templates/admin_footer');
  }

  public function edit($id) {
    $data['title'] = 'Edit Katalog';
    $data['katalog'] = $this->Catalogue_model->get($id);
    if ($this->input->post()) {
      $update = [
        'package_name' => $this->input->post('package_name'),
        'categories'   => $this->input->post('categories'),
        'description'  => $this->input->post('description'),
        'price'        => $this->input->post('price'),
        'status_publish' => $this->input->post('status_publish'),
      ];
      $this->Catalogue_model->update($id, $update);
      redirect('catalogue');
    }
    $this->load->view('templates/admin_header', $data);
    $this->load->view('admin/katalog_form', $data);
    $this->load->view('templates/admin_footer');
  }

  public function delete($id) {
    $this->Catalogue_model->delete($id);
    redirect('catalogue');
  }
}

## buat view katalog_list.php
application/views/admin/katalog_list.php:

<h3>Daftar Katalog</h3>
<a href="<?= base_url('catalogue/create') ?>" class="btn btn-primary mb-3">Tambah Katalog</a>
<table class="table table-bordered">
  <thead>
    <tr>
      <th>#</th>
      <th>Nama Paket</th>
      <th>Kategori</th>
      <th>Harga</th>
      <th>Status</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $no=1; foreach($catalogues as $row): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= $row->package_name ?></td>
      <td><?= $row->categories ?></td>
      <td>Rp <?= number_format($row->price, 0, ',', '.') ?></td>
      <td><?= $row->status_publish ?></td>
      <td>
        <a href="<?= base_url('catalogue/edit/'.$row->catalogue_id) ?>" class="btn btn-sm btn-warning">Edit</a>
        <a href="<?= base_url('catalogue/delete/'.$row->catalogue_id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus katalog ini?')">Hapus</a>
      </td>
    </tr>
    <?php endforeach ?>
  </tbody>
</table>

## view kalatog_form.php

application/views/admin/katalog_form.php:

<h3><?= $title ?></h3>
<form method="post">
  <div class="mb-3">
    <label>Nama Paket</label>
    <input type="text" name="package_name" class="form-control" required value="<?= $katalog->package_name ?? '' ?>">
  </div>
  <div class="mb-3">
    <label>Kategori</label>
    <select name="categories" class="form-control" required>
      <option value="Toko Online" <?= (isset($katalog) && $katalog->categories == 'Toko Online') ? 'selected' : '' ?>>Toko Online</option>
      <option value="Perusahaan" <?= (isset($katalog) && $katalog->categories == 'Perusahaan') ? 'selected' : '' ?>>Perusahaan</option>
      <option value="Custom" <?= (isset($katalog) && $katalog->categories == 'Custom') ? 'selected' : '' ?>>Custom</option>
    </select>
  </div>
  <div class="mb-3">
    <label>Deskripsi</label>
    <textarea name="description" class="form-control"><?= $katalog->description ?? '' ?></textarea>
  </div>
  <div class="mb-3">
    <label>Harga</label>
    <input type="number" name="price" class="form-control" required value="<?= $katalog->price ?? '' ?>">
  </div>
  <div class="mb-3">
    <label>Status Publish</label>
    <select name="status_publish" class="form-control" required>
      <option value="Y" <?= (isset($katalog) && $katalog->status_publish == 'Y') ? 'selected' : '' ?>>Y</option>
      <option value="N" <?= (isset($katalog) && $katalog->status_publish == 'N') ? 'selected' : '' ?>>N</option>
    </select>
  </div>
  <button class="btn btn-success">Simpan</button>
</form>

## testing

Akses: http://localhost/website_services/catalogue
Tambah, edit, hapus katalog
Data langsung masuk ke tabel tb_catalogues
