<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('m_data');
    }

    // Jalankan setiap awal bulan: http://localhost/simkos/cron/update-tagihan-bulanan
    function update_tagihan_bulanan(){
        // Ambil semua penghuni aktif
        $penghuni_aktif = $this->db->get_where('penghuni', ['status' => 'Penghuni'])->result();
        
        $bulan_sekarang = date('Y-m');
        $updated = 0;
        
        foreach ($penghuni_aktif as $penghuni) {
            // Cek apakah sudah di-update bulan ini
            if ($penghuni->bulan_terakhir_bayar != $bulan_sekarang) {
                // Update bulan terakhir bayar
                $this->db->where('id', $penghuni->id);
                $this->db->update('penghuni', [
                    'bulan_terakhir_bayar' => $bulan_sekarang
                ]);
                $updated++;
            }
        }
        
        echo "Updated $updated penghuni untuk bulan $bulan_sekarang";
    }
}