<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("./libs/MPDF57/mpdf.php");

class Pdfm extends mPDF{
  protected $ci;

	public function __construct()
	{
        $this->ci =& get_instance();
        parent::__construct();
	}

	

}

/* End of file Mpdf.php */
/* Location: ./application/libraries/Mpdf.php */


?>