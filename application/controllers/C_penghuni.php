<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_penghuni extends CI_Controller {

    function __construct(){
        parent::__construct();
        if ($this->session->userdata('status_penghuni') != 'login'){
            redirect(base_url('penghuni/login'));
        }
        date_default_timezone_set("Asia/Bangkok");
        $this->load->model('m_data');
        $this->load->model('m_pembayaran');
    }

    // Fungsi helper untuk ambil data penghuni dengan foto
    private function get_penghuni_data($id_penghuni) {
        $this->db->select('penghuni.*, 
                          COALESCE(SUM(keuangan.bayar), 0) as bayar,
                          penghuni.biaya - COALESCE(SUM(keuangan.bayar), 0) as piutang');
        $this->db->from('penghuni');
        $this->db->join('keuangan', 'keuangan.id_penghuni = penghuni.id', 'left');
        $this->db->where('penghuni.id', $id_penghuni);
        $this->db->group_by('penghuni.id');
        return $this->db->get()->row();
    }

    function dashboard(){
        $data['judul_halaman'] = 'Dashboard Penghuni';
        $data['pesan'] = $this->session->flashdata('pesan');
        $data['id_penghuni'] = $this->session->userdata('id_penghuni');
        
        // Ambil data penghuni dengan foto
        $data['penghuni'] = $this->get_penghuni_data($data['id_penghuni']);
        
        // Ambil riwayat pembayaran
        $data['pembayaran'] = $this->m_data->detail_pembayaran(array('id_penghuni' => $data['id_penghuni']))->result();

        // Ambil pengajuan pembayaran
        $data['pengajuan_pending'] = $this->m_pembayaran->get_pengajuan_by_penghuni($data['id_penghuni']);

        $this->load->view('_partials/v_head', $data);
        $this->load->view('penghuni/_partials/v_header_penghuni', $data);
        $this->load->view('penghuni/_partials/v_sidebar_penghuni', $data);
        $this->load->view('penghuni/v_dashboard_penghuni', $data);
        $this->load->view('_partials/v_footer');
        $this->load->view('_partials/v_preloader');
        $this->load->view('_partials/v_js', $data);
    }

    function ajukan_pembayaran(){
        $id_penghuni = $this->session->userdata('id_penghuni');
        $nominal = $this->input->post('nominal');
        $metode = $this->input->post('metode_pembayaran');
        $keterangan = $this->input->post('keterangan');

        // Upload bukti transfer
        $config['upload_path'] = './assets/uploads/bukti_transfer/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;
        $config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('bukti_transfer')) {
            $this->session->set_flashdata('pesan', 'toastr.error("'.$this->upload->display_errors().'")');
            redirect(base_url('penghuni/dashboard'));
        } else {
            $upload_data = $this->upload->data();
            $bukti_file = $upload_data['file_name'];

            $data = array(
                'id_penghuni' => $id_penghuni,
                'nominal' => $nominal,
                'metode_pembayaran' => $metode,
                'bukti_transfer' => $bukti_file,
                'tgl_pengajuan' => date('Y-m-d H:i:s'),
                'status' => 'pending',
                'keterangan' => $keterangan
            );

            if ($this->m_pembayaran->insert_pengajuan($data)) {
                $this->session->set_flashdata('pesan', 'toastr.success("Pengajuan pembayaran berhasil dikirim. Menunggu konfirmasi admin.")');
            } else {
                $this->session->set_flashdata('pesan', 'toastr.error("Terjadi kesalahan")');
            }

            redirect(base_url('penghuni/dashboard'));
        }
    }

    function profil(){
        $data['judul_halaman'] = 'Profil Saya';
        $data['pesan'] = $this->session->flashdata('pesan');
        $data['id_penghuni'] = $this->session->userdata('id_penghuni');
        
        // Ambil data penghuni dengan foto
        $data['penghuni'] = $this->get_penghuni_data($data['id_penghuni']);

        $this->load->view('_partials/v_head', $data);
        $this->load->view('penghuni/_partials/v_header_penghuni', $data);
        $this->load->view('penghuni/_partials/v_sidebar_penghuni', $data);
        $this->load->view('penghuni/_partials/v_breadcrump_penghuni', $data);
        $this->load->view('penghuni/v_profil_penghuni', $data);
        $this->load->view('_partials/v_footer');
        $this->load->view('_partials/v_preloader');
        $this->load->view('_partials/v_js', $data);
    }

    function upload_foto(){
        header('Content-Type: application/json');
        
        $id_penghuni = $this->session->userdata('id_penghuni');
        
        if (!$id_penghuni) {
            echo json_encode(['success' => false, 'message' => 'Session expired']);
            return;
        }

        $config['upload_path'] = './assets/uploads/foto_penghuni/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;
        $config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('foto')) {
            echo json_encode([
                'success' => false, 
                'message' => strip_tags($this->upload->display_errors())
            ]);
            return;
        }

        $upload_data = $this->upload->data();
        $foto_baru = $upload_data['file_name'];

        $penghuni = $this->db->get_where('penghuni', ['id' => $id_penghuni])->row();
        $foto_lama = $penghuni->foto;

        $this->db->where('id', $id_penghuni);
        $update = $this->db->update('penghuni', ['foto' => $foto_baru]);

        if ($update) {
            if ($foto_lama && $foto_lama != 'default-avatar.png') {
                $path_lama = './assets/uploads/foto_penghuni/' . $foto_lama;
                if (file_exists($path_lama)) {
                    unlink($path_lama);
                }
            }

            echo json_encode([
                'success' => true, 
                'message' => 'Foto profil berhasil diperbarui',
                'foto_url' => base_url('assets/uploads/foto_penghuni/' . $foto_baru)
            ]);
        } else {
            $path_baru = './assets/uploads/foto_penghuni/' . $foto_baru;
            if (file_exists($path_baru)) {
                unlink($path_baru);
            }

            echo json_encode([
                'success' => false, 
                'message' => 'Gagal memperbarui database'
            ]);
        }
    }

    function riwayat_pembayaran(){
        $data['judul_halaman'] = 'Riwayat Pembayaran';
        $data['pesan'] = $this->session->flashdata('pesan');
        $data['id_penghuni'] = $this->session->userdata('id_penghuni');
        
        $data['pembayaran'] = $this->m_data->detail_pembayaran(array('id_penghuni' => $data['id_penghuni']))->result();

        $this->load->view('_partials/v_head', $data);
        $this->load->view('penghuni/_partials/v_header_penghuni', $data);
        $this->load->view('penghuni/_partials/v_sidebar_penghuni', $data);
        $this->load->view('penghuni/_partials/v_breadcrump_penghuni', $data);
        $this->load->view('penghuni/v_riwayat_pembayaran_penghuni', $data);
        $this->load->view('_partials/v_footer');
        $this->load->view('_partials/v_preloader');
        $this->load->view('_partials/v_js', $data);
    }

    function ubah_password(){
        $data['judul_halaman'] = 'Ubah Password';
        $data['pesan'] = $this->session->flashdata('pesan');

        $this->load->view('_partials/v_head_form', $data);
        $this->load->view('penghuni/v_ubah_password_penghuni', $data);
        $this->load->view('_partials/v_preloader');
        $this->load->view('_partials/v_js_form');
    }

    function aksi_ubah_password(){
        $id_penghuni = $this->session->userdata('id_penghuni');
        $nama_penghuni = $this->session->userdata('nama_penghuni');
        $password = $this->input->post('password');
        $password_baru = sha1($this->input->post('password_baru'));

        $this->load->model('m_login_penghuni');
        $cek = $this->m_login_penghuni->cek_login($nama_penghuni, $password);

        if ($cek->num_rows() > 0){
            $this->db->where('id', $id_penghuni);
            if ($this->db->update('penghuni', array('password' => $password_baru))){
                $this->session->set_flashdata('pesan', 'berhasil_ubah_pass');
                redirect(base_url('penghuni/login'));
            }
            else{
                echo 'Terjadi Kesalahan';
            }
        }
        else {
            $this->session->set_flashdata('pesan', 'gagal_ubah_pass');
            redirect(base_url('penghuni/ubah-password'));
        }
    }
}