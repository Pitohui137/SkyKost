<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_admin extends CI_Controller {

    function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != 'login'){
            redirect (base_url('login'));
        }
        date_default_timezone_set("Asia/Bangkok");
        $this->load->model('m_data');
    }

   

    // superadmin only
    // tidak terpakai
    function daftar_user(){
        $data['judul_halaman'] = 'Daftar User';
        $data['pesan'] = $this->session->flashdata('pesan');
        $data['username'] = $this->session->userdata('username');
        $data['user'] = $this->m_data->data_user(['username !=' => 'superadmin'])->result();

        if ($data['username'] != 'superadmin') show_404();

        $this->load->view('_partials/v_head', $data);
        $this->load->view('_partials/v_header');
        $this->load->view('_partials/v_sidebar', $data);
        $this->load->view('_partials/v_breadcrump', $data);
        $this->load->view('v_daftar_user', $data); //page content
        $this->load->view('_partials/v_footer');
        // $this->load->view('_partials/v_theme-config');
        $this->load->view('_partials/v_preloader');
        $this->load->view('_partials/v_js', $data);
    }

    function daftar_kamar(){
        $data['judul_halaman'] = 'Daftar Kamar';
        $data['username'] = $this->session->userdata('username');
        $data['kamar'] = $this->m_data->detail_kamar(['1' => '1'])->result();
        $data['pesan'] = $this->session->flashdata('pesan');

        $this->load->view('_partials/v_head', $data);
        $this->load->view('_partials/v_header');
        $this->load->view('_partials/v_sidebar', $data);
        $this->load->view('_partials/v_breadcrump', $data);
        $this->load->view('v_daftar_kamar', $data); //page content
        $this->load->view('_partials/v_footer');
        // $this->load->view('_partials/v_theme-config');
        $this->load->view('_partials/v_preloader');
        $this->load->view('_partials/v_js');
    }

    function daftar_penghuni(){
        $data['judul_halaman'] = 'Daftar Penghuni';
        $data['pesan'] = $this->session->flashdata('pesan');
        $data['username'] = $this->session->userdata('username');
        $data['penghuni'] = $this->m_data->detail_penghuni(['status' => 'Penghuni'])->result();

        $this->load->view('_partials/v_head', $data);
        $this->load->view('_partials/v_header');
        $this->load->view('_partials/v_sidebar', $data);
        $this->load->view('_partials/v_breadcrump', $data);
        $this->load->view('v_daftar_penghuni', $data); //page content
        $this->load->view('_partials/v_footer');
        // $this->load->view('_partials/v_theme-config');
        $this->load->view('_partials/v_preloader');
        $this->load->view('_partials/v_js');
    }

    function riwayat_pembayaran(){
        $data['judul_halaman'] = 'Riwayat Pembayaran';
        $data['pesan'] = $this->session->flashdata('pesan');
        $data['username'] = $this->session->userdata('username');
        $data['pembayaran'] = $this->m_data->detail_pembayaran(['1' => '1'])->result();

        $this->load->view('_partials/v_head', $data);
        $this->load->view('_partials/v_header');
        $this->load->view('_partials/v_sidebar', $data);
        $this->load->view('_partials/v_breadcrump', $data);
        $this->load->view('v_riwayat_pembayaran', $data); //page content
        $this->load->view('_partials/v_footer');
        // $this->load->view('_partials/v_theme-config');
        $this->load->view('_partials/v_preloader');
        $this->load->view('_partials/v_js', $data);
    }

    // superadmin only
    function tambah_user(){
        if ($this->session->userdata('username') != 'superadmin') show_404();

        $data['judul_halaman'] = 'Tambah User';
        $data['pesan'] = $this->session->flashdata('pesan');

        $this->load->view('_partials/v_head_form', $data);
        $this->load->view('v_tambah_user');
        $this->load->view('_partials/v_preloader');
        $this->load->view('_partials/v_js_form');
    }

    function tambah_penghuni($no_kamar = null){
        $data['judul_halaman'] = 'Tambah Penghuni';
        $data['username'] = $this->session->userdata('username');
        $data['kamar'] = $this->m_data->detail_kamar(array('no_kamar' => $no_kamar))->row();

        if (!$data['kamar']) show_404();

        else if ($data['kamar']->jml_penghuni == '1'){
            $this->session->set_flashdata('pesan', 'toastr.warning("Kamar '.$no_kamar.' sudah terisi, silakan pilih kamar lain")');
            redirect (base_url('daftar-kamar'));
        }

        $this->load->view('_partials/v_head', $data);
        $this->load->view('_partials/v_header');
        $this->load->view('_partials/v_sidebar', $data);
        $this->load->view('_partials/v_breadcrump', $data);
        $this->load->view('v_tambah_penghuni', $data); //page content
        $this->load->view('_partials/v_footer');
        // $this->load->view('_partials/v_theme-config');
        $this->load->view('_partials/v_preloader');
        $this->load->view('_partials/v_js');
    }

    function tambah_pembayaran($id = null){
        $data['judul_halaman'] = 'Tambah Pembayaran';
        $data['username'] = $this->session->userdata('username');
        $data['penghuni'] = $this->m_data->detail_penghuni(array('id' => $id))->row();

        if (!$data['penghuni']) show_404();

        $this->load->view('_partials/v_head', $data);
        $this->load->view('_partials/v_header');
        $this->load->view('_partials/v_sidebar', $data);
        $this->load->view('_partials/v_breadcrump', $data);
        $this->load->view('v_tambah_pembayaran', $data); //page content
        $this->load->view('_partials/v_footer');
        // $this->load->view('_partials/v_theme-config');
        $this->load->view('_partials/v_preloader');
        $this->load->view('_partials/v_js');
    }

    function edit_harga_kamar($no_kamar){
        $data['judul_halaman'] = 'Edit Harga Kamar';
        $data['username'] = $this->session->userdata('username');
        $data['kamar'] = $this->m_data->detail_kamar(['no_kamar' => $no_kamar])->row();

        if (!$data['kamar']) show_404();

        $this->load->view('_partials/v_head', $data);
        $this->load->view('_partials/v_header');
        $this->load->view('_partials/v_sidebar', $data);
        $this->load->view('_partials/v_breadcrump', $data);
        $this->load->view('v_edit_harga_kamar', $data); //page content
        $this->load->view('_partials/v_footer');
        // $this->load->view('_partials/v_theme-config');
        $this->load->view('_partials/v_preloader');
        $this->load->view('_partials/v_js');
    }

    function edit_penghuni($id = null){

        if (!isset($id)) redirect (base_url('daftar-penghuni'));

        $data['penghuni'] = $this->m_data->detail_penghuni(array('id' => $id))->row();

        if (!$data['penghuni']) show_404();

        $data['judul_halaman'] = 'Edit Penghuni';
        $data['username'] = $this->session->userdata('username');
        $data['kamar'] = $this->m_data->detail_kamar(['1' => '1'])->result();

        $this->load->view('_partials/v_head', $data);
        $this->load->view('_partials/v_header');
        $this->load->view('_partials/v_sidebar', $data);
        $this->load->view('_partials/v_breadcrump', $data);
        $this->load->view('v_edit_penghuni', $data); //page content
        $this->load->view('_partials/v_footer');
        // $this->load->view('_partials/v_theme-config');
        $this->load->view('_partials/v_preloader');
        $this->load->view('_partials/v_js');
    }

    function edit_pembayaran($id_pembayaran = null){

        if (!isset($id_pembayaran)) redirect('riwayat_pembayaran');

        $data['judul_halaman'] = 'Edit Pembayaran';
        $data['username'] = $this->session->userdata('username');
        $data['pembayaran'] = $this->m_data->detail_pembayaran(array('id_pembayaran' => $id_pembayaran))->row();

        if (!$data['pembayaran']) show_404();

        $this->load->view('_partials/v_head', $data);
        $this->load->view('_partials/v_header');
        $this->load->view('_partials/v_sidebar', $data);
        $this->load->view('_partials/v_breadcrump', $data);
        $this->load->view('v_edit_pembayaran', $data); //page content
        $this->load->view('_partials/v_footer');
        // $this->load->view('_partials/v_theme-config');
        $this->load->view('_partials/v_preloader');
        $this->load->view('_partials/v_js');
    }

    function ubah_pass(){
        $data['judul_halaman'] = 'Ubah Password';
        $data['pesan'] = $this->session->flashdata('pesan');
        $data['username'] = $this->session->userdata('username');

        $this->load->view('_partials/v_head_form', $data);
        $this->load->view('v_ubah_pass');
        $this->load->view('_partials/v_preloader');
        $this->load->view('_partials/v_js_form');
    }
    function konfirmasi_pembayaran(){
        $data['judul_halaman'] = 'Konfirmasi Pembayaran';
        $data['pesan'] = $this->session->flashdata('pesan');
        $data['username'] = $this->session->userdata('username');
        
        $this->load->model('m_pembayaran');
        $data['pengajuan'] = $this->m_pembayaran->get_all_pengajuan();

        $this->load->view('_partials/v_head', $data);
        $this->load->view('_partials/v_header');
        $this->load->view('_partials/v_sidebar', $data);
        $this->load->view('_partials/v_breadcrump', $data);
        $this->load->view('v_konfirmasi_pembayaran', $data);
        $this->load->view('_partials/v_footer');
        $this->load->view('_partials/v_preloader');
        $this->load->view('_partials/v_js', $data);
    }

    function approve_pembayaran($id_pengajuan){
        $this->load->model('m_pembayaran');
        
        if ($this->m_pembayaran->approve_pengajuan($id_pengajuan)){
            $this->session->set_flashdata('pesan', 'toastr.success("Pembayaran berhasil disetujui dan masuk ke riwayat pembayaran")');
        } else {
            $this->session->set_flashdata('pesan', 'toastr.error("Terjadi kesalahan")');
        }

        redirect(base_url('konfirmasi-pembayaran'));
    }

    function reject_pembayaran($id_pengajuan){
        $this->load->model('m_pembayaran');
        
        if ($this->m_pembayaran->reject_pengajuan($id_pengajuan)){
            $this->session->set_flashdata('pesan', 'toastr.warning("Pembayaran ditolak")');
        } else {
            $this->session->set_flashdata('pesan', 'toastr.error("Terjadi kesalahan")');
        }

        redirect(base_url('konfirmasi-pembayaran'));
    }

    function delete_pengajuan($id_pengajuan){
        $this->load->model('m_pembayaran');
        
        if ($this->m_pembayaran->delete_pengajuan($id_pengajuan)){
            $this->session->set_flashdata('pesan', 'toastr.success("Pengajuan berhasil dihapus")');
        } else {
            $this->session->set_flashdata('pesan', 'toastr.error("Terjadi kesalahan")');
        }

        redirect(base_url('konfirmasi-pembayaran'));
    }

      function dasbor(){
        $data['judul_halaman'] = 'Dasbor';
        $data['pesan'] = $this->session->flashdata('pesan');
        $data['username'] = $this->session->userdata('username');

        // Load model pembayaran
        $this->load->model('m_pembayaran');
        $this->load->model('m_dashboard');

        // Statistik Umum
        $data['total_kamar'] = $this->m_data->data_kamar()->num_rows();
        $data['kamar_terisi'] = $this->m_dashboard->get_kamar_terisi();
        $data['total_penghuni'] = $this->m_data->detail_penghuni(['status' => 'Penghuni'])->num_rows();
        $data['pengajuan_pending'] = $this->m_pembayaran->get_all_pengajuan(['status' => 'pending']);
        
        // Pendapatan
        $data['pendapatan_bulan_ini'] = $this->m_dashboard->get_pendapatan_bulan_ini();
        $data['pendapatan_tahun_ini'] = $this->m_dashboard->get_pendapatan_tahun_ini();
        $data['total_piutang'] = $this->m_dashboard->get_total_piutang();
        
        // Data untuk grafik pendapatan per bulan (12 bulan terakhir)
        $data['grafik_pendapatan'] = $this->m_dashboard->get_pendapatan_12_bulan();

        // Pembayaran terbaru
        $data['pembayaran_terbaru'] = $this->m_data->detail_pembayaran(['1' => '1'])->result();
        $data['pembayaran_terbaru'] = array_slice($data['pembayaran_terbaru'], 0, 5);

        $this->load->view('_partials/v_head', $data);
        $this->load->view('_partials/v_header');
        $this->load->view('_partials/v_sidebar', $data);
        $this->load->view('v_dasbor', $data); //page content
        $this->load->view('_partials/v_footer');
        $this->load->view('_partials/v_preloader');
        $this->load->view('_partials/v_js', $data);
    }
}
