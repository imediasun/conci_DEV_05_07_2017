<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 /**
* 
* This controller contains the functions related to Payment gateway management 
* @author Casperon
*
**/

class Payment_gateway extends MY_Controller {

	function __construct(){
        parent::__construct();
		$this->load->helper(array('cookie','date','form'));
		$this->load->library(array('encrypt','form_validation'));		
		$this->load->model('payment_gateway_model');
		$this->load->model('admin_model');
		
		if ($this->checkPrivileges('payment_gateway',$this->privStatus) == FALSE){
			$this->setErrorMessage('error','You have no privilege for this managment','admin_payment_gate_no_privilage');
			redirect('admin/dashboard/admin_dashboard');
		}
		
		$c_fun = $this->uri->segment(3);
		$restricted_function = array('insertEditGateway','change_payment_gateway_status_global','pay_by_cash_status','use_wallet_amount_status');
		if(in_array($c_fun,$restricted_function) && $this->data['isDemo']===TRUE){
			$this->setErrorMessage('error','You are in demo version. you can\'t do this action.','admin_common_demo_version');
			redirect($_SERVER['HTTP_REFERER']); die;
		}
    }
    
    /**
    * 
    * This function loads the Payment gateway list page
	*
    **/
   	public function index(){	
		if ($this->checkLogin('A') == ''){
			redirect('admin');
		} else {
			redirect('admin/payment_gateway/display_payment_gateway_list');
		}
	}
	
	
	/**
	 * 
	 * This function loads the payment gateway list
	 */
	public function display_payment_gateway_list(){
		if ($this->checkLogin('A') == ''){
			redirect('admin');
		}else {
		    if ($this->lang->line('admin_menu_payment_gateway') != '') 
		    $this->data['heading']= stripslashes($this->lang->line('admin_menu_payment_gateway')); 
		    else  $this->data['heading'] = 'Payment Gateway';
			$condition = array();
			$this->data['gatewayLists'] = $this->payment_gateway_model->get_all_details(PAYMENT_GATEWAY,$condition);
			$this->load->view('admin/payment_gateway/display_payment_gateway',$this->data);
		}
	}
	
	/**
	 * 
	 * This function loads the edit gateway form
	 */
	public function edit_gateway_form(){
		if ($this->checkLogin('A') == ''){
			redirect('admin');
		}else {
		    if ($this->lang->line('admin_edit_payment_gateway_settings') != '') 
		    $this->data['heading']= stripslashes($this->lang->line('admin_edit_payment_gateway_settings')); 
		    else  $this->data['heading'] = 'Edit Gateway Settings';
			$gateway_id = $this->uri->segment(4,0);
			$condition = array('_id'=>new \MongoId($gateway_id));
			$this->data['gateway_details'] = $this->payment_gateway_model->get_all_details(PAYMENT_GATEWAY,$condition);
			if ($this->data['gateway_details']->num_rows() == 1){
				$this->load->view('admin/payment_gateway/edit_gateway',$this->data);
			}else {
				redirect('admin');
			}
		}
	}
	
	/**
	 * 
	 * This function insert and edit a payment gateway
	 */
	public function insertEditGateway(){
		if ($this->checkLogin('A') == ''){
			redirect('admin');
		}else {
				$gateway_id = $this->input->post('gateway_id');
				$mode = $this->input->post('mode');
				$gatewaySettings = array();
				if ($mode == ''){
					$gatewaySettings['mode'] = 'sandbox';
				}else {
					$gatewaySettings['mode'] = 'live';
				}
				
				$condition = array('_id'=>new \MongoId($gateway_id));;
				$getGateWayName = $this->payment_gateway_model->get_selected_fields(PAYMENT_GATEWAY,$condition,array('gateway_name'));
				

	
				if($getGateWayName->row()->gateway_name == 'Stripe'){
					if($gatewaySettings['mode'] != $this->data['stripe_settings']['settings']['mode']){
						$userdataArr = array('stripe_customer_id' =>''); 
						$this->payment_gateway_model->update_details(USERS,$userdataArr,array());
					}
				}
				
				
				$excludeArr = array("gateway_id","mode");
				foreach ($this->input->post() as $key => $val){
					if (!in_array($key, $excludeArr)){
						$gatewaySettings[$key] = $val;
					}
				}
				$dataArr = array('settings' => $gatewaySettings);
				$condition = array('_id'=>new \MongoId($gateway_id));
				if ($gateway_id == ''){
					$this->setErrorMessage('success','Payment gateway updated successfully','admin_payment_gate_update_success');
				}else {
					$this->payment_gateway_model->update_details(PAYMENT_GATEWAY,$dataArr,$condition);
					$this->payment_gateway_model->savePaymentSettings();
					$this->setErrorMessage('success','Payment gateway updated successfully','admin_payment_gate_update_success');
				}
				redirect('admin/payment_gateway/display_payment_gateway_list');
		}
	}
	
