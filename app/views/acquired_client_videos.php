<?php
/**
 * Created by PhpStorm.
 * Date: 3/15/2018
 * Time: 3:15 PM
 */?>
<!-- MultiStep Form -->
<style>
    .parsley-errors-list li {
        font-size: 13px;
    }
    .btn-group{
        display: none !important;
    }
    .new-chosen{
        width: 100%;
        height: 40px;
        border: 1px solid #cfd8dc;
        border-radius: 0;
        box-shadow: none;
        color: #455a64;
        display: inline-block;
        font-family: 'Roboto';
        font-size: 14px;
        height: 40px;
        line-height: 40px;
        margin-bottom: 20px;
        padding: 8px 10px;
        text-align: left;
    }
  nav.navbar.navbar-default {
        display: none;
    }
    .left-toggle-button {
    border-radius: 20px 0 0 20px;
    }
    .right-toggle-button {
    border-radius: 0 20px 20px 0;
    }
     #video-upload-toggle-container{
        border-radius: 10px;
        margin-top: 20px;
        text-align: center;
        position: relative;
        font-size: 14px;
    }
    #video-upload-toggle-container .video-upload-toggle-button:nth-child(1) {
        text-align: center;
        color: black;
        padding: 9px 41px;
        display: inline-block;
        width: 50%;
        border: 1px solid #f4f2f2;
    }
    #video-upload-toggle-container #divider {
        font-weight: 400;
        position: absolute;
        left: 50%;
        -webkit-transform: translateX(-50%);
        transform: translateX(-50%);
        width: 29px;
        top: 13%;
        height: 29px;
        line-height: 27px;
        font-size: 12px;
        background-color: #FCFCFC;
        border: 1px solid #DFE1E3;
        border-radius: 15px;
        text-align: center;
    }
    #video-upload-toggle-container #video-link-button{
        text-align: center;
        color: black;
        padding: 9px 41px;
        display: inline-block;
        width: 50%;
        background-color: #FCFCFC;
        padding: 9px 25px;
        border: 1px solid #f4f2f2;
    }
