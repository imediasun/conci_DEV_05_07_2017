<?php
$this->load->view('admin/templates/header.php');
?>
<div id="content">
		<div class="grid_container">
			<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list"></span>
						<h6>Add New Help Title</h6>
                        
					</div>
					<div class="widget_content">
					<?php 
						$attributes = array('class' => 'form_container left_label', 'id' => 'addattribute_form', 'enctype' => 'multipart/form-data');
						echo form_open_multipart('admin/cms/insertEditHelp',$attributes) 
					?>
                    
						<ul>	 							
							<li>
								<div class="form_grid_12">
								<label class="field_title" for="attribute_name"><?php if ($this->lang->line('admin_cms_title_name') != '') echo stripslashes($this->lang->line('admin_cms_title_name')); else echo 'Title Name'; ?> <span class="req">*</span></label>
								<div class="form_input">
									<input name="title" id="title" type="text" tabindex="1" class="required large tipTop" title="Please enter the title"/>
								</div>
								</div>
							</li>						
                        	
							<li>
								<div class="form_grid_12">
									<label class="field_title" for="admin_name"><?php if ($this->lang->line('admin_common_status') != '') echo stripslashes($this->lang->line('admin_common_status')); else echo 'Status'; ?> <span class="req">*</span></label>
									<div class="form_input">
										<div class="active_inactive">
											<input type="checkbox" tabindex="11" name="status" checked="checked" id="active_inactive_active" class="active_inactive"/>
										</div>
									</div>
								</div>
							</li>
							
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