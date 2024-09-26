<?php 
defined('BASEPATH') OR exit('No direct script acces allowed');


require_once APPPATH . 'application\PhpSpreadsheet-1.29.1\PHPExcel\IOFactory.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportExcel {
	protected $ci;

	public function __construct() {
		$this->ci =& get_instance();
	}

	public function export()
	{
		$spreadsheet = new Spreadsheet();
		$activeWorksheet = $spreadsheet->getActiveSheet();
		$activeWorksheet->setCellValue('A1', 'Hello World !');

		$writer = new Xlsx($spreadsheet);
		$writer->save('hello world.xlsx');
	}
}
