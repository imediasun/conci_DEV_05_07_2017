<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Query extends MY_Controller {
	function __construct(){
        parent::__construct();
		$this->load->helper(array('cookie','date','form'));
		$this->load->library(array('encrypt','form_validation'));		
		$this->load->model(array('user_model'));
		$this->load->model(array('app_model'));
    }
    
	/* public function createCol(){
		$this->cimongo->command(array("create" => 'dectar_operators'));
	} */
	public function unsetAdmin(){
		#$field = array('meta_title','meta_keyword','meta_description','google_verification','google_verification_code','text_title','text_description','media_title');
		#$field = array('currency');
		#$this->cimongo->where(array())->unset_field($field)->update(ADMIN);
	}
	public function eilocation(){
		#$this->cimongo->ensure_index(LOCATIONS,array('location'=>'2dsphere'));
	}
	public function eilocation2(){
		#$this->cimongo->ensure_index(LOCATIONS,array('bounds'=>'2d'));
	}
    public function user_geo(){
		#$this->cimongo->ensure_index(RIDE_STATISTICS,array('location'=>'2dsphere'));
	}
	public function geoindex(){
		$this->cimongo->ensure_index(RIDE_STATISTICS,array('location'=>'2dsphere'));
		$this->cimongo->ensure_index(USERS,array('loc'=>'2dsphere'));
	}
    public function update_user_loc()
    {
         $checkGeo = $this->user_model->get_all_details(USER_LOCATION, array());
         foreach($checkGeo->result() as $row ){
             
             $geo_data_user = array('loc' => array('lon' => floatval($row->geo[0]), 'lat' => floatval($row->geo[1])),'last_active_time'=>new \MongoDate(time()));
             $this->user_model->update_details(USERS, $geo_data_user, array('_id' => new \MongoId((string)$row->user_id)));
         }
       echo "done";
    }
	public function update_year_of_model(){
		
		$all_model=$this->user_model->get_selected_fields(MODELS,array(),array('_id'));
		$year_of_model=array(2005,2006,2007,2008,2009,2010,2011,2012,2013,2014,2015,2016);
		foreach($all_model->result() as $row){
				$inserting_array=array();
				
				$random_keys=array_rand($year_of_model,4);
				foreach($random_keys as $key){
					$inserting_array[]=$year_of_model[$key];
				}
				
				$update=$this->user_model->update_details(MODELS,array('year_of_model'=>$inserting_array),array('_id'=>$row->_id));
		
		}
	}
	
	public function v_ride(){
		$rideId = $this->uri->segment(3);
		$getRide = $this->user_model->get_all_details(RIDES,array('ride_id' => $rideId));
		echo '<pre>'; print_r($getRide->result()); die;
	}
	public function t_ride(){
		$rideId = $this->uri->segment(3);
		$getRideTracking = $this->user_model->get_all_details(TRANSACTION,array('ride_id' => $rideId));
		$steps = $getRideTracking->row()->steps;
		$json_encode = json_encode($steps, JSON_PRETTY_PRINT);
        echo $this->cleanString($json_encode);
		echo '<pre>'; print_r($getRideTracking->result()); die;
	}
	public function h_ride(){
		$rideId = $this->uri->segment(3);
		$getRideHIstory = $this->user_model->get_all_details(TRAVEL_HISTORY,array('ride_id' => $rideId));
		echo '<pre>'; print_r($getRideHIstory->result()); die;
	}
	public function ht_ride(){
		date_default_timezone_set('Asia/Kolkata');
		$rideId = $this->uri->segment(3);
		$getRideHIstory = $this->user_model->get_all_details(TRAVEL_HISTORY,array('ride_id' => $rideId));
		$h1Val = array();
		$hVal = array();
		if($getRideHIstory->num_rows()>0){
			$history = $getRideHIstory->row()->history_end;
			foreach($history as $rows){
				$hVal[] = array("lat"=>$rows['lat'],"lon"=>$rows['lon'],"update_time"=>date("Y-m-d H:i:s",$rows['update_time']->sec));
			}
			$history1 = $getRideHIstory->row()->history;
			foreach($history1 as $rows){
				$h1Val[] = array("lat"=>$rows['lat'],"lon"=>$rows['lon'],"update_time"=>date("Y-m-d H:i:s",$rows['update_time']->sec));
			}
		}
		echo '<pre>'; 
		print_r($h1Val); 
		print_r($hVal); 
		print_r($getRideHIstory->result()); 
		die;
	}
	
	
   
   public function j_ride() {
   
    $res_arr = array();
    $res2_arr = array();
    $res3_arr = array();
    $res4_arr = array();
    $val1 = array();
    $val2 = array();
    $rideId = $this->uri->segment(3);
	 $getRideHIstory = $this->user_model->get_all_details(TRAVEL_HISTORY,array('ride_id' => $rideId));
    foreach ($getRideHIstory->result() as $key => $data) {
      
      #exit;
      foreach($data->history_end as $value) {
      
       $val1[]=array('lat'=>$value['lat'],'lon'=>$value['lon']); 
       
      
      }
    }
    
    
    
    $json_encode = json_encode($val1);
    echo $this->cleanString($json_encode);
   
   }
   
   public function f_ride() {
   
    $res_arr = array();
    $res2_arr = array();
    $res3_arr = array();
    $res4_arr = array();
    $val1 = array();
    $val2 = array();
    $rideId = $this->uri->segment(3);
	 $getRideHIstory = $this->user_model->get_all_details(TRAVEL_HISTORY,array('ride_id' => $rideId));
    foreach ($getRideHIstory->result() as $key => $data) {
      
      #exit;
      foreach($data->history_end as $value) {
      
        if(count($val1)==0){
        $val1[0] = $value['lat'];
        $val1[1] = $value['lon']; 
        $val2[0] = $value['lat'];
        $val2[1] = $value['lon'];
        continue;
      }else{
        $val1[0] = $val2[0];
        $val1[1] = $val2[1]; 
      }

      $val2[0] = $value['lat'];
      $val2[1] = $value['lon'];

      

      $res_arr[] = round($this->distance($val1[0], $val1[1], $val2[0], $val2[1], "K"),3);
      $res2_arr[] =round($this->distance2($val1[0], $val1[1], $val2[0], $val2[1]),3);
      $res3_arr[] = round($this->distance3($val1[0], $val1[1], $val2[0], $val2[1]),3);
      $res4_arr[] = round($this->distance4($val1[0], $val1[1], $val2[0], $val2[1]),3);
       
      
      }
    }
    echo "<pre>";
    print_r($res_arr);
    print_r($res2_arr);
    print_r($res3_arr);
    print_r($res4_arr);
    echo "Total1 <b>".array_sum($res_arr)."</b> km<p>";
    echo "Total2 <b>".array_sum($res2_arr)."</b> km<p>";
    echo "Total3 <b>".array_sum($res3_arr)."</b> km<p>";
    echo "Total4 <b>".array_sum($res4_arr)."</b> km<p>";
    
   
   }
   
   
   function distance($lat1, $lon1, $lat2, $lon2, $unit) {

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
      return ($miles * 1.609344);
  } else if ($unit == "N") {
      return ($miles * 0.8684);
  } else {
      return $miles;
  }
}

