<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 3/26/2018
 * Time: 2:27 PM
 */?>
<div class="content-wrapper">
    <div class="container">
        <h2 class="section-title"><?php echo $profile_nav;?></h2><!-- /.section-title -->
        <table class="table">
            <thead>
            <tr>
                <th  class="col-md-6"><div style = width:100%;padding-left:50px;">Month</div></th>
                <th  class="col-md-3"><div style = width:100%;padding-left:30px;">Licensing Earning</div></th>
                <th  class="col-md-3" ><div style = width:100%;padding-left:20px;">Social Earning</div></th>
            </tr>
            </thead>
        </table>
        <div class="accordion">
            <?php
                $result = getEarningsMonthly($client_id);
                ?>
                <div class="row">
                    <div  class="col-md-6"><?php
                        $date = date('F, Y');
                        echo $date;

                        ?></div>
                    <div  class="col-md-3">$<?php if(!empty($result['this_month_licensing_earning'] && isset($result['this_month_licensing_earning']))){echo  $result['this_month_licensing_earning'];} else{echo 0 ;}?></div>
                    <div  class="col-md-3">$<?php if(!empty($result['this_month_social_earning']) && isset($result['this_month_social_earning'])){ echo $result['this_month_social_earning'] ;} else{ echo 0 ;}?></div>
                </div>
                <div class="row">
                    <div class="accordion">

                        <?php  $videos = getVideos($date,$client_id);
                        foreach ($videos as $video){

                            ?>
                            <div class="row">
                                <div  class="col-md-6"><?php if(!empty($video['title'] && isset($video['title']))) {echo $video['title'];} else {}?></div>
                                <div  class="col-md-3">$<?php if(!empty($video['license'] && isset($video['license']))){echo  $video['license'];} else{echo 0 ;}?></div>
                                <div  class="col-md-3">$<?php if(!empty($video['socail']) && isset($video['socail'])){ echo $video['socail'] ;} else{ echo 0 ;}?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6" style="padding-left: 180px;"><h4>Licensing Earnings</h4></div>
                                <div class="col-md-6" style="padding-left: 180px;"><h4>Social Earnings</h4></div>
                                <div class="col-md-6">
                                    <?php
                                    $licensing = getLicensingDetail($date,$video['id']);
                                    foreach ($licensing as $license){
                                        ?>

                                        <div class="col-md-6" style="padding-left: 100px;"><h6><?php if(!empty($license['full_name']) && isset($license['full_name'])){ echo $license['full_name'];}?></h6></div>
                                        <div class="col-md-6" style="padding-left: 100px;"><h6>$<?php if(!empty($license['earning_amount']) && isset($license['earning_amount'])){echo $license['earning_amount'];} else {echo 0;}?></h6></div>
                                    <?php } ?>
                                </div>
                                <div class="col-md-6" >
                                    <?php
                                    $social = getSocialDetail($date,$video['id']);
                                    foreach ($social as $ss){
                                        ?>

                                        <div class="col-md-6" style="padding-left: 100px;"><h6><?php if(!empty($ss['sources']) && isset($ss['sources'])){  echo $ss['sources'];}?></h6></div>
                                        <div class="col-md-6" style="padding-left: 100px;"><h6>$<?php if(!empty($ss['earning_amount']) && isset($ss['earning_amount'])){echo $ss['earning_amount'];} else {echo 0;}?></h6></div>

                                    <?php }     ?>

                                </div>



                            </div>
                        <?php }?>
                    </div>
                </div>
        </div>
    </div>
</div>
