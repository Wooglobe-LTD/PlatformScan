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
/*                                    $slug = $this->input->get('video');

                                    if($slug == $title['slug']) {echo 'selected="selected"';}*/?> >
                                        <?php /*echo $title['title']*/?>
                                </option>
                            <?php /*} */?>
                        </select>
                    </div>-->
                <?php $this->load->view('common_files/profile_nav');?>
                <div class="jumbotron" style="padding: 20px 20px; !important;">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-12">
                                <h6>Amount Accumulated Towards Next Payment</h6>
                            </div>
                            <div class="col-md-12">
                                <h2 style="margin-left: 0px;"><?php echo $currency;?><?php if(empty($next_payment)) {echo 0;} else {echo number_format($next_payment,2);}?></h2>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-12">
                                <h6>Lifetime Paid</h6>
                            </div>
                            <div class="col-md-12">
                                <h2 style="margin-left: 0px;"><?php echo $currency;?><?php if(empty($paid)) {echo 0;} else {echo number_format($paid,2);}?></h2>
                            </div>
                        </div>

                    </div>


                    <div class="row">

                        <h6>Question about your payment history? Browse our <a target="_blank" href="<?php echo base_url('faq');?>">FAQ's</a> or send us a message</h6>
                    </div>
                </div>

                <table id="datatable" class="display" cellspacing="0" width="100%">
                <thead class="thead-dark">
                <tr>
                    <th>Date</th>
                    <!--<th>Video Title</th>-->
                    <th>Payment Detail</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($ad_revenue as $revenue){
                        echo '<tr>';

                        echo '<td>';
                        echo date('Y-m-d',strtotime($revenue['updated_at']));
                        echo '</td>';

                        //echo '<td>';
                        //echo $revenue['title'];
                        //echo '</td>';

                        echo '<td>';

                        echo $revenue['payment_transaction_detail'];

                        echo '</td>';

                        echo '<td>';
                        echo $revenue['symbol'].$revenue['client_net_earning'];
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
                    ?>
                </tbody>
                </table>
                </div>
            
            </div>

        </div>
    </div>
</section>
<script>
    var ad_revenue = 0;
    var license_revenue = 0;
</script>