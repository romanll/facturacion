<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pdf extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('pdfm');
    }

    function index() {
        $file=$this->pdfm->SetDisplayMode('fullpage');
        /*//cargar estilo
        $stylesheet = file_get_contents("./css/invoice.css");
        $this->pdfm->WriteHTML($stylesheet,1); // The parameter 1 tells that this is css/style only and no html*/
        //escribir html
        $this->pdfm->WriteHTML(file_get_contents("./application/views/invoice.php"));
        $this->pdfm->Output();
    }

    function htmltest(){
        $this->load->view('invoice');
    }

    function qrc(){
        $this->load->library('ciqrcode');
        $datos['data']="?re=XAXX010101000&rr=XAXX010101000&tt=1234567890.123456&id=ad662d33-6934-459c-a128-BDf0393f0f44";
        $datos['level']='M';
        $datos['size']=4;
        $datos['savename']="./ufiles/qr.png";
        $this->ciqrcode->generate($datos);
        echo "<img src='".base_url("ufiles/qr.png")."'>";
    }


}

?>