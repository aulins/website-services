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

# menambahkan dropdown kategori agar lebih bervariasi

## ubah struktur ENUM di tb_catalogue

jalan di SQL di phpMyAdmin:

ALTER TABLE tb_catalogues
MODIFY categories ENUM(
'Toko online (e-Commerce)',
'Company Profile',
'Personal Profile Website',
'Wedding',
'Web3 / Blockchain Project',
'Blog / Artikel Pribadi',
'Landing Page',
'Portfolio',
'Kursus Online (e-Learning)',
'Custom'
) NOT NULL;

## Update katalog_form.php

Ganti bagian dropdown <select name="categories"> menjadi:

<div class="mb-3">
  <label>Kategori</label>
  <select name="categories" class="form-control" required>
    <?php
      $opsi = [
        'Toko online (e-Commerce)',
        'Company Profile',
        'Personal Profile Website',
        'Wedding',
        'Web3 / Blockchain Project',
        'Blog / Artikel Pribadi',
        'Landing Page',
        'Portfolio',
        'Kursus Online (e-Learning)',
        'Custom'
      ];
      foreach ($opsi as $kategori) {
        $selected = (isset($katalog) && $katalog->categories == $kategori) ? 'selected' : '';
        echo "<option value=\"$kategori\" $selected>$kategori</option>";
      }
    ?>
  </select>
</div>

# Fitur Form Pemesanan

Mengisi form pemesanan (nama, email, nomor HP, paket, deskripsi, logo opsional)
Data masuk ke tabel tb_orders
File logo disimpan ke folder uploads/logos/

## struktur folder

application/
├── controllers/
│ └── Order.php ← controller untuk form pemesanan
├── models/
│ └── Order_model.php ← model untuk simpan data order
├── views/
│ └── user/
│ └── order_form.php ← halaman form pemesanan
uploads/
└── logos/ ← tempat simpan file logo (sudah ada)

## buat application/models/Order_model.php:

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model {

  public function insert($data) {
    return $this->db->insert('tb_orders', $data);
  }
}

## buat application/controllers/Order.php:

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Order_model');
    $this->load->database();
    $this->load->helper(['form', 'url']);
  }

  public function create() {
    $data['title'] = 'Form Pemesanan';
    $data['catalogues'] = $this->db->where('status_publish', 'Y')->get('tb_catalogues')->result();
    $this->load->view('templates/header', $data);
    $this->load->view('user/order_form', $data);
    $this->load->view('templates/footer');
  }

  public function store() {
    $logo_name = null;

    // Upload logo jika ada
    if ($_FILES['logo']['name']) {
      $config['upload_path']   = './uploads/logos/';
      $config['allowed_types'] = 'jpg|jpeg|png';
      $config['max_size']      = 2048;
      $config['file_name']     = time().'_'.$_FILES['logo']['name'];

      $this->load->library('upload', $config);

      if ($this->upload->do_upload('logo')) {
        $logo_name = $this->upload->data('file_name');
      } else {
        $this->session->set_flashdata('error', $this->upload->display_errors());
        redirect('order/create');
      }
    }

    $data = [
      'catalogue_id'   => $this->input->post('catalogue_id'),
      'name'           => $this->input->post('name'),
      'email'          => $this->input->post('email'),
      'phone_number'   => $this->input->post('phone_number'),
      'project_deadline' => $this->input->post('project_deadline'),
      'logo'           => $logo_name,
      'status'         => 'requested',
      'created_at'     => date('Y-m-d H:i:s')
    ];

    $this->Order_model->insert($data);
    $this->session->set_flashdata('success', 'Pemesanan berhasil dikirim!');
    redirect('order/create');
  }
}

## buat application/views/user/order_form.php:

<h2>Form Pemesanan</h2>

<?php if ($this->session->flashdata('success')): ?>
  <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
<?php elseif ($this->session->flashdata('error')): ?>
  <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
<?php endif; ?>

