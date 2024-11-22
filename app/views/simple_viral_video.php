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
    .tooltip-form-share {
        position: absolute;
        display: inline-block;
        left: 116px;
        right: 0px;
        bottom: 15px;
        width: 20px;
        height: 20px;
        background-color: #353641;
        color: #fff;
        border-radius: 50%;
        line-height: 22px;
        font-size: 14px;
    }
    .tooltip-form-share .tooltiptext {
        visibility: hidden;
        width: 300px;
        background-color: #353641;
        color: #fff;
        text-align: center;
        font-size: 13px;
        font-weight: 200;
        border-radius: 2px;
        padding: 7px 0;
        left: 28px;
        position: absolute;
        z-index: 1;
        left: -304px;
        top: -6px;
        line-height: 1.6;
        padding: 15px;
    }
    .tooltip-form-share:hover .tooltiptext {
        visibility: visible;
    }
    nav.navbar.navbar-default {
        display: none;
    }

    #simple-video-submit-form .form-group {
        margin: 0;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
    }

    #simple-video-submit-form .form-input.w-50 {
        width: 48%;
    }

    .form-elements form .form-input.chk-bxs {
        margin-bottom: 0;
    }

    .form-elements form .form-input.chk-bxs .ele-lbl.w-100 {
        width: 100%;
        padding-top: 12px;
        padding-bottom: 5px;
    }

    .form-elements form .form-input.chk-bxs .form-input.w-50 {
        display: inline-flex;
        margin: 0;
    }

    .form-elements form .form-input.chk-bxs .form-input.w-50 label.ele-lbl {
        padding-left: 20px;
        background: url(../../assets/img/check-icon.png);
        background-size: 15px;
        background-repeat: no-repeat;
        background-position: left top;
        font-weight: 500;
    }

    #simple-video-submit-form .fileuploader-theme-dragdrop .fileuploader-input {
        border: none;
        border-radius: 6px;
        background: transparent;
        padding: 10px;
    }

    #simple-video-submit-form .fileuploader {
        background: none;
        border: 2px dashed #f5544d;
    }

    #simple-video-submit-form .social-lnks img {
        width: 100%;
        padding-bottom: 10px;
    }

    #simple-video-submit-form .social-lnks .iframe-social {
        display: none;
    }

    #simple-video-submit-form  .fileuploader-input .fileuploader-input-button {
        background: #353641;
        border: 1px solid #353641;
        border-radius: 0;
        color: #b0bec5;
    }

    #simple-video-submit-form  .fileuploader-input .fileuploader-input-button:hover {
        color: #f5544d;
    }

    #simple-video-submit-form a.btn.btn--secondary.clear-button {
        background: #353641;
        border: 1px solid #353641;
        border-radius: 0;
        color: #b0bec5;
        text-decoration: none;
        padding: 6px 20px;
        font-style: normal;
        margin: 0;
    }

    #simple-video-submit-form a.btn.btn--secondary.clear-button:hover {
        color: #f5544d;
    }

    #simple-video-submit-form canvas.jSignature {
        background: #eceff1;
    }

    .form-elements form .form-input:nth-child(even) {
        float: none;
    }

    #video-submit-frm .card-header {
        display: block;
        background: #eceff1;
    }

    #video-submit-frm .card-header h6 {
        line-height: 50px;
        font-size: 16px;
        cursor: pointer;
        text-align: left;
        margin-left: 15px;
        -webkit-transition: all 0.25s ease;
        -moz-transition: all 0.25s ease;
        -ms-transition: all 0.25s ease;
        -o-transition: all 0.25s ease;
        transition: all 0.25s ease;
    }

    #video-submit-frm .card-header h6:after {
        content: '\f106';
        font-family: fontawesome;
        position: relative;
        display: inline-block;
        float: right;
        right: 20px;
        transform: rotate(0deg);
        font-weight: 400;
        font-size: 24px;
        -webkit-transition: all 0.25s ease;
        -moz-transition: all 0.25s ease;
        -ms-transition: all 0.25s ease;
        -o-transition: all 0.25s ease;
        transition: all 0.25s ease;
        top: -2px;
        line-height: 50px;
    }

    #video-submit-frm .card-header h6[aria-expanded="true"]:after {
        transform: rotate(-180deg);
        top: 2px;
    }

    #video-submit-frm .card-header h6[aria-expanded="true"] {
        color: #f5544d;
    }

    #video-submit-frm .card-header h6:hover, .age-btn:hover {
        color: #f5544d;
    }
    .mnd-lbl-str {
        font-size: 14px;
    }
    .age-btn{
        background: #353641;
        border: 1px solid #353641;
        border-radius: 0;
        color: #b0bec5;
        cursor: pointer;
        font-family: 'Poppins';
        font-size: 16px;
        transition: 0.3s ease;
        font-weight: 700;
        font-style: normal;
        line-height: 40px;
        margin-top: 15px;
        max-height: 60px;
        max-width: 100%;
        padding: 5px 40px;
        text-decoration: none;
        text-transform: uppercase;
        position: inherit;
        width: auto !important;
        display: inline-block;
    }
    /* input.age-btn.launch-modal {
      line-height: 26px !important;
    } */
    .pill-container {
        text-align: left;
        line-height: 25px;
        display: block;
        margin: 5px 0px;
    }
    .yes-button, .no-button {
        height: 36px;
        text-align: center;
        display: inline-block;
        width: 58px !important;
        padding: 5px 0;
        border: 1px solid #DFE1E3;
        line-height: 25px;
    }
    .left-toggle-button {
        border-radius: 20px 0 0 20px;
    }
    .right-toggle-button {
        border-radius: 0 20px 20px 0;
    }
    .paypal-active{
        background-color: #f5544d !important;
        color: #fff !important;
    }
    canvas.jSignature {
        border: 1px solid !important;
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
        padding: 9px 20px;
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
        padding: 9px 20px;
        display: inline-block;
        width: 50%;
        background-color: #FCFCFC;
        padding: 9px 25px;
        border: 1px solid #f4f2f2;
    }

    #video-upload-toggle-container .video-link-button{
        text-align: center;
        color: black;
        display: inline-block;
        width: 50%;
        background-color: #FCFCFC;
        padding: 9px 17px;
        border: 1px solid #f4f2f2;
    }
    .video-active {
        background-color: #f5544d !important;
        color: #fff !important;
    }

    .modal-dialog {
        width: 600px !important;
        margin: 50px auto;
    }

    .modal-body {
        text-align: center;
    }

    .modal-body h4 {
        border-bottom: 1px solid #ccc;
        padding-bottom: 20px;
        margin: 10px;
    }

    .label-s-text {
        padding: 12px 0;
    }

    @media only screen and (max-width: 768px) {
        .modal-open .modal {
            /* padding-right: 0 !important; */
        }

        .modal-dialog {
            width: 80% !important;
        }

        .age-btn {
            line-height: 1;
            padding: 15px 40px;
            max-height: 100%;
        }

        #video-upload-toggle-container #video-link-button,
        #video-upload-toggle-container .video-upload-toggle-button:nth-child(1) {
            padding: 9px 0px;
        }
        .rev-share-text{
            font-size: 14px !important;
        }
        .up-head {
            text-align: center;
        }
        .tooltip-form {
            top: 30px !important;
            right: -7px !important;
            left: inherit !important;
        }
        .tooltip-form-share .tooltiptext {
            right: 0;
            left: -133px;
            top: 22px;
        }
    }

    @media only screen and (max-width: 490px) {
        #simple-video-submit-form canvas.jSignature {
            width: 100% !important;
        }
        #video-submit-frm .card-header h6 {
            font-size: 14px;
        }
        .rev-share-text {
            font-size: 12px !important;
        }
    }

    @media only screen and (max-width: 380px) {
        #video-upload-toggle-container #video-link-button,
        #video-upload-toggle-container .video-upload-toggle-button:nth-child(1) {
            font-size: 12px;
            line-height: 1;
        }

        #video-upload-toggle-container #divider {
            top: 2px;
        }
    }
    .outer-video {
        position: relative;
        border: 2px solid;
        border-radius: 5px;
        margin-bottom:15px;
        color: #353641;
        padding: 15px;
        box-shadow: 5px 5px #9c9ca1;
    }
    .cross-outer{
        position: absolute;
        top: 5px;
        right: 5px;
        float: right;
        cursor:pointer;
        font-size: 20px;
    }
