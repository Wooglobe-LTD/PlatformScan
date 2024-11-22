<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 3/8/2018
 * Time: 3:43 PM
 */?>

<div class="content-wrapper">
    <div class="container">

        <div class="row">
           
            <div class="col-md-3 col-xs-12">
                <?php include("common_files/dashboard-sidenav.php");?>
            </div>

            <div class="col-md-9 col-xs-12  dashboard">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <section class="no-anch">
                        <table><tr><td>

                        <h3>Account Summary djfgdsgfj</h3>
                        <div class="eq-hgt">
	                        <h5>Amount Accumulated Toward Next Payment </h5>
	                        <h2>$<?php if(empty($next_payment)) {echo 0;} else {echo $next_payment;}?></h2>
                        </div>
                        <hr>
                        <h5>Lifetime Paid</h5>
                        <h2>$<?php if(empty($paid)) {echo 0;} else {echo $paid;}?></h2>
                        <a class="dash-inner-btn" href="<?php echo base_url('account-summary');?>">
                        View Payment History</a>

                        </td></tr></table>
                    </section>

                    <section class="no-anch">
                    
                        <table><tr><td>
                        <div class="bk-clr-two">
                            <img src="<?php echo $image;?>approved_videos.png" alt="Approved Videos">
                            <br/>
                            <br/>
                            <h5>Approved Videos : <span style="font-weight: bold;font-size: x-large;"><?php echo $deals;?></span>
                            </h5>
                        </div>    
                        <a class="dash-inner-btn" href="<?php echo base_url('approved-videos');?>">View Approved Videos</a>
                        </td></tr></table>
                    </section>

                    <section class="no-anch">
                        <table><tr><td>
                        <h3 style="margin-top:75px;">Your Videos : <?php echo $all_videos_count;?></h3>
                        <h5>Select your video for detail </h5>
                        <?php foreach ($videos_title as $title){?>
                        <span>
                            <a href="<?php echo base_url('account-summary')?>?video=<?php echo $title['slug']?>"><p class="fa fa-angle-right" style="float: right;margin-right: 10px;margin-top: 10px;"></p>
                                <h5 style="padding: 10px;"><?php echo $title['title'];?></h5>
                            </a>
                        </span>

                        <hr style="margin: 2%">
                        <?php }?>

                        <a class="dash-inner-btn" href="<?php echo base_url('account-summary')?>">View All Videos
                        </a>
                        </td></tr></table>
                    </section>

                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                   
                    <section class="no-anch">
                        <h3 style="margin-bottom: 0px">Earning Revenue</h3>
                        <div id="chartdiv"></div> 
                        <!-- <div class="chart-comp">
                            <div class="comp-name">
                                <div class="comp-name-sub">
                                    <span class="ad_rv"></span>
                                    <p>Add Revenue</p>
                                </div>
                                <div class="comp-name-sub">
                                    <span class="lis"></span>
                                    <p>Licensing</p>
                                </div>
                            </div>
                            <div class="comp-val">
                                <div class="comp-val-sub"></div>
                                <div class="comp-val-sub"></div>
                            </div>
                        </div> -->
                        <a class="dash-inner-btn" href="<?php echo base_url('earnings-breakdown');?>">View Earning Breakdown</a>      
                        <!-- <div id="container" style="min-width: 300px; height: 300px; max-width: 300px; margin: 0 auto"></div> -->
                        <!-- <table><tr><td>
                        <h3>Earning Revenue</h3>
                        <div class="pieID pie"></div>
                        <ul class="pieID legend">
                          <li>
                            <em style="color:cornflowerblue">Add Revenue</em>
                            <span><?php echo $ad_revenue;?></span>
                          </li>
                          <li>
                            <em style="color: #73b52d;">Licensing</em>
                            <span><?php echo $license_revenue;?></span>
                          </li>
                        </ul>

                        <a class="dash-inner-btn" href="<?php echo base_url('earnings-breakdown');?>">View Earning Breakdown</a>
                        </td></tr></table> -->
                    </section>

                    <section class="no-anch">
                        <table><tr><td>
                        <div class="bk-clr-one">
                            
                            <img src="<?php echo $image;?>pending.png" alt="Pending Approval" /><br/><br/>
                            <h5>Pending Approval : <span style="font-weight: bold;font-size: x-large;">
                                <?php echo $requests;?></span> 
                            </h5>
                        </div>
                        <a class="dash-inner-btn" href="<?php echo base_url('video-requests');?>">View Pending Approval</a>
                        </td></tr></table>
                    </section>
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                
                <section class="no-anch">
                    <table><tr><td>
                    <div class="bk-clr-three">
                        
                        <img src="<?php echo $image;?>acquired-video.png" alt="Pending Approval" /><br/><br/>
                            
                        <h5>Acquired Videos : <span style="font-weight: bold;font-size: x-large;"><?php echo $uploaded_videos;?></span></h5>
                    </div>    
                    <a class="dash-inner-btn" href="<?php echo base_url('acquired-videos')?>">
                    View Acquired Videos</a>
                    </td></tr></table>
                </section>
                
                </div>
            </div>




        </div>
    </div>
</div>
<script>
    var ad_revenue = '<?php echo $ad_revenue;?>';
    var license_revenue = '<?php echo $license_revenue;?>';
</script>