<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
    }

    public function index() {
        $this->load->view('templates/header', ['title' => 'Login']);
        $this->load->view('auth/login');
        $this->load->view('templates/footer');
    }

    public function login() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $user = $this->User_model->get_by_username($username);

        if ($user && password_verify($password, $user->password)) {
        $this->session->set_userdata('user_id', $user->user_id);
        redirect('admin');
        } else {
        $this->session->set_flashdata('error', 'Username atau Password salah!');
        redirect('auth');
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('auth');
    }
}