<form method="post" action="<?= base_url('order/store') ?>" enctype="multipart/form-data">
  <div class="mb-3">
    <label>Nama</label>
    <input type="text" name="name" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>No HP</label>
    <input type="text" name="phone_number" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Pilihan Paket</label>
    <select name="catalogue_id" class="form-control" required>
      <option value="">-- Pilih Paket --</option>
      <?php foreach ($catalogues as $row): ?>
        <option value="<?= $row->catalogue_id ?>"><?= $row->package_name ?> - Rp <?= number_format($row->price,0,',','.') ?></option>
      <?php endforeach ?>
    </select>
  </div>
  <div class="mb-3">
    <label>Deadline Proyek</label>
    <input type="date" name="project_deadline" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Logo (Opsional)</label>
    <input type="file" name="logo" class="form-control">
  </div>
  <button type="submit" class="btn btn-success">Kirim Pemesanan</button>
</form>

## tambahkan routing

application/config/routes.php:

$route['order/create'] = 'order/create';
$route['order/store'] = 'order/store';

## testing

http://localhost/website_services/order/create

✅ Isi form
✅ Pilih paket
✅ Submit
✅ Cek tabel tb_orders di phpMyAdmin
✅ Cek folder /uploads/logos/ untuk logo yang terupload

# fitur daftar pemesanan - admin

Admin dapat:

Melihat semua pesanan dari pengguna
Mengetahui status (requested, approved, completed, rejected)
Melihat info paket, nama pemesan, deadline, dan logo (jika ada)
Mengubah status pesanan (edit status)

## struktur folder

application/
├── controllers/
│ └── OrderAdmin.php ← controller khusus admin
├── views/
│ └── admin/
│ └── pesanan_list.php ← halaman daftar pesanan

## buat controller application/controllers/OrderAdmin.php:

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OrderAdmin extends CI_Controller {

  public function __construct() {
    parent::__construct();
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }
    $this->load->database();
  }

  public function index() {
    $this->db->select('tb_orders.*, tb_catalogues.package_name');
    $this->db->from('tb_orders');
    $this->db->join('tb_catalogues', 'tb_orders.catalogue_id = tb_catalogues.catalogue_id');
    $this->db->order_by('tb_orders.created_at', 'DESC');
    $data['orders'] = $this->db->get()->result();

    $data['title'] = 'Daftar Pesanan';
    $this->load->view('templates/admin_header', $data);
    $this->load->view('admin/pesanan_list', $data);
    $this->load->view('templates/admin_footer');
  }

  public function update_status($id) {
    $status = $this->input->post('status');
    $this->db->where('order_id', $id)->update('tb_orders', ['status' => $status]);
    redirect('orderadmin');
  }
}


## buat view application/views/admin/pesanan_list.php:

<h3>Daftar Pesanan</h3>

<table class="table table-bordered">
  <thead>
    <tr>
      <th>Nama</th>
      <th>Email</th>
      <th>No HP</th>
      <th>Paket</th>
      <th>Deadline</th>
      <th>Logo</th>
      <th>Status</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($orders as $row): ?>

    <tr>
      <td><?= $row->name ?></td>
      <td><?= $row->email ?></td>
      <td><?= $row->phone_number ?></td>
      <td><?= $row->package_name ?></td>
      <td><?= $row->project_deadline ?></td>
      <td>
        <?php if ($row->logo): ?>
          <a href="<?= base_url('uploads/logos/'.$row->logo) ?>" target="_blank">Lihat</a>
        <?php else: ?>
          -
        <?php endif ?>
      </td>
      <td><strong><?= strtoupper($row->status) ?></strong></td>
      <td>
        <form method="post" action="<?= base_url('orderadmin/update_status/'.$row->order_id) ?>" class="d-flex">
          <select name="status" class="form-select form-select-sm me-2">
            <option value="requested" <?= $row->status == 'requested' ? 'selected' : '' ?>>Requested</option>
            <option value="approved" <?= $row->status == 'approved' ? 'selected' : '' ?>>Approved</option>
            <option value="completed" <?= $row->status == 'completed' ? 'selected' : '' ?>>Completed</option>
            <option value="rejected" <?= $row->status == 'rejected' ? 'selected' : '' ?>>Rejected</option>
          </select>
          <button class="btn btn-sm btn-primary">Update</button>
        </form>
      </td>
    </tr>
    <?php endforeach ?>

  </tbody>
