<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $data=array(
            'email'=>NULL,
            //'iduser' => NULL,
            'logged_in' => FALSE,
            'tipo'=>NULL
        );
        $this->session->unset_userdata($data);
        $this->session->sess_destroy();
        redirect('login');
    }
}

?>