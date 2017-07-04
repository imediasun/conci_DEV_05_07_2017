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
						$attributes = array('class' => 'form_container left_label', 'id' => 'regitstraion_form', 'enctype' => 'multipart/form-data');
						echo form_open_multipart('admin/drivers/change_password',$attributes);
						
						$driver_details = $driver_details->row();  
					?>
	 						<ul>
	 							<li>
								<div class="form_grid_12">
									<label class="field_title" for="driver_email"><?php if ($this->lang->line('admin_drivers_change_password_driver_email') != '') echo stripslashes($this->lang->line('admin_drivers_change_password_driver_email')); else echo 'Driver Email'; ?> <span class="req">*</span></label>
									<div class="form_input">
										<input name="driver_email" id="driver_email" type="text" tabindex="1" class="required large tipTop"  value="<?php if(isset($driver_details->email)) echo $driver_details->email; ?>" disabled="disabled" />
									</div>
								</div>
								</li>
								
								<li>
								<div class="form_grid_12">
									<label class="field_title" for="newpassword"><?php if ($this->lang->line('admin_drivers_new_password') != '') echo stripslashes($this->lang->line('admin_drivers_new_password')); else echo 'New Password'; ?> <span class="req">*</span></label>
									<div class="form_input">
										<input name="new_password" id="new_password" type="password" tabindex="2" class="required large tipTop" title="<?php if ($this->lang->line('dash_enter_new_password') != '') echo stripslashes($this->lang->line('dash_enter_new_password')); else echo 'Please enter a new password'; ?>"/>
									</div>
								</div>
								</li>
								<li>
								<div class="form_grid_12">
									<label class="field_title" for="confirm_password"><?php if ($this->lang->line('admin_drivers_new_password') != '') echo stripslashes($this->lang->line('admin_drivers_new_password')); else echo 'Retype Password'; ?> <span class="req">*</span></label>
									<div class="form_input">
										<input name="confirm_password" id="confirm_password" type="password" tabindex="2" class="required large tipTop" title="<?php if ($this->lang->line('dash_reenter_password_again') != '') echo stripslashes($this->lang->line('dash_reenter_password_again')); else echo 'Please enter a new password'; ?>"/>
									</div>
								</div>
								</li>
								<input name="driver_id" type="hidden" value="<?php echo $driver_details->_id; ?>" />
								<li>
								<div class="form_grid_12">
									<div class="form_input">
										<button type="submit" class="btn_small btn_blue" tabindex="9"><span><?php if ($this->lang->line('admin_common_submit') != '') echo stripslashes($this->lang->line('admin_common_submit')); else echo 'Submit'; ?></span></button>
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
</div>


<?php 
$this->load->view('admin/templates/footer.php');
?>