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
						$attributes = array('class' => 'form_container left_label', 'id' => 'addeditcurrency_form');
						echo form_open('admin/reviews/insertEditReviews_options',$attributes) 
					?> 		
	 						<ul>								
                                <li>
									<div class="form_grid_12">
										<label class="field_title" for="name"><?php if ($this->lang->line('admin_review_review_option_for') != '') echo stripslashes($this->lang->line('admin_review_review_option_for')); else echo 'Dashboard'; ?>? <span class="req">*</span></label>
										<div class="form_input">
										<select name="option_holder" id="option_holder" class="required" style="width: 51%;">
											<option value=""><?php if ($this->lang->line('admin_review_choose_the_option_for') != '') echo stripslashes($this->lang->line('admin_review_choose_the_option_for')); else echo 'Choose the option for ?'; ?></option>
											<option value="driver"><?php if ($this->lang->line('admin_common_driver') != '') echo stripslashes($this->lang->line('admin_common_driver')); else echo 'Driver'; ?></option>
											<option value="rider"><?php if ($this->lang->line('admin_common_rider') != '') echo stripslashes($this->lang->line('admin_common_rider')); else echo 'Rider'; ?></option>
										</select>
										</div>
									</div>
								</li>							
                                
								<li>
									<div class="form_grid_12">
										<label class="field_title" for="option_name"><?php if ($this->lang->line('admin_review_review_option_name') != '') echo stripslashes($this->lang->line('admin_review_review_option_name')); else echo 'Review Option Name'; ?><span class="req">*</span></label>
										<div class="form_input">
										<input name="option_name" id="option_name" type="text" tabindex="2" class="large required tipTop" title="<?php if ($this->lang->line('admin_review_enter_review_option_name') != '') echo stripslashes($this->lang->line('admin_review_enter_review_option_name')); else echo 'Please enter review option name'; ?>" />
										</div>
									</div>
								</li>			

								<li>
									<div class="form_grid_12">
										<label class="field_title" for="status"><?php if ($this->lang->line('admin_common_status') != '') echo stripslashes($this->lang->line('admin_common_status')); else echo 'Status'; ?> <span class="req">*</span></label>
										<div class="form_input">
											<div class="active_inactive">
												<input type="checkbox" tabindex="5" checked="checked" name="status" id="active_inactive_active" class="active_inactive" />
											</div>
										</div>
									</div>
								</li>
								
								<input type="hidden" name="option_id" value=""/>
								<li>
									<div class="form_grid_12">
										<div class="form_input">
											<button type="submit" class="btn_small btn_blue" tabindex="4"><span><?php if ($this->lang->line('admin_common_submit') != '') echo stripslashes($this->lang->line('admin_common_submit')); else echo 'Submit'; ?></span></button>
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