	/**
	 * 
	 * This function change the gateway status
	 */
	public function change_gateway_status(){
		if ($this->checkLogin('A') == ''){
			redirect('admin');
		}else {
				$mode = $this->uri->segment(4,0);
				$gateway_id = $this->uri->segment(5,0);
				$status = ($mode == '0')?'Disable':'Enable';
				$newdata = array('status' => $status);
				$condition = array('_id'=>new \MongoId($gateway_id));
				$this->payment_gateway_model->update_details(PAYMENT_GATEWAY,$newdata,$condition);
				$this->payment_gateway_model->savePaymentSettings();
				$this->setErrorMessage('success','Payment Gateway Status Changed Successfully','admin_payment_gate_status_successs');
				redirect('admin/payment_gateway/display_payment_gateway_list');
		}
	}
	
	/**
	 * 
	 * This function delete the seller request records
	 */
	public function change_payment_gateway_status_global(){
			if(count($_POST['checkbox_id']) > 0 &&  $_POST['statusMode'] != ''){ 
				$this->payment_gateway_model->activeInactiveCommon(PAYMENT_GATEWAY,'_id');
				$this->payment_gateway_model->savePaymentSettings();
				$this->setErrorMessage('success','Payment gateway records status changed successfully','admin_payment_gate_record_changed');
				redirect('admin/payment_gateway/display_payment_gateway_list');
			} else {
				$this->setErrorMessage('success','Payment gateway records failed to update','admin_payment_gate_record_failed_update');
				redirect('admin/payment_gateway/display_payment_gateway_list');
			}
	}
	
	/**
	 * 
	 * This function change the status of the pay_by_cash
	 */
	public function pay_by_cash_status(){
		if ($this->checkLogin('A') == ''){
			redirect('admin');
		}else {
			$mode = $this->uri->segment(4,0);
			$condition = array('admin_id'=>'1');
			$status = ($mode == '0')?'Disable':'Enable';
			$newdata = array('pay_by_cash' => $status);
			$this->payment_gateway_model->update_details(ADMIN,$newdata,$condition);
			$this->admin_model->saveAdminSettings();
			$this->setErrorMessage('success','Payment Gateway Status Changed Successfully','admin_payment_gate_status_successs');
			redirect('admin/payment_gateway/display_payment_gateway_list');
		}
	}
	/**
	 * 
	 * This function change the status of the use_wallet_amount
	 */
	public function use_wallet_amount_status(){
		if ($this->checkLogin('A') == ''){
			redirect('admin');
		}else {
			$mode = $this->uri->segment(4,0);
			$condition = array('admin_id'=>'1');
			$status = ($mode == '0')?'Disable':'Enable';
			$newdata = array('use_wallet_amount' => $status);
			$this->payment_gateway_model->update_details(ADMIN,$newdata,$condition);
			$this->admin_model->saveAdminSettings();
			$this->setErrorMessage('success','Payment Gateway Status Changed Successfully','admin_payment_gate_status_successs');
			redirect('admin/payment_gateway/display_payment_gateway_list');
		}
	}
	
}

/* End of file payment_gateway.php */
/* Location: ./application/controllers/admin/payment_gateway.php */