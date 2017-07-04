<?php
$this->load->view('admin/templates/header.php');
extract($privileges);
?>
<div id="content">
	<div class="grid_container">
<style>
.refCode {
    color: gold;
    font-weight: bold;
    margin: 12px;
	float:right;
}
</style>
		<?php 
			$attributes = array('id' => 'display_form');
			echo form_open('admin/referral/change_user_status_global',$attributes) 
		?>
			<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6><?php echo $heading; ?></h6> <?php if(isset($reff_member->row()->unique_code)){ ?><span class="refCode"> <?php if ($this->lang->line('admin_referral_history_referral_code') != '') echo stripslashes($this->lang->line('admin_referral_history_referral_code')); else echo 'Referral Code'; ?> : [ <?php echo $reff_member->row()->unique_code; ?> ] </span> <?php } ?>
					</div>
					<div class="widget_content">
						<table class="display" id="userListTbl">
							<thead>
								<tr>
									<th class="center">
										<?php if ($this->lang->line('admin_s_no') != '') echo stripslashes($this->lang->line('admin_s_no')); else echo 'S.No'; ?>
									</th>
									<th class="tip_top" title="<?php if ($this->lang->line('dash_click_sort') != '') echo stripslashes($this->lang->line('dash_click_sort')); else echo 'Click to sort'; ?>">
										 <?php if ($this->lang->line('admin_notification_email') != '') echo stripslashes($this->lang->line('admin_notification_email')); else echo 'Email'; ?>
									</th>
									<th>
										<?php if ($this->lang->line('admin_referral_history_credited_amount') != '') echo stripslashes($this->lang->line('admin_referral_history_credited_amount')); else echo 'Credited Amount'; ?>
									</th>
									<th class="tip_top" title="<?php if ($this->lang->line('dash_click_sort') != '') echo stripslashes($this->lang->line('dash_click_sort')); else echo 'Click to sort'; ?>">
											<?php if ($this->lang->line('admin_referral_history_referred_date') != '') echo stripslashes($this->lang->line('admin_referral_history_referred_date')); else echo 'Referred Date'; ?>
									</th>
									<th>
										<?php if ($this->lang->line('admin_referral_history_reference_id') != '') echo stripslashes($this->lang->line('admin_referral_history_reference_id')); else echo 'Reference Id'; ?>
									</th>
								</tr>
							</thead>
							<tbody>
								<?php $i = 1;
								if (isset($referralsList->row()->history)){
									foreach ($referralsList->row()->history as $row){
								?>
								<tr>
									<td class="center tr_select ">
										<?php echo $i; $i++;?>
									</td>
									<td class="center">
										<?php if(isset($row['reference_mail'])) echo $row['reference_mail'];?>
									</td>
									<td class="center">
										<?php if(isset($row['amount_earns'])) echo $dcurrencySymbol.number_format($row['amount_earns'],2); ?>
									</td>
									<td class="center">
										<?php if(isset($row['reference_date']->sec)) echo date('Y M d, H:i:A',$row['reference_date']->sec);?>
									</td>

									
									<td class="center">
										<?php if(isset($row['reference_id'])) echo $row['reference_id']; else echo 'N/A'; ?>
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
										<?php if ($this->lang->line('admin_s_no') != '') echo stripslashes($this->lang->line('admin_s_no')); else echo 'S.No'; ?>
									</th>
									<th class="tip_top" title="<?php if ($this->lang->line('dash_click_sort') != '') echo stripslashes($this->lang->line('dash_click_sort')); else echo 'Click to sort'; ?>">
										 <?php if ($this->lang->line('admin_notification_email') != '') echo stripslashes($this->lang->line('admin_notification_email')); else echo 'Email'; ?>
									</th>
									<th>
										<?php if ($this->lang->line('admin_referral_history_credited_amount') != '') echo stripslashes($this->lang->line('admin_referral_history_credited_amount')); else echo 'Credited Amount'; ?>
									</th>
									<th class="tip_top" title="<?php if ($this->lang->line('dash_click_sort') != '') echo stripslashes($this->lang->line('dash_click_sort')); else echo 'Click to sort'; ?>">
										<?php if ($this->lang->line('admin_referral_history_referred_date') != '') echo stripslashes($this->lang->line('admin_referral_history_referred_date')); else echo 'Referred Date'; ?>
									</th>
									<th>
										<?php if ($this->lang->line('admin_referral_history_reference_id') != '') echo stripslashes($this->lang->line('admin_referral_history_reference_id')); else echo 'Reference Id'; ?>
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


<style>
.filterbtn {
    background-color: #a7a9ac !important;
    border: 1px solid #e0761a;
    color: #000;
    cursor: pointer;
    height: 29px;
    margin-bottom: 3px;
    vertical-align: middle;
}
</style>

<?php 
$this->load->view('admin/templates/footer.php');
?>