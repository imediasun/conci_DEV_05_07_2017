<?php 
error_reporting(0);
ob_start();
if(session_id() == '') {
	session_start();
}

require './req_check.php'; 

if($reqChk > 0){
require './req_failed.php';
} else {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title>INSTALLATION</title>
	
	<link rel="stylesheet" type="text/css" href="component/css/style.css" media="all" />
	
    <script type="text/javascript" src="component/js/jquery.min.js"></script>
    <script type="text/javascript" src="component/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="component/js/jquery.inputfocus-0.9.min.js"></script>
    <script type="text/javascript" src="component/js/jquery.main.js"></script>
</head>
<body>
	<div class="logo-img">
		<img src="dectar-logo.png" alt="Logo" />
	</div>
	<?php 
	if (isset($_SESSION['errorMSG']) && $_SESSION['errorMSG']!=''){
	?> 
	<div class="errorCon">
		<p><?php echo $_SESSION['errorMSG'];?></p>
		<script>setTimeout(hideErrDiv,5000);</script>
	</div>
	<?php 
		unset($_SESSION['errorMSG']);
	}
	?> 
	<div id="container">
        <form action="check_db_connect.php" method="post" autocomplete="off" id="installation">
            <!-- #first_step -->
            <div id="first_step">
				<h1><span>Admin Information & Login Credentials</span></h1>
                <div class="form">
					<input type="text" name="email" id="email" value="" placeholder="email address" />
                    <label for="email">Your email address.</label> 
					
                    <input type="text" name="username" id="username" value="" placeholder="username" />
                    <label for="username">At least 4 characters.</label>
                    
                    <input type="password" name="password" id="password" value="" placeholder="password" />
                    <label for="password">At least 4 characters.</label>
                    
                    <input type="password" name="cpassword" id="cpassword" value="" placeholder="confirm password" />
                    <label for="cpassword">If your passwords aren’t equal, you won’t be able to continue.</label>
                </div>      
				<!-- clearfix --><div class="clear"></div><!-- /clearfix -->
                <input class="submit" type="submit" name="submit_first" id="submit_first" value="" />
            </div>      
			<!-- clearfix --><div class="clear"></div><!-- /clearfix -->


            <!-- #second_step -->
            <div id="second_step">
                <h1><span>Site Name and Url</span></h1>

                <div class="form">
                    <input type="text" name="sitename" id="sitename" value="" placeholder="Website Name" />
                    <label for="sitename">Your Website Name. </label>
					
                    <input type="text" name="siteurl" id="siteurl" value="" placeholder="Website Url" />
                    <label for="siteurl">Your Website Url. </label>
					
                </div>      
				<!-- clearfix --><div class="clear"></div><!-- /clearfix -->
				<input class="submit" type="submit" name="submit_second" id="submit_second" value="" />
            </div>      
			<!-- clearfix --><div class="clear"></div><!-- /clearfix -->


            <!-- #third_step -->
            <div id="third_step">
                <h1><span>MongoDB Configuration</span></h1>

                <div class="form">
                    <input type="text" name="mongo_host" id="mongo_host" value="" placeholder="Host Name" />
                    <label for="mongo_host">MongoDB Host Name (default "localhost"). </label>
					
                    <input type="text" name="mongo_port" id="mongo_port" value="" placeholder="Port Number" />
                    <label for="mongo_port">MongoDB Port Number (default "27017"). </label>
					
                    <input type="text" name="mongo_user" id="mongo_user" value="" placeholder="User Name" />
                    <label for="mongo_user">MongoDB User Name. </label>
					
                    <input type="password" name="mongo_pass" id="mongo_pass" value="" placeholder="Password" />
                    <label for="mongo_pass">MongoDB Password. </label>
					
                    <input type="text" name="mongo_db" id="mongo_db" value="" placeholder="Database Name" />
                    <label for="mongo_db">MongoDB Database Name. </label>                    
                </div>      
				<!-- clearfix --><div class="clear"></div><!-- /clearfix -->
                <input class="submit" type="submit" name="submit_third" id="submit_third" value="" />
            </div>      
			<!-- clearfix --><div class="clear"></div><!-- /clearfix -->
            
            
            <!-- #fourth_step -->
            <div id="fourth_step">
                <div class="form">
                    <h2>Informations</h2>
                    <table>
                        <tr><td>Email</td><td></td></tr>
                        <tr><td>Username</td><td></td></tr>
                        <tr><td>Password</td><td></td></tr>
                        <tr><td>Site Name</td><td></td></tr>
                        <tr><td>Site Url</td><td></td></tr>
                        <tr><td>MongoDB Host Name</td><td></td></tr>
                        <tr><td>MongoDB Port Number</td><td></td></tr>
                        <tr><td>MongoDB User Name</td><td></td></tr>
                        <tr><td>MongoDB Password</td><td></td></tr>
                        <tr><td>MongoDB Database Name</td><td></td></tr>
                    </table>
                </div>      
				<!-- clearfix --><div class="clear"></div><!-- /clearfix -->
                <input class="send submit" type="submit" name="submit_fourth" id="submit_fourth" value="" />
            </div>
            
        </form>
	</div>
	<div id="progress_bar">
        <div id="progress"></div>
        <div id="progress_text">0% Complete</div>
	</div>
	
</body>
</html>
<?php } ?>
