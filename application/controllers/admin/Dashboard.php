<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('m_umum');
        $this->load->model('M_kmeans');
    }

    public function index()
	{
		$data['userLogin'] = $this->session->userdata('loginData');
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
			$kmeans  = $this->M_kmeans->kmeans_2($data_ppdb);
			$data_cluster = $kmeans['data_mining'];
			$data['last_iteration'] = $kmeans['cluster'];
        } else {
			$data['last_iteration'] = null;
        }

        // echo $this->M_kmeans->kmeans_2($data_ppdb);
		
		$data['v_content'] = 'member/dashboard/content';
		$this->load->view('member/layout',$data);
	}
    
	function decryptIt( $q ) {
		$cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
		$qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
		return( $qDecoded );
	}
       
}
