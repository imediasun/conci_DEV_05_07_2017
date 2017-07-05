<?php
error_reporting(0);
require 'src/facebook.php';
include('../commonsettings/dectar_admin_settings.php');


$basepathurl = $config['base_url'];
$callback_website_url=$config['base_url'].'fb-redirect';
$app_id = $config['facebook_app_id'];;
$app_secret = $config['facebook_app_secret'];;

$my_url = $basepathurl.'facebook/user.php'; 
session_start(); 

	if(isset($_REQUEST["code"])){
		$code = $_REQUEST["code"];
	}else{
		$code = 0;
	}

  
	if(empty($code)) {
		$_SESSION['state'] = md5(uniqid(rand(), TRUE)); // CSRF protection
		$dialog_url = "https://www.facebook.com/dialog/oauth?client_id=".$app_id . "&redirect_uri=" . urlencode($my_url) . "&state=". $_SESSION['state']. "&scope=email,user_birthday,read_stream";

		echo("<script> top.location.href='" . $dialog_url . "'</script>");
	}
   
	if($_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state'])) {
		$token_url = "https://graph.facebook.com/oauth/access_token?"
		   . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
		   . "&client_secret=" . $app_secret . "&code=" . $code;
		   
		$URL = $token_url;
		$curl_handle=curl_init();
		curl_setopt($curl_handle,CURLOPT_URL,$URL);
		curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
		curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
		$pageContent = curl_exec($curl_handle);
		curl_close($curl_handle);
		$response = $pageContent;

		$params = null;
		 #parse_str($response, $params);
		$params = json_decode($response);
		$fb_access_token = $params->access_token;
		$_SESSION['access_token'] = $fb_access_token;

		$graph_url = "https://graph.facebook.com/me?access_token=" . $fb_access_token."&fields=id,email,first_name,last_name,gender,picture";
		$FBlogout='https://www.facebook.com/logout.php?next='.$basepathurl.'logout%3Fsecret%3D&access_token='.$fb_access_token;
		$URL1 = $graph_url;
		$curl_handle=curl_init();
		curl_setopt($curl_handle,CURLOPT_URL,$URL1);
		curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
		curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
		$pageContent1 = curl_exec($curl_handle);
		curl_close($curl_handle);

		$user = json_decode($pageContent1);
		if(!empty($user) && !isset($user->error)) {
			$username = @explode('@',$user->email);
			$image_data = file_get_contents('https://graph.facebook.com/'.$user->id.'/picture?type=large');
			
			$CURL_URL=$basepathurl."upload-fb-profile-pic";
			
			$data=array('image_data'=>base64_encode($image_data),'user_id'=>$user->id);
			$ch=curl_init();
			curl_setopt($ch,CURLOPT_URL,$CURL_URL);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
			$OUPTUT=curl_exec($ch);
			curl_close($ch);
			
			 $_SESSION['email']=$user->email;
			 $_SESSION['first_name']=$user->first_name;		 
			 $_SESSION['last_name']=$user->last_name;
			 $_SESSION['user_name']=$username[0];
			 $_SESSION['user_image']=$user->id.'.jpg';
			 $_SESSION['fb_user_id']=$user->id; 
			 $_SESSION['FBlogout'] = $FBlogout;
			header('Location: '.$callback_website_url);
		} else {
			$error = $user->error->message;
			header('Location: '.$callback_website_url.'?flashErr='.$error);
		}
	}
?>