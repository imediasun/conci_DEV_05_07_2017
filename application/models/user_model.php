<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 *
 * This model contains all db functions related to user management
 * @author Casperon
 *
 */

class User_model extends My_Model {

    public function __construct() {
        parent::__construct();
    }

    public function insert_user($user_data = array()) {
        if (!empty($user_data)) {
            $this->cimongo->insert(USERS, $user_data);
        }
    }

    public function check_user_exist($condition = array()) {
        $this->cimongo->select();
        $this->cimongo->where($condition);
        return $res = $this->cimongo->get(USERS);
    }

    public function get_user_details($origin, $refcollection, $primary, $reference) {
        if ($origin->num_rows() > 0) {
            $neworigin = $origin->result_array();
            foreach ($origin->result_array() as $key => $value) {
                $data = array($value[$primary]);
                $this->cimongo->where_in($reference, $data);
                $res = $this->cimongo->get($refcollection);
                if ($res->num_rows() > 0) {
                    $neworigin[$key]['geo'] = $res->row()->geo;
                } else {
                    $neworigin[$key]['geo'] = '';
                }
            }
        }
        return (object) $neworigin;
    }

    public function remove_favorite_location($condition = array(), $field = '') {
        $this->cimongo->where($condition);
        $this->cimongo->unset_field($field);
        $this->cimongo->update(FAVOURITE);
    }

    public function get_current_location() {
        
    }

    /**
     *
     * This function return the ride list
     * @param String $type (all/upcoming/completed)
     * @param String $user_id
     * @param Array $fieldArr
     *
     * */
    public function get_ride_list($user_id = '', $type = '', $fieldArr = array(), $limit, $offset) {
        if ($user_id != '' && $type != '') {
            $this->cimongo->select($fieldArr);

            switch ($type) {
                case 'all':
                    $where_clause = array("user.id" => $user_id);
                    break;
                case 'onride':
                    $where_clause = array(
                        '$or' => array(
                            array("ride_status" => 'Arrived'),
                            array("ride_status" => 'Onride'),
                            array("ride_status" => 'Finished'),
                        ),
                        "user.id" => $user_id
                    );
                    break;
                case 'upcoming':
                    $where_clause = array(
                        '$or' => array(
                            array("ride_status" => 'Booked'),
                            array("ride_status" => 'Confirmed'),
                        ),
                        "user.id" => $user_id
                    );
                    break;
                case 'cancelled':
                    $where_clause = array(
                        '$or' => array(
                            array("ride_status" => 'Cancelled'),
                        ),
                        "user.id" => $user_id
                    );
                    break;
                case 'completed':
                    $where_clause = array(
                        '$or' => array(
                            array("ride_status" => 'Completed')
                        ),
                        "user.id" => $user_id
                    );
                    break;
                default:
                    $where_clause = array("user.id" => $user_id);
                    break;
            }
            $this->cimongo->where($where_clause, TRUE);
            $this->cimongo->order_by(array('_id' => 'DESC'));
            $res = $this->cimongo->get(RIDES, $limit, $offset);
            return $res;
        }
    }
	
	public function check_vehicle_number($vehicle_number="", $driver_id=""){
		$exist = 0;
		if($vehicle_number!=""){
			$this->cimongo->select(array('_id')); 
			$this->cimongo->where(array("vehicle_number"=>$vehicle_number));
			if($driver_id!=""){
				$this->cimongo->where_ne('_id',new \MongoId($driver_id));
			}
			$res = $this->cimongo->get(DRIVERS);		
			if($res->num_rows()>0){
				$exist = 1;
			}
		}
		return $exist;
	}
	
	public function user_transaction($user_id, $trans_type) {
   
      if($trans_type!='')
      {
        $option = array(
                array('$match' => array('user_id'=>New \MongoId($user_id))),
                array('$unwind'=>'$transactions'),
                array('$match' => array('transactions.type'=>$trans_type)),
                array('$group'=>array('_id'=>'$_id','transactions'=>array('$push'=>'$transactions'))));
              
       }
       else
       {
          $option = array(
      
                array('$match' => array('user_id'=>New \MongoId($user_id))),
                );
       }
        $res = $this->cimongo->aggregate(WALLET, $option);
        
        return $res;
    }


}
