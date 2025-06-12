<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Review_model extends CI_Model {

    public function insert($data) {
        return $this->db->insert('tb_reviews', $data);
    }

    public function get_all() {
        $this->db->select('r.*, o.name AS pemesan, c.package_name');
        $this->db->from('tb_reviews r');
        $this->db->join('tb_orders o', 'o.order_id = r.order_id');
        $this->db->join('tb_catalogues c', 'c.catalogue_id = o.catalogue_id');
        $this->db->order_by('r.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function approve($id) {
        return $this->db->where('review_id', $id)->update('tb_reviews', ['is_approved' => 'Y']);
    }
}