</style>
<div class="container">
<div class="row">

    <section class="form-elements style-2">
        <br>

            <div class="process-block01">
                <div class="card" style="margin-bottom: 20px;">
                    <img src="<?php echo base_url('images/logo.png');?>" alt="Site Logo" class="site-logo">
                    <h3>WooGlobe</h3>
                    <p>
                        <strong>Earn $250 for every video featured on our TikTok Account!</strong><br><br>
                        Submit your video to WooGlobe by uploading it below.<br><br>

                        We will be putting the finest submissions on our Facebook, Instagram, Twitter, Youtube, Tiktok, and website.<br><br>

                        If we feature your video as an individual video on the WooGlobe TikTok account, you will receive $250.
                    </p>

                </div>
                <?php
                if(isset($staff[0]->header_text)){ ?>
                    <!--<div class="up-head-image"><img src="<?php /*echo $staff[0]->image */?>"></div>
                    <div class="up-head"><h4><?php /*echo $staff[0]->header_text  */?></h4></div>-->
                <?php }else{ ?>
                    <!--<div class="up-head"><h4>Submit Your Video</h4></div>-->
                <?php } ?>
                <div class="right-panel">
                    <!-- Form start -->
                    <form action="#" class="" id="simple-video-submit-form" >



                    <!-- Accordion -->
                    <div id="video-submit-frm" class="acordion shadow">

                    <!-- Accordion item 1 -->
                    <div class="card">
                    <div id="PersonalInfo" class="card-header">
                        <h6 data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Personal Information<span class="mnd-lbl-str">*</span></h6>
                    </div>
                    <div id="collapseOne" aria-labelledby="PersonalInfo" data-parent="#video-submit-frm" class="collapse in">
                        <div class="card-body">
                        <div class="form-group">
                            <div class="form-input">


                                <!--<textarea disabled="" style="width: 100%;resize: none; height: 160px;padding: 10px; margin-bottom: 10px;">Submit your video to WooGlobe by uploading it below. We will be putting the finest submissions on our Facebook, Instagram, Twitter, YouTube, Snapchat, TikTok and website.
                                 &#13;&#10;If we feature your video as an individual video on our Facebook page, you will receive £100.
                                </textarea>-->

                                <textarea disabled="" style="display:none;width: 100%;resize: none; height: 160px;padding: 10px; margin-bottom: 10px;">Earn $250 for every video featured on our TikTok Account!

