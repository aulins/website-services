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
