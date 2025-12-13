<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_login_penghuni extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('m_login_penghuni');
    }

    function login(){
        $data['judul_halaman'] = 'Login Penghuni';
        $data['pesan'] = $this->session->flashdata('pesan');

        if ($data['pesan'] == 'berhasil_logout'){
            $this->session->sess_destroy();
        }
        else if ($this->session->userdata('status_penghuni') == 'login'){
            redirect(base_url('penghuni/dashboard'));
        }

        $this->load->view('_partials/v_head_form', $data);
        $this->load->view('penghuni/v_login_penghuni', $data);
        $this->load->view('_partials/v_preloader');
        $this->load->view('_partials/v_js_form');
    }

    function aksi_login(){
        $nama = $this->input->post('nama');
        $password = $this->input->post('password');

        $penghuni = $this->m_login_penghuni->cek_login($nama, $password);
        
        if ($penghuni->num_rows() > 0){
            $penghuni = $penghuni->row();
            
           // Cek apakah masih berstatus penghuni aktif 
            if ($penghuni->status != 'Penghuni') {
                $this->session->set_flashdata('pesan', 'tidak_aktif');
                redirect(base_url('penghuni/login'));
                return;
            }
            
            $data_session = array(
                'id_penghuni' => $penghuni->id,
                'nama_penghuni' => $penghuni->nama,
                'no_ktp' => $penghuni->no_ktp,
                'no_kamar' => $penghuni->no_kamar,
                'status_penghuni' => 'login'
            );
            
            $this->session->set_userdata($data_session);
            $this->session->set_flashdata('pesan', 'toastr.success("Selamat datang, '.$penghuni->nama.'")');
            redirect(base_url('penghuni/dashboard'));
        }
        else {
            $this->session->set_flashdata('pesan', 'gagal_login');
            redirect(base_url('penghuni/login'));
        }
    }

    function logout(){
        $this->session->set_flashdata('pesan', 'berhasil_logout');
        redirect(base_url('penghuni/login'));
    }
}