Submit your video to WooGlobe by uploading it below.

We will be putting the finest submissions on our Facebook, Instagram, Twitter, Youtube, Tiktok, and website.

If we feature your video as an individual video on the WooGlobe TikTok account, you will receive $250.
                                </textarea>

                            </div>
                        </div>
                            <div class="form-group">

                            <!-- first name start  -->
                                <div class="form-input w-50">
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
                                <div class="form-input w-50">
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

                            </div>

                            <!-- email start  -->
                            <div class="form-input">
                                <label class="ele-lbl">Email Address:<span class="mnd-lbl-str">*</span></label>
                                    <input type="email" class="form-control" name="email" id="email" placeholder=""
                                        data-parsley-required-message="Email Address field is required."
                                        data-parsley-type-message="Please enter the valid email address."
                                        required
                                        value="<?php /*if(isset($videos['email'])){ echo $videos['email'];}*/?>"
                                        tabindex="3"
                                    >
                                <div class="error" id="email_err"></div>
                            </div>
                            <!-- email end  -->

                            <!-- phone start  -->
                            <div class="form-element form-input">
                                <label class="ele-lbl" style="float: left;width: 100%;">Phone Number:</label>
                                <div class="col-md-4" style="width: 43%;padding-left: 0px;padding-right: 0;float: left;">
		                            <select name="country_code" class="country_code form-control" id="country_code"
		                                    data-parsley-required-message="Country Code field is required."
                                            data-parsley-trigger-after-failure="focusin"

		                                    tabindex="1"
		                            >
		                                <option value="" <?php /*if($users['country_code'] == ''){ echo 'selected="selected"';}*/?> >Select Code</option>
		                                <?php foreach($countries->result() as $country){ ?>
		                                    <option  value="+<?php echo $country->phonecode;?>" <?php /*if($users['country_code'] == $country->phonecode){ echo 'selected="selected"';}*/?>><?php echo $country->name;?> (+<?php echo $country->phonecode;?>)</option>
		                                <?php } ?>
		                            </select>
                            		<div class="error" id="country_code_err"><p></p></div>
                        		</div>
                        		<div class="col-md-8" style="width: 57%; padding-left: 0px;padding-right: 0; float: right;">
                                    <input type="text" class="form-control" name="phone" id="phone" placeholder=""
                                        data-parsley-required-message="Phone Number field is required."
                                        pattern="[a-zA-Z0-9\s]+"
                                        style="width: 100%;"
                                        data-parsley-pattern-message="Only number are allowed."
                                        maxlength="13"
                                        required
                                        value="<?php /*if(isset($users['mobile'])){ echo $users['mobile'];}*/?>"
                                        tabindex="4" >
                                	<div class="error" id="phone_err"></div>
                           		</div>
                            </div>
                            <!-- phone end  -->

                            <!-- address start  -->
                            <div class="form-input" style="display: none;">
                                <label class="ele-lbl">Address:<span class="mnd-lbl-str">*</span></label>
                                <input type="text" class="form-control" name="address" id="address" placeholder=""
                                    data-parsley-required-message="Address field is required."
                                    data-parsley-trigger-after-failure="focusin"
                                    required
                                    value="<?php /*if(isset($users['address'])){ echo $users['address'];}*/?>"
                                    tabindex="2">
                                <div class="error" id="address_err"></div>
                            </div>
                            <!-- address end  -->


                            <div class="form-group" style="display: none;">
                                <!-- city start  -->
                                <div class="form-input w-50" tyle="display: none;">
                                    <label class="ele-lbl">City:<span class="mnd-lbl-str">*</span></label>
                                    <input type="text" class="form-control" name="city" id="city" placeholder=""
                                        data-parsley-required-message="City field is required."
                                        pattern="[a-zA-Z\s]+"
                                        data-parsley-pattern-message="Only alphabets are allowed."
                                        required
                                        value="<?php /*if(isset($users['city_id'])){ echo $users['city_id'];}*/?>"
                                        tabindex="2">
                                    <div class="error" id="city_err"></div>
                                </div>
                                <!-- city end  -->

                                <!-- state start  -->
                                <div class="form-input w-50" style="display: none;">
                                    <label class="ele-lbl">State:<span class="mnd-lbl-str">*</span></label>
                                    <input type="text" class="form-control" name="state" id="state" placeholder=""
                                        data-parsley-required-message="City field is required."
                                        pattern="[a-zA-Z\s]+"
                                        data-parsley-pattern-message="Only alphabets are allowed."
                                        required
                                        value="<?php /*if(isset($users['state_id'])){ echo $users['state_id'];}*/?>"
                                        tabindex="2">
                                    <div class="error" id="state_err"></div>
                                </div>
                                <!-- state end  -->
                            </div>

                            <!-- country start  -->
                            <div class="form-input" style="display: none;">
                                <label class="ele-lbl">Country:<span class="mnd-lbl-str">*</span></label>
                                <input type="text" class="form-control" name="country" id="country" placeholder=""
                                    data-parsley-required-message="City field is required."
                                   data-parsley-trigger-after-failure="focusin"
                                   pattern="[a-zA-Z\s]+"
                                    data-parsley-pattern-message="Only alphabets are allowed."
                                    required
                                    value="<?php /*
                                    if(isset($users['country_code'])){
                                    $country_name=explode("-",$users['country_code']);
                                        if(isset($country_name[1])){
                                            print_r($country_name[1]);
                                        }
                                    }
                                        */?>"
                                    tabindex="2">
                                <div class="error" id="country_err"></div>
                            </div>
                            <!-- country end  -->
                            <!-- zip start  -->
                            <div class="form-input" style="display: none;">
                                <label class="ele-lbl">Zip Code:<span class="mnd-lbl-str">*</span></label>
                                <input type="text" class="form-control" name="zip" id="zip" placeholder=""
                                    data-parsley-required-message="Zip field is required."
                                    pattern="[a-zA-Z0-9\s]+"
                                    data-parsley-pattern-message="Only alphabets and number are allowed."
                                    maxlength="13"
                                    required
                                    value="<?php /*if(isset($users['zip_code'])){ echo $users['zip_code'];}*/?>"
                                    tabindex="4" >
                                <div class="error" id="zip_err"></div>
                            </div>
                            <!-- zip end  -->
                            <!-- paypal start  -->


                    <!-- Accordion item 2 -->
                    <div class="card">
                    <div id="PayPalInfo" class="card-header">
                        <h6 data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">Video Information<span class="mnd-lbl-str">*</span></h6>
                    </div>
                    <div id="collapseTwo" aria-labelledby="PayPalInfo" data-parent="#video-submit-frm" class="collapse in">
                        <div class="card-body">
                            <div class="form-input" style="position: relative;">
                                <div class="tooltip-form-share" style="top:-3px;left: 85px;">?
                                    <span class="tooltiptext">
                                        Please copy and paste the link of the video you were contacted about. This would have been shared in the chat or you can get it from your social media account . Video links typically look like below
                                        <br>Youtube: https://youtu.be/ffFCTen9g1c
                                        <br>TikTok: https://vm.tiktok.com/ZSqVnYRp/
                                        <br>Instagram: https://www.instagram.com/p/CFhngTlD16Y/
                                    </span>
                                </div>
                                <label class="ele-lbl">Video Link:<span class="mnd-lbl-str">*</span></label>

                                    <input type="text" class="form-control" name="video_single_url[videos][]" id="video_single_url" placeholder="Enter your IG/TikTok/YouTube etc. video link"
                                           tabindex="8">

                                <div class="error" id="video_single_url_err_1"></div>
                            </div>

                            <!--pattern="[a-zA-Z0-9\s]+"-->
                            <div class="form-input">
                                <label class="ele-lbl">Video Title:<span class="mnd-lbl-str">*</span></label>


                                    <input type="text" class="form-control" name="video_title[videos][]" id="video_title" placeholder=""
                                           tabindex="9"
                                        <?php if($this->sess->userdata('isClientLogin') != ''){?>
                                            autofocus
                                        <?php } ?>
                                    >

                                <!-- pattern="[a-zA-Z0-9\s]+"
                                 data-parsley-pattern-message="Only alphabet and number are allowed."-->
                                <div class="error" id="video_title_err_1"></div>
                            </div>
                            <!-- Question When Taken start  -->
                            <div class="form-input">
                                <label class="ele-lbl">Where was this taken(City, Country)?<span class="mnd-lbl-str">*</span></label>
                                <input type="text" class="form-control" name="question1[videos][]" id="question1" placeholder=""

                                    value="<?php /*if(isset($video['question_video_taken'])){ echo $video['question_video_taken'];}*/?>"
                                    tabindex="5">
                                <div class="error" id="question1_err_1"></div>
                            </div>
                            <!-- Question When Taken end  -->

                            <!-- Question Taken start  -->
                            <div class="form-input">
                                <label class="ele-lbl">When was this video Taken?<span class="mnd-lbl-str">*</span></label>
                                <div class="relative">
                                    <div class="pick-ico"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                                    <input type="text" class="form-control" name="question3[videos][]" id="question3" placeholder=""

                                         tabindex="6">
                                    <!-- question3 -->
                                </div>
                                <div class="error" id="question3_err_1"></div>
                            </div>
                            <!-- Question Taken end  -->

                            <!-- share story start  -->
                            <div class="form-input" style="position: relative;">
                                <div class="tooltip-form-share" style="top:0px;">?
                                    <span class="tooltiptext">Any information such as names, locations, or any other interesting elements are important to us so that we can pitch your clip with as much information as possible. This way our clients are able to create something that allows your video to be viewed as a story and not just a clip!</span>
                                </div>
                                <label class="ele-lbl">Share your story:<span class="mnd-lbl-str">*</span></label>
                                <textarea type="text" class="form-control vid-sub-txt" name="question4[videos][]" id="question4" placeholder="Please provide background story / context for the video. Click on ? above for more details."
                                         style="margin: 0px 0px 20px; height: 165px; width: 100%;line-height: 19px;"
                                         ></textarea>
                                <div class="error" id="question4_err_1"></div>
                            </div>
                            <!-- share story end  -->

                        <!-- Hidden fields start  -->
                        <div class="form-input" style="display: none;" id="video_urls"></div>
                        <input type="hidden" value="<?php echo $tid;?>" name="tid[videos][]" id="tid">
                        <input type="hidden" value="<?php echo $tid;?>" name="uid_mul[videos][]" id="tid">
                            <?php
                            if(isset($staff[0]->id)){ ?>
                                <input type="hidden" value="<?php echo $staff[0]->id;?>" name="staff_id" id="staff_id">
                            <?php } ?>
                            <?php
                            if(isset($staff[0]->link)){ ?>
                                <input type="hidden" value="<?php echo $staff[0]->link;?>" name="staff_link" id="staff_link">
                            <?php } ?>
                            <input type="hidden" value="<?php echo $staff_party;?>" name="staff_party" id="staff_party">
                        <!-- Hidden fields end  -->
                        <!-- Add Video start  -->

                           <!-- <div class="form-input">
                                <label class="mnd-lbl">Message:</label>
                                <div class="lbl-field">
                                     <textarea  class="form-control" style="line-height: 1.5;" name="message" id="message" placeholder=""
                                                tabindex="10"

                                                data-parsley-pattern-message="Only alphabet and number are allowed."
                                     ></textarea>
                                </div>
                                <div class="error" id="message_err"></div>
                            </div>-->
                            <!--<div class="form-input" id="img" style="display: none"></div>-->
                            <input type="hidden" name="yeslink[videos][]" id="Yes_link_two" class="yeslink" value="">
                            <div id="video-upload-toggle-container"><input type="button" id="video-upload-button" class="video-upload-toggle-button  left-toggle-button video-active"  value="Upload Original Video"><span id="divider">OR</span><input type="button" class="video-upload-toggle-button right-toggle-button" id="video-link-button" style="background-color: rgb(252, 252, 252); color: rgb(0, 0, 0);" value="Too Big to Upload?"></div>

                            <div id="video-div">
                                <div class="form-input chk-bxs">
                                    <label class="ele-lbl w-100">The unedited origional video is:</label>
                                    <div class="form-input w-50">
                                        <label class="ele-lbl">Unedited</label>
                                    </div>
                                    <div class="form-input w-50">
                                        <label class="ele-lbl">High Quality</label>
                                    </div>
                                    <div class="form-input w-50">
                                        <label class="ele-lbl">Without Watermark and Captions</label>
                                    </div>
                                    <div class="form-input w-50">
                                        <label class="ele-lbl">Uncut, Full Length</label>
                                    </div>
                                </div>
                            <input type="file" name="files" style="display: none;">
                                <div class="error" id="file_err"></div>
                            </div>
                            <!-- Add file link start  -->
                            <div id="link_add" class="form-input" style="display: none;position: relative;">
                                <!--<label class="ele-lbl label-s-text">I have the original unedited (raw) video available on Dropbox / OneDrive / Google Drive etc. (Please provide the shareable link with viral@wooglobe.com below):<span class="mnd-lbl-str">*</span></label>-->
                                <div class="lint-content" style="margin: 15px; text-align: left; border: 2px solid #f44336;border-radius: 5px;padding: 5px;">
                                    <p style="color: #333;">
                                        Please upload original video to the Cloud (e.g. OneDrive/DropBox/iCloud etc)  and copy <b>shareable</b> file link below.
                                    </p>
                                    <p style="color: #333;">
                                        Please also share the file with<br>
                                        <b>viral@wooglobe.com</b> in cloud drive settings.

                                    </p>

                                </div>
                                <input type="text" class="form-control" name="link_name[videos][]" id="link_name" placeholder="Paste iCloud/Dropbox etc. link here"
                                    data-parsley-required-message="First Name field is required.">
                                <div class="error" id="link_name_err_1"></div>
                                <!--<div class="tooltip-form" style="top: 45px;right: 0;left: inherit;">?
                                    <span class="tooltiptext">Please share original / raw video through Dropbox / OneDrive / Google Drive/ iCloud Drive etc)</span>
                                </div>-->
                            </div>
                            <!-- Add file link end  -->

                            <!-- Add Video end  -->
                        </div>
                    </div>
                    </div>
                        <div id="new-video-html">

                        </div>
                        <div class="clearfix"></div>
                        <div id="" class="card-header">
                            <h5 style="line-height: 50px;font-size: 16px;text-align: left;margin-left: 15px;">Submit More Videos<i id="add-new-video-html" style="float: right;cursor:pointer;font-size: 36px; margin-top: 7px;margin-right: 15px;" class="fa fa-plus-circle"></i></h5>
                        </div>






                    <!-- Add Signature start -->
                    <label class="ele-lbl">Draw your signature here:<span class="mnd-lbl-str">*</span></label>
                        <div id="signature"></div>
                        <div class="error" id="img_err"></div>
                        <a href="#nogo" class="btn btn--secondary clear-button">Clear</a>
                        <!-- Add Signature end -->

                        <!-- Terms Check box start -->

                        <div class="pop-checkbx">

                                     <div class="checkbox">
                                         <input name="shotVideo" type="checkbox" class="shotVideo" value="Yes" tabindex="12"
                                          data-parsley-required-message="Please confirm to proceed."
                                                required/>
                                         I’ve shot the video myself / I am representing a minor who shot the video<span class="mnd-lbl-str">*</span>
                                         <div class="error" id="shotVideo_err"></div>
                                     </div>

                                     <div class="checkbox">
                                         <input name="ageVideo" type="checkbox" class="ageVideo" value="Yes" tabindex="13"
                                                data-parsley-required-message="Please confirm to proceed."
                                                required
                                         />
                                         I am 18 years of age or older. <span class="mnd-lbl-str">*</span>
                                         <div class="error" id="ageVideo_err"></div>
                                     </div>

                                     <div class="checkbox">
                                         <input name="termsShared" type="checkbox" value="1" tabindex="14"
                                                data-parsley-required-message="Please confirm to proceed."
                                                required
                                         />
                                         I haven’t signed any exclusive agreement for this clip with anyone else and I haven’t shared/submitted the video on any website <span class="mnd-lbl-str">*</span>

                                         <div class="error" id="termsShared_err"></div>
                                     </div>
                            <div class="checkbox">
                                <input name="terms_check" type="checkbox" id="terms_check" data-md-icheck="" class="file_link" value="1"  tabindex="11" required  data-parsley-required-message="Please confirm to proceed.">
                                By signing above, I agree that all information submitted through this form is true and accurate. Additionally I confirm that have reviewed and agree to <a href="<?php echo $url;?>terms_of_use" target="_blank">Terms of Service </a>, <a href="<?php echo $url;?>terms_of_submission_video_sharing" target="_blank">Terms of Submission </a>, <a href="<?php echo $url;?>privacy" target="_blank">Privacy Policy</a> and <a href="<?php echo $url;?>appearancerelease" target="_blank">Appearance Release</a><span class="mnd-lbl-str">*</span>
                                <div class="error" id="terms_check_err"></div>
                            </div>

                                   <!--  <div class="checkbox">
                                         <input name="eu" type="checkbox" value="" tabindex="16" />
                                         I am an EU citizen and/or reside in the EU.<span style="color: green;font-weight: bold;">(Optional)</span>
                                     </div>-->
						</div>
						<!-- Terms Check box end -->

                        <!-- submit start -->
                        <input type="submit"  class="btn" id="simple_submit_button" name="simple_submit_button" value="Submit"/>
                        <!-- submit end -->
                        <?php  ?>

                    </div>
                    </form>
                    <!-- Form end -->
                </div>
            </div>
    </section>

