<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/** 
* 
* User related functions
* @author Casperon
*
**/
 
class Masking extends MY_Controller {

	function __construct(){
        parent::__construct();
		$this->load->helper(array('cookie','date','form','email'));
		$this->load->library(array('encrypt','form_validation'));
		$this->load->model('user_action_model'); 
		$this->load->model('app_model'); 
		$responseArr=array();
		
		/* $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
        if (stripos($ua, 'cabily2k15android') === false) {
            show_404();
        } */
		
		header('Content-type:application/json;charset=utf-8');
		/*Authentication Begin*/
		$headers = $this->input->request_headers();
		if(array_key_exists("Apptype",$headers)) $this->Apptype =$headers['Apptype'];
		if(array_key_exists("Userid",$headers)) $this->Userid =$headers['Userid'];
		if(array_key_exists("Driverid",$headers)) $this->Driverid =$headers['Driverid'];
		if(array_key_exists("Apptoken",$headers)) $this->Token =$headers['Apptoken'];
		try{
			if(($this->Userid!="" || $this->Driverid!="") && $this->Token!="" && $this->Apptype!=""){
				if($this->Driverid!=''){
					$deadChk = $this->app_model->get_selected_fields(DRIVERS, array('_id' => new \MongoId($this->Driverid)), array('push_notification'));
					if($deadChk->num_rows()>0){
						$storedToken ='';
						if(strtolower($deadChk->row()->push_notification['type']) == "ios"){
							$storedToken = $deadChk->row()->push_notification["key"];
						}
						if(strtolower($deadChk->row()->push_notification['type']) == "android"){
							$storedToken = $deadChk->row()->push_notification["key"];
						}
						$c_fun= $this->router->fetch_method();
						$apply_function = array('update_receive_mode','get_app_info');
						if(!in_array($c_fun,$apply_function)){
							if($storedToken!=''){
								if ($storedToken != $this->Token) {
									echo json_encode(array("is_dead" => "Yes"));
									die;
								}
							}
						}
					}
				}
				if($this->Userid!=''){
					$deadChk = $this->app_model->get_selected_fields(USERS, array('_id' => new \MongoId($this->Userid)), array('push_type', 'push_notification_key'));
					if($deadChk->num_rows()>0){
						$storedToken ='';
						if(strtolower($deadChk->row()->push_type) == "ios"){
							$storedToken = $deadChk->row()->push_notification_key["ios_token"];
						}
						if(strtolower($deadChk->row()->push_type) == "android"){
							$storedToken = $deadChk->row()->push_notification_key["gcm_id"];
						}
						if($storedToken!=''){
							if($storedToken != $this->Token){
								echo json_encode(array("is_dead"=>"Yes")); die;
							}
						}
					}
				}
			 }
		} catch (MongoException $ex) {}
		/*Authentication End*/
    }
	
