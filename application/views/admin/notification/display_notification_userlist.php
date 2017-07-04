<?php 
/*
  @Coder: Shabeer
 */
$this->load->view('admin/templates/header.php');
extract($privileges);
?>
<?php
 $dialcode=array();

 foreach ($countryList as $country) {
    
     if ($country->dial_code != '') {
        
      $dialcode[]=str_replace(' ', '', $country->dial_code);  
       
       
       
     }
 }
 
   asort($dialcode);
   $dialcode=array_unique($dialcode);

                                    
?>
<script>
$(document).ready(function(){
    $country='';
	<?php if(isset($_GET['country'])) {?>
	$country = "<?php echo $_GET['country']; ?>";
    <?php }?>
	$("#country").attr("disabled", true);
    if($country != ''){
		$('#country').css("display","inline");
       
		$('#country').prop("disabled", false);
	}
	$("#filtertype").change(function(){
        $('#filtervalue').val('');
		$filter_val = $(this).val();
        $('#country').css("display","none");
		$("#country").attr("disabled", true);
        if($filter_val == 'phone_number'){
			$('#country').css("display","inline");
            $('#country').prop("disabled", false);
			
		}
		
	});
	
});
</script>
<script>
    $(document).ready(function () {
		$.validator.setDefaults({ ignore: ":hidden:not(select)" });
        $("#s_notification").validate();
		$("#s_email_form").validate();
	
	});
	
	function sendNotifi(){
		var tmplId = $('#sel_notification').val();
		if(tmplId != ''){
			$('.notification_cnt').css('display','block');
			var ntContent = $('#sel_notification').find(':selected').attr('data-cnt');		
			$('#notifytContent').html('<div class="form_grid_12 notification_container"><h3>Notification Template Preview</h3><p>'+ntContent+'</p></div>');
		} else {
			$('.notification_cnt').css('display','none');
			$('#notifytContent').html('');
		}
	}
	
	function sendEmail(){
		var tmplId = $('#sel_email').val();
		if(tmplId != ''){
			var ntContent = $('#sel_email').find(':selected').attr('data-cnt'); 
			$('.notification_cnt').css('display','block');
			$('#emailContent').html('<div class="form_grid_12 notification_container" ><h3>Email Template Preview</h3><p>'+ntContent+'</p></div>');
		} else {
			$('.notification_cnt').css('display','none');
			$('#emailContent').html('');
		}
	}
	
	