</table>

## tambahkan routing application/config/routes.php:

$route['orderadmin'] = 'orderadmin/index';
$route['orderadmin/update_status/(:num)'] = 'orderadmin/update_status/$1';

## testing

http://localhost/website_services/orderadmin

🟢 Tampil daftar pesanan
🟢 Klik dropdown status → pilih → klik Update
🟢 Perubahan langsung tersimpan ke tb_orders

# Fitur Ulasan User

Pengguna bisa mengirimkan ulasan + rating
Ulasan disimpan di tb_reviews dengan status is_approved = 'N'
Admin bisa melihat dan menyetujui (Y) agar ulasan tampil ke publik

## struktur folder

application/
├── controllers/
│ ├── Review.php ← form user
│ └── ReviewAdmin.php ← moderasi admin
├── models/
│ └── Review_model.php ← akses ke tb_reviews
├── views/
│ ├── user/
│ │ └── review_form.php ← form tambah ulasan
│ └── admin/
│ └── review_list.php ← daftar ulasan untuk admin

## buat model application/models/Review_model.php:

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Review_model extends CI_Model {

  public function insert($data) {
    return $this->db->insert('tb_reviews', $data);
  }

  public function get_all() {
    $this->db->select('r.*, o.name AS pemesan, c.package_name');
    $this->db->from('tb_reviews r');
    $this->db->join('tb_orders o', 'o.order_id = r.order_id');
    $this->db->join('tb_catalogues c', 'c.catalogue_id = o.catalogue_id');
    $this->db->order_by('r.created_at', 'DESC');
    return $this->db->get()->result();
  }

  public function approve($id) {
    return $this->db->where('review_id', $id)->update('tb_reviews', ['is_approved' => 'Y']);
  }
}

## buat controller application/controllers/Review.php:

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Review extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Review_model');
    $this->load->database();
  }

  public function create() {
    $data['title'] = 'Form Ulasan';
    $data['orders'] = $this->db->get_where('tb_orders', ['status' => 'completed'])->result(); // hanya yang selesai

    $this->load->view('templates/header', $data);
    $this->load->view('user/review_form', $data);
    $this->load->view('templates/footer');
  }

  public function store() {
    $data = [
      'order_id'    => $this->input->post('order_id'),
      'name'        => $this->input->post('name'),
      'rating'      => $this->input->post('rating'),
      'comment'     => $this->input->post('comment'),
      'is_approved' => 'N',
      'created_at'  => date('Y-m-d H:i:s')
    ];

    $this->Review_model->insert($data);
    $this->session->set_flashdata('success', 'Ulasan berhasil dikirim dan menunggu persetujuan admin.');
    redirect('review/create');
  }
}

## buat view application/views/user/review_form.php:

<h2>Form Ulasan</h2>

<?php if ($this->session->flashdata('success')): ?>
  <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
<?php endif; ?>

<form method="post" action="<?= base_url('review/store') ?>">
  <div class="mb-3">
    <label>Nama Anda</label>
    <input type="text" name="name" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Pilih Pesanan</label>
    <select name="order_id" class="form-control" required>
      <option value="">-- Pilih Paket --</option>
      <?php foreach ($orders as $o): ?>
        <option value="<?= $o->order_id ?>"><?= $o->name ?> - <?= $o->project_deadline ?></option>
      <?php endforeach ?>
    </select>
  </div>
  <div class="mb-3">
    <label>Rating (1 - 5)</label>
    <input type="number" name="rating" min="1" max="5" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Ulasan</label>
    <textarea name="comment" class="form-control" required></textarea>
  </div>
  <button class="btn btn-primary">Kirim Ulasan</button>
</form>

