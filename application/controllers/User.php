<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Memanggil model Catalogue_model untuk mengelola katalog
        $this->load->model('Catalogue_model');
        $this->load->model('Review_model');
    }

    // Menampilkan halaman utama (landing page)
    public function index() {
        $data['title'] = 'Beranda';
        
        // Mendapatkan data katalog dari model
        // $data['catalogues'] = $this->Catalogue_model->get_all();  // Ambil semua data katalog
        // $data['reviews'] = $this->Review_model->get_approved();  // Ambil ulasan yang disetujui

        // Memuat header, konten utama, dan footer
        $this->load->view('templates/header', $data);
        $this->load->view('user/home', $data); // Pass data untuk halaman home
        $this->load->view('templates/footer');
    }

    // Menampilkan katalog
    public function katalog() {
        $data['title'] = 'Katalog Layanan';
        
        // Mendapatkan semua katalog dari model
        $data['catalogues'] = $this->Catalogue_model->get_all();

        // Menampilkan halaman katalog
        $this->load->view('templates/header', $data);
        $this->load->view('user/katalog', $data);  // Halaman katalog
        $this->load->view('templates/footer');
    }
}
