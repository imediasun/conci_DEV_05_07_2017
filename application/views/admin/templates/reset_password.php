<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta name="viewport" content="width=device-width"/>
<base href="<?php echo base_url(); ?>">
<title><?php echo $title;?></title>
<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url();?>images/logo/<?php echo $favicon;?>">
<link href="css/reset.css" rel="stylesheet" type="text/css">
<link href="css/typography.css" rel="stylesheet" type="text/css">
<link href="css/styles.css" rel="stylesheet" type="text/css">
<link href="css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="css/jquery-ui-1.8.18.custom.css" rel="stylesheet" type="text/css">
<link href="css/gradient.css" rel="stylesheet" type="text/css">
<link href="css/developer.css" rel="stylesheet" type="text/css">
<link href="css/style-responsive.css" rel="stylesheet" type="text/css">
<link href="css/developer_colors.css" rel="stylesheet" type="text/css">

<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="css/ie/ie7.css" />
<![endif]-->
<!--[if IE 8]>
<link rel="stylesheet" type="text/css" href="css/ie/ie8.css" />
<![endif]-->
<!--[if IE 9]>
<link rel="stylesheet" type="text/css" href="css/ie/ie9.css" />
<![endif]-->
<!-- Jquery -->
<script src="js/jquery-1.7.1.min.js"></script>
<script src="js/jquery-ui-1.8.18.custom.min.js"></script>

<script src="js/chosen.jquery.js"></script>
<script src="js/uniform.jquery.js"></script>
<script src="js/jquery.tagsinput.js"></script>
<script src="js/jquery.cleditor.js"></script>
<script src="js/jquery.jBreadCrumb.1.1.js"></script>
<script src="js/accordion.jquery.js"></script>
<script src="js/autogrow.jquery.js"></script>
<script src="js/duallist.jquery.js"></script>
<script src="js/input-limiter.jquery.js"></script>
<script src="js/inputmask.jquery.js"></script>
<script src="js/iphone-style-checkbox.jquery.js"></script>
<script src="js/raty.jquery.js"></script>
<script src="js/stepy.jquery.js"></script>
<script src="js/vaidation.jquery.js"></script>
<script src="js/jquery.collapse.js"></script>

<script src="js/bootstrap-dropdown.js"></script>
<script src="js/bootstrap-colorpicker.js"></script>
<script src="js/jquery.tipsy.js"></script>
<script type="text/javascript">

function hideErrDiv(arg) {
    document.getElementById(arg).style.display = 'none';
}
</script>
</head>
<body id="theme-default" class="full_block">
<div id="login_page">
	<div class="login_container">
		<div class="login_header blue_lgel">
			<ul class="login_branding">
				<li>
				<div class="logo_small">
                	<h1>
					<img src="images/logo/<?php echo $this->config->item('logo_image'); ?>" width="" alt="<?php echo $siteTitle;?>" title="<?php echo $siteTitle;?>">
                    </h1>
				</div>
				<span></span>
				</li>
				<li class="right go_to"><a href="<?php echo base_url();?>" title="Go to Main Site" class="home"><?php if ($this->lang->line('admin_login_main_site') != '') echo stripslashes($this->lang->line('admin_login_main_site')); else echo 'Go To Main Site'; ?></a></li>
			</ul>
		</div>
		<?php if (validation_errors() != ''){?>
		<div id="validationErr">
			<script>setTimeout("hideErrDiv('validationErr')", 3000);</script>
			<p><?php echo validation_errors();?></p>
		</div>
		<?php }?>

		 <script src="js/jquery.growl.js" type="text/javascript"></script>
		<link href="css/jquery.growl.css" rel="stylesheet" type="text/css" />
		<?php if($this->session->flashdata('sErrMSG') != '') { ?>
		<script type="text/javascript">
		  <?php 
		  $sErrMSGdecoded = base64_decode($this->session->flashdata('sErrMSG'));
		  if($this->session->flashdata('sErrMSGType')=='message-red'){ ?>
		  $.growl.error({ title:"Error",message: "<?php echo  $sErrMSGdecoded;  ?>" });
		  <?php } ?>
		  <?php if($this->session->flashdata('sErrMSGType')=='message-green'){ ?>
		  $.growl.notice({ title:"Success",message: "<?php echo  $sErrMSGdecoded;  ?>"});
		  <?php } ?>
		  <?php if($this->session->flashdata('sErrMSGType')=='warning'){ ?>
		  $.growl.warning({ message: "<?php echo  $sErrMSGdecoded;  ?>" });
		  <?php } ?>
		</script>
		<?php } ?>
		
		<div class="col-md-12 login-color">
		<?php echo form_open('admin/adminlogin/reset_password');  ?>
			<div class="login_form">
				<input type="hidden" name="type" value="<?php echo $admin_type; ?>" />
				<input type="hidden" name="reset_id" value="<?php echo $reset_id; ?>" />
				<ul>
					<li class="login_user tipBot" title="Please enter your new password">
						<input name="new_password" type="password" placeholder="New Password" >
					</li>
					<li class="login_pass tipTop" title="Please confirm your password">
						<input name="confirm_password" type="password" placeholder="Confirm Password">
					</li>
				</ul>
			</div><div class="clear"></div>
			<input class="login_btn blue_lgel" name="" value="Reset" type="submit">
		</form>
		</div>
        
	</div>
</div>
</body>
</html>