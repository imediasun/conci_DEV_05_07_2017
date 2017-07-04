<?php
$this->load->view('site/templates/profile_header');
?> 
<div class="rider-signup">
    <div class="container-new">
        <section>
            <div class="col-md-12 profile_rider">

                <!-------Profile side bar ---->

                <?php
                $this->load->view('site/templates/profile_sidebar');
                ?>
                <div class="col-md-9 nopadding profile_rider_right">
                    <div class="col-md-12 rider-pickup-detail">
                        <h2><?php if ($this->lang->line('home_share_earn') != '') echo stripslashes($this->lang->line('home_share_earn')); else echo 'SHARE AND EARN'; ?></h2>
                        <div class="col-md-12 friend-earn">
                           <?php if($this->config->item('welcome_amount') > 0 ) {?>
                            <p><?php if ($this->lang->line('user_friend_joins_earns') != '') echo stripslashes($this->lang->line('user_friend_joins_earns')); else echo 'Friend joins, friend earns'; ?><strong> <?php echo $dcurrencySymbol; ?><?php echo number_format($this->config->item('welcome_amount'), 2); ?></strong>
                            </p>
                            <?php } ?>
                            <p>
                            
                            <?php if($this->config->item('referal_credit')=='instant') {?>
							<?php if ($this->lang->line('user_share_friend_join_earn') != '') echo stripslashes($this->lang->line('user_share_friend_join_earn')); else echo 'Friend joins, you earns'; ?>
                            <?php } else {?>
							<?php if ($this->lang->line('user_share_friend_ride_you_earn') != '') echo stripslashes($this->lang->line('user_share_friend_ride_you_earn')); else echo 'Friend rides, you earns'; ?>
                            <?php } ?>
                                <strong> <?php echo $dcurrencySymbol; ?><?php echo number_format($this->config->item('referal_amount'), 2); ?></strong>
                            </p>
                            <img src="images/site/Car-1.png">
                            <div class="referral-code">
                                <p><?php if ($this->lang->line('user_share_referral_code') != '') echo stripslashes($this->lang->line('user_share_referral_code')); else echo 'Share your referral code'; ?></p>
                                <h3><?php echo $rider_info->row()->unique_code; ?></h3>
                            </div>
                        </div>
                        <div class="col-md-12 share-all">
                            <h5><?php if ($this->lang->line('user_let_the_world_know') != '') echo stripslashes($this->lang->line('user_let_the_world_know')); else echo 'Let the world know'; ?></h5>
                            <ul class="share_wrap">
                                <li>
                                    <a href="http://www.facebook.com/sharer.php?u=<?php echo base_url() . 'rider/signup?ref=' . base64_encode($rider_info->row()->unique_code); ?>" onclick="window.open('http://www.facebook.com/sharer.php?u=<?php echo base_url() . 'rider/signup?ref=' . base64_encode($rider_info->row()->unique_code); ?>', 'popup', 'height=500px, width=400px');
                                            return false;">
                                           <?php if ($this->lang->line('user_facebook') != '') echo stripslashes($this->lang->line('user_facebook')); else echo 'Facebook'; ?> 
                                    </a>
                                </li>
                                <li>
                                    <a href="http://twitter.com/share?text=<?php echo $shareDesc; ?>&url=<?php echo base_url() . 'rider/signup?ref=' . base64_encode($rider_info->row()->unique_code); ?>" target=" _blank" onclick="window.open('http://twitter.com/share?text=<?php echo $shareDesc; ?>&url=<?php echo base_url() . 'rider/signup?ref=' . base64_encode($rider_info->row()->unique_code); ?>', 'popup', 'height=500px, width=400px');
                                            return false;">
                                           <?php if ($this->lang->line('user_twitter') != '') echo stripslashes($this->lang->line('user_twitter')); else echo 'Twitter'; ?>
                                    </a>
                                </li>
                                <?php /* <li>
									<a href="mailto:example@yahoo.in?subject=SHARE AND EARN&body=<?php echo $shareDesc; ?>" target="_blank" >
                                        <?php if ($this->lang->line('user_mail') != '') echo stripslashes($this->lang->line('user_mail')); else echo 'Mail';  ?> 
                                    </a>
                                </li> */ ?>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
</div>                
<?php
$this->load->view('site/templates/footer');
?> 