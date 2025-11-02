<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pembayaran extends CI_Model {

    function insert_pengajuan($data){
        $this->db->insert('pengajuan_pembayaran', $data);
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    function get_pengajuan_by_penghuni($id_penghuni){
        $this->db->where('id_penghuni', $id_penghuni);
        $this->db->order_by('tgl_pengajuan', 'DESC');
        return $this->db->get('pengajuan_pembayaran')->result();
    }

    function get_all_pengajuan($where = null){
        $this->db->select('pengajuan_pembayaran.*, penghuni.nama, penghuni.no_kamar');
        $this->db->join('penghuni', 'penghuni.id = pengajuan_pembayaran.id_penghuni');
        
        if ($where) {
            // Perbaikan: tambahkan nama tabel di depan kolom status
            foreach ($where as $key => $value) {
                if ($key == 'status') {
                    $this->db->where('pengajuan_pembayaran.status', $value);
                } else {
                    $this->db->where($key, $value);
                }
            }
        }
        
        $this->db->order_by('tgl_pengajuan', 'DESC');
        return $this->db->get('pengajuan_pembayaran')->result();
    }

    function get_pengajuan_by_id($id_pengajuan){
        return $this->db->get_where('pengajuan_pembayaran', array('id_pengajuan' => $id_pengajuan))->row();
    }

    function approve_pengajuan($id_pengajuan){
        $pengajuan = $this->get_pengajuan_by_id($id_pengajuan);
        
        if (!$pengajuan) return false;

        // Update status pengajuan
        $this->db->where('id_pengajuan', $id_pengajuan);
        $this->db->update('pengajuan_pembayaran', array(
            'status' => 'approved',
            'tgl_konfirmasi' => date('Y-m-d H:i:s')
        ));

        // Insert ke tabel keuangan
        $data_keuangan = array(
            'id_penghuni' => $pengajuan->id_penghuni,
            'tgl_bayar' => date('d-m-Y'),
            'bayar' => $pengajuan->nominal,
            'ket' => 'Pembayaran via ' . $pengajuan->metode_pembayaran . ($pengajuan->keterangan ? ' - ' . $pengajuan->keterangan : '')
        );

        $this->db->insert('keuangan', $data_keuangan);

        return ($this->db->affected_rows() > 0) ? true : false;
    }

    function reject_pengajuan($id_pengajuan){
        $this->db->where('id_pengajuan', $id_pengajuan);
        $this->db->update('pengajuan_pembayaran', array(
            'status' => 'rejected',
            'tgl_konfirmasi' => date('Y-m-d H:i:s')
        ));

        return ($this->db->affected_rows() > 0) ? true : false;
    }

    function delete_pengajuan($id_pengajuan){
        // Hapus file bukti transfer
        $pengajuan = $this->get_pengajuan_by_id($id_pengajuan);
        if ($pengajuan && $pengajuan->bukti_transfer) {
            $file_path = './assets/uploads/bukti_transfer/' . $pengajuan->bukti_transfer;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        $this->db->delete('pengajuan_pembayaran', array('id_pengajuan' => $id_pengajuan));
        return ($this->db->affected_rows() > 0) ? true : false;
    }
}