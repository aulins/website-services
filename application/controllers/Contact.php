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
