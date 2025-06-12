<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model {

    public function insert($data) {
        return $this->db->insert('tb_orders', $data);
    }
}