</div>
</div>
<!-- Modal HTML -->
<div id="myModalage" class="modal fade" style="background: #000000cf">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            </div>
            <div class="modal-body">
                <h4>Please choose your age range to continue</h4>
    <a href="#" class="age-btn"  data-dismiss="modal">18 and over</a>
   <input type="button" class="age-btn launch-modal" value="Under 18">
            </div>
        </div>
    </div>
</div>
<div id="myModalunder" class="modal fade" style="background: #000000cf">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                </div>
                <div class="modal-body">
                  <h4>You must be 18 or over for submission. Your guardian / parents can submit this on your behalf</h4>
                   <a href="#" class="age-btn" id="age-above" data-dismiss="modal">Yes, I am the Parent/Guardian</a>
                   <a class="age-btn" href="<?php echo base_url() ?>">No, The Parent/Guardian is not available right now.</a>
                </div>
            </div>
        </div>
</div>



<div id="video-html" style="display: none;">
    <div class="clearfix"></div>
    <div class="outer-video" >
        <span class="cross-outer"><i class="fa fa-times-circle"></i></span>
    <div class="card">
        <div id="" class="card-header videos-data-div">
            <h6 class="video-title" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">Video Information<span class="mnd-lbl-str">*</span></h6>
        </div>
        <div id="collapseTwo" aria-labelledby="PayPalInfo" data-parent="#video-submit-frm" class="collapse in video-body">
            <div class="card-body">

                <!-- Add Video start  -->
                <div class="form-input" style="position: relative;">
                    <div class="tooltip-form-share" style="top:-3px;left: 85px;">?
                        <span class="tooltiptext">
                                        Please copy and paste the link of the video you were contacted about. This would have been shared in the chat or you can get it from your social media account . Video links typically look like below
                                        <br>Youtube: https://youtu.be/ffFCTen9g1c
                                        <br>TikTok: https://vm.tiktok.com/ZSqVnYRp/
                                        <br>Instagram: https://www.instagram.com/p/CFhngTlD16Y/
                                    </span>
                    </div>
                    <label class="ele-lbl">Video Link:<span>*</span></label>

                        <input type="text" class="form-control video_single_url" name="video_single_url[videos][]" placeholder="Enter your IG/TikTok/YouTube etc. video link"
                               data-parsley-type="url"
                               data-parsley-trigger-after-failure="focusin"
                               data-parsley-required-message="URL field is required."
                               required
                               tabindex="8">

                    <div class="error" id="video_single_url_err"></div>
                </div>

                <!--pattern="[a-zA-Z0-9\s]+"-->
                <div class="form-input">
                    <label class="ele-lbl">Video Title:<span>*</span></label>


                        <input type="text" class="form-control video_title" name="video_title[videos][]" placeholder=""
                               data-parsley-required-message="Video Title field is required."
                               required
                               tabindex="9"

                               data-parsley-pattern-message="Only alphabet and number are allowed."
                            <?php if($this->sess->userdata('isClientLogin') != ''){?>
                                autofocus
                            <?php } ?>
                        >

                    <!-- pattern="[a-zA-Z0-9\s]+"
                     data-parsley-pattern-message="Only alphabet and number are allowed."-->
                    <div class="error" id="video_title_err"></div>
                </div>
                <!-- Question When Taken start  -->
                <div class="form-input">
                    <label class="ele-lbl">Where was this taken(City, Country)?<span class="mnd-lbl-str">*</span></label>
                    <input type="text" class="form-control" name="question1[videos][]"  placeholder=""
                           data-parsley-required-message="This field is Mandatory."
                           pattern="[a-zA-Z0-9\s]+"
                           data-parsley-pattern-message="Only alphabet and number are allowed."
                           required
                           value="<?php /*if(isset($video['question_video_taken'])){ echo $video['question_video_taken'];}*/?>"
                           tabindex="5">
                    <div class="error" id="question1_err"></div>
                </div>
                <!-- Question When Taken end  -->

                <!-- Question Taken start  -->
                <div class="form-input">
                    <label class="ele-lbl">When was this video Taken?<span class="mnd-lbl-str">*</span></label>
                    <div class="relative">
                        <div class="pick-ico"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                        <input type="text" class="form-control question3" name="question3[videos][]"  placeholder=""
                               data-parsley-required-message="This field is Mandatory."
                               data-maxdate="today",
                               value="<?php /*if(isset($video['question_when_video_taken'])){ echo date('d/m/Y',strtotime($video['question_when_video_taken']));}*/?>"
                               required tabindex="6"><!-- question3 -->
                    </div>
                    <div class="error" id="question3_err"></div>
                </div>
                <!-- Question Taken end  -->

                <!-- share story start  -->
                <div class="form-input" style="position: relative;">
                    <div class="tooltip-form-share" style="top:0px;">?
                        <span class="tooltiptext">Any information such as names, locations, or any other interesting elements are important to us so that we can pitch your clip with as much information as possible. This way our clients are able to create something that allows your video to be viewed as a story and not just a clip!</span>
                    </div>
                    <label class="ele-lbl">Share your story:<span class="mnd-lbl-str">*</span></label>
                    <textarea type="text" class="form-control vid-sub-txt" name="question4[videos][]"  placeholder="Please provide background story / context for the video. Click on ? above for more details." data-parsley-required-message="This field is Mandatory." style="margin: 0px 0px 20px; height: 165px; width: 100%;line-height: 19px;" required tabindex="7" ></textarea>
                    <div class="error" id="question4_err"></div>
                </div>
                <!-- share story end  -->

                <!-- Hidden fields start  -->
                <div class="form-input video_urls" style="display: none;" id=""></div>


                <!-- Hidden fields end  -->

                <!--<div class="form-input img" style="display: none"></div>-->
                <!-- <div class="form-input">
                     <label class="mnd-lbl">Message:</label>
                     <div class="lbl-field">
                          <textarea  class="form-control" style="line-height: 1.5;" name="message" id="message" placeholder=""
                                     tabindex="10"

                                     data-parsley-pattern-message="Only alphabet and number are allowed."
                          ></textarea>
                     </div>
                     <div class="error" id="message_err"></div>
                 </div>-->



                <div>
                    <div id="video-upload-toggle-container">
                        <input style="width: 49%;" type="button" id="" class="video-upload-toggle-button  left-toggle-button video-active video-upload-button"  value="Upload Original Video">
                        <span id="divider">OR</span>
                        <input type="button" class="video-upload-toggle-button right-toggle-button video-link-button" id="" style="background-color: rgb(252, 252, 252); color: rgb(0, 0, 0);width: 49%;" value="Too Big to Upload?">
                        <input type="hidden" name="yeslink[videos][]" class="yeslink" value="">
                    </div>

                    <div class="video-div">
                        <div class="form-input chk-bxs">
                            <label class="ele-lbl w-100">The unedited origional video is:</label>
                            <div class="form-input w-50">
                                <label class="ele-lbl">Unedited</label>
                            </div>
                            <div class="form-input w-50">
                                <label class="ele-lbl">High Quality</label>
                            </div>
                            <div class="form-input w-50">
                                <label class="ele-lbl">Without Watermark and Captions</label>
                            </div>
                            <div class="form-input w-50">
                                <label class="ele-lbl">Uncut, Full Length</label>
                            </div>
                        </div>
                    </div>
                    <!-- Add file link start  -->
                    <div id="" class="form-input link_add" style="display: none;position: relative;">
                        <!--<label class="ele-lbl label-s-text">I have the original unedited (raw) video available on Dropbox / OneDrive / Google Drive etc. (Please provide the shareable link with viral@wooglobe.com below):<span class="mnd-lbl-str">*</span></label>-->
                        <div class="lint-content" style="margin: 15px; text-align: left; border: 2px solid #f44336;border-radius: 5px;padding: 5px;">
                            <p style="color: #333;">
                                Please upload original video to the Cloud (e.g. OneDrive/DropBox/iCloud etc)  and copy <b>shareable</b> file link below.
                            </p>
                            <p style="color: #333;">
                                Please also share the file with<br>
                                <b>viral@wooglobe.com</b> in cloud drive settings.

                            </p>

                        </div>
                        <input type="text" class="form-control" name="link_name[videos][]"  placeholder="Paste iCloud/Dropbox etc. link here"
                               data-parsley-required-message="First Name field is required.">
                        <div class="error" id="link_name_err"></div>
                        <!--<div class="tooltip-form" style="top: 45px;right: 0;left: inherit;">?
                            <span class="tooltiptext">Please share original / raw video through Dropbox / OneDrive / Google Drive/ iCloud Drive etc)</span>
                        </div>-->
                    </div>
                    <!-- Add file link end  -->
                </div>


                <!-- Add Video end  -->
            </div>
        </div>
    </div>
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
var uid = '<?php echo $tid;?>';


</script>

