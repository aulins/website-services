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
