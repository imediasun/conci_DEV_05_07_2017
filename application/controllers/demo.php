<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Demo extends MY_Controller {
	function __construct(){
        parent::__construct();
		$this->load->helper(array('cookie','date','form'));
		$this->load->library(array('encrypt','form_validation'));		
		$this->load->model(array('user_model'));
		$this->load->model(array('mail_model'));
		$this->load->model(array('app_model'));
    }
	public function index(){
	}
	public function cls(){
		echo 'Sorry ! go and do you vealai.';
		/* $this->user_model->commonDelete(RIDES,array());
		$this->user_model->commonDelete(PAYMENTS,array());
		$this->user_model->commonDelete(WALLET,array());
		$this->user_model->commonDelete(WALLET_RECHARGE,array());
		$this->user_model->commonDelete(REFER_HISTORY,array());
		$this->user_model->commonDelete(USERS,array());
		$this->user_model->commonDelete(USER_LOCATION,array());
		$this->user_model->commonDelete(STATISTICS,array());
		$this->user_model->commonDelete(FAVOURITE,array());
		$this->user_model->commonDelete(PROMOCODE,array());
		$this->user_model->commonDelete(DRIVERS,array()); 
		$this->user_model->commonDelete(TRANSACTION,array());   
		$this->user_model->commonDelete(BILLINGS,array()); */
	}
	public function update_location_geo_points(){
	
   $condition = array();
	$locationdetails= $this->app_model->get_all_details(LOCATIONS,$condition);
   foreach($locationdetails->result() as $location) {
       
      $oldcoordinatesArr = array();
      /* if(isset($location->loc)){
         $oldcoordinatesArr = $location->loc['coordinates'][0];
         
         $noco = array();
         foreach($oldcoordinatesArr as $key=>$val){
            $noco[] = array_reverse($val);
         }
         $oldcoordinatesArr = $noco;
        
         unset($oldcoordinatesArr[count($oldcoordinatesArr)-1]);
         
      } */
      $map_radius = 10;
      if(isset($location->map_searching_radius)){
         $map_radius = intval($location->map_searching_radius/1000);
      }			
		$data=$this->make_coordinates($location->location['lat'],$location->location['lng'],'',$oldcoordinatesArr,$map_radius);
    
      $bcArr=array();
      foreach($data as $points){
		$bcArr[]=array_reverse($points);
         
		}
     #if(!isset($location->loc)){
	 #if((string)$location->_id=='5759be55e7a1b625568b4567'){
      $boundarydata = array('loc'=>array("type"=>"Polygon",'coordinates'=>array($bcArr)));
		$condition = array('_id' => new \MongoId($location->_id));
	   $this->app_model->update_details(LOCATIONS,$boundarydata,$condition);
	  #}
     #}
	}		
			
  }
    Public function make_coordinates($latpoint,$lngpoint,$t='',$oldcoordinatesArr,$map_radius=10){
      
      for($i=1;$i<=16;$i++){
         $coordinatesArr[] =$this->get_gps_distance($latpoint,$lngpoint,$map_radius,22.5*$i,$t);
      }
      if(!empty($oldcoordinatesArr)){
         $coordinatesArr = $oldcoordinatesArr;
      }
		$coordinatesArr[15] = $coordinatesArr[0];
       return $coordinatesArr;
   }

    Public function get_gps_distance($lat1,$long1,$d,$angle,$type=''){
          # Earth Radious in KM
          $R = 6378.14;

          # Degree to Radian
          $latitude1 = $lat1 * (M_PI/180);
          $longitude1 = $long1 * (M_PI/180);
          $brng = $angle * (M_PI/180);

          $latitude2 = asin(sin($latitude1)*cos($d/$R) + cos($latitude1)*sin($d/$R)*cos($brng));
          $longitude2 = $longitude1 + atan2(sin($brng)*sin($d/$R)*cos($latitude1),cos($d/$R)-sin($latitude1)*sin($latitude2));

          # back to degrees
          $latitude2 = $latitude2 * (180/M_PI);
          $longitude2 = $longitude2 * (180/M_PI);

          # 6 decimal for Leaflet and other system compatibility
         $lat2 = round ($latitude2,6);
         $long2 = round ($longitude2,6);

         // Push in array and get back
         $tab[0] = $lat2;
         $tab[1] = $long2;
         if($type==''){
         return $tab;
         }else{
         return @implode('|',$tab);
         }
      }
	  
	  
	  /*	 jasdikjhknjasd hnjbamns bdfmnb asd njba bsm,nd mansd	*/
	public function get_categorylist(){
		$service_id = $this->user_model->get_selected_fields(RIDES,array(),array('booking_information.service_id','booking_information.service_type'));
	    $property_types = array();
		if($service_id->num_rows()>0){
            foreach($service_id->result() as $filter_result){
                if ( in_array($filter_result->booking_information, $property_types) ) {
                continue;
                }
                $property_types[] = $filter_result->booking_information;
                echo "<pre>";print_r($filter_result->booking_information);
            }
		}
		$new_category = $this->user_model->get_selected_fields(CATEGORY,array(),array('_id','name'));
		if($new_category->num_rows()>0){
            echo "<pre>";print_r($new_category->result());die;
		}
	}

	public function update_category(){
		$new_category = '57ab1afce7a1b69b308b4567'; 	  //   mini
		$old_category = '55a7b349cae2aa0408000029'; 	  //   mini
		
		
		/* $new_category = '57ab1a9de7a1b60a298b4567';  		// prime
		$old_category = '55c47f9bcae2aa4408000029';    		// prime   */
		$this->user_model->update_details(DRIVERS,array('category' => new \MongoId($new_category)),array('category' =>   new \MongoId($old_category)));
		$tr = $this->user_model->get_all_details(DRIVERS,array('_id' => new \MongoId("562a12b4e7a1b6b3328b4568")));
		echo "<pre>"; print_r($tr->result()); die;
	}
	
	public function get_last_active_time(){
		#57c7f03ce7a1b6867b8b4568
		$driver_id = $this->uri->segment(3);
		$deriver_details = $this->user_model->get_all_details(DRIVERS, array('_id'=>new \MongoId($driver_id)));
		#echo "<pre>"; print_r($deriver_details->result());
		echo date("Y-m-d H:i:s",$deriver_details->row()->last_active_time->sec);
		echo "<pre>";
		print_r($deriver_details->row()->loc);
	}
	public function update_location(){
		$location_list = $this->user_model->get_all_details(LOCATIONS);
		
		//MINI
		/* $newCatgoryId = '57ab1afce7a1b69b308b4567';  //   mini
		$oldCatgoryId = '55a7b349cae2aa0408000029';	  //   mini */
		
		
		$newCatgoryId = '57ab1a9de7a1b60a298b4567';  //   prime
		$oldCatgoryId = '55c47f9bcae2aa4408000029';  //   prime	
		foreach($location_list->result() as $location){	
			$updateCatCond = array('_id' => new MongoId($location->_id));
			
			$newAvailCat = $location->avail_category;
			for($i=0;$i < count($newAvailCat);$i++){
				if($newAvailCat[$i]== $oldCatgoryId){
					$newAvailCat[$i] = $newCatgoryId;
				}
			}
			$fareArr = $location->fare;
			foreach($location->fare as $catKey => $catVal){
			  if($catKey == $oldCatgoryId){
				$fareArr[$newCatgoryId] = $catVal;
				unset($fareArr[$oldCatgoryId]);
			  }
			} 
			echo '<pre>'; print_r($newAvailCat);
			echo '<pre>'; print_r($fareArr); 
 			$this->user_model->update_details(LOCATIONS,array('avail_category' => $newAvailCat,'fare' => $fareArr),$updateCatCond);
		}
	}
	
			public function update_user_loc(){		

			  /* $uesr_id='58188717cae2aab81c000029';
				//$checkGeo = $this->user_model->get_all_details(USER_LOCATION, array());
				$val = array('push_type'=>'IOS');
				$this->user_model->update_details(USERS, $val,array('_id' => new \MongoId(($uesr_id))));
				echo "1 update";	
	  
				$uesr_id='58188717cae2aab81c000029';
				//$checkGeo = $this->user_model->get_all_details(USER_LOCATION, array());
				$val = array('loc' => array('lon' => floatval(80.270718), 'lat' => floatval(13.082680)),'last_active_time'=>new \MongoDate(time()));
				$this->user_model->update_details(USERS, $val,array('_id' => new \MongoId(($uesr_id))));
				echo "2 update";   */
    }
	
	
	
public function v_driver(){
	$driver_id=$this->uri->segment(3);
	$getInfo = $this->user_model->get_all_details(DRIVERS, array('_id' => new \MongoId(($driver_id))));
	if($getInfo->num_rows()>0){
		echo "<pre>"; print_r($getInfo->result());
	}else{
		echo "Invalid";
	}
}

public function t_loc(){ 
	$from = '13.057269,80.253192';
	$to =  '13.054501,80.211422';

	$gmap = file_get_contents('https://maps.googleapis.com/maps/api/directions/json?origin=' . $from . '&destination=' . $to . '&alternatives=true&sensor=false&mode=driving'.$this->data['google_maps_api_key']);
	$map_values = json_decode($gmap);
	$routes = $map_values->routes;
	echo '<pre>'; print_r($routes ); die;
}
	
	
	

	
}

/* End of file demo.php */
/* Location: ./application/controllers/demo.php */