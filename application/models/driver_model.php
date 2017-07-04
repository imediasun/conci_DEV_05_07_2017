<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 *
 * This model contains all db functions related to user management
 * @author Casperon
 *
 */
class Driver_model extends My_Model{
	public function __construct(){
        parent::__construct();

    }

	/***
	* Check driver exist or not
	***/
	public function check_driver_exist($condition = array()){ 
		$this->cimongo->select();
		$this->cimongo->where($condition);
		return $res = $this->cimongo->get(DRIVERS);  
	}
	
	/**
	* 
	* This function selects the vehicles list by category using where IN
	*/
    public function get_vehicles_list_by_category($idsList=''){  
		$ids=array();
		foreach($idsList as $val){
			$ids[]=new \MongoId($val);
		}
		$idsList = array();
		$this->cimongo->where_in('_id',$ids);
		$res = $this->cimongo->get(VEHICLES); 
		return $res;
    }
	
	
	/**
	*
	* This function return the trip summary
	*	String $driver_id
	*
	**/
	public function get_trip_summary($driver_id = '',$start_date = '',$end_date = ''){
		
		if($start_date!='' && $end_date!=''){
			$matchArr=array(
										'$match' => array(
											'driver.id' =>array('$eq'=>$driver_id),
											'ride_status' =>array('$eq'=>'Completed'),
											'pay_status' =>array('$eq'=>'Paid'),
											'history.end_ride' =>array('$gte'=>new \MongoDate($start_date),'$lte'=>new \MongoDate($end_date))
										)
									);
		}else{
			$matchArr=array(
										'$match' => array(
											'driver.id' =>array('$eq'=>$driver_id),
											'ride_status' =>array('$eq'=>'Completed'),
											'pay_status' =>array('$eq'=>'Paid')
										)
									);		
		}
								
		$option = array(
								array(
									'$project' => array(
										'ride_id' =>1,
										'commission_percent' =>1,
										'driver' =>1,
										'total' =>1,
										'booking_information' =>1,
										'ride_status' =>1,
										'pay_status' =>1,
										'summary' =>1,
										'pay_summary' =>1,
										'history' =>1,
										'driver_revenue' =>1,
										'amount_commission' =>1,
										'amount_detail' =>1
									)
								),
								$matchArr
							);
		#echo "<pre>";print_r($option);
		$res = $this->cimongo->aggregate(RIDES,$option);
		return $res;
	}
	
	/**
	*
	* This function return the total earnings
	*
	**/
	public function get_total_earnings($driver_id=''){
		$option = array(								
								array(
									'$project' => array(
										'ride_status' =>1,
										'driver' =>1,
										'total' =>1
									)
								),							
								array(
									'$match' => array(
										'ride_status' =>array('$eq'=>'Completed'),
										'driver.id' =>array('$eq'=>$driver_id)
									)
								),
								array(
									'$group' => array(
										'_id' =>'$ride_status',
										'ride_status'=>['$last'=>'$ride_status'],
										'totalAmount'=>array('$sum'=>'$total.grand_fare')
									)
								)
							);
		$res = $this->cimongo->aggregate(RIDES,$option);
		$totalAmount=0;
		if(!empty($res)){ 
			if(isset($res['result'][0]['totalAmount'])){
				$totalAmount=$res['result'][0]['totalAmount'];
			}
		}
		return $totalAmount;
	}
  public function get_available_category($condition = array()) {
        $data = array();
        $k = 0;
        foreach ($condition as $key => $value) {
            $data[$k] = new MongoId($value);
            $k++;
        }
        $this->cimongo->select();
        $this->cimongo->where_in('_id', $data);
        $res = $this->cimongo->get(CATEGORY);
        return $res;
    }
	
	public function get_driver_last_ride_status($driver_id=''){
		$this->cimongo->select(array('ride_id','ride_status'));
		$this->cimongo->where(array('driver.id' => $driver_id));
		$this->cimongo->order_by(array('ride_id' => 'DESC'));
		$this->cimongo->limit(1);
		return $res = $this->cimongo->get(RIDES);  
	}
	
}