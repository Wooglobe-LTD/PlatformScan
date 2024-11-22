<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 4/2/2018
 * Time: 2:43 PM
 */
?>
<style>
    .custom-item{
        border-top: none !important;
        border-left:none !important;
        border-right:none !important;
        border-bottom: 1px solid #ddd !important;
        margin-bottom: 10px !important;
        background-color: unset; !important;
        font-weight: bold !important;
    }
</style>
<section class="author-page-contents">
    <div class="section-padding">
        <div class="container">
            
            <div class="row">
                <div class="col-md-3 col-xs-12">
                    <?php include("common_files/dashboard-sidenav.php");?>
                </div>
                <div class="col-md-9 col-xs-12">
                    <!--<div class="form-element">
                        <select class="form-control" id="search" style="float: right; width: 29%;">
                            <option value=""
                                <?php /*$slug = $this->input->get('video');
                                if(empty($slug)){
                                    echo 'selected="selected"';
                                }
                                */?>
                            >Videos
                            </option>
                            <?php /*foreach($videos_title as $title){ */?>
                                <option value="<?php /*echo $title['slug'];*/?>" <?php
/*                                $slug = $this->input->get('video');

                                if($slug == $title['slug']) {echo 'selected="selected"';}*/?> >
                                    <?php /*echo $title['title']*/?>
                                </option>
                            <?php /*} */?>
                        </select>
                    </div>-->
                    <?php $this->load->view('common_files/profile_nav');?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="jumbotron" style="padding: 20px 20px; !important;height: 400px;">
                                <h4>Account Summary</h4>
                                <div class="row">

                                    <div class="col-md-12">
                                        <h6>Amount Accumulated Towards Next Payment <i class="fa fa-question-circle fa-lg" title="This amount is only an estimate. The amount you actually receive may be different. Differences are generally due to changes in the foreign exchange rate by the time of payment, and any applicable withholding tax, which will be applied when the payment is made."></i></h6>
                                    </div>
                                    <div class="col-md-12">
                                        <h2 style="margin-left: 0px;"><?php echo $currency;?><?php if(empty($next_payment)) {echo 0;} else {echo number_format($next_payment,2);}?></h2>
                                    </div>

                                </div>
                                <hr>
                                <div class="row">

                                    <div class="col-md-12">
                                        <h6>Lifetime Paid</h6>
                                    </div>
                                    <div class="col-md-12">
                                        <h2 style="margin-left: 0px;"><?php echo "$";?><?php if(empty($paid)) {echo 0;} else {echo number_format($paid,2);}?></h2>
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
                                    <?php

                                    foreach ($total_revenue as $revenue){ ?>
                                        <li class="list-group-item custom-item"><a href="<?php echo base_url('earnings-breakdown?video='.$revenue['url']);?>"> <?php echo $revenue['title'];?></a></li>
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
                        <?php if(empty($next_payment) && (empty($paid) || $paid == 0)){ ?>
                        <div style="padding: 0px 10px;"><P style="color: Red; font-size: 16px; text-align: center;">It looks like your video hasn't generated any income, yet but we are actively promoting it to our media partners. We will update the dashboard as soon as the video generates any earnings.</P></div>
                    </div>
                <?php } ?>
                    </div>



                </div>
            </div>
        </div>
    </div>
</section>
<script>
    var ad_revenue = 0;
    var license_revenue = 0;
</script>