<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title><?php echo $this->config->item('email_title'); ?> - <?php if ($this->lang->line('stripe_complete_payment') != '') echo stripslashes($this->lang->line('stripe_complete_payment')); else echo 'Complete Payment'; ?></title>
		<base href="<?php echo base_url(); ?>" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>css/mobile/app-style.css" type="text/css" media="all" />
		<script type="text/javascript" src="<?php echo base_url();?>js/site/jquery-1.10.2.js"></script>
	</head>
	<body>
		<section>
			<div class="shipping_address">
				<div class="main">
					<div class="app-content-box ">
					
						<h1><?php if ($this->lang->line('stripe_complete_booking') != '') echo stripslashes($this->lang->line('stripe_complete_booking')); else echo 'Complete Booking'; ?> <a class="close_btn" href="<?php echo base_url(); ?>v5/mobile/payment/Cancel?mobileId=<?php echo $mobileId; ?>"></a></h1>
						<div class="col-md-11 pay-instructions">
							<h2> <?php if ($this->lang->line('stripe_your_total_charge') != '') echo stripslashes($this->lang->line('stripe_your_total_charge')); else echo 'Your total charge is'; ?> <?php echo $this->config->item('currency_symbol').' '.number_format($total_amount,2); ?></h2>
							<span class="payNote"><b><?php if ($this->lang->line('stripe_NOTE') != '') echo stripslashes($this->lang->line('stripe_NOTE')); else echo 'NOTE'; ?> : </b> <?php if ($this->lang->line('stripe_card_information_saved') != '') echo stripslashes($this->lang->line('stripe_card_information_saved')); else echo 'Your card information will be saved in stripe secure gateway for your later and faster transaction'; ?>.</span>
							<form action="<?php echo base_url(); ?>v5/mobile/stripe-manual-payment-process" name="wallet_recharge_form" id="wallet_recharge_form" method="POST" onsubmit="return showLoader();">
								<?php 
								$tatalAmount  = ($total_amount * 100);
								$product_description = ucfirst($this->config->item('email_title')).' Booking Ride';
								$newImgpathtoStripe = base_url().'images/logo/'.$this->config->item('logo_image');
								$payment_btn_label = 'Pay By Card' ;
								?>
								
								<input type="hidden" value="<?php echo $mobileId;?>" name="mobileId" />
								<input type="hidden" value="<?php echo $ride_id;?>" name="transaction_id" />
								<input type="hidden" value="<?php echo $user_id;?>" name="user_id" />
								<input type="hidden" value="<?php echo $total_amount;?>" name="total_amount" />
								
								<input type="hidden" name="Stripeproduct_description" value="<?php echo $product_description; ?>"/>
								<script src="https://checkout.stripe.com/checkout.js" 
								class="stripe-button"
								data-key="<?php echo $stripe_settings['settings']['publishable_key']; ?>"
								data-amount="<?php echo $tatalAmount; ?>" 
								data-billing-address ="false"
								data-image="<?php echo $newImgpathtoStripe; ?>"
								data-name="<?php echo $this->config->item('email_title'); ?>"
								data-label = "<?php echo $payment_btn_label; ?>"
								data-description="<?php echo $product_description; ?>">
								</script> 
								<br/>
								<img src="images/loader.gif" style="display:none;" id="payLoader">
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>

<style>
.payment_radio{
	width: 3%;
}

label {
	cursor: pointer;
}

.pay-instructions {
    line-height: 2;
	padding: 3%;
	text-align:center;
}

.pay-instructions .payNote{
	color:#9f9f9f;
}
</style>
<script>
	function showLoader(){
		$('#payLoader').css('display','block'); 
	}
</script>
	
	
	</body>
</html>




			