</script>
<div id="content">
    <div class="grid_container">
	
		<div class="grid_12">
			<div class="">
					<div class="widget_content">
						<span class="clear"></span>						
						<div class="">
							<div class=" filter_wrap">
								<div class="widget_top filter_widget">
								
									<h6><?php if ($this->lang->line('admin_notification_filter_users') != '') echo stripslashes($this->lang->line('admin_notification_filter_users')); else echo 'Filter Users'; ?></h6>
									<div class="btn_30_light">	
									<form method="GET" id="filter_form" action="admin/notification/display_notification_user_list" accept-charset="UTF-8">	
										<select class="form-control" id="filtertype" name="type" tabindex="1">
											<option value="" data-val=""><?php if ($this->lang->line('admin_notification_select_filter_type') != '') echo stripslashes($this->lang->line('admin_notification_select_filter_type')); else echo 'Select Filter Type'; ?></option>
											<option value="user_name" data-val="user_name" <?php if(isset($type)){if($type=='user_name'){ echo 'selected="selected"'; } }?>><?php if ($this->lang->line('admin_notification_user_name') != '') echo stripslashes($this->lang->line('admin_notification_user_name')); else echo 'User Name'; ?></option>
											<option value="email" data-val="email" <?php if(isset($type)){if($type=='email'){ echo 'selected="selected"'; } }?>><?php if ($this->lang->line('admin_notification_user_email') != '') echo stripslashes($this->lang->line('admin_notification_user_email')); else echo 'User Email'; ?></option>
											<option value="phone_number" data-val="phone_number" <?php if(isset($type)){if($type=='phone_number'){ echo 'selected="selected"'; } }?>><?php if ($this->lang->line('admin_notification_user_phonenumber') != '') echo stripslashes($this->lang->line('admin_notification_user_phonenumber')); else echo 'User PhoneNumber'; ?></option>
										</select>
										<select name="country" id="country"  class=" form-control" title="Please choose your country" style="display:none;">
                                        <?php 
                                        $country = '';
											if(isset($_GET['country']) && $_GET['country']!=''){
												$country = $_GET['country'];
											}
                                        
                                        foreach ($dialcode as $row) {
                                         
                                    
                                            if($country != '' && $country == $row){
													echo "<option selected value=".$row.">".$row."</option>";
												}else{
													echo "<option value=".$row.">".$row."</option>";
												}
                                        } ?>
                                       </select>
										<input name="value" id="filtervalue" type="text" tabindex="2" class="tipTop" title="<?php if ($this->lang->line('driver_enter_keyword') != '') echo stripslashes($this->lang->line('driver_enter_keyword')); else echo 'Please enter keyword'; ?>" value="<?php if(isset($value)) echo $value; ?>" placeholder="<?php if ($this->lang->line('driver_enter_keyword') != '') echo stripslashes($this->lang->line('driver_enter_keyword')); else echo 'Please enter keyword'; ?>" />
										
										<button type="submit" class="tipTop filterbtn" tabindex="3" original-title="<?php if ($this->lang->line('driver_enter_keyword_filter') != '') echo stripslashes($this->lang->line('driver_enter_keyword_filter')); else echo 'Select filter type and enter keyword to filter'; ?>">
											<span class="icon search"></span><span class="btn_link"><?php if ($this->lang->line('admin_notification_filter') != '') echo stripslashes($this->lang->line('admin_notification_filter')); else echo 'Filter'; ?></span>
										</button>
										<?php if(isset($filter) && $filter!=""){ ?>
										<a href="admin/notification/display_notification_user_list"class="tipTop filterbtn" original-title="View All Users">
											<span class="icon delete_co"></span><span class="btn_link"><?php if ($this->lang->line('admin_notification_remove_filter') != '') echo stripslashes($this->lang->line('admin_notification_remove_filter')); else echo 'Remove Filter'; ?></span>
										</a>
										<?php } ?>
										</form>
										
									</div>
								</div>
							</div>
						</div>
					</div>
			</div>
		</div>
	
		<?php
        $attributes = array('id' => 'display_form');
        echo form_open('admin/users/change_user_status_global', $attributes)
        ?>
        <div class="grid_12">
            <div class="widget_wrap">
                <div class="widget_top">
                    <span class="h_icon blocks_images"></span>
                    <h6><?php echo $heading ?></h6>
                    <div style="float: right;line-height:40px;padding:0px 10px;height:39px;">
                        <?php #if ($allPrev == '1' || in_array('2', $notification)) { ?>
                        <div class="btn_30_light" style="height: 29px;">
                            <a class="o-modal" data-content="send_notification" style="background:#6bba70 !important;border: 1px solid #6bba70" href="javascript:void(0)">
                                <span class="icon accept_co"></span>
                                <span style="border-left: 1px solid green;" class="btn_link"><?php if ($this->lang->line('admin_notification_send_notification') != '') echo stripslashes($this->lang->line('admin_notification_send_notification')); else echo 'Send Notification'; ?></span>
                            </a>

                        </div>
						<div class="btn_30_light" style="height: 29px;">
                            <a class="o-modal" data-content="send_emails" style="background:#6bba70 !important;border: 1px solid #6bba70" href="javascript:void(0)">
                                <span class="icon accept_co"></span>
                                <span style="border-left: 1px solid green;" class="btn_link"><?php if ($this->lang->line('admin_notification_send_email') != '') echo stripslashes($this->lang->line('admin_notification_send_email')); else echo 'Send Email'; ?></span>
                            </a>

                        </div>

                    </div>
                </div>

                <div class="widget_content">
                    <?php
                    if ($paginationLink != '') {
                        echo $paginationLink;
                        $tble = 'userListTblCustom';
                    } else {
                        $tble = 'userListTbl';
                    }
                    ?>

                    <table class="display" id="<?php echo $tble; ?>">
                        <thead>
                            <tr>
                                <th class="center">
                                    <input  name="checkbox_id[]" type="checkbox" value="on" class="checkall">
                                </th>
                                <th class="tip_top" title="<?php if ($this->lang->line('dash_click_sort') != '') echo stripslashes($this->lang->line('dash_click_sort')); else echo 'Click to sort'; ?>">
                                    <?php if ($this->lang->line('admin_notification_user_name') != '') echo stripslashes($this->lang->line('admin_notification_user_name')); else echo 'User Name'; ?>
                                </th>
                                <th class="tip_top" title="<?php if ($this->lang->line('dash_click_sort') != '') echo stripslashes($this->lang->line('dash_click_sort')); else echo 'Click to sort'; ?>">
                                    <?php if ($this->lang->line('admin_notification_email') != '') echo stripslashes($this->lang->line('admin_notification_email')); else echo 'Email'; ?>
                                </th>
								<th>
                                    <?php if ($this->lang->line('admin_notification_device_type') != '') echo stripslashes($this->lang->line('admin_notification_device_type')); else echo 'Device Type'; ?>
                                </th>
                                <th>
                                    <?php if ($this->lang->line('admin_notification_ratings') != '') echo stripslashes($this->lang->line('admin_notification_ratings')); else echo 'Ratings'; ?>
                                </th>
                                <th class="tip_top" title="<?php if ($this->lang->line('dash_click_sort') != '') echo stripslashes($this->lang->line('dash_click_sort')); else echo 'Click to sort'; ?>">
                                    <?php if ($this->lang->line('admin_common_status') != '') echo stripslashes($this->lang->line('admin_common_status')); else echo 'Status'; ?>
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($usersList->num_rows() > 0) {
                                foreach ($usersList->result() as $row) {
                                    ?>
                                    <tr>
                                        <td class="center tr_select ">
                                            <input name="checkbox_id[]" class="user_mongo_id" type="checkbox" value="<?php echo $row->_id; ?>">
                                        </td>   
                                        <td class="center">
                                            <?php echo $row->user_name; ?>
                                        </td>
                                        <td class="center">
										<?php if($isDemo){ ?>
										<?php echo $dEmail; ?>
										<?php }  else{ ?>
										<?php echo $row->email; ?>
										<?php } ?>
                                            
                                        </td>
										<td class="center">
                                            <?php if(isset($row->push_type))echo get_language_value_for_keyword($row->push_type,$this->data['langCode']); ?>
                                        </td>
                                        <td class="center">

                                            <?php if (isset($row->avg_review)) { ?>
                                                <a href="admin/reviews/view_user_reviews/<?php echo $row->_id; ?>" title="<?php if ($this->lang->line('user_view_review_details') != '') echo stripslashes($this->lang->line('user_view_review_details')); else echo 'View review details'; ?>" class="tip_top"style="color:blue;"><?php echo $row->avg_review; ?> (<?php echo $row->total_review; ?>) </a>
                                            <?php } else { ?>
                                                <a> 0 (0) </a>
                                            <?php } ?>

                                        </td>

                                        <td class="center">
                                            <?php
                                            if (strtolower($row->status) == 'active') {
                                                ?>
                                                <span class="badge_style b_done"><?php echo get_language_value_for_keyword($row->status,$this->data['langCode']); ?></span>
                                                <?php
                                            } else {
                                                ?>
                                                <span class="badge_style b_notDone"><?php echo get_language_value_for_keyword($row->status,$this->data['langCode']); ?></span>
                                                <?php
                                            }
                                            ?>

                                            <?php
                                        }
                                    }
                                    ?>
                                </td>

                            </tr>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="center">
                                    <input  name="checkbox_id[]" type="checkbox" value="on" class="checkall">
                                </th>
                                <th>
                                    <?php if ($this->lang->line('admin_notification_user_name') != '') echo stripslashes($this->lang->line('admin_notification_user_name')); else echo 'User Name'; ?>
                                </th>
                                <th>
                                    <?php if ($this->lang->line('admin_notification_email') != '') echo stripslashes($this->lang->line('admin_notification_email')); else echo 'Email'; ?>
                                </th>
                                <th>
                                    <?php if ($this->lang->line('admin_notification_device_type') != '') echo stripslashes($this->lang->line('admin_notification_device_type')); else echo 'Device Type'; ?>
                                </th>
                                <th>
                                    <?php if ($this->lang->line('admin_notification_ratings') != '') echo stripslashes($this->lang->line('admin_notification_ratings')); else echo 'Ratings'; ?>
                                </th>

                                <th>
                                    <?php if ($this->lang->line('admin_common_status') != '') echo stripslashes($this->lang->line('admin_common_status')); else echo 'Status'; ?>
                                </th>
                            </tr>
                        </tfoot>
                    </table>

                    <?php
                    if ($paginationLink != '') {
                        echo $paginationLink;
                    }
                    ?>
							
                </div>
            </div>
        </div>
        <input type="hidden" name="statusMode" id="statusMode"/>
        <input type="hidden" name="SubAdminEmail" id="SubAdminEmail"/>
        </form>	
        <div id="send_notification" style="display:none;">
            <h3><?php if ($this->lang->line('admin_notification_send_notification') != '') echo stripslashes($this->lang->line('admin_notification_send_notification')); else echo 'Send Notification'; ?></h3>
            <?php
            $attributes = array('class' => 'form_container left_label', 'id' => 's_notification', 'enctype' => 'multipart/form-data', 'method' => 'post');
            echo form_open_multipart('admin/notification/send_notification_to_device', $attributes);
            ?>
            <ul>

                <li>
                    <input name="userIds" type="hidden" class="tdesc"  value=""/>
					 <input name="user_type" type="hidden"   value="user"/>
                    <div class="form_grid_12">
                        <label class="field_title" for="media_title" style="width: 56%;"><?php if ($this->lang->line('admin_notification_choose_notification') != '') echo stripslashes($this->lang->line('admin_notification_choose_notification')); else echo 'Choose Notification'; ?> <span class="req">*</span></label>
                        <div class="form_input">
                          <select id="sel_notification" onchange="sendNotifi();" class="chzn-select1 required" name="notification_id" tabindex="1" style="width: 350px;" data-placeholder="Select Model Type">
							<option value=""><?php if ($this->lang->line('admin_notification_select_notification') != '') echo stripslashes($this->lang->line('admin_notification_select_notification')); else echo 'Select Notification'; ?>....</option>
							<?php 
							
								foreach($template_details->result() as $temp_detail){
									if($temp_detail->notification_type=='notification'){
										?>
									<option value="<?php echo $temp_detail->news_id; ?>" data-cnt="<?php echo htmlentities($temp_detail->message['msg_description']) ?>"><?php echo $temp_detail->message['title'] ?></option>		
										<?php 
									}
								}
							?>
							</select>
                        </div> <button type="submit" class="btn_small btn_blue " tabindex="5"><span><?php if ($this->lang->line('admin_notification_send') != '') echo stripslashes($this->lang->line('admin_notification_send')); else echo 'Send'; ?></span></button>
                    </div>	
					
                </li>
				<li class="notification_cnt" id="notifytContent">
					
				</li>
               
            </ul>
            <?php echo form_close(); ?>
        </div>
		 <div id="send_emails" style="display:none;">
            <h3><?php if ($this->lang->line('admin_notification_send_email') != '') echo stripslashes($this->lang->line('admin_notification_send_email')); else echo 'Send Email'; ?></h3>
            <?php
            $attributes = array('class' => 'form_container left_label', 'id' => 's_email_form', 'enctype' => 'multipart/form-data', 'method' => 'post');
            echo form_open_multipart('admin/notification/send_email_to_users', $attributes);
            ?>
            <ul>

               <li>
                    <input name="userIds" type="hidden" class="tdesc"  value=""/>
					 <input name="user_type" type="hidden"   value="user"/>
                    <div class="form_grid_12">
                        <label class="field_title" for="media_title" style="width: 56%;"><?php if ($this->lang->line('admin_notification_choose_email_template') != '') echo stripslashes($this->lang->line('admin_notification_choose_email_template')); else echo 'Choose Email Template'; ?> <span class="req">*</span></label>
                        <div class="form_input">
                          <select id="sel_email"  class="chzn-select1 required" onchange="sendEmail();" name="email_id" tabindex="1" style="width: 350px;" data-placeholder="Select Model Type">
							<option value=""><?php if ($this->lang->line('admin_notification_select_email_template') != '') echo stripslashes($this->lang->line('admin_notification_select_email_template')); else echo 'Select Email Template....'; ?></option>
							<?php 
							
								foreach($template_details->result() as $temp_detail){
									if($temp_detail->notification_type=='email'){
							?>
							<option value="<?php echo $temp_detail->news_id; ?>" data-cnt="<?php echo htmlentities($temp_detail->message['mail_description']) ?>"><?php echo $temp_detail->message['title'] ?></option>		
									<?php }
								}
							?>
							</select>
                        </div> <button type="submit" class="btn_small btn_blue " tabindex="5"><span><?php if ($this->lang->line('admin_notification_send') != '') echo stripslashes($this->lang->line('admin_notification_send')); else echo 'Send'; ?></span></button>
                    </div>
                </li>
				<li class="notification_cnt"  id="emailContent">
					
				</li>

            </ul>
            <?php echo form_close(); ?>
        </div>
    </div>
    <span class="clear"></span>
