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
                    <div id="widget_tab">
                    </div>
                </div>
                <div class="widget_content">
                    <form class="form_container left_label" action="admin/brand/insertEditModel" id="addeditmodel_form" method="post" enctype="multipart/form-data">
                        <div>
                        <ul>
                        <li>
						<div class="form_grid_12">
						<label class="field_title" for="vehicle_type"><?php if ($this->lang->line('admin_make_and_model_model_list_maker') != '') echo stripslashes($this->lang->line('admin_make_and_model_model_list_maker')); else echo 'Maker'; ?></label>
						<div class="form_input">
						<select class="chzn-select required" name="brand" tabindex="1" style="width: 375px; display: none;" data-placeholder="Select Maker">
								<?php if ($brandList->num_rows() > 0) { ?>
									<?php foreach ($brandList->result() as $brand) { ?>
										<option value="<?php echo $brand->_id; ?>" <?php
										if ($form_mode) {
											if ($brand->_id == $modeldetails->row()->brand) {
												echo 'selected="selected"';
											}
										}
										?>><?php echo $brand->brand_name; ?></option>
											<?php } ?>
										<?php } ?>
							</select>
						</div>
						</div>
						</li>

						<li>
						<div class="form_grid_12">
						<label class="field_title" for="name"><?php if ($this->lang->line('admin_make_and_model_model_list_model_name') != '') echo stripslashes($this->lang->line('admin_make_and_model_model_list_model_name')); else echo 'Model Name'; ?> <span class="req">*</span></label>
							<div class="form_input">
								<input name="name" id="name" type="text" tabindex="2" class="large tipTop required" title="<?php if ($this->lang->line('make_model_document_name') != '') echo stripslashes($this->lang->line('make_model_document_name')); else echo 'Please enter document name'; ?>" value="<?php
								if ($form_mode) {
									echo $modeldetails->row()->name;
								}
								?>"/>
							</div>
						</div>
						</li>
						
						<li> 		
						<?php 
							$modelYrs = array();
							if($form_mode && isset($modeldetails->row()->year_of_model)){
								$modelYrs = $modeldetails->row()->year_of_model; 
							} 
							
						?>
							<div class="form_grid_12 year-of-models">
								<label class="field_title" for="name"><?php if ($this->lang->line('admin_make_and_model_model_add_new_model') != '') echo stripslashes($this->lang->line('admin_make_and_model_model_add_new_model')); else echo 'Year Of Models'; ?><span class="req">*</span></label>
								<div class="form_input">
									
									<select class="chzn-select required" multiple="multiple" id="year_of_model" name="year_of_model[]" tabindex="1" data-placeholder="<?php if ($this->lang->line('dash_please_choose_year_of_model') != '') echo stripslashes($this->lang->line('dash_please_choose_year_of_model')); else echo 'Choose the year of model'; ?>">
									<?php 
									$curYear = date('Y');
									for($i=$curYear; 2000 <= $i; $i--){  ?>
									<option value="<?php echo $i; ?>" <?php if(in_array($i,$modelYrs))echo 'selected="selected"';?>><?php echo $i; ?></option>
										<?php 
									}
									?>
									</select>
									
									
								</div>
							</div>
                        </li>


						<li>
						<div class="form_grid_12">
						<label class="field_title" for="vehicle_type"><?php if ($this->lang->line('admin_make_and_model_model_list_model_type') != '') echo stripslashes($this->lang->line('admin_make_and_model_model_list_model_type')); else echo 'Model Type'; ?></label>
							<div class="form_input model_type">
								<select id="model_type" class="chzn-select required" name="type" tabindex="1" style="width: 375px; display: none;" data-placeholder="Select Model Type">
									<?php if ($typeList->num_rows() > 0) { ?>
										<?php foreach ($typeList->result() as $type) { ?>
											<option value="<?php echo $type->_id; ?>" <?php
											if ($form_mode) {
												if (isset($modeldetails->row()->type)) {
													if ($type->_id == $modeldetails->row()->type) {
														echo 'selected="selected"';
													}
												}
											}
											?>><?php echo $type->vehicle_type; ?></option>
												<?php } ?>
											<?php } ?>
								</select>
							</div>
							</div>
							</li>
								<!-- <li>
									<div class="form_grid_12">
										<label class="field_title" for="attribute_name">Year of Vehicle</label>
										<div class="form_input">
										<textarea name="year_of_vehicle" class=""></textarea>
										</div>
									</div>
								</li> -->

                                <li>
                                    <div class="form_grid_12">
                                        <label class="field_title" for="status"><?php if ($this->lang->line('admin_common_status') != '') echo stripslashes($this->lang->line('admin_common_status')); else echo 'Status'; ?> <span class="req">*</span></label>
                                        <div class="form_input">
                                            <div class="active_inactive">
                                                <input type="checkbox" tabindex="7" name="status" id="active_inactive_active" class="active_inactive" <?php
                                                if ($form_mode) {
                                                    if ($modeldetails->row()->status == 'Active') {
                                                        echo 'checked="checked"';
                                                    }
                                                } else {
                                                    echo 'checked="checked"';
                                                }
                                                ?>/>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <div class="form_grid_12">
                                        <div class="form_input">
                                            <input type="hidden" name="model_id" id="model_id" value="<?php
                                            if ($form_mode) {
                                                echo $modeldetails->row()->_id;
                                            }
                                            ?>"  />
                                            <button type="submit" class="btn_small btn_blue" tabindex="15"><span><?php if ($this->lang->line('admin_common_submit') != '') echo stripslashes($this->lang->line('admin_common_submit')); else echo 'Submit'; ?></span></button>
                                        </div>
                                    </div>
                                </li>

                            </ul>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <span class="clear"></span>
</div>
<style>
.model_type .error {
    float: right;
    margin-right: 30%;
}
.year-of-models .chzn-drop{
	width: 65px !important;
}
#year_of_model_chzn{
	width: 250px !important;
}
.default {
	width: 650px !important;
}
</style>
<script>
$.validator.setDefaults({ ignore: ":hidden:not(select)" });
</script>
<?php
$this->load->view('admin/templates/footer.php');
?>