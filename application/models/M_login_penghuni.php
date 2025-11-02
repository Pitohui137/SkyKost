<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_login_penghuni extends CI_Model {

    function cek_login($nama, $password){
        return $this->db->get_where('penghuni', array(
            'nama' => $nama, 
            'password' => sha1($password)
        ));
    }
}