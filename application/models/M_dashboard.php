<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_dashboard extends CI_Model {

    function get_kamar_terisi(){
        $this->db->select('COUNT(DISTINCT no_kamar) as total');
        $this->db->where('status', 'Penghuni');
        $result = $this->db->get('penghuni')->row();
        return $result ? $result->total : 0;
    }

    function get_pendapatan_bulan_ini(){
        $bulan = date('m');
        $tahun = date('Y');
        
        $this->db->select_sum('bayar');
        $this->db->like('tgl_bayar', $bulan . '-' . $tahun, 'before');
        $result = $this->db->get('keuangan')->row();
        
        return $result->bayar ? $result->bayar : 0;
    }

    function get_pendapatan_tahun_ini(){
        $tahun = date('Y');
        
        $this->db->select_sum('bayar');
        $this->db->like('tgl_bayar', $tahun, 'before');
        $result = $this->db->get('keuangan')->row();
        
        return $result->bayar ? $result->bayar : 0;
    }

    function get_total_piutang(){
        $this->db->select('SUM(piutang) as total_piutang');
        $this->db->where('status', 'Penghuni');
        $result = $this->db->get('detail_penghuni')->row();
        
        return $result->total_piutang ? $result->total_piutang : 0;
    }

    function get_pendapatan_12_bulan(){
        $data = array();
        
        for ($i = 11; $i >= 0; $i--) {
            $bulan = date('m', strtotime("-$i months"));
            $tahun = date('Y', strtotime("-$i months"));
            $bulan_nama = date('M Y', strtotime("-$i months"));
            
            $this->db->select_sum('bayar');
            $this->db->like('tgl_bayar', $bulan . '-' . $tahun, 'before');
            $result = $this->db->get('keuangan')->row();
            
            $data[] = array(
                'bulan' => $bulan_nama,
                'total' => $result->bayar ? $result->bayar : 0
            );
        }
        
        return $data;
    }

    function get_pendapatan_per_bulan($tahun = null){
        if (!$tahun) {
            $tahun = date('Y');
        }
        
        $data = array();
        
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $bulan_str = str_pad($bulan, 2, '0', STR_PAD_LEFT);
            $bulan_nama = date('F', mktime(0, 0, 0, $bulan, 1));
            
            $this->db->select_sum('bayar');
            $this->db->like('tgl_bayar', $bulan_str . '-' . $tahun, 'before');
            $result = $this->db->get('keuangan')->row();
            
            $data[] = array(
                'bulan' => $bulan_nama,
                'bulan_num' => $bulan,
                'total' => $result->bayar ? $result->bayar : 0
            );
        }
        
        return $data;
    }

    function get_tahun_tersedia(){
        $this->db->select('DISTINCT SUBSTRING_INDEX(tgl_bayar, "-", -1) as tahun');
        $this->db->order_by('tahun', 'DESC');
        $result = $this->db->get('keuangan')->result();
        
        $tahun_list = array();
        foreach ($result as $row) {
            if ($row->tahun) {
                $tahun_list[] = $row->tahun;
            }
        }
        
        return $tahun_list;
    }
}