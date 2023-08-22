<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kmeans extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('m_umum');
		$this->load->model('M_covid');
		$this->load->model('M_lokasi');
		$this->load->model('M_kmeans');
    }

	public function area(){
		$data['userLogin']  	= $this->session->userdata('loginData');
		//$data['v_content']  = 'member/covid/area';
		$this->load->view('member/covid/area', $data);
	}

    public function daftar_kmeans(){
        $data['userLogin']      = $this->session->userdata('loginData');
        $data['lokasi'] = $this->M_lokasi->getListKecamatan();
        $data['centroid'] = $this->db->query("select * from tbl_centroid")->result_array()[0];
        $data['tahun'] = $this->db->query("SELECT DISTINCT tahun FROM tbl_ppdb_wilayah ORDER BY tahun DESC")->result_array();

        if($_GET['tahun']){
			$data['tahun'] = $_GET['tahun'];
            $where .= " WHERE tahun = '".$data['tahun']."'";
            $data_ppdb = $this->db->query(
                "select * from tbl_ppdb_wilayah 
                inner join tbl_kecamatan ON tbl_kecamatan.id = tbl_ppdb_wilayah.id_kecamatan ".$where)->result();
        } else {
            $data_ppdb = $this->db->query(
                "select * from tbl_ppdb_wilayah 
                inner join tbl_kecamatan ON tbl_kecamatan.id = tbl_ppdb_wilayah.id_kecamatan")->result();
        }

        if(count($data_ppdb) > 0) {
            $data['kmeans']  = $this->M_kmeans->kmeans_2($data_ppdb);
        } else {
            $data['kmeans']  = null;
        }
        
        $data['v_content']  = 'member/covid/kmeans';
        $this->load->view('member/layout', $data);
    }

    public function update_centro()
    {
        $id = $this->input->post('id');
		$c1 = $this->input->post('c1');
		$c2 = $this->input->post('c2');
		$c3 = $this->input->post('c3');

        $cekc1 = $this->db->where('jumlah', $c1)->get('tbl_ppdb_wilayah')->row();
        $cekc2 = $this->db->where('jumlah', $c2)->get('tbl_ppdb_wilayah')->row();
        $cekc3 = $this->db->where('jumlah', $c3)->get('tbl_ppdb_wilayah')->row();

        if(!$cekc1) {
            $this->m_umum->generatePesan("Gagal update centroid, data c1 tidak sesuai dengan db","gagal");

            redirect('admin/kmeans/daftar_kmeans');
        }

        if(!$cekc2) {
            $this->m_umum->generatePesan("Gagal update centroid, data c2 tidak sesuai dengan db","gagal");

            redirect('admin/kmeans/daftar_kmeans');
        }

        if(!$cekc3) {
            $this->m_umum->generatePesan("Gagal update centroid, data c3 tidak sesuai dengan db","gagal");

            redirect('admin/kmeans/daftar_kmeans');
        }

		$data = [
			'center1' => $c1,
			'center2' => $c2,
			'center3' => $c3,
		];
		
		$this->db->where('id', $id);
		$this->db->update('tbl_centroid', $data);

		$this->m_umum->generatePesan("Berhasil update data","berhasil");

        redirect('admin/kmeans/daftar_kmeans');
    }
}
