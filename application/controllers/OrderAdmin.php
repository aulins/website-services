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
