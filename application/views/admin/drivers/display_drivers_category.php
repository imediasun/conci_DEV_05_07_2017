<?php
$this->load->view('admin/templates/header.php');
extract($privileges);
?>
<div id="content">
		<div class="grid_container">
			<?php 
				$attributes = array('id' => 'display_form');
				echo form_open('admin/drivers/change_category_status_global',$attributes) 
			?>
			<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6><?php echo $heading?></h6>
						<div style="float: right;line-height:40px;padding:0px 10px;height:39px;">
						<?php if ($allPrev == '1' || in_array('1', $drivers)){?>
                        
							<div class="btn_30_light" style="height: 29px; text-align:left;">
								<a href="admin/drivers/add_edit_category" class="tipTop" title="<?php if ($this->lang->line('driver_add_new_category') != '') echo stripslashes($this->lang->line('driver_add_new_category')); else echo 'Click here to Add New Category'; ?>"><span class="icon add_co"></span><span class="btn_link"><?php if ($this->lang->line('admin_car_add_new') != '') echo stripslashes($this->lang->line('admin_car_add_new')); else echo 'Add New'; ?></span></a>
							</div>
                            

						<?php } ?>
						<?php if ($allPrev == '1' || in_array('2', $drivers)){?>
							<div class="btn_30_light" style="height: 29px;">
								<a href="javascript:void(0)" onclick="return checkBoxValidationAdmin('Active','<?php echo $subAdminMail; ?>');" class="tipTop" title="<?php if ($this->lang->line('driver_select_publish_records') != '') echo stripslashes($this->lang->line('driver_select_publish_records')); else echo 'Select any checkbox and click here to publish records'; ?>"><span class="icon accept_co"></span><span class="btn_link"><?php if ($this->lang->line('admin_subadmin_active') != '') echo stripslashes($this->lang->line('admin_subadmin_active')); else echo 'Active'; ?></span></a>
							</div>
							<div class="btn_30_light" style="height: 29px;">
								<a href="javascript:void(0)" onclick="return checkBoxValidationAdmin('Inactive','<?php echo $subAdminMail; ?>');" class="tipTop" title="<?php if ($this->lang->line('driver_select_unpublish_records') != '') echo stripslashes($this->lang->line('driver_select_unpublish_records')); else echo 'Select any checkbox and click here to unpublish records'; ?>"><span class="icon delete_co"></span><span class="btn_link"><?php if ($this->lang->line('admin_subadmin_inactive') != '') echo stripslashes($this->lang->line('admin_subadmin_inactive')); else echo 'Inactive'; ?></span></a>
							</div>
						<?php 
						}
						if ($allPrev == '1' || in_array('3', $drivers)){
						?>
							<div class="btn_30_light" style="height: 29px;">
								<a href="javascript:void(0)" onclick="return checkBoxValidationAdmin('Delete','<?php echo $subAdminMail; ?>');" class="tipTop" title="<?php if ($this->lang->line('driver_select_delete_records') != '') echo stripslashes($this->lang->line('driver_select_delete_records')); else echo 'Select any checkbox and click here to delete records'; ?>"><span class="icon cross_co"></span><span class="btn_link"><?php if ($this->lang->line('admin_subadmin_delete') != '') echo stripslashes($this->lang->line('admin_subadmin_delete')); else echo 'Delete'; ?></span></a>
							</div>
						<?php }?>
						</div>
					</div>
					<div class="widget_content">
						<table class="display display_tbl" id="category_tbl">
							<thead>
								<tr>
									<th class="center">
										<input name="checkbox_id[]" type="checkbox" value="on" class="checkall">
									</th>
									<th class="tip_top" title="<?php if ($this->lang->line('dash_click_sort') != '') echo stripslashes($this->lang->line('dash_click_sort')); else echo 'Click to sort'; ?>">
										 <?php if ($this->lang->line('admin_car_types_name') != '') echo stripslashes($this->lang->line('admin_car_types_name')); else echo 'Name'; ?>
									</th>
									<th class="tip_top" title="<?php if ($this->lang->line('dash_click_sort') != '') echo stripslashes($this->lang->line('dash_click_sort')); else echo 'Click to sort'; ?>">
										<?php if ($this->lang->line('admin_car_types_image') != '') echo stripslashes($this->lang->line('admin_car_types_image')); else echo 'Image'; ?>
									</th>
									<?php /* <th class="tip_top" title="Click to sort">
										Default
									</th> */ ?>
									<th class="tip_top" title="<?php if ($this->lang->line('dash_click_sort') != '') echo stripslashes($this->lang->line('dash_click_sort')); else echo 'Click to sort'; ?>">
										<?php if ($this->lang->line('admin_subadmin_status') != '') echo stripslashes($this->lang->line('admin_subadmin_status')); else echo 'Status'; ?>
									</th>
									<th>
										 <?php if ($this->lang->line('admin_subadmin_action') != '') echo stripslashes($this->lang->line('admin_subadmin_action')); else echo 'Action'; ?>
									</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								if ($categoryList->num_rows() > 0){
									foreach ($categoryList->result() as $row){
								?>
								<tr>
									<td class="center tr_select ">
										<input name="checkbox_id[]" type="checkbox" value="<?php echo $row->_id;?>">
									</td>
									<td class="center">
										<?php echo $row->name;?>
									</td><td class="center">
										<?php 
										if(isset($row->image)){ 
											if($row->image!=""){ 
												$image=CATEGORY_IMAGE.$row->image;
											}else{
												$image=CATEGORY_IMAGE_DEFAULT;
											}
										}else{
											$image=CATEGORY_IMAGE_DEFAULT;
										}
										?>
										<img src="<?php echo base_url().$image; ?>" alt="<?php echo $image;?>" width="100px" />
									</td>
									<?php /*
									<td class="center">
									<?php 
									$mode = ($row->isdefault == 'Yes')?'0':'1';
									if ($mode == '0'){
									?><span class="badge_style b_done"><?php echo $row->isdefault;?></span>
									<?php
									}else {	
									?>
									<span class="badge_style"><?php echo $row->isdefault;?></span>
									<?php 
									}
									?>
									</td>
									*/ ?>
									<td class="center">
									<?php 
									$disp_status = get_language_value_for_keyword($row->status,$this->data['langCode']);
									if ($allPrev == '1' || in_array('2', $drivers)){
										$mode = ($row->status == 'Active')?'0':'1';
										if ($mode == '0'){
									?>
										<a title="<?php if ($this->lang->line('driver_click_to_unpublish') != '') echo stripslashes($this->lang->line('driver_click_to_unpublish')); else echo 'Click to unpublish'; ?>" class="tip_top" href="javascript:confirm_status('admin/drivers/change_category_status/<?php echo $mode;?>/<?php echo $row->_id;?>');"><span class="badge_style b_done"><?php echo $disp_status;?></span></a>
									<?php
										}else {	
									?>
										<a title="<?php if ($this->lang->line('driver_click_to_publish') != '') echo stripslashes($this->lang->line('driver_click_to_publish')); else echo 'Click to publish'; ?>" class="tip_top" href="javascript:confirm_status('admin/drivers/change_category_status/<?php echo $mode;?>/<?php echo $row->_id;?>')"><span class="badge_style"><?php echo $disp_status;?></span></a>
									<?php 
										}
									}else {
									?>
									<span class="badge_style b_done"><?php echo $disp_status;?></span>
									<?php }?>
									</td>
									<td class="center">
										<span><a class="action-icons c-suspend" href="admin/drivers/add_edit_category_types/<?php echo $row->_id;?>" title="<?php if ($this->lang->line('driver_view_vechicle_types') != '') echo stripslashes($this->lang->line('driver_view_vechicle_types')); else echo 'view vechicle types'; ?>"><?php if ($this->lang->line('admin_subadmin_view') != '') echo stripslashes($this->lang->line('admin_subadmin_view')); else echo 'View'; ?></a></span>
									<?php if ($allPrev == '1' || in_array('2', $drivers)){?>
										
										<?php /* <span><a class="action-icons c-pencil_basic_red" href="admin/drivers/edit_language_category/<?php echo $row->_id;?>" title="<?php if ($this->lang->line('admin_common_lang_edit') != '') echo stripslashes($this->lang->line('admin_common_lang_edit')); else echo 'Edit Language'; ?>"><?php if ($this->lang->line('admin_common_lang_edit') != '') echo stripslashes($this->lang->line('admin_common_lang_edit')); else echo 'Edit Language'; ?></a></span> */ ?>
										
										<span><a class="action-icons c-edit" href="admin/drivers/add_edit_category/<?php echo $row->_id;?>" title="<?php if ($this->lang->line('admin_common_edit') != '') echo stripslashes($this->lang->line('admin_common_edit')); else echo 'Edit'; ?>"><?php if ($this->lang->line('admin_subadmin_edit') != '') echo stripslashes($this->lang->line('admin_subadmin_edit')); else echo 'Edit'; ?></a></span>
									<?php }?>
									<?php if ($allPrev == '1' || in_array('3', $drivers)){?>	
										<span><a class="action-icons c-delete" href="javascript:confirm_delete('admin/drivers/delete_category/<?php echo $row->_id;?>')" title="<?php if ($this->lang->line('admin_common_delete') != '') echo stripslashes($this->lang->line('admin_common_delete')); else echo 'Delete'; ?>"><?php if ($this->lang->line('admin_subadmin_delete') != '') echo stripslashes($this->lang->line('admin_subadmin_delete')); else echo 'Delete'; ?></a></span>
									<?php }?>
									</td>
								</tr>
								<?php 
									}
								}
								?>
							</tbody>
							<tfoot>
								<tr>
									<th class="center">
										<input name="checkbox_id[]" type="checkbox" value="on" class="checkall">
									</th>
									<th>
										 <?php if ($this->lang->line('admin_car_types_name') != '') echo stripslashes($this->lang->line('admin_car_types_name')); else echo 'Name'; ?>
									</th>
									<th>
										<?php if ($this->lang->line('admin_car_types_image') != '') echo stripslashes($this->lang->line('admin_car_types_image')); else echo 'Image'; ?>
									</th>
									<?php /*
									<th>
										Default
									</th>
									*/ ?>
									<th>
										<?php if ($this->lang->line('admin_subadmin_status') != '') echo stripslashes($this->lang->line('admin_subadmin_status')); else echo 'Status'; ?>
									</th>
									<th>
										 <?php if ($this->lang->line('admin_subadmin_action') != '') echo stripslashes($this->lang->line('admin_subadmin_action')); else echo 'Action'; ?>
									</th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
			<input type="hidden" name="statusMode" id="statusMode"/>
			<input type="hidden" name="SubAdminEmail" id="SubAdminEmail"/>
		</form>	
			
		</div>
		<span class="clear"></span>
	</div>
</div>
<?php 
$this->load->view('admin/templates/footer.php');
?>