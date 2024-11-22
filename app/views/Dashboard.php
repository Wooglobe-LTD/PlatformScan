<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 3/8/2018
 * Time: 3:43 PM
 */?>
<style>
    a{
      color: unset;
    }
    a:hover {
        color: unset;
    }
    .dash_vid_listing {
        -webkit-box-shadow: 0px 0px 4px 0px rgba(0,0,0,0.20);
        -moz-box-shadow: 0px 0px 4px 0px rgba(0,0,0,0.20);
        box-shadow: 0px 0px 4px 0px rgba(0,0,0,0.20);
        padding: 6px 0px;
        margin: 8px 0px;
    }
    .new_desh {
        border: 1px solid #cfd8dc;
        padding: 0px;
        margin-bottom: 20px;
    }
    .vid_status {
        vertical-align: middle;
        padding: 0px 15px 0px 0px;
        text-align: right;
    }
    .dash_head{
        border-bottom: 1px solid #cfd8dc;
        padding: 8px 20px;
        font-size: 19px;
        color: #455a64;
        font-family: 'Poppins';
        font-weight: 700;
    }
    hr{
        margin-bottom: 10px;
        margin-top: 5px;
    }

    .legends ul{
        list-style: none;
    }
    .legends ul li {
        float: left;
        font-family: 'Poppins';
        padding: 0px 40px 5px 0px;
    }
    .legends{
        width: 100%;
        display: inline-block;
    }
    .thumb img{
        width: 100px;
        max-height: 60px;
    }
    span.glyphicon {
        font-size: 11px;
        color: white;
        width: 26px;
        border-radius: 100%;
        outline: none;
        text-align: center;
        height: 26px;
        margin-top: -3px;
        line-height: 25px;
        margin-right: 5px;
    }
    span.glyphicon-ok{
        border: 1px solid #3c923c;
        background: #3c923c;
    }
    span.glyphicon-remove {
        border: 1px solid #c34040;
        background: #c34040;

    }

    span.glyphicon-info{
        border: 1px solid #3c923c;
        background: #3c923c;
    }
    span.glyphicon-exclamation {
        background: #ababab;
        font-family: inherit;
        font-size: 18px;
        font-weight: bold;
        text-align: center;
        line-height: 26px !important;
        cursor: default;
    }
    .info_pen button {
        background: #f5544d;
        border: 1px solid #f5544d;
        padding: 5px 10px;
        font-family: 'Poppins';
        color: #ffffff;
        font-size: 12px;
        border-radius: 3px;
        font-weight: 500;
    }
    .vid-data span{
        margin: 0px 0px 0px 0px;
        font-size: 14px;
    }

    .info_pen{
        text-align: center;
        padding: 0px 0px;
    }
    .expand {
        position: absolute;
        bottom: 1px;
        color: #455a64;
        border-radius: 3px;
        outline: none;
        font-size: 14px;
        padding: 2px 0px;
        text-decoration: underline;
        right: 38px;
        margin-right: -18px;
        cursor: default;
    }
    .tltip {
        position: relative;
        display: inline-block;
        cursor: default;
    }
    .right{
        float: right;
    }
    .tltip .tltiptext {
        visibility: hidden;
        min-width: 270px;
        background-color: #455a64;
        color: #fff;
        text-align: center;
        border-radius: 3px;
        padding: 10px 10px;
        position: absolute;
        z-index: 1;
        top: 0px;
        right: 40px;
        margin-left: -60px;
        font-size: 14px;
        line-height: 1.3;
    }

    .tltip:hover .tltiptext {
        visibility: visible;
    }

    .resale table th{
        outline: none;
        padding: 10px 10px;
        text-align: center;
    }
    .resale table {
        width: 100%;
        font-family: 'Poppins';
        color: #455a64;
        font-size: 14px;
    }
    .resale table > tbody > tr{
        text-align: center;
        border-top: 1px solid #cfd8dc;
        border-bottom: 1px solid #cfd8dc;
        height: 45px;
        margin-top: 0px;
    }
    .info_pen button i{
        padding-left: 4px;
        font-size: 10px;
    }
    .term-list{
        width: 100%;
        display: inline-block;
    }
    .lic_select{
        display: block;
        margin-bottom: 12px;
    }
    .lic_select select{
        display: block;
        width: 100%;
        height: 35px;
        padding: 6px 6px;
        box-sizing: border-box;
        border: 1px solid #cfd8dc;
        font-family: 'Poppins';
        font-size: 14px;
        line-height: normal;
    }
    .vid-data{
        padding: 3px 10px 0px 0px;
    }
    .thumb i{
        font-size: 25px;
        color: #f5544d;
    }

    .side-nav ul li a {
        color: unset;
    }
    .side-nav ul li{
        color: #455a64;
    }
    .side-nav ul li.active{
        color: #f5544d;
    }
    @media (max-width:767px){
        .vid-data span {
            margin: 0px 0px 0px -17%;
        }
        .vid-data .right {
            float: left;
            width: 100%;
        }
    }
    @media (max-width:600px){
        .legends ul li {
            float: none;
            display: block;
        }
        .vid-data span {
            margin: 0px 0px 0px -10%;
        }

    }
    @media (max-width:480px){
        .resale{
            overflow: scroll;
        }
        .resale table{
            width: 600px;
        }
    }