	/**
	*
	*	This function will return the information to app during launch app
	*
	**/	
	public function make_masking_call() {
	
        $returnArr['status'] = '0';
        $returnArr['response'] = '';
		
		try {
			$ride_id = (string)$this->input->post('ride_id');
			$user_type = (string)$this->input->post('user_type'); #(user/driver)
			$checkRide = $this->app_model->get_all_details(RIDES, array('ride_id' => $ride_id));
			if ($checkRide->num_rows() == 1) {
				$ride_status = $checkRide->row()->ride_status;
				$allowed_status = array('Confirmed','Arrived','Onride','Finished','Completed');
				if(in_array($ride_status,$allowed_status)){
					
					if($checkRide->row()->user['phone']){
						$passanger_number = $checkRide->row()->user['phone'];
					}
					if($checkRide->row()->driver['phone']){
						$driver_number = $checkRide->row()->driver['phone'];
					}
					
					if($user_type=='user'){
						$primary_call = $passanger_number;
						$secondary_call = $driver_number;
					}
					if($user_type=='driver'){
						$primary_call = $driver_number;
						$secondary_call = $passanger_number;
					}
					
					if($passanger_number!='' && $driver_number!=''){
						
						$twilio_mode        = $this->config->item('twilio_account_type');
						$twilio_account_sid = $this->config->item('twilio_account_sid');
						$twilio_auth_token  = $this->config->item('twilio_auth_token');
						$twilio_number      = '+'.$this->config->item('twilio_number');
						
						try{
							// this line loads the library 
							require(APPPATH.'/third_party/twilio/Services/Twilio.php'); 

							$account_sid = $twilio_account_sid; 
							$auth_token = $twilio_auth_token; 
							$client = new Services_Twilio($account_sid, $auth_token); 
							
							$url = base_url().'phmsk?callid='.$secondary_call;
							$client->account->calls->create($twilio_number, $primary_call, $url, array( 
							'Method' => 'GET',  
							'FallbackMethod' => 'GET',  
							'StatusCallbackMethod' => 'GET',    
							'Record' => 'false', 
							));
							
							$returnArr['status'] = '1';
							$returnArr['response'] = $this->format_string("Please wait, we will call you back", "wait_will_call");
						}catch(Exception $e){
							#$returnArr['response'] = $e->getMessage();
							$returnArr['response'] = $this->format_string("Number is unverified", "number_unverified");
						}
					}else{
						$returnArr['response'] = $this->format_string("Call not allowed", "call_not_allowed");
					}
					
				}else{
					$returnArr['response'] = $this->format_string("You cannot make a call now", "cannot_make_a_call_now");
				}
			}else{
				$returnArr['response'] = $this->format_string("You cannot make a call now", "cannot_make_a_call_now");
			}
		}catch (MongoException $ex) {
			$returnArr['response'] = $this->format_string("Error in connection", "error_in_connection");
        }
        $json_encode = json_encode($returnArr, JSON_PRETTY_PRINT);
        echo $this->cleanString($json_encode);
    }
	
	/**
	*
	*	This function will send the sms to user/driver
	*
	**/	
	public function send_sms() {
	
        $returnArr['status'] = '0';
        $returnArr['response'] = '';
		
		try {
			$ride_id = (string)$this->input->post('ride_id');
			$user_type = (string)$this->input->post('user_type'); #(user/driver)
			$sms_content = (string)$this->input->post('sms_content');
			
			$checkRide = $this->app_model->get_all_details(RIDES, array('ride_id' => $ride_id));
			if ($checkRide->num_rows() == 1) {
				$ride_status = $checkRide->row()->ride_status;
				$allowed_status = array('Confirmed','Arrived','Onride','Finished','Completed');
				if(in_array($ride_status,$allowed_status)){
					
					if($checkRide->row()->user['phone']){
						$passanger_number = $checkRide->row()->user['phone'];
					}
					if($checkRide->row()->driver['phone']){
						$driver_number = $checkRide->row()->driver['phone'];
					}
					
					$number_to_send_sms = "";
					if($user_type=='user'){
						$number_to_send_sms = $driver_number;
					}
					if($user_type=='driver'){
						$number_to_send_sms = $passanger_number;
					}
					
					if($number_to_send_sms!=''){
						$from = $this->config->item('twilio_number');
						$to = $number_to_send_sms;
						$message = $sms_content;
						$response = $this->twilio->sms($from, $to, $message); 
						$returnArr['status'] = '1';
						$returnArr['response'] = $this->format_string("SMS sent successfully", "sms_sent");
					}else{
						$returnArr['response'] = $this->format_string("You cannot send a sms now", "cannot_send_a_sms_now");
					}
				}else{
					$returnArr['response'] = $this->format_string("You cannot send a sms now", "cannot_send_a_sms_now");
				}
			}else{
				$returnArr['response'] = $this->format_string("You cannot send a sms now", "cannot_send_a_sms_now");
			}
		}catch (MongoException $ex) {
			$returnArr['response'] = $this->format_string("Error in connection", "error_in_connection");
        }
        $json_encode = json_encode($returnArr, JSON_PRETTY_PRINT);
        echo $this->cleanString($json_encode);
    }
	
}

/* End of file common.php */
/* Location: ./application/controllers/api_v3/common.php */