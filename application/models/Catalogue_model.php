<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogue_model extends CI_Model {

    // Mendapatkan semua katalog
    public function get_all() {
        return $this->db->order_by('created_at', 'DESC')->get('tb_catalogues')->result();
    }

    // Mendapatkan katalog berdasarkan ID
    public function get($id) {
        return $this->db->get_where('tb_catalogues', ['catalogue_id' => $id])->row();
    }

    // Menambah data katalog
    public function insert($data) {
        return $this->db->insert('tb_catalogues', $data);
    }

    // Update data katalog
    public function update($id, $data) {
        return $this->db->where('catalogue_id', $id)->update('tb_catalogues', $data);
    }

    // Hapus data katalog
    public function delete($id) {
        return $this->db->delete('tb_catalogues', ['catalogue_id' => $id]);
    }
}