</div>


<style>
#simplemodal-container {
	/* width: 700px !important; */
}

.left_label ul li .form_input {
    margin-left: 20% !important;
}

.left_label ul li label.field_title {
    margin-right: 0;    
	width: 20%;
}
.mceLayout {
    min-width: 500px !important;
}


.btn_blue {
    background: #a7a9ac none repeat scroll 0 0;
    border: 1px solid #000;
    color: #fff;
    float: right;
    font-size: 12px;
    margin-right: 10px;
    margin-top: -32px;
    padding: 0 16px;
    text-shadow: 1px 1px 0 #000;
}

.notification_cnt {
    background-image: none;
    border: 1px solid gray !important;
    border-radius: 5px;
	display:none;
}
.notification_container {
    height: 193px;
    overflow: auto;
    width: 100% !important;
}
</style>

<script>
    $(document).ready(function () {
		
       /* var checkedValues = $('.user_mongo_id:checked').map(function () {
            return this.value;
        }).get();*/
		//$('#sendMailtextarea').tinymce().execCommand('mceRemoveControl', true, 'sendMailtextarea');
		
		
		
        $('.o-modal').click(function (e) {
            var contentId = $(this).attr("data-content");
            if ($(".tdesc").val() != '') {
                $('#' + contentId).modal({
				 onClose: function(dialog){
					location.reload();
					$.modal.close();
				 }
				});
            } else {
                alert("<?php if ($this->lang->line('admin_please_select_one_more') != '') echo stripslashes($this->lang->line('admin_please_select_one_more')); else echo 'Please select one or more user to send notification'; ?>");
            }

            return false;
        });
        /*$(".checkall,.user_mongo_id").change(function () {
            checkedValues = $('.user_mongo_id:checked').map(function () {
                return this.value;
            }).get();
            $(".tdesc").val(checkedValues);
        });*/
		
		$(document).on('change', '.checkall,.user_mongo_id', function() {
				var oTable = $('#<?php echo $tble; ?>').dataTable();
				var rowcollection =  oTable.$("input:checked", {"page": "all"});
				checkbox_value = [];
				rowcollection.each(function(index,elem){
					checkbox_value.push($(elem).val());
				});
				
				$(".tdesc").val(checkbox_value);
		});

        $(".media_image").change(function (e) {
            e.preventDefault();
            if (typeof (FileReader) != "undefined") {
                var image_holder = $("#image-holder");
                image_holder.empty();
                var reader = new FileReader();
                reader.onload = function (e) {

                    var res = e.target.result;
                    var ext = res.substring(11, 14);
                    extensions = ['jpg', 'jpe', 'gif', 'png', 'bmp'];
                    if ($.inArray(ext, extensions) !== -1) {
                        var image = new Image();
                        image.src = e.target.result;

                        image.onload = function () {
                            if (this.width >= 75 && this.height >= 42) {
                                $("#loadedImg").css("display", "none");
                                $("<img />", {
                                    "src": e.target.result,
                                    "id": "thumb-image",
                                    "style": "width:100px;height:100px;margin-top:20px",
                                }).appendTo(image_holder);
                                $('#ErrNotify').html('');




                            } else {
                                $('#ErrNotify').html('Upload Image Too Small. Please Upload Image Size More than or Equalto 75 X 42 .');
                            }
                        };
                    }
                    else {
                        $('#ErrNotify').html('Please Select an Image file');
                    }
                }
                image_holder.show();
                reader.readAsDataURL($(this)[0].files[0]);
            }
        });

        $(".filtertype").on("change", function () {
            $(".filtervalue").empty();
            var filter_value = [];
            var device_type = new Array('android', 'ios');
            var status = new Array('active', 'inactive');
            var filter_type = new Array();

            $(".filtertype option").each(function () {
                if ($(this).attr('value') != '')
                    filter_type.push($(this).attr('value'));
            });
            if ($.inArray(this.value, filter_type) != -1) {
                switch (this.value) {
                    case 'device-type':
                        filter_value = device_type;
                        break;
                    case 'status':
                        filter_value = status;
                        break;
                    default:
                        filter_value = ' ';
                        break;
                }
            }

            $.each(filter_value, function (ind, val) {
                $(".filtervalue").append($("<option>", {
                    value: val,
                    text: val,
                }));
            });

        });


    });


</script>
<script type="text/javascript">
$(document).ready(function(){
	$.validator.setDefaults({ ignore: ":hidden:not(select)" })
});

</script>
<?php
$this->load->view('admin/templates/footer.php');
?>