.video-active {
    background-color: #f5544d !important;
    color: #fff !important;
}
input.iframe-social {
    display: none;
}
</style>
<div class="container">
<div class="row">

    <section class="form-elements style-2">
        <br>

            <div class="process-block01">

                <div class="up-head"><h4> Please Upload the Orignal Video File/Unedited.</h4></div>
                <div class="right-panel">
                    <form action="#" class="" id="acquired-client-video-form">
                        <!-- Hidden fields start  -->
                        <input type="hidden" value="<?php echo $videos['id'];?>" name="lead_id" id="lead_id">
                        <input type="hidden" value="<?php echo $slug;?>" name="slug" id="slug">
                        <input type="text" name="video_single_url" style="display: none;" value="<?php echo $videos['video_url'] ?>">
                        <div class="form-input" style="display: none;" id="video_urls"></div>
                        <input type="text" name="video_title" style="display: none;" value="<?php echo $videos['video_title'] ?>">
                        <input type="text" name="unique_key" style="display: none;" value="<?php echo $videos['unique_key'] ?>">
                        <input type="text" name="revenue_share" style="display: none;" value="<?php echo $videos['revenue_share'] ?>">
                        <!-- Hidden fields end  -->
                        
  <!-- Add Video start  -->
                            <div id="video-upload-toggle-container"><input type="button" id="video-upload-button" class="video-upload-toggle-button  left-toggle-button video-active"  value="Video Upload"><span id="divider">OR</span><input type="button" class="video-upload-toggle-button right-toggle-button" id="video-link-button" style="background-color: rgb(252, 252, 252); color: rgb(0, 0, 0);" value="Video Link / URL"></div>
                            <div id="video-div">
                            <input type="file" name="file" style="display: none;">
                                <div class="error" id="file_err"></div>
                            </div>
                            <!-- Add file link start  -->
                            <div id="link_add" class="form-input" style="display: none;position: relative;">
                                <label class="ele-lbl">I’ll share the original / raw video through Dropbox / OneDrive / Google Drive etc):<span class="mnd-lbl-str">*</span></label>
                                <input type="text" class="form-control" name="link_name" id="link_name" placeholder=""
                                    data-parsley-required-message="First Name field is required.">
                                <div class="error" id="link_name_err"></div>
                                <div class="tooltip-form" style="top:29px;">?
                                    <span class="tooltiptext">Please share original / raw video through Dropbox / OneDrive / Google Drive etc)</span>
                                </div>
                            </div>
                            <!-- Add file link end  -->
                            <span style="font-weight: bold;color: #444;float: left;padding-bottom: 10px;display: none;text-align: left;">VIDEO TITLE  :  <p style="display: inline;font-size: large;color: #444;text-transform: capitalize;"><?php echo $videos['video_title']?></p> </span>
                            <span  style="font-weight: bold;color: #444;float: left;padding-bottom: 10px;text-align: left;">Licensed file social media link</span>
                            <div class="social-lnks">
                                <?php
                                //This is a general function for generating an embed link of an FB/Vimeo/Youtube Video.
                                $finalUrl = '';
                                $iframe = '';
                                if(strpos($videos['video_url'], 'facebook.com/') !== false) {
                                    //it is FB video
                                    $finalUrl = 'https://www.facebook.com/plugins/video.php?href='.rawurlencode($videos['video_url']).'&show_text=1&width=200';
                                    $iframe = '<iframe  src = "'.$finalUrl.'"  frameborder="0" allowfullscreen style="height: 30vh; width:100%;"></iframe>';
                                }else if(strpos($videos['video_url'], 'vimeo.com/') !== false) {
                                    //it is Vimeo video
                                    $videoId = explode("vimeo.com/",$videos['video_url'])[1];
                                    if(strpos($videoId, '&') !== false){
                                        $videoId = explode("&",$videoId)[0];
                                    }
                                    $finalUrl = 'https://player.vimeo.com/video/'.$videoId;
                                    $iframe = '<iframe  src = "'.$finalUrl.'"  frameborder="0" allowfullscreen style="height: 30vh;width:100%;"></iframe>';

                                }else if(strpos($videos['video_url'], 'youtube.com/') !== false) {
                                    //it is Youtube video
                                    $videoId = explode("v=",$videos['video_url'])[1];
                                    if(strpos($videoId, '&') !== false){
                                        $videoId = explode("&",$videoId)[0];
                                    }
                                    $finalUrl = 'https://img.youtube.com/vi/'.$videoId.'/mqdefault.jpg';
                                    $iframe = $finalUrl;

                                }else if(strpos($videos['video_url'], 'youtu.be/') !== false){
                                    //it is Youtube video
                                    $videoId = explode("youtu.be/",$videos['video_url'])[1];
                                    if(strpos($videoId, '&') !== false){
                                        $videoId = explode("&",$videoId)[0];
                                    }
                                    $finalUrl = 'https://img.youtube.com/vi/'.$videoId.'/mqdefault.jpg';
                                    $iframe = $finalUrl;



                                }else{
                                    //echo $finalUrl;
                                }

                                ?>
                                <img  src="<?php echo $iframe;?>" alt="Video Featured Image">
                                <input class="iframe-social" type="text" name="img_url" value="<?php echo $iframe;?>">
                            </div>
                            <!-- Add Video end  -->
                        <div class="error" id="disabled-error" style="margin-bottom: 5px;display: none;"><strong>Note : </strong>Please wait for the file upload to complete</div>
                        <input type="submit" name="" class="btn" id="acquired-client-video-form-submit" name="video-contract-form-submit" value="Submit"/>
                        <?php  ?>
                        </form>
                </div>
            </div>
    </section>

</div>
</div>

<style>
    .chosen-container{
        width: 100% !important;
    }
    .not-active{
        display: none !important;
    }
</style>

<script>
var uid = '<?php echo $videos['unique_key'];?>';
</script>

