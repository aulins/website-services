<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function index() {
        $data['title'] = 'Beranda';
        $this->load->model('Review_model');
        $data['reviews'] = $this->Review_model->get_approved();

        $this->load->view('templates/header', $data);
        $this->load->view('user/home');
        $this->load->view('templates/footer');
    }
    public function katalog() {
        $data['catalogues'] = $this->db->get('tb_catalogues')->result(); // Ambil data katalog
        $this->load->view('templates/header');
        $this->load->view('user/katalog', $data);  // Pastikan data dikirim ke view
        $this->load->view('templates/footer');
    }      
}

