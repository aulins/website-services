<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model {

    public function get_settings() {
        return $this->db->get('tb_settings')->row(); // Ambil data settings
    }
}
