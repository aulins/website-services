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
