<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("./libs/html2pdf_v4.03/html2pdf.class.php"); 

class H2pdf extends HTML2PDF
{
  //protected 	$ci;

	public function __construct()
	{
        //$this->ci =& get_instance();
        parent::__construct();
	}

	

}

/* End of file html2pdf.php */
/* Location: ./application/libraries/html2pdf.php */



?>