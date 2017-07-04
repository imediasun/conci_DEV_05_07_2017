<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Phmsk extends MY_Controller {
	function __construct(){
        parent::__construct();
		$this->load->helper(array('cookie','date','form'));
		$this->load->library(array('encrypt','form_validation'));	
		$this->load->model(array('app_model'));
    }
	public function index(){
		$callid = $_GET['callid'];
		if($callid){
			$xmlCnt = '<?xml version="1.0" encoding="utf-8"?>';
			$xmlCnt .= '<Response>';
			if($callid != ''){
				$xmlCnt.='<Dial>'.trim($callid).'</Dial>';
			}
			$xmlCnt.= '</Response>';
			header("Content-Type: text/xml");
			header('Content-Transfer-Encoding: binary');
			header('Connection: close');
			echo $xmlCnt; die;
		}
	}
}

/* End of file phmsk.php */
/* Location: ./application/controllers/phmsk.php */