## buat controller application/controllers/ReviewAdmin.php:

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReviewAdmin extends CI_Controller {

  public function __construct() {
    parent::__construct();
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }
    $this->load->model('Review_model');
  }

  public function index() {
    $data['title'] = 'Moderasi Ulasan';
    $data['reviews'] = $this->Review_model->get_all();

    $this->load->view('templates/admin_header', $data);
    $this->load->view('admin/review_list', $data);
    $this->load->view('templates/admin_footer');
  }

  public function approve($id) {
    $this->Review_model->approve($id);
    redirect('reviewadmin');
  }
}

## buat view application/views/admin/review_list.php:
<h3>Moderasi Ulasan</h3>

<table class="table table-bordered">
  <thead>
    <tr>
      <th>Nama</th>
      <th>Paket</th>
      <th>Rating</th>
      <th>Ulasan</th>
      <th>Status</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($reviews as $r): ?>

    <tr>
      <td><?= $r->name ?></td>
      <td><?= $r->package_name ?></td>
      <td><?= $r->rating ?></td>
      <td><?= $r->comment ?></td>
      <td><?= $r->is_approved == 'Y' ? 'Disetujui' : 'Menunggu' ?></td>
      <td>
        <?php if ($r->is_approved == 'N'): ?>
          <a href="<?= base_url('reviewadmin/approve/'.$r->review_id) ?>" class="btn btn-sm btn-success">Setujui</a>
        <?php else: ?>
          <span class="text-success">✔</span>
        <?php endif ?>
      </td>
    </tr>
    <?php endforeach ?>

  </tbody>
</table>

## tambah routing application/config/routes.php:

$route['review/create'] = 'review/create';
$route['review/store'] = 'review/store';
$route['reviewadmin']   = 'reviewadmin/index';
$route['reviewadmin/approve/(:num)'] = 'reviewadmin/approve/$1';

## testing

Buka: http://localhost/website_services/review/create → isi form → kirim
Buka: http://localhost/website_services/reviewadmin → lihat ulasan → klik “Setujui”

Jika berhasil:

✅ Ulasan tersimpan
✅ Admin bisa menyetujui
✅ Disiapkan untuk ditampilkan ke halaman user (fitur berikutnya)

# menampilkan ulasan di LP

## strukrur folder

application/
├── controllers/
│ └── User.php ← controller landing page
├── views/
│ └── user/
│ └── home.php ← landing page user

## menambahkan query review di controller

$this->load->model('Review_model');
$data['reviews'] = $this->Review_model->get_approved();

JADI:
public function index() {
$data['title'] = 'Beranda';
$this->load->model('Review_model');
$data['reviews'] = $this->Review_model->get_approved();

        $this->load->view('templates/header', $data);
        $this->load->view('user/home');
        $this->load->view('templates/footer');
    }

## Tambahkan Fungsi get_approved() di Review_model

di paling bawah :
public function get_approved() {
$this->db->select('r.name, r.rating, r.comment');
$this->db->from('tb_reviews r');
$this->db->where('is_approved', 'Y');
$this->db->order_by('created_at', 'DESC');
return $this->db->get()->result();
}

## Tambahkan Kode Ulasan di View home.php

Tambahkan di bagian bawah landing page:

<h3 class="mt-5">Ulasan Pengguna</h3>
<div class="row">
  <?php foreach ($reviews as $review): ?>
    <div class="col-md-4">
      <div class="border p-3 mb-3 rounded shadow-sm">
        <strong><?= $review->name ?></strong><br>
        Rating: <?= str_repeat('⭐', $review->rating) ?><br>
        <p><?= $review->comment ?></p>
      </div>
    </div>
  <?php endforeach; ?>
</div>

## testing

http://localhost/website_services/

# upload dan tampilkan gambar katalog

Admin bisa upload gambar saat tambah/edit katalog
Gambar disimpan di uploads/catalogue/
Gambar tampil di halaman katalog pengguna

## stuktur folder yang digunakna

