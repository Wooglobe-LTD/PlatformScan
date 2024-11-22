<div class="side-nav">
    <div class="side-nav-content userPro">
        <ul>
            <li class="last userName">
                <h3 style="margin: 0px;">
                    <?php echo $this->sess->userdata('clientName');?>
                </h3>
            </li>
            <li class="last edtPro">
                <a href="<?php echo $url;?>profile">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    Edit Profile
                </a>
            </li>
            
        </ul>
        
    </div>  
</div>
<div class="side-nav">
    <div class="side-nav-content">
        <ul id="nav_status">
            <li class="<?php if($page == 'dashboard'){ echo 'active';}?>">
                <a href="<?php echo base_url('dashboard');?>" class="sts_blk <?php if($page == 'dashboard'){ echo 'active';}?>">
                    <span><i class="fa fa-tachometer" aria-hidden="true"></i></span>
                    Dashboard
                </a>
            </li>
            <!--<li class="<?php /*if($page == 'license'){ echo 'active';}*/?>">
                <a href="javascript:void(0)" class="license_blk <?php /*if($page == 'license'){ echo 'active';}*/?>">
                    <span><i class="fa fa-certificate" aria-hidden="true"></i></span>
                    Licensed Video
                </a>
            </li>
            <li>
                <a href="<?php echo base_url('account-summary')?>" class="<?php if($page == 'account-summary'){ echo 'active';}?>">
                    <span><i class="fa fa-video-camera" aria-hidden="true"></i></span>
                    All Videos
                </a>
            </li>-->
            <li>
                <a href="<?php echo base_url('earnings-breakdown');?>" class="<?php if($page == 'earnings-breakdown'){ echo 'active';}?>">
                    <span><i class="fa fa-money" aria-hidden="true"></i></span>
                    Earning Breakdown
                </a>
            </li>
            <li>
                <a href="<?php echo base_url('payment-history');?>" class="<?php if($page == 'payment-history'){ echo 'active';}?>">
                    <span><i class="fa fa-history" aria-hidden="true"></i></span>
                    Payment History
                </a>
            </li>
            <li>
                <a href="javascript:void(0);" class="upload">
                    <span><i class="fa fa-upload" aria-hidden="true"></i></span>
                    Submit New Video
                </a>
            </li>

            <!--<li class="last">
                <a href="<?php /*echo $url;*/?>logout">
                    <span><i class="fa fa-sign-out" aria-hidden="true"></i></span>
                    Logout
                </a>
            </li>-->

            <!--
            <li>
                <a href="<?php /*echo base_url('approved-videos');*/?>" class="<?php /*if($page == 'approved-videos'){ echo 'active';}*/?>">
                    <span><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></span>
                    Approved Videos
                </a>
            </li>
            <li>
                <a href="<?php /*echo base_url('acquired-videos')*/?>" class="<?php /*if($page == 'acquired-videos'){ echo 'active';}*/?>">
                    <span><i class="fa fa-shopping-cart" aria-hidden="true"></i></span>
                    Acquired Videos
                </a>
            </li>
            <li>
                <a href="<?php /*echo base_url('video-requests');*/?>" class="<?php /*if($page == 'video-requests'){ echo 'active';}*/?>">
                    <span><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></span>
                    Pending Approval
                </a>
            </li>-->

            <li class="last">
                <a href="<?php echo $url;?>logout">
                    <span><i class="fa fa-sign-out" aria-hidden="true"></i></span>
                    Logout
                </a>
            </li>
            
            
            
        </ul>
        
    </div>  
</div>