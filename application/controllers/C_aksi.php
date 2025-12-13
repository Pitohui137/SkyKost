<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_aksi extends CI_Controller {

    function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != 'login'){
            redirect (base_url('login'));
        }
        $this->load->model('m_data');
    }

    function get_detail_penghuni(){
        $id_penghuni = $this->input->post('id_penghuni');
        $penghuni = $this->m_data->detail_penghuni(array('id' => $id_penghuni))->row();
        echo json_encode($penghuni);
    }

    function aksi_edit_harga_kamar(){
        $no_kamar = $this->input->post('no_kamar');
        $harga = $this->input->post('harga');

        if ($this->m_data->update_harga($no_kamar, $harga) == true){
            $this->session->set_flashdata('pesan', 'toastr.success("Berhasil memperbarui harga kamar no. '.$no_kamar.' menjadi Rp'.number_format($harga, 0, ',', '.').' per bulan")');
        }
        else {
            $this->session->set_flashdata('pesan', 'toastr.error("Terjadi kesalahan")');
        }
        redirect (base_url('daftar-kamar'));
    }

    /**
     * ====================================================================
     * UPDATED: Tambah Penghuni dengan Prevent Double Submit
     * ====================================================================
     */
    function aksi_tambah_penghuni(){
        // ============================================
        // CEGAH DOUBLE SUBMIT DENGAN TOKEN
        // ============================================
        $token = $this->input->post('form_token');
        $session_token = $this->session->userdata('form_token');
        
        if (!$token || !$session_token || $token !== $session_token) {
            $this->session->set_flashdata('pesan', 'toastr.error("Invalid form submission. Silakan coba lagi.")');
            redirect(base_url('daftar-kamar'));
            return;
        }
        
        // Hapus token setelah digunakan (one-time use)
        $this->session->unset_userdata('form_token');

        // ============================================
        //  VALIDASI INPUT
        // ============================================
        $no_kamar       = $this->input->post('no_kamar');
        $nama           = trim($this->input->post('nama'));
        $no_ktp         = $this->input->post('no_ktp');
        $alamat         = trim($this->input->post('alamat'));
        $no             = $this->input->post('no');
        $password       = $this->input->post('password');
        $tgl_masuk      = $this->input->post('tgl_masuk');

        // Validasi input required
        if (empty($nama) || empty($no_kamar) || empty($tgl_masuk)) {
            $this->session->set_flashdata('pesan', 'toastr.error("Data tidak lengkap. Silakan isi semua field yang wajib.")');
            redirect(base_url('tambah-penghuni/'.$no_kamar));
            return;
        }

        // Validasi format tanggal
        $date_parts = explode('-', $tgl_masuk);
        if (count($date_parts) != 3 || !checkdate($date_parts[1], $date_parts[0], $date_parts[2])) {
            $this->session->set_flashdata('pesan', 'toastr.error("Format tanggal tidak valid")');
            redirect(base_url('tambah-penghuni/'.$no_kamar));
            return;
        }

        // ============================================
        //  CEK KAMAR
        // ============================================
        $kamar = $this->m_data->detail_kamar(array('no_kamar' => $no_kamar))->row();
        
        if (!$kamar) {
            $this->session->set_flashdata('pesan', 'toastr.error("Kamar tidak ditemukan")');
            redirect(base_url('daftar-kamar'));
            return;
        }

        // Cek apakah kamar sudah terisi
        if ($kamar->jml_penghuni >= 1){
            $this->session->set_flashdata('pesan', 'toastr.warning("Kamar '.$no_kamar.' sudah terisi, silakan pilih kamar lain")');
            redirect(base_url('daftar-kamar'));
            return;
        }

        // ============================================
        // SET HARGA PER BULAN (dari harga kamar)
        // ============================================
        // Harga per bulan = harga kamar (akan auto update setiap bulan via cron)
        $harga_per_bulan = $kamar->harga;

        // Hash password
        $password_hash = hash('sha1', $password);

        // ============================================
        // CEK DUPLIKAT (Double Check)
        // ============================================
        // Cek apakah penghuni dengan nama yang sama sudah ada di kamar ini
        $existing = $this->db->get_where('penghuni', array(
            'nama' => $nama,
            'no_kamar' => $no_kamar,
            'status' => 'Penghuni'
        ))->num_rows();

        if ($existing > 0) {
            $this->session->set_flashdata('pesan', 'toastr.warning("Penghuni dengan nama '.$nama.' sudah ada di kamar '.$no_kamar.'")');
            redirect(base_url('daftar-kamar'));
            return;
        }

        // ============================================
        // PREPARE DATA
        // ============================================
        $data = array(
            'no_kamar'          => $no_kamar,
            'nama'              => $nama,
            'no_ktp'            => $no_ktp,
            'alamat'            => $alamat,
            'no'                => $no,
            'password'          => $password_hash,
            'tgl_masuk'         => $tgl_masuk,
            'harga_per_bulan'   => $harga_per_bulan,
            'status'            => 'Penghuni',
            'foto'              => 'default-avatar.png',
            'bulan_terakhir_bayar' => NULL
        );

        // ============================================
        // INSERT DENGAN TRANSACTION
        // ============================================
        $this->db->trans_start();

        try {
            // Insert penghuni baru
            $this->db->insert('penghuni', $data);
            
            if ($this->db->affected_rows() <= 0) {
                throw new Exception('Gagal menambahkan penghuni ke database');
            }

            // Get inserted ID
            $insert_id = $this->db->insert_id();

            // Complete transaction
            $this->db->trans_complete();
            
            // Check transaction status
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Transaction failed');
            }
            
            // SUCCESS
            $this->session->set_flashdata('pesan', 'toastr.success("Berhasil menambah penghuni <strong>'.$nama.'</strong> pada kamar <strong>'.$no_kamar.'</strong> dengan harga <strong>Rp'.number_format($harga_per_bulan, 0, ',', '.').' per bulan</strong>")');
            
            // Log activity (optional)
            $this->log_activity('INSERT', 'penghuni', $insert_id, 'Tambah penghuni: '.$nama.' di kamar '.$no_kamar);
            
        } catch (Exception $e) {
            // ROLLBACK jika error
            $this->db->trans_rollback();
            
            // Log error
            log_message('error', 'Error adding penghuni: ' . $e->getMessage());
            
            $this->session->set_flashdata('pesan', 'toastr.error("Terjadi kesalahan: Penghuni gagal ditambahkan. Silakan coba lagi.")');
        }

        redirect(base_url('daftar-kamar'));
    }


    function aksi_hapus_penghuni($id = null){

        if (!isset($id)) redirect (base_url('daftar-penghuni'));

        $penghuni = $this->m_data->detail_penghuni(array('id' => $id))->row();

        if (!$penghuni){
            show_404();
        }
        else {
            if ($this->m_data->delete_penghuni($id) == true){
                $no_kamar = $penghuni->no_kamar;

                if ($penghuni->status == 'Penghuni'){
                    $this->session->set_flashdata('pesan', 'toastr.success("Berhasil menghapus penghuni <strong>'.$penghuni->nama.'</strong> dari kamar <strong>'.$no_kamar.'</strong>")');
                    
                    // Log activity
                    $this->log_activity('DELETE', 'penghuni', $id, 'Hapus penghuni: '.$penghuni->nama.' dari kamar '.$no_kamar);
                }
                else {
                    $this->session->set_flashdata('pesan', 'toastr.error("Terjadi kesalahan")');
                }
            }
            else {
                $this->session->set_flashdata('pesan', 'toastr.error("Terjadi kesalahan")');
            }

            if ($penghuni->status == 'Penghuni'){
                redirect (base_url('daftar-penghuni'));
            }
            else{
                redirect (base_url(''));
            }
        }
    }

    function aksi_tambah_pembayaran(){
        $id_penghuni    = $this->input->post('id_penghuni');
        $nama           = $this->input->post('nama');
        $no_kamar       = $this->input->post('no_kamar');
        $bayar          = $this->input->post('bayar');
        $tgl_bayar      = $this->input->post('tgl_bayar');
        $ket            = $this->input->post('ket');

        $data = array(
            'id_penghuni'   => $id_penghuni,
            'bayar'         => $bayar,
            'tgl_bayar'     => $tgl_bayar,
            'ket'           => $ket
        );

        if ($this->m_data->insert_pembayaran($data) == true){
            $this->session->set_flashdata('pesan', 'toastr.success("Berhasil menambah data pembayaran penghuni <strong>'.$nama.'</strong> pada kamar <strong>'.$no_kamar.'</strong>")');
            
            // Log activity
            $this->log_activity('INSERT', 'keuangan', $this->db->insert_id(), 'Tambah pembayaran: '.$nama.' Rp'.number_format($bayar, 0, ',', '.'));
        }
        else {
            $this->session->set_flashdata('pesan', 'toastr.error("Terjadi kesalahan")');
        }
        redirect (base_url('riwayat-pembayaran'));
    }

    function aksi_edit_pembayaran(){
        $id_pembayaran  = $this->input->post('id_pembayaran');
        $nama           = $this->input->post('nama');
        $no_kamar       = $this->input->post('no_kamar');
        $bayar          = $this->input->post('bayar');
        $tgl_bayar      = $this->input->post('tgl_bayar');
        $ket            = $this->input->post('ket');

        $data = array(
            'tgl_bayar' => $tgl_bayar,
            'bayar' => $bayar,
            'ket'   => $ket
        );

        if ($this->m_data->update_pembayaran($id_pembayaran, $data) == true){
            $this->session->set_flashdata('pesan', 'toastr.success("Berhasil memperbarui pembayaran tanggal <strong>'.$tgl_bayar.'</strong> dari penghuni <strong>'.$nama.'</strong>")');
            
            // Log activity
            $this->log_activity('UPDATE', 'keuangan', $id_pembayaran, 'Edit pembayaran: '.$nama.' tanggal '.$tgl_bayar);
        }
        else {
            $this->session->set_flashdata('pesan', 'toastr.error("Terjadi kesalahan")');
        }
        redirect (base_url('riwayat-pembayaran'));
    }

    function aksi_hapus_pembayaran($id_pembayaran = null){

        if (!isset($id_pembayaran)) redirect (base_url('riwayat-pembayaran'));

        $pembayaran = $this->m_data->detail_pembayaran(array('id_pembayaran' => $id_pembayaran))->row();

        if (!$pembayaran){
            show_404();
        }
        else {
            if ($this->m_data->delete_pembayaran($id_pembayaran) == true){
                $this->session->set_flashdata('pesan', 'toastr.success("Berhasil menghapus pembayaran tanggal <strong>'.$pembayaran->tgl_bayar.'</strong> dari penghuni <strong>'.$pembayaran->nama.'</strong>")');
                
                // Log activity
                $this->log_activity('DELETE', 'keuangan', $id_pembayaran, 'Hapus pembayaran: '.$pembayaran->nama.' tanggal '.$pembayaran->tgl_bayar);
            }
            else {
                $this->session->set_flashdata('pesan', 'toastr.error("Terjadi kesalahan")');
            }
            redirect (base_url('riwayat-pembayaran'));
        }
    }

    function aksi_ubah_pass(){
        $username = $this->session->userdata('username');
        $password = $this->input->post('password');
        $password_baru = sha1($this->input->post('password_baru'));

        $this->load->model('m_login');
        $cek = $this->m_login->cek_login($username, $password);

        if ($cek->num_rows() > 0){
            if ($this->m_data->update_password($username, $password_baru) == true){
                $this->session->set_flashdata('pesan', 'berhasil_ubah_pass');
                redirect (base_url('login'));
            }
            else{
                echo 'Terjadi Kesalahan';
            }
        }
        else {
            $this->session->set_flashdata('pesan', 'gagal_ubah_pass');
            redirect (base_url('ubah-pass'));
        }
    }

    /**
     * ====================================================================
     * Get Pendapatan Tahunan untuk Dashboard
     * ====================================================================
     */
    function get_pendapatan_tahunan(){
        $tahun = $this->input->post('tahun');
        
        if (!$tahun) {
            $tahun = date('Y');
        }

        $this->load->model('m_dashboard');
        
        $data = array();
        $bulan_indo = array(
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        );
        
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $bulan_str = str_pad($bulan, 2, '0', STR_PAD_LEFT);
            
            // Total pendapatan
            $this->db->select_sum('bayar');
            $this->db->like('tgl_bayar', $bulan_str . '-' . $tahun, 'before');
            $result_total = $this->db->get('keuangan')->row();
            
            // Jumlah transaksi
            $this->db->where('tgl_bayar LIKE', '%-' . $bulan_str . '-' . $tahun);
            $jumlah_transaksi = $this->db->count_all_results('keuangan');
            
            $data[] = array(
                'bulan' => $bulan_indo[$bulan],
                'bulan_num' => $bulan,
                'total' => $result_total->bayar ? $result_total->bayar : 0,
                'jumlah_transaksi' => $jumlah_transaksi
            );
        }
        
        echo json_encode($data);
    }

}