application/
├── controllers/
│ └── Catalogue.php
├── views/
│ ├── admin/katalog_form.php ← tambah upload
│ └── user/katalog.php ← tampilkan gambar katalog
uploads/
└── catalogue/ ← folder simpan gambar katalog

## Perbarui Controller Catalogue.php → Tambah Upload Gambar

📍 Method create() dan edit():
Tambahkan di dalam if ($this->input->post()):

$config['upload_path']   = './uploads/catalogue/';
$config['allowed_types'] = 'jpg|jpeg|png';
$config['max_size']      = 2048;
$config['file_name'] = time() . '\_' . $\_FILES['image']['name'];

$this->load->library('upload', $config);

$image = null;
if (!empty($\_FILES['image']['name'])) {
if ($this->upload->do_upload('image')) {
$image = $this->upload->data('file_name');
} else {
$this->session->set_flashdata('error', $this->upload->display_errors());
redirect(current_url());
}
}

Lalu tambahkan image ke data $insert atau $update:
di method create setelah baris data $insert = []

if ($image) $insert['image'] = $image;
if ($image) $update['image'] = $image;

## Perbarui Form View katalog_form.php

📄 application/views/admin/katalog_form.php
Tambahkan enctype dan field upload:

<form method="post" enctype="multipart/form-data">

tambahkan input:

<div class="mb-3">
  <label>Gambar (opsional)</label>
  <input type="file" name="image" class="form-control">
  <?php if (isset($katalog->image) && $katalog->image): ?>
    <img src="<?= base_url('uploads/catalogue/'.$katalog->image) ?>" alt="gambar" width="120" class="mt-2">
  <?php endif ?>
</div>

## Tampilkan Gambar di Halaman Katalog Pengguna

📄 application/views/user/katalog.php
Di dalam loop daftar katalog:

<h2>Daftar Katalog</h2>

<div class="row">
  <?php foreach ($catalogues as $k): ?>
    <div class="col-md-4">
      <div class="card mb-3">
        <?php if ($k->image): ?>
          <img src="<?= base_url('uploads/catalogue/'.$k->image) ?>" class="card-img-top" alt="<?= $k->package_name ?>">
        <?php endif ?>
        <div class="card-body">
          <h5 class="card-title"><?= $k->package_name ?></h5>
          <p><?= $k->description ?></p>
          <p><strong>Rp <?= number_format($k->price, 0, ',', '.') ?></strong></p>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

✅ PASTIKAN FOLDER UPLOAD
📂 uploads/catalogue/ harus sudah ada dan writeable (chmod 755 / 777 jika perlu)

## tambahkan di controller/User.php:

public function katalog() {
$data['catalogues'] = $this->db->get('tb_catalogues')->result(); // Ambil data katalog
$this->load->view('templates/header');
$this->load->view('user/katalog', $data); // Pastikan data dikirim ke view
$this->load->view('templates/footer');
}

## routing

Pastikan routing di application/config/routes.php sudah ada:

$route['user/katalog'] = 'user/katalog'; // Sesuaikan dengan controller dan method kamu

## testing

Tambah katalog baru → upload gambar → Submit
Cek folder uploads/catalogue/
Lihat halaman katalog user di:
http://localhost/website_services/user/katalog

# fitur laporan penjualan & download laporan untuk admin

Admin bisa melihat rekap pendapatan dan pesanan dalam rentang tanggal tertentu.
Admin bisa mendownload laporan dalam format .csv.

## struktur folder

application/
├── controllers/
│ └── ReportAdmin.php ← controller untuk laporan
├── models/
│ └── Report_model.php ← model untuk query laporan
├── views/
│ └── admin/
│ └── report_list.php ← halaman daftar laporan

