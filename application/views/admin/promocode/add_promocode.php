<?php
$this->load->view('admin/templates/header.php');
?>
<div id="content">
	<div class="grid_container">
		<div class="grid_12">
			<div class="widget_wrap">
				<div class="widget_top">
					<span class="h_icon list"></span>
					<h6><?php echo $heading; ?></h6>
				</div>
				<div class="widget_content">
					<?php 
						$attributes = array('class' => 'form_container left_label', 'id' => 'addpromo_form');
						echo form_open('admin/promocode/insertEditPromoCode',$attributes) 
					?>
	 					<ul>
                            <li>
								<div class="form_grid_12">
									<label class="field_title" for="user_name"><?php if ($this->lang->line('admin_promocode_coupon_code') != '') echo stripslashes($this->lang->line('admin_promocode_coupon_code')); else echo 'Coupon code'; ?> <span class="req">*</span></label>
									<div class="form_input">
										<input name="promo_code" id="promo_code" type="text" tabindex="0" class="required large tipTop" title="<?php if ($this->lang->line('admin_promocode_enter_coupon_code') != '') echo stripslashes($this->lang->line('admin_promocode_enter_coupon_code')); else echo 'Please Enter the Coupon Code'; ?>" value="<?php echo $code; ?>" />
									</div>
								</div>
							</li>
                                
							<li>
								<div class="form_grid_12">
									<label class="field_title" for="usage_allowed"><?php if ($this->lang->line('admin_promocode_usage_limit_per_coupon') != '') echo stripslashes($this->lang->line('admin_promocode_usage_limit_per_coupon')); else echo 'Usage limit per coupon'; ?> <span class="req">*</span></label>
									<div class="form_input">
										<input name="usage_allowed" id="usage_allowed" type="text" tabindex="1" class="required number positiveNumber minfloatingNumber large tipTop" title="<?php if ($this->lang->line('admin_promocode_enter_coupon_usage_limit') != '') echo stripslashes($this->lang->line('admin_promocode_enter_coupon_usage_limit')); else echo 'Please enter the coupon usage limit'; ?>"/>
									</div>
								</div>
							</li>
							
							<li>
								<div class="form_grid_12">
									<label class="field_title" for="user_usage"><?php if ($this->lang->line('admin_promocode_usage_limit_per_user') != '') echo stripslashes($this->lang->line('admin_promocode_usage_limit_per_user')); else echo 'Usage limit per user'; ?> <span class="req">*</span></label>
									<div class="form_input">
										<input name="user_usage" id="user_usage" type="text" tabindex="5" class="required number positiveNumber minfloatingNumber large tipTop" title="<?php if ($this->lang->line('admin_promocode_enter_coupon_usage_limit_per_user') != '') echo stripslashes($this->lang->line('admin_promocode_enter_coupon_usage_limit_per_user')); else echo 'Please enter the coupon usage limit per user'; ?>"/>
									</div>
								</div>
							</li>
                                
                            <li>
								<div class="form_grid_12">
									<label class="field_title" for="datefrom"><?php if ($this->lang->line('admin_promocode_coupon_valid_from') != '') echo stripslashes($this->lang->line('admin_promocode_coupon_valid_from')); else echo 'Coupon Valid From'; ?><span class="req">*</span></label>
									<div class="form_input">
										<input name="validity[valid_from]" readonly="readonly" id="datefrom" type="text" tabindex="2" class="required large tipTop datepicker" title="<?php if ($this->lang->line('admin_promocode_select_the_code') != '') echo stripslashes($this->lang->line('admin_promocode_select_the_code')); else echo 'Please select the date'; ?>"/>
									</div>
								</div>
							</li>
							
							<li>
								<div class="form_grid_12">
									<label class="field_title" for="dateto"><?php if ($this->lang->line('admin_promocode_coupon_valid_till') != '') echo stripslashes($this->lang->line('admin_promocode_coupon_valid_till')); else echo 'Coupon Valid Till'; ?><span class="req">*</span></label>
									<div class="form_input">
										<input name="validity[valid_to]" readonly="readonly" id="dateto" type="text" tabindex="3" class="required large tipTop datepicker" title="<?php if ($this->lang->line('admin_promocode_select_the_code') != '') echo stripslashes($this->lang->line('admin_promocode_select_the_code')); else echo 'Please select the date'; ?>"/>
									</div>
								</div>
							</li>
	 						
							<li>
									<div class="form_grid_12">
										<label class="field_title" for="price_type"><?php if ($this->lang->line('admin_promocode_coupon_type') != '') echo stripslashes($this->lang->line('admin_promocode_coupon_type')); else echo 'Coupon Type'; ?> <span class="req">*</span></label>
										<div class="form_input">
											<div class="flat_percentage">
												<input type="checkbox" name="price_type" checked="checked" id="flat_percentage_flat" class="flat_percentage"/>
											</div>
										</div>
									</div>
							</li>
							
							<li>
								<div class="form_grid_12">
									<label class="field_title" for="promo_value"><?php if ($this->lang->line('admin_promocode_coupon_amount') != '') echo stripslashes($this->lang->line('admin_promocode_coupon_amount')); else echo 'Coupon Amount'; ?> <span class="req">*</span></label>
									<div class="form_input">
										<input name="promo_value" id="promo_value" type="text" tabindex="5" class="required number positiveNumber minfloatingNumber large tipTop" title="<?php if ($this->lang->line('admin_promocode_enter_coupon_code') != '') echo stripslashes($this->lang->line('admin_promocode_enter_coupon_code')); else echo 'Please enter the coupon amount'; ?>"/>
									</div>
								</div>
							</li>
	 						
							<li>
									<div class="form_grid_12">
										<label class="field_title" for="status"><?php if ($this->lang->line('admin_common_status') != '') echo stripslashes($this->lang->line('admin_common_status')); else echo 'Status'; ?> <span class="req">*</span></label>
										<div class="form_input">
											<div class="active_inactive">
												<input type="checkbox" name="status" checked="checked" id="active_inactive_active" class="active_inactive"/>
											</div>
										</div>
									</div>
							</li>
							
							<li>
								<div class="form_grid_12">
									<div class="form_input">
										<button type="submit" class="btn_small btn_blue" tabindex="7"><span><?php if ($this->lang->line('admin_common_submit') != '') echo stripslashes($this->lang->line('admin_common_submit')); else echo 'Submit'; ?></span></button>
									</div>
								</div>
							</li>  
							
						</ul>
					</form>
				</div>
			</div>
		</div>
	</div>
	<span class="clear"></span>
</div>
<?php 
$this->load->view('admin/templates/footer.php');
?>