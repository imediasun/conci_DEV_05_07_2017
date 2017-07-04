<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 /**
* 
* This model contains all db functions related to map management
* @author Casperon
*
**/

class Map_model extends My_Model{

	public function __construct(){
		parent::__construct();
	}
	
	/**
	* Check driver exist or not
	**/
	public function get_nearest_driver($coordinates = array()){
		/* $option=array('$geoNear'=>array("near"=>array("type"=>"Point",
														"coordinates"=>$coordinates
														),
								"spherical"=> true,
								"maxDistance"=>5000000,
								"includeLocs"=>'loc',
								"distanceField"=>"distance",
								"distanceMultiplier"=>0.001,
								'num' => 10
								)); */
	
		$option = array(
								array(
									'$geoNear'=>array("near"=>array("type"=>"Point",
														"coordinates"=>$coordinates
														),
									"spherical"=> true,
									//"maxDistance"=>50000,
									"includeLocs"=>'loc',
									"distanceField"=>"distance",
									"distanceMultiplier"=>0.001
									
									),
								),
								array(
									'$project' => array(
										'category' =>1,
										'driver_name' =>1,
										'loc' =>1,
										'availability' =>1,
										'status' =>1,
										'distance' =>1,
                                        'mode'=>1,
                                        'last_active_time' => 1
									)
								),
								array(
									'$match' => array(
										//'availability' =>array('$eq'=>'Yes'),
										'status' =>array('$eq'=>'Active')
									)
								)
							);
		$res = $this->cimongo->aggregate(DRIVERS,$option);
		return $res;
	}
    public function get_nearest_user($coordinates = array()){
		/* $option=array('$geoNear'=>array("near"=>array("type"=>"Point",
														"coordinates"=>$coordinates
														),
								"spherical"=> true,
								"maxDistance"=>5000000,
								"includeLocs"=>'loc',
								"distanceField"=>"distance",
								"distanceMultiplier"=>0.001,
								'num' => 10
								)); */
		
		$option = array(
								array(
									'$geoNear'=>array("near"=>array("type"=>"Point",
														"coordinates"=>$coordinates
														),
									"spherical"=> true,
									//"maxDistance"=>50000,
									"includeLocs"=>'loc',
									"distanceField"=>"distance",
									"distanceMultiplier"=>0.001
									
									),
								),
								array(
									'$project' => array(
										'user_name' =>1,
										'loc' =>1,
									    'status' =>1,
									    'email' =>1,
									    'phone_number' =>1,
									    'distance' =>1
										
									)
								),
								array(
									'$match' => array(
										//'availability' =>array('$eq'=>'Yes'),
										'status' =>array('$eq'=>'Active')
									)
								)
							);
		$res = $this->cimongo->aggregate(USERS,$option);
		return $res;
	}
	
}

?>