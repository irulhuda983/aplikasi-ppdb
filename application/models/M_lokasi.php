<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_lokasi extends CI_Model {
 
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }
	
	public function getListProvinsi(){
		$this->db->select("*");
		$this->db->from("tbl_provinsi");
		$query  = $this->db->get();
		$result = $query->result();
		return $result;
	}

	public function getListKecamatan(){
		$this->db->select("*");
		$this->db->from("tbl_kecamatan");
		$query  = $this->db->get();
		$result = $query->result();
		return $result;
	}
	
}