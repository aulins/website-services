<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogue extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('user_id')) {
        redirect('login');
        }
        $this->load->model('Catalogue_model');
    }

    public function index() {
        $data['title'] = 'Kelola Katalog';
        $data['catalogues'] = $this->Catalogue_model->get_all();
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/katalog_list', $data);
        $this->load->view('templates/admin_footer');
    }

    public function create() {
        $data['title'] = 'Tambah Katalog';
        if ($this->input->post()) {
            //upload
            $config['upload_path']   = './uploads/catalogue/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size']      = 2048;
            $config['file_name']     = time() . '_' . $_FILES['image']['name'];

            $this->load->library('upload', $config);

            $image = null;
            if (!empty($_FILES['image']['name'])) {
            if ($this->upload->do_upload('image')) {
                $image = $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect(current_url());
            }
            }

// 
        $insert = [

            'package_name' => $this->input->post('package_name'),
            'categories'   => $this->input->post('categories'),
            'description'  => $this->input->post('description'),
            'price'        => $this->input->post('price'),
            'status_publish' => $this->input->post('status_publish'),
            'created_at'   => date('Y-m-d H:i:s')
        ];

        if ($image) $insert['image'] = $image; // ⬅️ Di sinilah dimasukkan

        $this->Catalogue_model->insert($insert);
        redirect('catalogue');
        }
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/katalog_form');
        $this->load->view('templates/admin_footer');
    }

    public function edit($id) {
        $data['title'] = 'Edit Katalog';
        $data['katalog'] = $this->Catalogue_model->get($id);
        if ($this->input->post()) {
            $config['upload_path']   = './uploads/catalogue/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size']      = 2048;
            $config['file_name']     = time() . '_' . $_FILES['image']['name'];

            $this->load->library('upload', $config);

            $image = null;
            if (!empty($_FILES['image']['name'])) {
            if ($this->upload->do_upload('image')) {
                $image = $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect(current_url());
            }
            }
// 
        $update = [
            'package_name' => $this->input->post('package_name'),
            'categories'   => $this->input->post('categories'),
            'description'  => $this->input->post('description'),
            'price'        => $this->input->post('price'),
            'status_publish' => $this->input->post('status_publish'),
        ];
        
        if ($image) $update['image'] = $image; // ⬅️ Ini baris pentingnya

        $this->Catalogue_model->update($id, $update);
        redirect('catalogue');
        }
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/katalog_form', $data);
        $this->load->view('templates/admin_footer');
    }

    public function delete($id) {
        $this->Catalogue_model->delete($id);
        redirect('catalogue');
    }
}
