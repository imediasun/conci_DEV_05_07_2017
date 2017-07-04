<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 
 * This model contains all db functions related to Rides management
 * @author Casperon
 *
 * */
class Rides_model extends My_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 
     * This functions selects rides list 
     * */
    public function get_rides_total($ride_actions = '', $driver_id = '') {
        $this->cimongo->select('*');
        if ($ride_actions == 'Booked' || $ride_actions == '') {
            $where_clause = array('ride_status' => 'Booked');
        } else if ($ride_actions == 'OnRide') {
            $where_clause = array(
                '$or' => array(
                    array("ride_status" => 'Onride'),
                    array("ride_status" => 'Confirmed'),
                    array("ride_status" => 'Arrived'),
                )
            );
        } else if ($ride_actions == 'Completed') {
            $where_clause = array(
                '$or' => array(
                    array("ride_status" => 'Completed'),
                    array("ride_status" => 'Finished'),
                )
            );
        } else if ($ride_actions == 'Cancelled') {
            $where_clause = array('ride_status' => 'Cancelled');
        } else if ($ride_actions == 'riderCancelled') {
            $where_clause = array('ride_status' => 'Cancelled', 'cancelled.primary.by' => 'User');
        } else if ($ride_actions == 'driverCancelled') {
            $where_clause = array('ride_status' => 'Cancelled', 'cancelled.primary.by' => 'Driver');
		} else if ($ride_actions == 'Expired') {
            $where_clause = array('ride_status' => 'Expired');
        } else if ($ride_actions == 'total') {
            $where_clause = array();
        }
        if ($driver_id != '') {
            $where_clause['driver.id'] = $driver_id;
        }
        $this->cimongo->where($where_clause, TRUE);
        $res = $this->cimongo->get(RIDES);
        return $res;
    }

    /**
     * 
     * This functions selects rides list 
     * */
    public function get_rides_list($ride_actions = '', $limit = FALSE, $offset = FALSE, $driver_id = '', $filter_array = array()) {
		$this->cimongo->select('*');
        if ($ride_actions == 'Booked' || $ride_actions == '') {
            $where_clause = array('ride_status' => 'Booked');
        } else if ($ride_actions == 'OnRide') {
            $where_clause = array(
                '$or' => array(
                    array("ride_status" => 'Onride'),
                    array("ride_status" => 'Confirmed'),
                    array("ride_status" => 'Arrived'),
                    array("ride_status" => 'Finished'),
                )
            );
        } else if ($ride_actions == 'Completed') {
            $where_clause = array(
                '$or' => array(
                    array("ride_status" => 'Completed'),
                #array("ride_status"=>'Finished'),
                )
            );
        } else if ($ride_actions == 'Cancelled') {
            $where_clause = array('ride_status' => 'Cancelled');
        } else if ($ride_actions == 'riderCancelled') {
            $where_clause = array('ride_status' => 'Cancelled', 'cancelled.primary.by' => 'User');
        } else if ($ride_actions == 'driverCancelled') {
            $where_clause = array('ride_status' => 'Cancelled', 'cancelled.primary.by' => 'Driver');
		} else if ($ride_actions == 'Expired') {
            $where_clause = array('ride_status' => 'Expired');
        } else if ($ride_actions == 'total') {
            $where_clause = array();
        }

        if ($driver_id != '') {
            $where_clause['driver.id'] = $driver_id;
        }
		/* Filter Rides*/
		if(!empty($filter_array))
			extract($filter_array);
			
		if(isset($location) && !empty($location)){
			$where_clause['location.id'] = $location;
		}	
		
		if(isset($to) && !empty($to) && isset($from) && !empty($from)){
			$from_date = base64_decode($from).' 00:00:00';
			$to_date = base64_decode($to).' 23:59:59';
			$where_clause['booking_information.est_pickup_date'] = array('$lte' => new MongoDate(strtotime($to_date)),'$gte' => new MongoDate(strtotime($from_date)));
		}else if(isset($from) && !empty($from)){
			$from_date = base64_decode($from).' 00:00:00';
			$where_clause['booking_information.est_pickup_date'] = array('$gte' => new MongoDate(strtotime($from_date)));
		}
		/* Filter Rides*/
		
        $this->cimongo->where($where_clause, TRUE);
		$this->cimongo->order_by(array('ride_id' => -1));
        if ($limit !== FALSE && is_numeric($limit) && $offset !== FALSE && is_numeric($offset)) {
            $res = $this->cimongo->get(RIDES, $limit, $offset);
        } else {
            $res = $this->cimongo->get(RIDES);
        }


        return $res;
    }

    /**
     *
     * This function return the ride list
     * @param String $type (all/upcoming/completed)
     * @param String $user_id
     * @param Array $fieldArr
     *
     * */
    public function get_ride_list($user_id = '', $type = '', $fieldArr = array()) {
        if ($user_id != '' && $type != '') {
            $this->cimongo->select($fieldArr);

            switch ($type) {
                case 'all':
                    $where_clause = array("user.id" => $user_id);
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
                case 'completed':
                    $where_clause = array(
                        '$or' => array(
                            #array("ride_status"=>'Finished')
                            array("ride_status" => 'Completed')
                        ),
                        "user.id" => $user_id
                    );
                    #$this->cimongo->or_where(array('ride_status'=>'Completed', 'ride_status'=>'Cancelled','ride_status'=>'Confirmed', 'ride_status'=>'Arrived','ride_status'=>'Onride', 'ride_status'=>'Finished'));
                    break;
                default:
                    $where_clause = array("user.id" => $user_id);
                    break;
            }
            $this->cimongo->where($where_clause, TRUE);
			$this->cimongo->order_by(array('ride_id' => -1));
            $res = $this->cimongo->get(RIDES);
            return $res;
        }
    }

    /**
     * 
     * This functions selects driver's rides list 
     * */
    public function get_driver_rides_list($ride_actions = '', $driver_id = '') {
        $this->cimongo->select('*');
        if ($driver_id != '') {
            $this->cimongo->where(array('driver.id' => $driver_id));
        }
        if ($ride_actions == 'Booked' || $ride_actions == '') {
            $this->cimongo->where(array('ride_status' => 'Booked'));
        } else if ($ride_actions == 'OnRide') {
            $this->cimongo->or_where(array('ride_status' => 'Onride', 'ride_status' => 'Confirmed', 'ride_status' => 'Arrived'));
        } else if ($ride_actions == 'Completed') {
            $this->cimongo->or_where(array('ride_status' => 'Completed', 'ride_status' => 'Finished'));
        } else if ($ride_actions == 'Cancelled') {
            $this->cimongo->where(array('ride_status' => 'Cancelled'));
        }
		$this->cimongo->order_by(array('ride_id' => -1));
        $res = $this->cimongo->get(RIDES);
        return $res;
    }
	
	
	/**
	* Get Unfilled Rides
	**/
	public function get_unfilled_rides($coordinates = array(),$matchArr = array()){
		$option = array(
								array(
									'$geoNear'=>array("near"=>array("type"=>"Point",
														"coordinates"=>$coordinates
														),
									"spherical"=> true,
									"maxDistance"=>50000,
									"includeLocs"=>'location',
									"distanceField"=>"distance",
									"distanceMultiplier"=>0.001
									
									),
								),
								array(
									'$project' => array(
										'pickup_address' =>1,
										'user_id' =>1,
										'location' =>1,
										'category' =>1,
										'ride_time' =>1
									)
								)
					);
		
		if(!empty($matchArr)){
			$option[] = $matchArr;
		}
		$res = $this->cimongo->aggregate(RIDE_STATISTICS,$option);
		return $res;
	}

}
