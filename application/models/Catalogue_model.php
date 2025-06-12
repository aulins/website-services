<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogue_model extends CI_Model {

    public function get_all() {
        return $this->db->order_by('created_at', 'DESC')->get('tb_catalogues')->result();
    }

    public function get($id) {
        return $this->db->get_where('tb_catalogues', ['catalogue_id' => $id])->row();
    }

    public function insert($data) {
        return $this->db->insert('tb_catalogues', $data);
    }

    public function update($id, $data) {
        return $this->db->where('catalogue_id', $id)->update('tb_catalogues', $data);
    }

    public function delete($id) {
        return $this->db->delete('tb_catalogues', ['catalogue_id' => $id]);
    }
}