## buat model application/models/Report_model.php:

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends CI_Model {

    public function get_order_report($start_date, $end_date) {
        // Join tb_orders dengan tb_catalogues untuk mendapatkan harga paket
        $this->db->select('tb_orders.order_id, tb_orders.name, tb_catalogues.price, tb_orders.created_at');
        $this->db->from('tb_orders');
        $this->db->join('tb_catalogues', 'tb_orders.catalogue_id = tb_catalogues.catalogue_id');
        $this->db->where('tb_orders.status', 'completed');
        $this->db->where('tb_orders.created_at >=', $start_date);
        $this->db->where('tb_orders.created_at <=', $end_date);
        
        return $this->db->get()->result();
    }      

    public function get_sales_report($start_date, $end_date) {
        $this->db->select('SUM(tb_catalogues.price) AS total_sales, COUNT(tb_orders.order_id) AS total_orders');
        $this->db->from('tb_orders');
        $this->db->join('tb_catalogues', 'tb_orders.catalogue_id = tb_catalogues.catalogue_id');
        $this->db->where('tb_orders.status', 'completed');
        $this->db->where('tb_orders.created_at >=', $start_date);
        $this->db->where('tb_orders.created_at <=', $end_date);
        return $this->db->get()->row();
    }
}

## buat controller application/controllers/ReportAdmin.php:
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportAdmin extends CI_Controller {

  public function __construct() {
    parent::__construct();
    if (!$this->session->userdata('user_id')) {
      redirect('login');
    }
    $this->load->model('Report_model');
    $this->load->helper('date');
  }

  public function index() {
    $data['title'] = 'Laporan Penjualan';

    // Ambil tanggal mulai dan selesai
    $start_date = $this->input->post('start_date') ? $this->input->post('start_date') : date('Y-m-01');
    $end_date = $this->input->post('end_date') ? $this->input->post('end_date') : date('Y-m-t');

    // Ambil laporan penjualan dan pesanan
    $data['sales_report'] = $this->Report_model->get_sales_report($start_date, $end_date);
    $data['order_report'] = $this->Report_model->get_order_report($start_date, $end_date);
    $data['start_date'] = $start_date;
    $data['end_date'] = $end_date;

    $this->load->view('templates/admin_header', $data);
    $this->load->view('admin/report_list', $data);
    $this->load->view('templates/admin_footer');
  }

  public function download_report() {
    $start_date = $this->input->post('start_date');
    $end_date = $this->input->post('end_date');

    // Ambil laporan pesanan
    $report_data = $this->Report_model->get_order_report($start_date, $end_date);

    // Membuat file CSV
    $filename = 'laporan_penjualan_' . date('YmdHis') . '.csv';
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Order ID', 'Nama Pemesan', 'Harga', 'Tanggal Pesanan']);
    foreach ($report_data as $row) {
      fputcsv($output, [$row->order_id, $row->name, $row->price, $row->created_at]);
    }
    fclose($output);
    exit();
  }
}

## buat view application/views/admin/report_list.php:
<h3>Laporan Penjualan</h3>

<form method="post" action="<?= base_url('reportadmin') ?>">
  <div class="row">
    <div class="col-md-3">
      <label>Tanggal Mulai</label>
      <input type="date" name="start_date" class="form-control" value="<?= $start_date ?>" required>
    </div>
    <div class="col-md-3">
      <label>Tanggal Selesai</label>
      <input type="date" name="end_date" class="form-control" value="<?= $end_date ?>" required>
    </div>
    <div class="col-md-3">
      <button type="submit" class="btn btn-primary mt-4">Tampilkan Laporan</button>
    </div>
  </div>
</form>

<hr>

<h4>Rekap Penjualan</h4>
<p>Total Pesanan: <?= $sales_report->total_orders ?></p>
<p>Total Pendapatan: Rp <?= number_format($sales_report->total_sales, 0, ',', '.') ?></p>

<hr>

<h4>Daftar Pesanan</h4>
<table class="table table-bordered">
  <thead>
    <tr>
      <th>Order ID</th>
      <th>Nama Pemesan</th>
      <th>Harga</th>
      <th>Tanggal Pesanan</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($order_report as $order): ?>
    <tr>
      <td><?= $order->order_id ?></td>
      <td><?= $order->name ?></td>
      <td>Rp <?= number_format($order->price, 0, ',', '.') ?></td>
      <td><?= $order->created_at ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<hr>

