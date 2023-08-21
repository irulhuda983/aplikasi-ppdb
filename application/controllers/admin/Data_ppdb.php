<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_ppdb extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('m_umum');
		$this->load->model('M_covid');
		$this->load->model('M_lokasi');
    }
	
	public function index(){
		$data['userLogin']  = $this->session->userdata('loginData');
		$data['listData']	= $this->M_covid->getAll();
		$data['lokasi']	= $this->M_lokasi->getListProvinsi();
		$data['v_content']  = 'member/covid/index';
		$this->load->view('member/layout', $data);

	}

	public function store()
	{
		$kecamatan = $this->input->post('kecamatan');
		$jumlah = $this->input->post('jumlah');
		$tahun = $this->input->post('tahun');

		$this->db->insert('tbl_ppdb_wilayah', [
			'id_kecamatan' => $kecamatan,
			'jumlah' => $jumlah,
			'tahun' => $tahun,
		]);

		$this->m_umum->generatePesan("Berhasil tambah data","berhasil");
		redirect('admin/data_ppdb/all_data');
	}

	public function update()
	{
		$id = $this->input->post('id');
		$kecamatan = $this->input->post('kecamatan');
		$jumlah = $this->input->post('jumlah');
		$tahun = $this->input->post('tahun');

		$data = [
			'id_kecamatan' => $kecamatan,
			'jumlah' => $jumlah,
			'tahun' => $tahun,
		];
		
		$this->db->where('ppdb_wilayah_id', $id);
		$this->db->update('tbl_ppdb_wilayah', $data);

		$this->m_umum->generatePesan("Berhasil update data","berhasil");
		redirect('admin/data_ppdb/all_data');
	}

	public function uploads(){

		$post = $this->input->post();
		$upload_path = './uploads/covid/';
		/*====================================== BEGIN UPLOADING FEATEURE IMAGE ======================================*/
		$file = "";
		if ($_FILES['file']['name'] <> "") {
			$ext           = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
			$file = "Data".date("dmYHis").rand(100,999).".".$ext;

			$config['upload_path']   = $upload_path;
			$config['allowed_types'] = '*';
			$config['file_name']     = $file;

			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload('file')){
				$error = 'error: '. $this->upload->display_errors();
				echo $error;
				die();
			}else{
				$file = "uploads/covid/".$file;
			}
		}


		$url = base_url($file); // path to your JSON file
		$data = file_get_contents($url); // put the contents of the file into a variable
		$characters = json_decode($data); // decode the JSON feed


		
		//$this->db->delete("tbl_covid_province", array("id_province" => $_POST['id_province']));
		foreach ($characters->list_perkembangan as $key => $value) {
			$time = strtotime($value->tanggal);

			$dataArray = array(
				"id_province"			=>	$_POST['id_province'],
				"kasus"					=>	$value->KASUS,
				"akumulasi_meninggal"	=>	$value->AKUMULASI_MENINGGAL,
				"akumulasi_sembuh"		=>	$value->AKUMULASI_SEMBUH,
				"rawat_isolasi"			=>	$value->AKUMULASI_DIRAWAT_OR_ISOLASI,
				"tanggal"				=>	$characters->last_date,
				"akumulasi_kasus"		=>	$value->AKUMULASI_KASUS,
			);

			$cek = $this->db->query("select * from tbl_covid_province where id_province = '".$_POST['id_province']."' AND tanggal = '".$characters->last_date."'")->row();
			if($cek){
				$dataArray['updated_date'] = date("Y-m-d");
				$this->db->update("tbl_covid_province",$dataArray, array("id_province" => $_POST['id_province'], "tanggal" =>$characters->last_date ));
			}else{
				$dataArray['created_date'] = date("Y-m-d");
				$this->db->insert("tbl_covid_province",$dataArray);
			}

			
		}
		

		redirect("admin/data_covid/all_data");		    
	}

	public function excel()
	{
		$upload_path = './uploads/ppdb/';
		$data = [];

		if( isset( $_FILES["file"]["name"] ) ) {
			// upload
			$file_tmp = $_FILES['file']['tmp_name'];
			$file_name = $_FILES['file']['name'];
			$file_size =$_FILES['file']['size'];
			$file_type=$_FILES['file']['type'];

			// move_uploaded_file($file_tmp,"uploads/".$file_name); // simpan filenya di folder uploads
			$object = PHPExcel_IOFactory::load($file_tmp);

			foreach($object->getWorksheetIterator() as $worksheet){
        
				$highestRow = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();
	
				for($row=2; $row<=$highestRow; $row++){
	
					$idProvinsi = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
					$idKabupaten = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
					$idKecamatan = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
					$jumlah = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
					$tahun = $worksheet->getCellByColumnAndRow(6, $row)->getValue();

					$data[] = array(
						'id_provinsi' => $idProvinsi,
						'id_kabkot' => $idKabupaten,
						'id_kecamatan' => $idKecamatan,
						'jumlah' => $jumlah,
						'tahun' => $tahun,
					);
	
				} 
	
			}

			$query = $this->db->insert_batch('tbl_ppdb_wilayah', $data);

			// var_dump($data);
			// die;
			$message = array(
				'msgbox'=>'<div class="alert alert-success">Import file excel berhasil disimpan di database</div>',
			);
			
			$this->session->set_flashdata($message);
			redirect("admin/data_ppdb/all_data", $data);
		}else{
			 $message = array(
				'msgbox'=>'<div class="alert alert-danger">Import file gagal, coba lagi</div>',
			);
			
			$this->session->set_flashdata($message);
			redirect("admin/data_ppdb/all_data", $data);	
		}
	}

	public function all_data(){
		$data['userLogin']  = $this->session->userdata('loginData');
		// $data['lokasi']	= $this->M_lokasi->getListProvinsi();
		$data['lokasi']	= $this->M_lokasi->getListKecamatan();
		$data['kecamatan']	= $this->db->query('select * from tbl_kecamatan order by nama asc')->result_array();
		$where = "";

		if($_GET['id_kecamatan'] && $_GET['date'] && $_GET['date_type']){
			$where .= " WHERE id_kecamatan = '".$_GET['id_kecamatan']."'";

			$where .= " AND ".$_GET['date_type']." = '".$_GET['date']."'";
		}
		$data['listData']	= $this->db->query("select * from tbl_ppdb_wilayah inner join tbl_kecamatan ON tbl_kecamatan.id = tbl_ppdb_wilayah.id_kecamatan ".$where." order by jumlah desc" )->result();
		$data['v_content']  = 'member/covid/all_data';
		$this->load->view('member/layout', $data);

	}

	public function destroy()
	{
		$id = $this->input->post('id');

		$this->db->where('ppdb_wilayah_id', $id);
		$this->db->delete('tbl_ppdb_wilayah');

		$this->m_umum->generatePesan("Berhasil hapus data","berhasil");
		redirect('admin/data_ppdb/all_data');
	}

	public function truncate(){
		$truncate = $this->db->truncate("tbl_ppdb_wilayah");
		if($truncate){
			$this->m_umum->generatePesan("Berhasil mengosongkan semua data","berhasil");
			redirect('admin/data_ppdb/all_data');
		}else{
			$this->m_umum->generatePesan("Gagal mengosongkan semua data","gagal");
			redirect('admin/data_ppdb/all_data'); 
		}

	}
	
}
