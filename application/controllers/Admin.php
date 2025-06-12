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