function distance2($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo) {

//Calculate distance from latitude and longitude
$theta = $longitudeFrom - $longitudeTo;
$dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
$dist = acos($dist);
$dist = rad2deg($dist);
$miles = $dist * 60 * 1.1515;

return $distance = ($miles * 1.609344);
}

function distance3($lat1, $lon1, $lat2, $lon2) {
  $rad = M_PI / 180;
  return acos(sin($lat2*$rad) * sin($lat1*$rad) + cos($lat2*$rad) * cos($lat1*$rad) * cos($lon2*$rad - $lon1*$rad)) * 6371;// Kilometers
}

function distance4($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 3959){
  // convert from degrees to radians
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $latDelta = $latTo - $latFrom;
  $lonDelta = $lonTo - $lonFrom;

  $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
  return $angle * $earthRadius * 1.609344;
}


   public function d_ride() {
   error_reporting(-1);
    $res_arr = array();
    $val1 = array();
    $val2 = array();
	$res_tArr = array();
    $rideId = $this->uri->segment(3);
	 $getRideHIstory = $this->user_model->get_all_details(TRAVEL_HISTORY,array('ride_id' => $rideId));
    foreach ($getRideHIstory->result() as $key => $data) {
		foreach($data->history_end as $value) {
		if(count($val1)==0){
			$val1[0] = $value['lat'];
			$val1[1] = $value['lon']; 
			$val2[0] = $value['lat'];
			$val2[1] = $value['lon'];
			continue;
		}else{
			$val1[0] = $val2[0];
			$val1[1] = $val2[1]; 
		}

		$val2[0] = $value['lat'];
		$val2[1] = $value['lon'];

		$res_arr[] = round($this->distance4($val1[0], $val1[1], $val2[0], $val2[1]),3);
			
		$dis = round($this->distance4($val1[0], $val1[1], $val2[0], $val2[1]),3);
		
		$keyT = date("H_i",$value["update_time"]->sec);
		if(array_key_exists($keyT,$res_tArr)){			
			if($dis<=1){
				$res_tArr["$keyT"]['km'] = $dis+$res_tArr["$keyT"]['km'];
				$res_tArr["$keyT"]['below_1_km'][] = date("Y-m-d H:i:s",$value["update_time"]->sec);
			}else{
				$res_tArr["$keyT"]['above_1_km'][] = date("Y-m-d H:i:s",$value["update_time"]->sec);
			}
		}else{
			if($dis<=1){
				$res_tArr["$keyT"]['km'] = $dis;
				$res_tArr["$keyT"]['below_1_km'][] = date("Y-m-d H:i:s",$value["update_time"]->sec);
			}else{
				$res_tArr["$keyT"]['above_1_km'][] = date("Y-m-d H:i:s",$value["update_time"]->sec);
			}
		}
		
		if($dis<=1){
			$res_arr_after[] = $dis;
		}
	  
		}
    }
    echo "<pre>";
    print_r($res_arr);
    echo "Total Distance : <b>".array_sum($res_arr)."</b> km<p></br/>";
    echo "Final Distance : <b>".array_sum($res_arr_after)."</b> km<p>";
    echo "Total Distance By Timing: <b>"; print_r($res_tArr); echo "</b><p></br/>";
   
   }
   
   
	public function dr_loc(){
		$driver_id = $this->uri->segment(3);
		$ride_id = $this->uri->segment(4);
		$deriver_details = $this->user_model->get_all_details(DRIVERS, array('_id'=>new \MongoId($driver_id)));
		#echo "<pre>"; print_r($deriver_details->result());
		echo date("Y-m-d H:i:s",$deriver_details->row()->last_active_time->sec);
		echo "<pre>";
		print_r($deriver_details->row()->loc);
		if($ride_id!=''){
			$checkInfo = $this->driver_model->get_all_details(TRACKING, array('ride_id' => $c_ride_id));
		}
		
	}
	public function r_ride(){
		$rideId = $this->uri->segment(3);
		$getRideHIstory = $this->user_model->get_all_details(RIDE_HISTORY,array('ride_id' => $rideId));
		echo '<pre>'; print_r($getRideHIstory->result()); die;
		
	}
   
	
}

/* End of file query.php */
/* Location: ./application/controllers/query.php */