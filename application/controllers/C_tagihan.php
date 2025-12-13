<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_tagihan extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('m_data');
        date_default_timezone_set("Asia/Bangkok");
    }

    /**
     * Cron Job untuk Update Tagihan Bulanan
     * URL: http://localhost/SkyKost/c_tagihan/update_tagihan_bulanan/[secret_key]
     */
    function update_tagihan_bulanan($secret_key = null){
        // Security: Hanya bisa diakses dengan secret key yang benar
        $valid_key = 'skykost'; // Ganti dengan key rahasia Anda
        
        if ($secret_key !== $valid_key) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ]);
            return;
        }

        // Ambil semua penghuni aktif
        $penghuni_aktif = $this->db->get_where('penghuni', ['status' => 'Penghuni'])->result();
        
        $bulan_sekarang = date('Y-m');
        $updated = 0;
        $errors = [];
        
        $this->db->trans_start(); // Start transaction
        
        foreach ($penghuni_aktif as $penghuni) {
            try {
                // Hitung berapa bulan sudah berlalu sejak masuk
                $tgl_masuk = DateTime::createFromFormat('d-m-Y', $penghuni->tgl_masuk);
                $now = new DateTime();
                
                if ($tgl_masuk) {
                    // Hitung jumlah bulan yang sudah berlalu
                    $interval = $tgl_masuk->diff($now);
                    $bulan_berlalu = ($interval->y * 12) + $interval->m;
                    
                    // Hitung tagihan seharusnya (harga per bulan * bulan berlalu)
                    $harga_kamar = $this->db->get_where('kamar', ['no_kamar' => $penghuni->no_kamar])->row();
                    $tagihan_seharusnya = $harga_kamar->harga * ($bulan_berlalu + 1); // +1 untuk bulan berjalan
                    
                    // Update tagihan penghuni
                    $this->db->where('id', $penghuni->id);
                    $this->db->update('penghuni', [
                        'tagihan' => $tagihan_seharusnya
                    ]);
                    
                    $updated++;
                }
            } catch (Exception $e) {
                $errors[] = "Error updating penghuni ID {$penghuni->id}: " . $e->getMessage();
            }
        }
        
        $this->db->trans_complete(); // Complete transaction
        
        $response = [
            'status' => 'success',
            'message' => "Updated {$updated} penghuni untuk bulan {$bulan_sekarang}",
            'total_penghuni' => count($penghuni_aktif),
            'updated' => $updated,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        if (!empty($errors)) {
            $response['errors'] = $errors;
        }
        
        // Log hasil update
        $this->log_cron_result($response);
        
        echo json_encode($response);
    }

    /**
     * Log hasil cron job
     */
    private function log_cron_result($data){
        $log_file = APPPATH . 'logs/cron_tagihan_' . date('Y-m') . '.log';
        $log_message = date('Y-m-d H:i:s') . ' - ' . json_encode($data) . PHP_EOL;
        file_put_contents($log_file, $log_message, FILE_APPEND);
    }

    /**
     * Cek status cron job terakhir
     */
    function status($secret_key = null){
        $valid_key = 'skykost';
        
        if ($secret_key !== $valid_key) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            return;
        }

        $log_file = APPPATH . 'logs/cron_tagihan_' . date('Y-m') . '.log';
        
        if (file_exists($log_file)) {
            $logs = file($log_file);
            $last_log = end($logs);
            echo json_encode([
                'status' => 'success',
                'last_run' => $last_log
            ]);
        } else {
            echo json_encode([
                'status' => 'warning',
                'message' => 'No log found for this month'
            ]);
        }
    }
}