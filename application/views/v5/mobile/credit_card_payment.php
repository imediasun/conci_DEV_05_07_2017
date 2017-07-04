<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title><?php echo $this->config->item('email_title'); ?> - <?php if ($this->lang->line('stripe_payment_card') != '') echo stripslashes($this->lang->line('stripe_payment_card')); else echo 'Payment Credit Card'; ?></title>
		<link rel="stylesheet" href="<?php echo base_url(); ?>css/mobile/app-style.css" type="text/css" media="all" />
	</head>
	<body>
		<section>
			<div class="shipping_address">
				<div class="main">		
					<div class="app-content-box">
						<h1><?php if ($this->lang->line('stripe_payment_enter_card_information') != '') echo stripslashes($this->lang->line('stripe_payment_enter_card_information')); else echo 'Enter Credit Card Informations'; ?><a class="close_btn" href="<?php echo base_url(); ?>v5/mobile/payment/Cancel?mobileId=<?php echo $mobileId; ?>"></a></h1>
						<form name="PaymentCard" id="PaymentCard" method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>v5/mobile/userPaymentCard">
						<ul>
							<li>
								<select id="cardType" name="cardType" class="input-scroll-4">
									<option value=""><?php if ($this->lang->line('credit_card_select_card_type') != '') echo stripslashes($this->lang->line('credit_card_select_card_type')); else echo 'Select Card Type'; ?></option>
									<option value="american_express">American Express</option>
									<option value="visa">Visa</option>
									<option value="master_card">Master Card</option>
									<option value="discover">Discover</option>
								</select>
							</li>
							
							<li><input type="text" class="input-scroll-3" placeholder="<?php if ($this->lang->line('stripe_enter_card_number') != '') echo stripslashes($this->lang->line('stripe_enter_card_number')); else echo 'Card number'; ?>" id="cardNumber" name="cardNumber" maxlength="16" size="16"></input></li>
							
							<li><label><?php if ($this->lang->line('stripe_card_expiration') != '') echo stripslashes($this->lang->line('stripe_card_expiration')); else echo 'Expiration'; ?></label> 
								<?php $Sel ='selected="selected"';  ?>
								<select id="CCExpDay" name="CCExpDay" class="input-scroll-2">
								<option value="01" <?php if(date('m')=='01'){ echo $Sel;} ?>>01</option>
								<option value="02" <?php if(date('m')=='02'){ echo $Sel;} ?>>02</option>
								<option value="03" <?php if(date('m')=='03'){ echo $Sel;} ?>>03</option>
								<option value="04" <?php if(date('m')=='04'){ echo $Sel;} ?>>04</option>
								<option value="05" <?php if(date('m')=='05'){ echo $Sel;} ?>>05</option>
								<option value="06" <?php if(date('m')=='06'){ echo $Sel;} ?>>06</option>
								<option value="07" <?php if(date('m')=='07'){ echo $Sel;} ?>>07</option>
								<option value="08" <?php if(date('m')=='08'){ echo $Sel;} ?>>08</option>
								<option value="09" <?php if(date('m')=='09'){ echo $Sel;} ?>>09</option>
								<option value="10" <?php if(date('m')=='10'){ echo $Sel;} ?>>10</option>
								<option value="11" <?php if(date('m')=='11'){ echo $Sel;} ?>>11</option>
								<option value="12" <?php if(date('m')=='12'){ echo $Sel;} ?>>12</option>
								</select>
								<select id="CCExpMnth" name="CCExpMnth" class="input-scroll-2"> 
									<?php for($i=date('Y');$i< (date('Y') + 30);$i++){ ?>
									<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
									<?php } ?>
								</select>
							</li>
							
							<li><input type="password" class="input-scroll" placeholder="<?php if ($this->lang->line('stripe_security_code') != '') echo stripslashes($this->lang->line('stripe_security_code')); else echo 'Security Code'; ?>" id="creditCardIdentifier" name="creditCardIdentifier"></input></li>
							
							<input type="hidden" class="input-scroll" value="<?php echo $mobileId; ?>" name="mobileId" id="mobileId"></input>
							
							<li class="last"><input type="submit" id="submit_btn" class="input-submit-btn" value="<?php if ($this->lang->line('credit_card_pay') != '') echo stripslashes($this->lang->line('credit_card_pay')); else echo 'Pay'; ?>" onClick="return validatecard();"></input></li>
							
							<li class="last"><p id="loading" class="input-loading" style="display:none;"><?php if ($this->lang->line('stripe_page_redirect_automatically') != '') echo stripslashes($this->lang->line('stripe_page_redirect_automatically')); else echo 'This page redirect automatically, please wait'; ?>...</p></li>
						</ul>
						</form>
					</div>
				</div>	
			</div>
		</section>
		<script type="text/javascript">
		function validatecard(){
			var cardNumber=document.getElementById("cardNumber").value.trim();
			var CCExpDay=document.getElementById("CCExpDay").value.trim();
			var CCExpMnth=document.getElementById("CCExpMnth").value.trim();
			var creditCardIdentifier=document.getElementById("creditCardIdentifier").value.trim();
			var cardType=document.getElementById("cardType").value.trim();
			
			document.getElementById("cardNumber").classList.remove("txt-error");
			document.getElementById("CCExpDay").classList.remove("txt-error");
			document.getElementById("CCExpMnth").classList.remove("txt-error");
			document.getElementById("creditCardIdentifier").classList.remove("txt-error");
			document.getElementById("cardType").classList.remove("txt-error");
			
			var status=0;
			if(cardNumber=="" || isNaN(cardNumber)){
				document.getElementById("cardNumber").classList.add("txt-error");
				status++;
			}
			if(CCExpDay==""){
				document.getElementById("CCExpDay").classList.add("txt-error");
				status++;
			}
			if(CCExpMnth==""){
				document.getElementById("CCExpMnth").classList.add("txt-error");
				status++;
			}
			if(creditCardIdentifier==""){
				document.getElementById("creditCardIdentifier").classList.add("txt-error");
				status++;
			}
			if(cardType==""){
				document.getElementById("cardType").classList.add("txt-error");
				status++;
			}
			if(status!=0){
				return false;
			}else{
				document.getElementById("submit_btn").style.display = 'none';
				document.getElementById("loading").style.display = 'block';
			}
		}
		</script>
	</body>
</html>
