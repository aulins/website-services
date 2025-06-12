<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends CI_Model {

    public function get_order_report($start_date, $end_date) {
        // Join tb_orders dengan tb_catalogues untuk mendapatkan harga paket
        $this->db->select('tb_orders.order_id, tb_orders.name, tb_catalogues.price, tb_orders.created_at');
        $this->db->from('tb_orders');
        $this->db->join('tb_catalogues', 'tb_orders.catalogue_id = tb_catalogues.catalogue_id');
        $this->db->where('tb_orders.status', 'completed');
        $this->db->where('tb_orders.created_at >=', $start_date);
        $this->db->where('tb_orders.created_at <=', $end_date);
        
        return $this->db->get()->result();
    }      

    public function get_sales_report($start_date, $end_date) {
        $this->db->select('SUM(tb_catalogues.price) AS total_sales, COUNT(tb_orders.order_id) AS total_orders');
        $this->db->from('tb_orders');
        $this->db->join('tb_catalogues', 'tb_orders.catalogue_id = tb_catalogues.catalogue_id');
        $this->db->where('tb_orders.status', 'completed');
        $this->db->where('tb_orders.created_at >=', $start_date);
        $this->db->where('tb_orders.created_at <=', $end_date);
        return $this->db->get()->row();
    }
}
