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
</style>
<div class="container">
<div class="row">

    <section class="form-elements style-2">
        <br>

            <div class="process-block01">

                <div class="up-head"><h4> Please Upload the Orignal Video File/Unedited.</h4></div>
                <div class="right-panel">
                    <!-- Form start -->
                    <form action="#" class="" id="video-submit-contract-form">
                        <!-- first name start  -->
                        <div class="form-input">
                            <label class="ele-lbl">First Name:<span class="mnd-lbl-str">*</span></label>

                                <input type="text" class="form-control" name="first_name" id="first_name" placeholder=""
                                       data-parsley-required-message="First Name field is required."
                                       pattern="[a-zA-Z0-9\s]+"
                                       data-parsley-pattern-message="Only alphabets are allowed."
                                       required
                                       value="<?php if(isset($videos['first_name'])){ echo $videos['first_name'];}?>"
                                       tabindex="1" autofocus>
                            <div class="error" id="first_name_err"></div>
                        </div>
                        <!-- first name end  -->

                        <!-- last name start  -->
                        <div class="form-input">
                            <label class="ele-lbl">Last Name:<span class="mnd-lbl-str">*</span></label>
                                <input type="text" class="form-control" name="last_name" id="last_name" placeholder=""
                                       data-parsley-required-message="Last Name field is required."
                                       pattern="[a-zA-Z0-9\s]+"
                                       data-parsley-pattern-message="Only alphabets are allowed."
                                       required
                                       value="<?php if(isset($videos['last_name'])){ echo $videos['last_name'];}?>"
                                       tabindex="2">
                            <div class="error" id="last_name_err"></div>
                        </div>
                        <!-- last name end  -->

                        <!-- email start  -->
                        <div class="form-input">
                            <label class="ele-lbl">Email Address:<span class="mnd-lbl-str">*</span></label>
                                <input type="email" class="form-control" name="email" id="email" placeholder=""
                                       data-parsley-required-message="Email Address field is required."
                                       data-parsley-type-message="Please enter the valid email address."
                                       required
                                       value="<?php if(isset($videos['email'])){ echo $videos['email'];}?>"
                                       tabindex="3" readonly
                                >
                            <div class="error" id="email_err"></div>
                        </div>
                        <!-- email end  -->

                        <!-- phone start  -->
                        <div class="form-input">
                            <label class="ele-lbl">Phone Number:<span class="mnd-lbl-str">*</span></label>
                                <input type="text" class="form-control" name="phone" id="phone" placeholder=""
                                       data-parsley-required-message="Phone Number field is required."
                                       pattern="[0-9+\s]+"
                                       data-parsley-pattern-message="Only plus sign and number are allowed."
                                       maxlength="13"
                                       required
                                       value="<?php if(isset($users['mobile'])){ echo $users['mobile'];}?>"
                                       tabindex="4" >
                            <div class="error" id="phone_err"></div>
                        </div>
                        <!-- phone end  -->

                        <!-- age start  -->
                        <div class="form-input">
                            <label class="ele-lbl">Age:<span class="mnd-lbl-str">*</span></label>
                            <input type="text" class="form-control" name="age" id="age" placeholder=""
                                   data-parsley-required-message="Age Number field is required."
                                   pattern="[0-9+\s]+"
                                   data-parsley-pattern-message="Only plus sign and number are allowed."
                                   maxlength="13"
                                   required
                                   value="<?php if(isset($users['age'])){ echo $users['age'];}?>"
                                   tabindex="4" >
                            <div class="error" id="age_err"></div>
                        </div>
                        <!-- age end  -->

                        <!-- zip start  -->
                        <div class="form-input">
                            <label class="ele-lbl">Zip Code:<span class="mnd-lbl-str">*</span></label>
                            <input type="text" class="form-control" name="zip" id="zip" placeholder=""
                                   data-parsley-required-message="Zip field is required."
                                   pattern="[0-9+\s]+"
                                   data-parsley-pattern-message="Only plus sign and number are allowed."
                                   maxlength="13"
                                   required
                                   value="<?php if(isset($users['zip_code'])){ echo $users['zip_code'];}?>"
                                   tabindex="4" >
                            <div class="error" id="zip_err"></div>
                        </div>
                        <!-- zip end  -->

                        <!-- address start  -->
                        <div class="form-input">
                            <label class="ele-lbl">Address:<span class="mnd-lbl-str">*</span></label>
                            <input type="text" class="form-control" name="address" id="address" placeholder=""
                                   data-parsley-required-message="City field is required."
                                   pattern="[a-zA-Z0-9\s]+"
                                   data-parsley-pattern-message="Only alphabet and number are allowed."
                                   required
                                   value="<?php if(isset($users['address'])){ echo $users['address'];}?>"
                                   tabindex="2">
                            <div class="error" id="address_err"></div>
                        </div>
                        <!-- address end  -->

                        <!-- city start  -->
                        <div class="form-input">
                            <label class="ele-lbl">City:<span class="mnd-lbl-str">*</span></label>
                            <input type="text" class="form-control" name="city" id="city" placeholder=""
                                   data-parsley-required-message="City field is required."
                                   pattern="[a-zA-Z\s]+"
                                   data-parsley-pattern-message="Only alphabets are allowed."
                                   required
                                   value="<?php if(isset($users['city_id'])){ echo $users['city_id'];}?>"
                                   tabindex="2">
                            <div class="error" id="city_err"></div>
                        </div>
                        <!-- city end  -->

                        <!-- state start  -->
                        <div class="form-input">
                            <label class="ele-lbl">State:<span class="mnd-lbl-str">*</span></label>
                            <input type="text" class="form-control" name="state" id="state" placeholder=""
                                   data-parsley-required-message="City field is required."
                                   pattern="[a-zA-Z\s]+"
                                   data-parsley-pattern-message="Only alphabets are allowed."
                                   required
                                   value="<?php if(isset($users['state_id'])){ echo $users['state_id'];}?>"
                                   tabindex="2">
                            <div class="error" id="state_err"></div>
                        </div>
                        <!-- state end  -->

                        <!-- country start  -->
                        <div class="form-input">
                            <label class="ele-lbl">Country:<span class="mnd-lbl-str">*</span></label>
                            <input type="text" class="form-control" name="country" id="country" placeholder=""
                                   data-parsley-required-message="City field is required."
                                   pattern="[a-zA-Z\s]+"
                                   data-parsley-pattern-message="Only alphabets are allowed."
                                   required
                                   value="<?php if(isset($users['country_code'])){ echo $users['country_code'];}?>"
                                   tabindex="2">
                            <div class="error" id="country_err"></div>
                        </div>
                        <!-- country end  -->

                        <!-- paypal start  -->
                        <div class="checkbox">
                            <input name="payapal_check" type="checkbox" id="payapal_check" data-md-icheck class="payapal_check" value="">
                           Pay Pal information sent Later
                        </div>
                        <div class="form-input payapal_info">
                            <label class="ele-lbl">Paypal email:<span class="mnd-lbl-str">*</span></label>
                            <input type="email" class="form-control" name="paypal" id="paypal" placeholder=""
                                   data-parsley-required-message="Email Address field is required."
                                   data-parsley-type-message="Please enter the valid email address."
                                   value="<?php if(isset($users['paypal_email'])){ echo $users['paypal_email'];}?>"
                                   tabindex="2">
                            <div class="error" id="paypal_err"></div>
                        </div>
                        <!-- paypal end  -->

                        <!-- Question When Taken start  -->
                        <div class="form-input">
                            <label class="ele-lbl">Where was this taken(Country/City)?<span class="mnd-lbl-str">*</span></label>
                            <input type="text" class="form-control" name="question1" id="question1" placeholder=""
                                   data-parsley-required-message="This field is Mandatory."
                                   pattern="[a-zA-Z0-9\s]+"
                                   data-parsley-pattern-message="Only alphabet and number are allowed."
                                   required
                                   value="<?php if(isset($video['question_video_taken'])){ echo $video['question_video_taken'];}?>"
                                   tabindex="5">
                            <div class="error" id="question1_err"></div>
                        </div>
                        <!-- Question When Taken end  -->

                        <!-- Question Taken start  -->
                        <div class="form-input">
                            <label class="ele-lbl">When was this video Taken?<span class="mnd-lbl-str">*</span></label>
                            <div class="relative">
                                <div class="pick-ico"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                                <input type="text" class="form-control" name="question3" id="question3" placeholder=""
                                       data-parsley-required-message="This field is Mandatory."
                                       data-maxdate="today",
                                       value="<?php if(isset($video['question_when_video_taken'])){ echo date('d/m/Y',strtotime($video['question_when_video_taken']));}?>"
                                       required tabindex="6"><!-- question3 -->
                            </div>
                            <div class="error" id="question3_err"></div>
                        </div>
                        <!-- Question Taken end  -->

                        <!-- share story start  -->
                        <div class="form-input" style="position: relative;">
                            <div class="tooltip-form" style="top:29px;">?
                                <span class="tooltiptext">Any information such as names, locations, or any other interesting elements are important to us so that we can pitch your clip with as much information as possible. This way our clients are able to create something that allows your video to be viewed as a story and not just a clip!</span>
                            </div>
                            <label class="ele-lbl">Share your story:<span class="mnd-lbl-str">*</span></label>
                            <textarea type="text" class="form-control vid-sub-txt" name="question4" id="question4" placeholder=""
                                      data-parsley-required-message="This field is Mandatory." style="margin: 0px 0px 20px; height: 165px; width: 100%;"
                                      required tabindex="4"><?php if(isset($video['question_video_context'])){ echo $video['question_video_context'];}?></textarea>
                            <div class="error" id="question4_err"></div>
                        </div>
                        <!-- share story end  -->

                        <!-- Hidden fields start  -->
                        <input type="hidden" value="<?php echo $videos['id'];?>" name="lead_id" id="lead_id">
                        <input type="hidden" value="<?php echo $slug;?>" name="slug" id="slug">
                        <input type="text" name="video_single_url" style="display: none;" value="<?php echo $videos['video_url'] ?>">
                        <div class="form-input" style="display: none;" id="video_urls"></div>
                        <input type="text" name="video_title" style="display: none;" value="<?php echo $videos['video_title'] ?>">
                        <input type="text" name="unique_key" style="display: none;" value="<?php echo $videos['unique_key'] ?>">
                        <input type="text" name="revenue_share" style="display: none;" value="<?php echo $videos['revenue_share'] ?>">
                        <!-- Hidden fields end  -->


                        <!-- File check box start  -->
                        <div class="checkbox">
                            <input name="file_link" type="checkbox" id="file_link" data-md-icheck class="file_link" value="">
                            Add Original File downloadable Link
                        </div>
                        <!-- File check box end  -->
                        <!-- Add file link start  -->
                        <div id="link_add" class="form-input" style="display: none;position: relative;">
                            <label class="ele-lbl">Add video downloadable link here:<span class="mnd-lbl-str">*</span></label>
                            <input type="text" class="form-control" name="link_name" id="link_name" placeholder=""
                                   data-parsley-required-message="First Name field is required.">
                            <div class="error" id="link_name_err"></div>
                            <div class="tooltip-form" style="top:29px;">?
                                <span class="tooltiptext">Please add google drive or one drive link here</span>
                            </div>
                        </div>
                        <!-- Add file link end  -->

                        <!-- Add Video start  -->
                        <div id="video-div">
                           <input type="file" name="file" style="display: none;">
                            <div class="error" id="file_err"></div>
                        </div>
                        <span style="font-weight: bold;color: #444;float: left;padding-bottom: 10px;display: none;text-align: left;">VIDEO TITLE  :  <p style="display: inline;font-size: large;color: #444;text-transform: capitalize;"><?php echo $videos['video_title']?></p> </span>
                        <span  style="font-weight: bold;color: #444;float: left;padding-bottom: 10px;text-align: left;">Licensed file social media link</span>
                        <div>
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
                            <img  src="<?php echo $iframe;?>" style="width: 100%;padding-bottom: 10px;" alt="Video Featured Image">
                            <input type="text" name="img_url" value="<?php echo $iframe;?>" style="display: none;">
                        </div>
                        <!-- Add Video end  -->

                        <!-- Add Signature start -->
                        <label class="ele-lbl">Draw your signature here:<span class="mnd-lbl-str">*</span></label>
                        <div id="signature"></div>
                        <a href="#nogo" class="btn btn--secondary clear-button">Clear</a>
                        <!-- Add Signature end -->

                        <!-- Terms Check box start -->
                        <div class="checkbox">
                            <input name="terms_check" type="checkbox" id="terms_check" data-md-icheck="" class="file_link" value="1" required  data-parsley-required-message="Please confirm to proceed.">
                            By signing above, I agree that all information submitted through this form is true and accurate, and I understand that  I am signing an exclusive contract with WooGlobe. I have reviewed and agree to the <a href="<?php echo $url; ?>/assets/img/terms_signed.pdf" target="_blank">Licensing Agreement, <a href="<?php echo $url;?>terms_of_use" target="_blank">Terms of use</a>, <a href="<?php echo $url;?>privacy" target="_blank"> Privacy Policy</a> and <a href="<?php echo $url;?>appearancerelease" target="_blank">Appearance Release</a><span class="mnd-lbl-str">*</span>
                        </div>
                        <!-- Terms Check box end -->

                        <!-- submit start -->
                        <input type="submit" name="" class="btn" id="video-contract-form-submit" name="video-contract-form-submit" value="Submit"/>
                        <!-- submit end -->
                        <?php  ?>
                    </form>
                    <!-- Form end -->
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
var state_id = '<?php echo $users['state_id'];?>';
var city_id = '<?php echo $users['city_id'];?>';
var uid = '<?php echo $videos['unique_key'];?>';
</script>

