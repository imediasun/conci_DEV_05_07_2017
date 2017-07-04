<?php
$this->load->view('admin/templates/header.php');
?>
<div id="content">
	<div class="grid_container">
		<div class="grid_12">
			<div class="widget_wrap">
				<div class="widget_top">
					<span class="h_icon list"></span>
					<h6><?php echo $heading;?></h6>
				</div>
				<div class="widget_content">
					<?php 
						$attributes = array('class' => 'form_container left_label');
						echo form_open('admin',$attributes) 
					?>
						<ul>
							<li>
								<div class="form_grid_12">
									<label class="field_title"><?php if ($this->lang->line('admin_templates_template_name') != '') echo stripslashes($this->lang->line('admin_templates_template_name')); else echo 'Template Name'; ?></label>
									<div class="form_input">
										<?php echo $template_details->row()->message['title'];?>
									</div>
								</div>
							</li>
							
							<li>
								<div class="form_grid_12">
									<label class="field_title"><?php if ($this->lang->line('admin_templates_email_subject') != '') echo stripslashes($this->lang->line('admin_templates_email_subject')); else echo 'Email Subject'; ?> </label>
									<div class="form_input">
										<?php echo $template_details->row()->message['subject'];?>
									</div>
								</div>
							</li>
							
							<?php /* <li>
								<div class="form_grid_12">
									<label class="field_title"><?php if ($this->lang->line('admin_templates_sender_name') != '') echo stripslashes($this->lang->line('admin_templates_sender_name')); else echo 'Sender Name'; ?></label>
									<div class="form_input">
										<?php echo $template_details->row()->sender['name'];?>
									</div>
								</div>
							</li>
							
							<li>
								<div class="form_grid_12">
									<label class="field_title"><?php if ($this->lang->line('admin_templates_sender_email_address') != '') echo stripslashes($this->lang->line('admin_templates_sender_email_address')); else echo 'Sender Email Address'; ?> </label>
									<div class="form_input">
										<?php echo $template_details->row()->sender['email'];?>
									</div>
								</div>
							</li> */ ?>
							
							<li>
								<div class="form_grid_12">
									<label class="field_title"><?php if ($this->lang->line('admin_templates_description') != '') echo stripslashes($this->lang->line('admin_templates_description')); else echo 'Description'; ?></label>
									<div class="form_input">
										<?php echo $template_details->row()->message['description'];?>
									</div>
								</div>
							</li>
	 							
							<li>
								<div class="form_grid_12">
									<div class="form_input">
										<a href="admin/templates/display_email_template" class="tipLeft" title="<?php if ($this->lang->line('admin_newsletter_template_list') != '') echo stripslashes($this->lang->line('admin_newsletter_template_list')); else echo 'Go to email templates list'; ?>">
											<span class="badge_style b_done"><?php if ($this->lang->line('admin_common_back') != '') echo stripslashes($this->lang->line('admin_common_back')); else echo 'Back'; ?></span>
										</a>
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