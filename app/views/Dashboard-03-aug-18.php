<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 3/8/2018
 * Time: 3:43 PM
 */?>

<div class="content-wrapper dashboard">
    <div class="container">

        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <section style=" border: 1px solid #000;" class="acc-summary">
                    <a href="<?php echo base_url('account-summary');?>">
                    <h4>Account Summary</h4>

                    <h5>Amount Accumulated Toward Next Payment </h5>
                    <h2>$<?php if(empty($next_payment)) {echo 0;} else {echo $next_payment;}?></h2>

                    <hr>

                    <h5>Lifetime Paid</h5>
                    <h2>$<?php if(empty($paid)) {echo 0;} else {echo $paid;}?></h2>
                    </a>
                </section>
                <!--<section style=" border: 1px solid #000;margin-top: 10px;">
                    <a href="<?php //echo base_url('monthly-account-summary');?>">
                    <h4 style="padding-left: 10%;">This Month Account Summary</h4>

                    <h5 style="margin-left: 20%;">Amount Accumulated Payment </h5>
                    <h2>$<?php //if(empty($next_payment_monthly)) {echo 0;} else {echo $next_payment_monthly;}?></h2>

                    <hr style="margin: 2%">

                    <h5 style="margin-left: 43%;">Paid</h5>
                    <h2>$<?php //if(empty($paidMonthly)) {echo 0;} else {echo $paidMonthly;}?></h2>
                    </a>
                </section> -->

                <section style="margin-top: 10px;">
                    <a href="<?php echo base_url('earnings-breakdown');?>">
                        <div >
                            <div class="dashboard-div-wrapper bk-clr-one">
                                <i  class="fa fa-play-circle dashboard-div-icon" ></i>

                                <h5>Earnings Breakdown </h5>
                            </div>
                        </div>
                    </a>
                </section>
                <section style=" border: 1px solid #000; margin-top: -37px;" class="acc-summary">

                        <h4>Your Videos</h4>
                        <h5>Select your video for detail </h5>
                        <hr style="margin: 2%">

                        <?php foreach ($videos_title as $title){?>
                        <span>
                            <a href="<?php echo base_url('account-summary')?>?video=<?php echo $title['slug']?>"><p class="fa fa-angle-right" style="float: right;margin-right: 10px;margin-top: 10px;"></p>
                                <h5 style="padding: 10px;"><?php echo $title['title'];?></h5>
                            </a>
                        </span>

                        <hr style="margin: 2%">
                        <?php }?>

                    <a class="btnVid" href="<?php echo base_url('account-summary')?>">
                        <button type="button">View All</button>
                    </a>
                </section>
            </div>
            <div class="col-md-8 col-sm-8 col-xs-12">
                <div class="row">

                    <a href="<?php echo base_url('video-requests');?>">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="dashboard-div-wrapper bk-clr-one margin-btm">
                                <i  class="fa fa-play-circle dashboard-div-icon" ></i>

                                <h5>Pending Approval : <span style="font-weight: bold;font-size: x-large;"><?php echo $requests;?></span> </h5>
                            </div>
                        </div>
                    </a>

                    <a href="<?php echo base_url('approved-videos');?>">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="dashboard-div-wrapper bk-clr-two margin-btm">
                                <i  class="fa fa-upload dashboard-div-icon" ></i>
                                <h5>Approved Videos : <span style="font-weight: bold;font-size: x-large;"><?php echo $deals;?></span> </h5>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="row">
                    <a href="<?php echo base_url('acquired-videos')?>">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="dashboard-div-wrapper bk-clr-three margin-btm">
                                <i  class="fa fa-download dashboard-div-icon" ></i>
                                <h5>Acquired Videos : <span style="font-weight: bold;font-size: x-large;"><?php echo $uploaded_videos;?></span></h5>
                            </div>
                        </div>
                    </a>
                  <!--  <a href="<?php // echo base_url('rejected-videos')?>">
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <div class="dashboard-div-wrapper bk-clr-three">
                                <i  class="fa fa-ban dashboard-div-icon" ></i>
                                <h5>Rejected Videos : <span style="font-weight: bold;font-size: x-large;"><?php // echo $rejected;?></span></h5>
                            </div>
                        </div>
                    </a> -->
                </div>
              <!--  <div class="row">
                    <div class="col-md-6 col-sm-4 col-xs-6">
                        <div class="dashboard-div-wrapper bk-clr-four">
                            <i  class="fa fa-money dashboard-div-icon" ></i>
                            <h5> $<?php echo number_format($social_earning,2);?> Social Earning </h5>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="dashboard-div-wrapper bk-clr-five">
                            <i  class="fa fa-money dashboard-div-icon" ></i>
                            <h5>$<?php echo number_format($buying_earning,2);?> Buying Earning </h5>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="dashboard-div-wrapper bk-clr-six">
                            <i  class="fa fa-money dashboard-div-icon" ></i>
                            <h5>$<?php echo number_format($social_earning+$buying_earning,2);?> Total Earning </h5>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</div>