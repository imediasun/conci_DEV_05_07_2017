<?php
$this->load->view('admin/templates/header.php');
?>
<div id="content">
		<div class="grid_container">
			<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list"></span>
						<h6><?php echo $gateway_details->row()->gateway_name;?></h6>
					</div>
					<div class="widget_content">
					<?php 
						$attributes = array('class' => 'form_container left_label', 'id' => 'commentForm');
						echo form_open('admin/payment_gateway/insertEditGateway',$attributes); 
						$gatewaySettings = $gateway_details->row()->settings;
						if (!is_array($gatewaySettings)){
							$gatewaySettings = array();
						}
						if (isset($gatewaySettings['mode'])){
					?>
	 						<ul>
	 							<li>
								<div class="form_grid_12">
									<label class="field_title" for="mode"><?php if ($this->lang->line('admin_payment_gateway_mode') != '') echo stripslashes($this->lang->line('admin_payment_gateway_mode')); else echo 'Mode'; ?> <span class="req">*</span></label>
									<div class="form_input">
										<div class="live_sandbox">
											<input type="checkbox" name="mode" <?php if ($gatewaySettings['mode'] == 'live'){echo 'checked="checked"';}?> id="live_sandbox" class="live_sandbox"/>
										</div>
									</div>
								</div>
								</li>
								<?php foreach ($gatewaySettings as $key => $val){
								if ($key != 'mode'){ 
									if($key == 'paypal_ipn_url'){ ?>
									<li>
									<div class="form_grid_12">
										<label class="field_title" for="<?php echo $key;?>"><?php echo ucwords(str_replace('_', ' ', $key));?> </label>
										<div class="form_input">
											<?php echo base_url().'site/order/ipnpaymet ';?>
										</div>
									</div>
									</li>		
								<?php }else{ ?>
									<li>
									<div class="form_grid_12">
										<label class="field_title" for="<?php echo $key;?>"><?php echo ucwords(str_replace('_', ' ', $key));?> <span class="req">*</span></label>
										<div class="form_input">
											<input name="<?php echo $key;?>" style=" width:295px" id="<?php echo $key;?>" value="<?php echo $val;?>" type="text" tabindex="1" class="required tipTop" title="<?php if ($this->lang->line('admin_payment_gateway_please_enter') != '') echo stripslashes($this->lang->line('admin_payment_gateway_please_enter')); else echo 'Please enter'; ?> <?php echo $key;?>"/>
										</div>
									</div>
									</li>
								<?php }}}?>
								<li>
								<div class="form_grid_12">
									<div class="form_input">
										<button type="submit" class="btn_small btn_blue" tabindex="4"><span><?php if ($this->lang->line('admin_common_update') != '') echo stripslashes($this->lang->line('admin_common_update')); else echo 'Update'; ?></span></button>
									</div>
								</div>
								</li>
							</ul>
							<?php }?>
							<input type="hidden" name="gateway_id" value="<?php echo $gateway_details->row()->_id;?>"/>
						</form>
					</div>
				</div>
			</div>
		</div>
		<span class="clear"></span>
	</div>
</div>
<?php 
$this->load->view('admin/templates/footer.php');
?>