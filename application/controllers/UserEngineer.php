<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserEngineer extends CI_Controller {
public function __construct() //construct itu buat klo jalani controller admin itu yg di cek itu contructnya dulu
{
	parent:: __construct();
		is_logged_in(2);//helper ini buat sendiri di folder helper namanya bebas dan harus di tambahkan juga di autoload dan tambahkan di library helper, cek aja!
		$this->load->model('LoginModel');
		
	}
	public function index()
	{
		$data['title'] =  'Profile';
		$data['user'] = $this->LoginModel->getUser($this->session->userdata('nik'));
		$data['role'] = $this->session->userdata('role');
		if (!$data['user']['photo']){
			$data['user']['photo'] = 'user.png';
		}
		
		$this->load->view('template/header');
		$this->load->view('engineer/sidebar',$data);
		$this->load->view('engineer/profile',$data);
		$this->load->view('template/footer');

	}

	public function work_activity($engineer,$location)
	{
		$data['title'] =  'Job';
		$data['user'] = $this->LoginModel->getUser($this->session->userdata('nik'));
		$data['location'] = $location;
		$workId = $this->LoginModel->getWorkByLocEngineer($engineer,$location);
		
		//tampilkan shift engineer by location
		$getWorkId = $this->LoginModel->getWorkByLocEng($location,$engineer);
		$data['shift'] = $this->LoginModel->getShiftLocation($getWorkId['id']);

		if (!$data['user']['photo']){
			$data['user']['photo'] = 'user.png';
		}
		
		$this->load->view('template/header');
		$this->load->view('engineer/sidebar',$data);
		$this->load->view('engineer/work_activity',$data);
		$this->load->view('engineer/report',$data);
		$this->load->view('template/footer');

	}
	public function work_activity_location()
	{
		$data['title'] =  'Choose Location';
		$data['user'] = $this->LoginModel->getUser($this->session->userdata('nik'));
		$data['location'] = $this->LoginModel->getLocationtByEngineer($data['user']['id']);

		if (!$data['user']['photo']){
			$data['user']['photo'] = 'user.png';
		}
		
		$this->load->view('template/header');
		$this->load->view('engineer/sidebar',$data);
		$this->load->view('engineer/work_activity_location',$data);
		$this->load->view('template/footer');
	}
	public function shift($shift,$engineer,$work)
	{
		$data['title'] =  'Your Job';
		$data['user'] = $this->LoginModel->getUser($this->session->userdata('nik'));
		
		//untk view engineer/shift
		$data['s'] = $this->LoginModel->getDetailShiftEngineerWork_($shift,$work)->row_array();
		//untk view engineer/workactivity
		$data['shift'] = $this->LoginModel->getDetailShiftEngineerWork($work)->result_array();
		$data['detail_shift'] = $this->LoginModel->getDetailShiftByShift($shift,$engineer);

		$this->load->view('template/header');
		$this->load->view('engineer/sidebar',$data);
		$this->load->view('engineer/work_activity',$data);
		$this->load->view('engineer/shift',$data);
		$this->load->view('template/footer');


	}

	//tampilkan data yang di pilih ke modal
	public function choose($shift,$work,$id)
	{
		//dapat data user yang dipilih
		$data['user'] = $this->LoginModel->getUser($this->session->userdata('nik'));
		$data['detail_shift'] = $this->LoginModel->getById($id,'detail_shift');
		// $data['shift'] = $this->LoginModel->getById($id,'shift');
		$data['shift'] = $this->LoginModel->getDetailShiftEngineerWork_($shift,$work)->row_array();

		$this->load->view('engineer/choose',$data);
	}

	public function save($shift,$user,$detail_shift,$work)
	{
		$data = [
			// 'priority' => htmlspecialchars($this->input->post('priority', true)),
			'information' => htmlspecialchars($this->input->post('information', true)),
			'respon_time' => htmlspecialchars($this->input->post('response_time', true)),
			'detail_shift' => $detail_shift,
			'work_date' => htmlspecialchars($this->input->post('work_date', true)),
			'engineer' => $user
		];
			$this->db->insert('work_activity', $data); //untuk simpan ke database
			$this->session->set_flashdata('flash' , 'Save');
			redirect(base_url()."userengineer/shift/".$shift.'/'.$user.'/'.$work); //redirect with parameter
		}

		public function report($location,$engineer)
		{
			$data['title'] =  'Report';
			$data['user'] = $this->LoginModel->getUser($this->session->userdata('nik'));

			$getWorkId = $this->LoginModel->getWorkByLocEng($location,$engineer);
			$data['shift'] = $this->LoginModel->getWorkByLocEng($getWorkId['id']);

			$this->load->view('template/header');
			$this->load->view('engineer/sidebar',$data);
			$this->load->view('engineer/report',$data);
			$this->load->view('template/footer');
		}
		public function report_choose($location)
		{
			$engineer = $this->input->post('engineer');
			$date_ = $this->input->post('date');
			$date = date('Y-m-d', strtotime($date_));
			$shift = $this->input->post('shift');

			$data['title'] =  'Report';
			$data['user'] = $this->LoginModel->getUser($this->session->userdata('nik'));
			
			$data['work_activity']= $this->LoginModel->getWorkActivityEngineer($engineer,$date,$shift,$location)->result_array();
			$data['wa']= $this->LoginModel->getWorkActivityEngineer($engineer,$date,$shift,$location)->row_array();
			$data['wh'] = $this->LoginModel->getWorkHour($engineer)->row_array();

			/*$data['param_egineer'] = $engineer;
			$data['param_data'] = $date;
			$data['param_shift'] = $shift;
			$data['param_location'] = $location;*/
			if (!$data['user']['photo']){
				$data['user']['photo'] = 'user.png';
			}
			$this->load->view('template/header');
			$this->load->view('engineer/sidebar',$data);
			$this->load->view('engineer/report_choose',$data);
			$this->load->view('template/footer');
		/*	var_dump($data);*/
		}

		public function pdf($engineer,$date,$shift,$location)
		{
			$this->load->library('mypdf');

			$data['title'] =  'Report';
			$data['user'] = $this->LoginModel->getUser($this->session->userdata('nik'));

			$data['work_activity']= $this->LoginModel->getWorkActivityEngineer($engineer,$date,$shift,$location)->result_array();
			$data['wa']= $this->LoginModel->getWorkActivityEngineer($engineer,$date,$shift,$location)->row_array();
			$data['wh'] = $this->LoginModel->getWorkHour($engineer)->row_array();

			$filename = 'Laporan '. $data['wa']['name'].' '.$data['wa']['shift'].' '.$data['wa']['work_date'];

			/*var_dump($data);*/
//			$view, $filename, $paper = 'A4', $orientation ='portrait', $data = array()

			//$view, $filename, $data, $paper = 'A4', $orientation ='portrait'
			$this->mypdf->generate('engineer/pdf',$filename, $data, 'A4', 'portrait');
//			$this->mypdf->generate('engineer/pdf',$data, $filename);


		}

		public function excel($engineer,$date,$shift,$location)
		{
			require (APPPATH.'PhpSpreadsheet-1.29.1/src/PhpSpreadsheet/Spreadsheet.php');
			require (APPPATH.'PhpSpreadsheet-1.29.1/Writer/Xlsx.php');

			$data['title'] =  'Report';
			$data['user'] = $this->LoginModel->getUser($this->session->userdata('nik'));
			
			$data['work_activity']= $this->LoginModel->getWorkActivityEngineer($engineer,$date,$shift,$location)->result_array();
			$data['wa']= $this->LoginModel->getWorkActivityEngineer($engineer,$date,$shift,$location)->row_array();
			$data['wh'] = $this->LoginModel->getWorkHour($engineer)->row_array();
			$filename = 'Laporan '.' '.$data['wa']['shift'].' '.$data['wa']['work_date'];

			/*if ($data['wh']['work_hour_id'] == '1'){
				//echo 'Flexible Hour : '.$data['wh']['work_hour_id'];
				$this->_covertToExcelOfficeHour($data, $filename);
			} else {
				//echo 'Office Hour : '.$data['wh']['work_hour_id'];
				$this->_covertToExcelFlexibleHour($data, $filename);
			}*/
			/*$this->_covertToExcel($data, $filename);*/

			$spreadsheet = new Spreadsheet();
			$activeWorksheet = $spreadsheet->getActiveSheet();
			$activeWorksheet->setCellValue('A1', 'Hello World !');

			$writer = new Xlsx($spreadsheet);
			$writer->save('hello world.xlsx');

		}

	public function _covertToExcelOfficeHour($data, $filename)
	{
		//load library Excel
		require(APPPATH.'PHPExcel-1.8/Classes/PHPExcel.php');
		require(APPPATH.'PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php');
		//buat obj baru
		$objPHPExcel = new PHPExcel();
		//ini untuk atur propeties saat file sudah di  convert
		$objPHPExcel->getProperties()->setCreator($filename);
		$objPHPExcel->getProperties()->setLastModifiedBy($filename);
		$objPHPExcel->getProperties()->setTitle($filename);
		$objPHPExcel->getProperties()->setSubject("");
		$objPHPExcel->getProperties()->setDescription("");
		//set index yang active
		$objPHPExcel->setActiveSheetIndex(0);
		//set Column value sheet active
		$objPHPExcel->getActiveSheet()->SetCellValue('A5','No');
		$objPHPExcel->getActiveSheet()->SetCellValue('B5','Start Time');
		$objPHPExcel->getActiveSheet()->SetCellValue('C5','Response Time');
		$objPHPExcel->getActiveSheet()->SetCellValue('D5','Activity');
		$objPHPExcel->getActiveSheet()->SetCellValue('E5','Information');
		// $objPHPExcel->getActiveSheet()->SetCellValue('F1','Information');
		//set Column DIMENSION sheet active
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(17);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(42);
		// $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(42);

		//set align value cell sheet active
		$style=array('alignment' =>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->applyFromArray($style);

		//set border color
		$objPHPExcel->getActiveSheet()->getStyle('A5:E5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		// $objPHPExcel->getActiveSheet(1)->getStyle('A2:F2')->getBorders()->getAllBorders()->getColor()->setRGB('CC0000');

		//set cell background color
		$objPHPExcel->getActiveSheet()->getStyle('A5:E5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A5:F5')->getFill()->getStartColor()->setRGB('C7D5E2');
		//Keterangan cetak laporan
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Name :');
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Date :');
		$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Shift/Location :');

		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $data['wa']['name']);
		$date_ = date('l, d-m-Y', strtotime($data['wa']['work_date']));
		$objPHPExcel->getActiveSheet()->SetCellValue('B2',$date_);
		$objPHPExcel->getActiveSheet()->SetCellValue('B3', $data['wa']['shift'].'/'.$data['wa']['location_name']);

		//set Value sheet Active
		$baris = 6;
		$no=1;

		foreach ($data['work_activity'] as $w) {

			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$baris, $no);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$baris, $w['time_start']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$baris, $w['respon_time']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$baris, $w['activity_shift']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$baris, $w['information']);
			// $objPHPExcel->getActiveSheet()->SetCellValue('F'.$baris, $w['information']);

			$no++;
			$baris++;
		}

		//buat nama file excel
		$filenameExcel = $filename.'.xlsx';

		$objPHPExcel->getActiveSheet()->setTitle($filename);

		//ini bagian penting dalam convert excel on php
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filenameExcel.'"');
		header('Cache-Control: max-age=0');

		$Writer=PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$Writer->save('php://output');

		exit;
	}

	public function _covertToExcelFlexibleHour($data, $filename)
	{
		//load library Excel
		require(APPPATH.'PHPExcel-1.8/Classes/PHPExcel.php');
		require(APPPATH.'PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php');
		//buat obj baru
		$objPHPExcel = new PHPExcel();
		//ini untuk atur propeties saat file sudah di  convert
		$objPHPExcel->getProperties()->setCreator($filename);
		$objPHPExcel->getProperties()->setLastModifiedBy($filename);
		$objPHPExcel->getProperties()->setTitle($filename);
		$objPHPExcel->getProperties()->setSubject("");
		$objPHPExcel->getProperties()->setDescription("");
		//set index yang active
		$objPHPExcel->setActiveSheetIndex(0);
		//set Column value sheet active
		$objPHPExcel->getActiveSheet()->SetCellValue('A5','No');
		/*$objPHPExcel->getActiveSheet()->SetCellValue('B5','Start Time');*/
		$objPHPExcel->getActiveSheet()->SetCellValue('B5','Response Time');
		$objPHPExcel->getActiveSheet()->SetCellValue('C5','Activity');
		$objPHPExcel->getActiveSheet()->SetCellValue('D5','Information');
		// $objPHPExcel->getActiveSheet()->SetCellValue('F1','Information');
		//set Column DIMENSION sheet active
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
		//$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(17);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
		// $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(42);

		//set align value cell sheet active
		$style=array('alignment' =>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->applyFromArray($style);

		//set border color
		$objPHPExcel->getActiveSheet()->getStyle('A5:D5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		// $objPHPExcel->getActiveSheet(1)->getStyle('A2:F2')->getBorders()->getAllBorders()->getColor()->setRGB('CC0000');

		//set cell background color
		$objPHPExcel->getActiveSheet()->getStyle('A5:D5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A5:D5')->getFill()->getStartColor()->setRGB('C7D5E2');
		//Keterangan cetak laporan
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Name :');
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Date :');
		$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Shift/Location :');

		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $data['wa']['name']);
		$date_ = date('l, d-m-Y', strtotime($data['wa']['work_date']));
		$objPHPExcel->getActiveSheet()->SetCellValue('B2',$date_);
		$objPHPExcel->getActiveSheet()->SetCellValue('B3', $data['wa']['shift'].'/'.$data['wa']['location_name']);

		//set Value sheet Active
		$baris = 6;
		$no=1;

		foreach ($data['work_activity'] as $w) {

			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$baris, $no);
			//$objPHPExcel->getActiveSheet()->SetCellValue('B'.$baris, $w['time_start']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$baris, $w['respon_time']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$baris, $w['activity_shift']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$baris, $w['information']);
			// $objPHPExcel->getActiveSheet()->SetCellValue('F'.$baris, $w['information']);

			$no++;
			$baris++;
		}

		//buat nama file excel
		$filenameExcel = $filename.'.xlsx';

		$objPHPExcel->getActiveSheet()->setTitle($filename);

		//ini bagian penting dalam convert excel on php
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filenameExcel.'"');
		header('Cache-Control: max-age=0');

		$Writer=PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$Writer->save('php://output');

		exit;
	}

	public function editEngineer_($id)
	{
		$data['engineer'] = $this->LoginModel->getById($id,'engineer');

		$this->load->view('engineer/editProfile',$data);
	}


}
