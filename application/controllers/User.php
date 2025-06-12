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