<form method="post" action="<?= base_url('reportadmin/download_report') ?>" class="d-flex">
  <input type="hidden" name="start_date" value="<?= $start_date ?>">
  <input type="hidden" name="end_date" value="<?= $end_date ?>">
  <button type="submit" class="btn btn-success">Download Laporan</button>
</form>

## tambahkan routing application/config/routes.php:

$route['reportadmin'] = 'reportadmin/index';
$route['reportadmin/download_report'] = 'reportadmin/download_report';

## testing

http://localhost/website_services/reportadmin

Pilih rentang tanggal → tampilkan laporan
Coba download laporan (format CSV)

# Halaman Kontak

Menampilkan informasi kontak (nama website, nomor telepon, email, alamat, dll) yang disimpan di tabel tb_settings.
Konten ini bisa ditampilkan di halaman kontak pada frontend.

## struktur folder

application/
├── controllers/
│ └── Contact.php ← controller untuk halaman kontak
├── views/
│ └── user/
│ └── contact.php ← halaman kontak pengguna

## buat controller application/controllers/Contact.php:

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Settings_model');
  }

  public function index() {
    $data['title'] = 'Kontak Kami';
    $data['settings'] = $this->Settings_model->get_settings();

    $this->load->view('templates/header', $data);
    $this->load->view('user/contact', $data);
    $this->load->view('templates/footer');
  }
}

## buat model application/models/Settings_model.php:
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model {

  public function get_settings() {
    return $this->db->get('tb_settings')->row(); // Ambil data settings
  }
}


## buat application/views/user/contact.php:
<h2>Kontak Kami</h2>

<div class="card">
  <div class="card-body">
    <h5 class="card-title"><?= $settings->website_name ?></h5>

    <p><strong>Telepon:</strong> <?= $settings->phone_number1 ?></p>
    <p><strong>Email:</strong> <?= $settings->email1 ?></p>
    <p><strong>Alamat:</strong> <?= $settings->address ?></p>
    <p><strong>Map:</strong> <?= $settings->maps ?></p>

    <?php if ($settings->logo): ?>
      <img src="<?= base_url('uploads/' . $settings->logo) ?>" alt="Logo" class="img-fluid">
    <?php endif; ?>

  </div>
</div>

## tambahkan routing application/config/routes.php:

```$route['contact'] = 'contact/index';

```

## pastikan data kontak ada di mysql

INSERT INTO `tb_settings` (`website_name`, `phone_number1`, `email1`, `address`, `maps`, `logo`)
VALUES ('Website XYZ', '1234567890', 'contact@xyz.com', 'Street XYZ', 'Google Map', 'logo.png');

## testing

http://localhost/website_services/contact

# Memperbaiki Responsivitas dan Sidebar

## update application/views/templates/admin_header.php

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

## update admin_footerphp

</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/sidebar.js') ?>"></script>
</body>
</html>

## buat application/assets/css/style.css:

/_ Style untuk sidebar _/
.sidebar {
background-color: #f8f9fa;
padding-top: 20px;
height: 100vh;
position: fixed;
top: 0;
left: 0;
width: 250px;
z-index: 1000;
}

/_ Style untuk main content _/
.main-content {
margin-left: 250px;
padding: 20px;
}

/_ Sidebar Toggle untuk mobile _/
@media (max-width: 768px) {
.sidebar {
position: absolute;
left: -250px;
width: 250px;
transition: left 0.3s ease;
}

.sidebar.active {
left: 0;
}

.main-content {
margin-left: 0;
}
}

/_ Style untuk tombol menu pada mobile _/
#menu-toggle {
display: none;
}

/_ Menampilkan tombol menu pada layar kecil _/
@media (max-width: 768px) {
#menu-toggle {
display: block;
background-color: #007bff;
color: white;
padding: 10px;
border: none;
cursor: pointer;
}
}

## bikin sidebar.js

// Menangani tombol menu untuk responsive sidebar
document.getElementById("menu-toggle").addEventListener("click", function () {
document.querySelector(".sidebar").classList.toggle("active");
});
