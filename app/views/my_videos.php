<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 3/21/2018
 * Time: 12:22 PM
 */?>
<section class="author-page-contents">
    <div class="section-padding">
        <div class="container">

            <div class="row">

                <div class="col-md-3 col-xs-12">
                    <?php include("common_files/dashboard-sidenav.php");?>
                </div>
                <?php $this->load->view('common_files/profile_nav');?>
                <div class="col-md-9 col-xs-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="jumbotron" style="padding: 20px 20px; !important;height: 400px;">
                            <h4>Account Summary</h4>
                            <div class="row">

                                <div class="col-md-12">
                                    <h6>Amount Accumulated Towards Next Payment</h6>
                                </div>
                                <div class="col-md-12">
                                    <h2 style="margin-left: 0px;"><?php echo $currency;?><?php if(empty($next_payment)) {echo 0;} else {echo $next_payment;}?></h2>
                                </div>

                            </div>
                            <hr>
                            <div class="row">

                                <div class="col-md-12">
                                    <h6>Lifetime Paid</h6>
                                </div>
                                <div class="col-md-12">
                                    <h2 style="margin-left: 0px;"><?php echo $currency;?><?php if(empty($paid)) {echo 0;} else {echo $paid;}?></h2>
                                </div>

                            </div>

                            <div class="row">
                                <div class="submit-input" style="text-align: center">
                                    <a href="<?php echo base_url('payment-history');?>" class="btn" style="width: 50% !important;font-size: 12px;padding: 0 27px;">View Payment History</a>
                                </div>
                                <h6>Question about your payment history? Browse our <a target="_blank" href="<?php echo base_url('faq');?>">FAQ's</a> or send us a message</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="jumbotron" style="padding: 20px 20px; !important;height: 400px;">
                            <ul class="list-group" style="height: 252px;">
                                <h3 style="font-size:30px; ">Your Videos</h3>
                                <h5 style="color: #526a757d;margin-left: 4%;">Select your videos for details </h5><br>
                                <?php foreach ($my_video as $video){?>
                                    <li class="" style="list-style: none;margin-left: 4%;"><!--<a href="#">Home</a>--> <?php echo $video['title'];?> </li>
                                    <div style="width: 85%;height: 8px;border-bottom: 1px solid #526a757d;position: absolute;"></div>

                                <?php } ?>
                            </ul>
                            <div class="row">
                                <div class="submit-input" style="text-align: center">
                                    <a href="<?php echo base_url('earnings-breakdown');?>" class="btn" style="width: 60% !important;font-size: 12px;padding: 0 27px;">View Earning Breakdown</a>
                                </div>
                            </div>
                        </div>

                        <!--<table id="datatable" class="display" cellspacing="0" width="100%">
                                <thead class="thead-dark">
                                <tr>
                                    <th>Date</th>
                                    <th>Video Title</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                        /*                                foreach ($total_revenue as $revenue){
                                                            echo '<tr>';

                                                            echo '<td>';
                                                            echo $revenue['earning_date'];
                                                            echo '</td>';

                                                            echo '<td>';
                                                            echo $revenue['title'];
                                                            echo '</td>';

                                                            echo '<td>';
                                                            if($revenue['earning_type_id'] == 1){
                                                                echo 'Ad Revenue';
                                                            }
                                                            else{
                                                                echo 'Licensing';
                                                            }
                                                            echo '</td>';

                                                            echo '<td>';
                                                            echo "$".$revenue['earning_amount'];
                                                            echo '</td>';

                                                            echo '<td>';
                                                            if($revenue['paid'] == 1){
                                                                echo 'Paid';
                                                            }
                                                            else{
                                                                echo 'Unpaid';
                                                            }
                                                            echo '</td>';

                                                            echo '</tr>';
                                                        }
                                                        */?>
                                </tbody>
                            </table>-->
                    </div>
                    <?php if(empty($next_payment)){ ?>
                    <div style="margin-left: 6%;"><P style="color: Red; font-size: 16px; text-align: center;">It looks like your video hasn't generated any incom yet but we are actively promoting it to our media partners. We will update the dashboard as soon as the video generates any earnings.</P></div>
                   </div>
                    <?php } ?>
                </div>
                <!--<div class="col-md-9 col-xs-12">


                    <table id="datatable" class="display" cellspacing="0" width="100%">
                        <thead class="thead-dark">
                        <tr>
                            <th>Video Title</th>
                            <!--<th>Video Title</th>
                            <th>Video URL</th>
                            <th>Submitted Date</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
/*                        foreach ($my_video as $video){

                            echo '<tr>';

                            echo '<td>';
                            echo $video['title'];
                            echo '</td>';

                            //echo '<td>';
                            //echo $revenue['title'];
                            //echo '</td>';

                            echo '<td>';

                            echo $video['url'];

                            echo '</td>';

                            echo '<td>';
                            echo $video['created_at'];
                            echo '</td>';



                            echo '</tr>';
                        }
                        */?>
                        </tbody>
                    </table>
                </div>-->

            </div>

        </div>
    </div>
</section>
<script>
    var ad_revenue = 0;
    var license_revenue = 0;
</script>