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

    #gviral-video-submit-form .form-group {
        margin: 0;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
    }

    #gviral-video-submit-form .form-input.w-50 {
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

    #gviral-video-submit-form .fileuploader-theme-dragdrop .fileuploader-input {
        border: none;
        border-radius: 6px;
        background: transparent;
        padding: 10px;
    }

    #gviral-video-submit-form .fileuploader {
        background: none;
        border: 2px dashed #f5544d;
    }

    #gviral-video-submit-form .social-lnks img {
        width: 100%;
        padding-bottom: 10px;
    }

    #gviral-video-submit-form .social-lnks .iframe-social {
        display: none;
    }

    #gviral-video-submit-form  .fileuploader-input .fileuploader-input-button {
        background: #353641;
        border: 1px solid #353641;
        border-radius: 0;
        color: #b0bec5;
    }

    #gviral-video-submit-form  .fileuploader-input .fileuploader-input-button:hover {
        color: #f5544d;
    }

    #gviral-video-submit-form a.btn.btn--secondary.clear-button {
        background: #353641;
        border: 1px solid #353641;
        border-radius: 0;
        color: #b0bec5;
        text-decoration: none;
        padding: 6px 20px;
        font-style: normal;
        margin: 0;
    }

    #gviral-video-submit-form a.btn.btn--secondary.clear-button:hover {
        color: #f5544d;
    }

    #gviral-video-submit-form canvas.jSignature {
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

    .modal-dialog {
        width: 600px !important;
        margin: 50px auto;
    }

    .modal-body {
        text-align: center;
    }
    .fsBody .ui-datepicker-trigger {
        top: 8px;
        margin-left: 5px;
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
        #gviral-video-submit-form canvas.jSignature {
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
    //datepicker
    }
    .fsBody .fieldset-content .ui-datepicker-trigger, .fsBody .fieldset-content select {
        float: left;
    }

    .fsBody .ui-datepicker-trigger {
        top: 8px;
        margin-left: 5px;
    }
    .fsBody .ui-datepicker-trigger {
        position: relative;
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
</style>


<div class="container"  id="divovereighteen">
    <div class="row">

        <section class="form-elements style-2">
            <br>

            <div class="process-block01">
                <?php
                if(isset($staff[0]->header_text)){ ?>
                    <div class="up-head-image"><img src="<?php echo $staff[0]->image ?>"></div>
                    <div class="up-head"><h4><?php echo $staff[0]->header_text  ?></h4></div>
                <?php }else{ ?>

                <?php } ?>
                <div class="right-panel">
                    <!-- Form start -->
                    <form action="#" class="" id="gviral-video-submit-form">
                        <input type="hidden" id="uid" name="uid" value="<?php echo $uid ?>">



                        <!-- Accordion -->
                        <div id="video-submit-frm" class="acordion shadow">

                            <!-- Accordion item 1 -->
                            <div class="card">
                                <div id="PersonalInfo" class="card-header">
                                    <h6 data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">VIDEO USAGE PERMISSION<span class="mnd-lbl-str">*</span></h6>
                                </div>
                                <div id="collapseOne" aria-labelledby="PersonalInfo" data-parent="#video-submit-frm" class="collapse in">
                                    <div class="card-body">


                                        <div class="form-input"style="margin-top:15px; margin-bottom:15px;text-align: left;">
                                            <label class="ele-lbl" style="padding-left: 3px;">Video Link:</label>
                                            <p>
                                                <a style="padding-left: 5px;" href="<?php echo $video_url ?>" target="_blank"><?php echo $video_url ?></a>
                                            </p>


                                        </div>
                                        <div class="form-group">

                                        <div class="form-input w-50">
                                            <label class="ele-lbl"> First Name<span class="mnd-lbl-str">*</span></label>

                                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder=""
                                                   value=""
                                                   tabindex="1" autofocus>
                                            <div class="error" id="first_name_err"></div>
                                        </div>

                                        <!-- first name end  -->

                                        <!-- last name start  -->
                                        <div class="form-input w-50">
                                            <label class="ele-lbl"> Last Name:<span class="mnd-lbl-str">*</span></label>
                                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="
                                                   value=""
                                                   tabindex="2">
                                            <div class="error" id="last_name_err"></div>
                                        </div>

                                        <!-- last name end  -->
                                        </div>
                                        <div class="error" id="duplicate_name_err"></div>

                                    <!-- email start  -->
                                    <div class="form-input">
                                        <label class="ele-lbl"> Email Address:<span class="mnd-lbl-str">*</span></label>
                                        <input type="email" class="form-control" name="email" id="email" placeholder=""
                                               value="<?php /*if(isset($videos['email'])){ echo $videos['email'];}*/?>"
                                               tabindex="3"
                                        >
                                        <div class="error" id="email_err"></div>
                                    </div>
                                    <!-- email end  -->

                                    <!-- phone start  -->
                                    <div class="form-element">
                                        <label class="ele-lbl" style="float: left;width: 100%;">Phone Number:<span class="mnd-lbl-str">*</span></label>
                                        <div class="col-md-4" style="width: 43%;padding-left: 0px;padding-right: 0;float: left;">
                                            <select name="country_code" class="country_code form-control" id="country_code"
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

                                                   style="width: 100%;"
                                                   maxlength="13"
                                                   value="<?php /*if(isset($users['mobile'])){ echo $users['mobile'];}*/?>"
                                                   tabindex="4" >
                                            <div class="error" id="phone_err"></div>
                                        </div>
                                    </div>
                                    <!-- phone end  -->

                                    <!-- address start  -->
                                    <div class="form-input">
                                        <label class="ele-lbl">Address:<span class="mnd-lbl-str">*</span></label>
                                        <input type="text" class="form-control" name="address" id="address" placeholder=""
                                               value="<?php /*if(isset($users['address'])){ echo $users['address'];}*/?>"
                                               tabindex="2">
                                        <div class="error" id="address_err"></div>
                                    </div>
                                    <!-- address end  -->

                                    <!-- address 2 start  -->
                                    <div class="form-input">
                                        <label class="ele-lbl">Address 2(Optional):</label>
                                        <input type="text" class="form-control" name="address2" id="address2" placeholder=""
                                               value="<?php /*if(isset($users['address2'])){ echo $users['address2'];}*/?>"
                                               tabindex="2">
                                        <div class="error" id="address_err"></div>
                                    </div>
                                    <!-- address end  -->

                                    <div class="form-group">
                                        <!-- city start  -->
                                        <div class="form-input w-50">
                                            <label class="ele-lbl">City:<span class="mnd-lbl-str">*</span></label>
                                            <input type="text" class="form-control" name="city" id="city" placeholder=""
                                                   value="<?php /*if(isset($users['city_id'])){ echo $users['city_id'];}*/?>"
                                                   tabindex="2">
                                            <div class="error" id="city_err"></div>
                                        </div>
                                        <!-- city end  -->

                                        <!-- state start  -->
                                        <div class="form-input w-50">
                                            <label class="ele-lbl">State:<span class="mnd-lbl-str">*</span></label>
                                            <input type="text" class="form-control" name="state" id="state" placeholder=""
                                                   value="<?php /*if(isset($users['state_id'])){ echo $users['state_id'];}*/?>"
                                                   tabindex="2">
                                            <div class="error" id="state_err"></div>
                                        </div>
                                    </div>
                                    <!-- state end  -->


                                    <!-- country start  -->
                                    <div class="form-input">
                                        <label class="ele-lbl">Country:<span class="mnd-lbl-str">*</span></label>
                                        <input type="text" class="form-control" name="country" id="country" placeholder=""
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
                                    <div class="form-input">
                                        <label class="ele-lbl">Zip Code:<span class="mnd-lbl-str">*</span></label>
                                        <input type="text" class="form-control" name="zip" id="zip" placeholder=""
                                               maxlength="13"
                                               value="<?php /*if(isset($users['zip_code'])){ echo $users['zip_code'];}*/?>"
                                               tabindex="4" >
                                        <div class="error" id="zip_err"></div>
                                    </div>
                                        <!-- zip end  -->


                                        <!-- paypal start  -->

                                        <!-- Add file link end  -->

                                        <!-- Add Video end  -->
                                        <!-- <div id="fsRow2469302-14" class="fsRow fsFieldRow fsLastRow">
                                             <div class="fsRowBody fsCell fsFieldCell fsFirst fsLast fsLabelVertical fsSpan100" id="fsCell70122473" aria-describedby="fsSupporting70122473" lang="en" fs-field-type="textarea" fs-field-validation-name="Help us find you in the video!">
                                                 <label id="label70122473" class="ele-lbl" for="field70122473">Help us find you in the video!<span class="mnd-lbl-str ">*</span>                                                    </label>
                                                 <textarea id="field70122473" class="fsField fsRequired " name="field70122473" rows="3" cols="47" required="" aria-required="true"></textarea>
                                                 <div id="fsSupporting70122473" class="fsSupporting">Please describe yourself to help us distinguish you from others in the video, i.e., I’m wearing red pants with a blue shirt, and I have black hair.</div>
                                             </div>-->

                                        <!-- <div id="fsRow2469302-16" class="fsRow fsFieldRow fsLastRow">
                                             <div class="fsRowBody fsCell fsFieldCell fsReadOnly fsFirst fsLast fsLabelVertical fsSpan100" id="fsCell45558835" lang="en" fs-field-type="textarea" fs-field-validation-name="Signature Terms">
                                                 <textarea id="field45558835" class="fsField " name="field45558835" rows="3" cols="47" readonly="readonly">By entering your name in the Signature blank below, and clicking “Submit,” you acknowledge that you have read and understand the Jukin Media, Inc. Appearance Release.</textarea>

                                             </div>
                                             <div class="fs-clear"></div>
                                         </div>-->



                        <!-- Add Signature start -->

                        <label class="ele-lbl">Draw your signature here:<span class="mnd-lbl-str">*</span></label>
                        <div id="signature">
<!--                            <div style="padding:0 !important;margin:0 !important;width: 100% !important; height: 0 !important;margin-top:-1em !important;margin-bottom:1em !important;"></div>
-->
                        </div>
                        <div class="error" id="img_err"></div>
                                <div id="fsSupporting45558837" class="fsSupporting">Use your mouse or finger to draw your signature above</div>

                                <a href="#nogo" class="btn btn--secondary clear-button">Clear</a>
                </div>
                <!-- Add Signature end -->

                <!-- Terms Check box start -->
                                <div class="checkbox">
                                    <input name="terms_check" type="checkbox" id="terms_check" data-md-icheck="" class="file_link" value="1" tabindex="11" required="" data-parsley-required-message="Please confirm to proceed.">
                                    I confirm that I am 18 years  of age or older and I recorded the video myself.  I agree to the <a href="<?php echo $url;?>terms_of_use" target="_blank">Terms of Service </a>, <a href="<?php echo $url;?>terms_of_submission" target="_blank">Terms of Submission </a> and <a href="<?php echo $url;?>appearancerelease" target="_blank">Appearance Release</a><span class="mnd-lbl-str">*</span>
                                    <div class="error" id="terms_check_err"></div>
                                </div>
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


                                </div>
            </div>
                            </div>
                        </div>
                                <!-- Terms Check box end -->

            <!-- submit start -->
                        <br><br>
            <input type="submit" name="" class="btn" id="guardian_submit_button" name="guardian_submit_button" value="Submit"/>
            <!-- submit end -->
            <?php  ?>


    </form>
            </div>
        </div>
    <!-- Form end -->
    </section>
</div>
</div>

<!-- Modal HTML -->
<!--<div id="myModalage" class="modal fade" style="background: #000000cf">
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
                <a class="age-btn" href="<?php /*echo base_url() */?>">No, The Parent/Guardian is not available right now.</a>
            </div>
        </div>
    </div>
</div>-->
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

