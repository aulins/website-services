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