</style>


<div class="content-wrapper">
    <div class="container">

        <div class="row">

            <div class="col-md-3 col-xs-12">
                <?php include("common_files/dashboard-sidenav.php");?>
            </div>

            <div class="col-md-9 col-xs-12">
                <div id="info_status" style="display: <?php if($page == 'dashboard'){ echo 'block';}else{ echo 'none';}?>;">
                    <div class="col-md-12 new_desh">
                        <div class="dash_head">
                            Dashboard
                        </div>

                        <div class="col-md-12" style="padding: 18px;">

                            <div class="legends">
                                <ul>
                                    <li>
                                        <span class="glyphicon glyphicon-ok"></span>
                                        All Steps completed</li>
                                    <li>
                                        <span class="glyphicon glyphicon-remove"></span>
                                        Steps pending</li>
                                    <li>
                                        <span class="glyphicon glyphicon-exclamation">!</span>
                                        Under Review
                                    </li>
                                </ul>

                            </div>

                            <hr/>

                            <div class="term-list expandible">
                                <?php foreach($videos->result() as $row){

                                    ?>
                                    <?php if($row->status == 6 || $row->status == 8 && ($row->published_yt == 1)){?>

                                            <div class="col-md-12 col-sm-12 col-xs-12 dash_vid_listing">
                                                <a href="<?php echo $url.'partner?video='.$row->slug;?>">
                                                    <div class="col-md-1 col-sm-1 col-xs-2 thumb">
                                                        <i class="fa fa-file-video-o" aria-hidden="true"></i>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 col-xs-5 vid-data">
                                                        <span><?php if(strlen($row->video_title) > 30){ echo substr($row->video_title,0,29).'...';}else{ echo $row->video_title; }?></span>
                                                        <span class="right"><?php echo $row->unique_key;?></span>
                                                    </div>
                                                </a>
                                                <div class="col-md-4 col-xs-4 col-xs-4 info_pen">
                                                    <button style="display: none;">Complete Remaining Steps <i class="fa fa-chevron-right" aria-hidden="true"></i></button>
                                                </div>
                                                <div class="col-md-1 col-sm-1 col-xs-1 vid_status">
                                                    <span class="glyphicon glyphicon-ok"></span>
                                                </div>


                                            </div>

                                    <?php }else if($row->status == 6 && ($row->published_yt == 0)){ ?>

                                            <div class="col-md-12 col-sm-12 col-xs-12 dash_vid_listing">
                                                <a href="javascript:void(0)">
                                                    <div class="col-md-1 col-sm-1 col-xs-2 thumb">
                                                        <i class="fa fa-file-video-o" aria-hidden="true"></i>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 col-xs-5 vid-data">
                                                        <span><?php if(strlen($row->video_title) > 30){ echo substr($row->video_title,0,29).'...';}else{ echo $row->video_title; }?></span>
                                                        <span class="right"><?php echo $row->unique_key;?></span>
                                                    </div>
                                                </a>
                                                <div class="col-md-4 col-xs-4 col-xs-4 info_pen">
                                                    <button style="display: none;">Complete Remaining Steps <i class="fa fa-chevron-right" aria-hidden="true"></i></button>
                                                </div>
                                                <div class="col-md-1 col-sm-1 col-xs-1 vid_status">
                                                    <div class="tltip">
                                                        <div class="tltiptext">All Steps are completed from you side</div>
                                                        <span class="glyphicon glyphicon-ok"></span>
                                                    </div>
                                                </div>

                                            </div>

                                        <?php }else if($row->status == 3 && $row->information_pending == 0){ ?>

                                            <div class="col-md-12 col-sm-12 col-xs-12 dash_vid_listing">
                                                <a href="<?php echo $url.'submit-video/'.$row->slug;?>">
                                                    <div class="col-md-1 col-sm-1 col-xs-2 thumb">
                                                        <i class="fa fa-file-video-o" aria-hidden="true"></i>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 col-xs-5 vid-data">
                                                        <span><?php if(strlen($row->video_title) > 30){ echo substr($row->video_title,0,29).'...';}else{ echo $row->video_title; }?></span>
                                                        <span class="right"><?php echo $row->unique_key;?></span>
                                                    </div>
                                                </a>
                                                <div class="col-md-4 col-sm-4 col-xs-4 info_pen">
                                                    <button onclick="window.location.href='<?php echo $url.'submit-video/'.$row->slug;?>'">Complete Remaining Steps <i class="fa fa-chevron-right" aria-hidden="true"></i></button>
                                                </div>
                                                <div class="col-md-1 col-sm-1 col-xs-1 vid_status">
                                                    <span class="glyphicon glyphicon-remove"></span>
                                                </div>

                                            </div>

                                        <?php }else if($row->status == 10){ ?>

                                            <div class="col-md-12 col-sm-12 col-xs-12 dash_vid_listing">
                                                <a href="<?php echo $row->video_url;?>" target="_blank">
                                                    <div class="col-md-1 col-sm-1 col-xs-2 thumb">
                                                        <i class="fa fa-file-video-o" aria-hidden="true"></i>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 col-xs-5 vid-data">
                                                        <span><?php if(strlen($row->video_title) > 30){ echo substr($row->video_title,0,29).'...';}else{ echo $row->video_title; }?></span>
                                                        <span class="right"><?php echo $row->unique_key;?></span>
                                                    </div>
                                                </a>
                                                <div class="col-md-4 col-sm-4 col-xs-4 info_pen">
                                                    <button style="display: none;">Complete Remaining Steps <i class="fa fa-chevron-right" aria-hidden="true"></i></button>
                                                </div>
                                                <div class="col-md-1 col-sm-1 col-xs-1 vid_status">
                                                    <div class="tltip">
                                                        <div class="tltiptext">The video is under review. We will get back in touch shortly "if" the video is approved by our team. Thanks.</div>
                                                        <span class="glyphicon glyphicon-exclamation">!</span>
                                                    </div>
                                                </div>

                                            </div>

                                        <?php }else if($row->status <= 1){ ?>

                                            <div class="col-md-12 col-sm-12 col-xs-12 dash_vid_listing">
                                                <a href="<?php echo $row->video_url;?>" target="_blank">
                                                    <div class="col-md-1 col-sm-1 col-xs-2 thumb">
                                                        <i class="fa fa-file-video-o" aria-hidden="true"></i>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 col-xs-5 vid-data">
                                                        <span><?php if(strlen($row->video_title) > 30){ echo substr($row->video_title,0,29).'...';}else{ echo $row->video_title; }?></span>
                                                        <span class="right"><?php echo $row->unique_key;?></span>
                                                    </div>
                                                </a>
                                                <div class="col-md-4 col-sm-4 col-xs-4 info_pen">
                                                    <button style="display: none;">Complete Remaining Steps <i class="fa fa-chevron-right" aria-hidden="true"></i></button>
                                                </div>
                                                <div class="col-md-1 col-sm-1 col-xs-1 vid_status">
                                                    <div class="tltip">
                                                        <div class="tltiptext">The video is under review. We will get back in touch shortly "if" the video is approved by our team. Thanks.</div>
                                                        <span class="glyphicon glyphicon-exclamation">!</span>
                                                    </div>
                                                </div>

                                            </div>

                                        <?php } ?>
                                <?php } ?>


                            </div>

                        </div>
                    </div>

                </div>



                <div id="resale_status" style="display: <?php if($page == 'license'){ echo 'block';}else{ echo 'none';}?>;">
                    <div class="col-md-12 new_desh">
                        <div class="dash_head">
                            Licensed/Resale Video
                        </div>

                        <div class="col-md-12 resale" style="padding: 18px;">

                            <div class="row">
                                <div class="col-md-8">

                                </div>
                                <div class="col-md-4">
                                    <div class="lic_select">
                                        <select name="earning-search" id="earning-search">
                                            <option value="">All Videos</option>
                                            <?php foreach ($earningsVideos->result() as $earning){?>
<<<<<<< HEAD
<<<<<<< HEAD
                                              <option value="<?php if($earning->slug){echo $earning->title;} ?>">
                                                  <?php if(strlen($earning->slug) > 30) {echo substr($earning->title,0,29).'...';} else {echo $earning->title;} ?>
=======
                                              <option <?php if($page == $earning->slug){ echo 'selected'; } ?> value="<?php echo $earning->slug; ?>">

                                                  <?php if(strlen($earning->title) > 30) {echo substr($earning->title,0,29).'...';} else {echo $earning->title;} ?>
>>>>>>> e8ff71f04f1c293bf394916e66349cd9599e270e
=======
                                              <option <?php if($slug == $earning->slug){ echo 'selected'; } ?> value="<?php echo $earning->slug; ?>">

                                                  <?php if(strlen($earning->title) > 25) {echo substr($earning->title,0,24).'...';} else {echo $earning->title;} ?>
>>>>>>> 192600ad2ba31689432b16ab9a601914774ea7cf
                                                </option>


                                            <?php } ?>

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <table>
                                <thead>
                                <tr>
                                    <th>Video Title</th>
                                    <th>JV #</th>
                                    <th>Licensee</th>
                                    <th>Income</th>
                                    <th>Revenue Share</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($earnings->result() as $earning){?>
                                <tr>
                                    <td><?php if(strlen($earning->title) > 25) {echo substr($earning->title,0,24).'...';} else {echo $earning->title;} ?></td>
                                    <?php if($earning->earning_type_id == 2){ ?>
                                    <td><?php echo $earning->lkey.'-'.$earning->ukey;?></td>
                                    <?php }else{ ?>
                                        <td>&nbsp;</td>
                                    <?php }?>
                                    <?php if($earning->earning_type_id == 1){ ?>

                                        <td><?php echo ucfirst($earning->earning_type).' ( '.ucwords(str_replace('_',' ',$earning->sources)).' )';?></td>
                                    <?php }else{?>
                                        <td><?php echo ucfirst($earning->earning_type);?></td>
                                    <?php } ?>
                                    <td>$<?php echo $earning->earning_amount;?></td>
                                    <td><?php echo $earning->revenue_share;?>%</td>
                                </tr>
                                <?php } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>




        </div>
    </div>
</div>
<script>
    var ad_revenue = '<?php //echo $ad_revenue;?>';
    var license_revenue = '<?php //echo $license_revenue;?>';
</script>
