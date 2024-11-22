<?php
/**
 * Created by PhpStorm.
 * User: Usman Ali Sarwar
 * Date: 16/01/2018
 * Time: 2:54 PM
 */
?>
<style>
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
    .tooltip-form-share .tooltiptext {
        right: 0;
        left: -133px;
        top: 22px;
    }
</style>
<?php if($title != 'Sign In' && $title != 'Sign Up'){?>
<div class="preloadr-div"></div>
<?php } ?>
<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
                 <div class="modal-body">
                     <section class="form-elements style-2">

                         <h4>New Video</h4><br>

                         <form action="#" class="" id="video-upload-form">
                             <?php if($this->sess->userdata('isClientLogin') == ''){?>

                                 <div class="form-input">
                                    <label class="mnd-lbl">First Name:<span>*</span></label>
                                    <div class="lbl-field">
                                    <input type="text" class="form-control" name="first_name" id="first_name" placeholder=""
                                        data-parsley-required-message="First Name field is required."
                                        pattern="[a-zA-Z0-9\s]+"
                                        data-parsley-pattern-message="Only alphabet and number are allowed."
                                        data-parsley-focus="first"
                                        required
                                        tabindex="1" autofocus>
                                    </div>
                                 <div class="error" id="first_name_err"></div>
                                 </div>
                                 <div class="form-input">
                                    <label class="mnd-lbl">Last Name:<span>*</span></label>
                                    <div class="lbl-field">
                                    <input type="text" class="form-control" name="last_name" id="last_name" placeholder=""
                                        data-parsley-required-message="Last Name field is required."
                                        pattern="[a-zA-Z0-9\s]+"
                                        data-parsley-pattern-message="Only alphabet and number are allowed."
                                        data-parsley-focus="first"
                                        required
                                        tabindex="2">
                                    </div>
                                 <div class="error" id="last_name_err"></div>
                                 </div>

                                 <div class="form-input">
                                    <label class="mnd-lbl">Email Address:<span>*</span></label>
                                    <div class="lbl-field">
                                     <input type="email" class="form-control" name="email" id="email_a" placeholder=""
                                            data-parsley-required-message="Email Address field is required."
                                            data-parsley-type-message="Please enter the valid email address."
                                            data-parsley-focus="first"
                                            required tabindex="3">
                                            <!--data-parsley-remote="<?php /*echo $url;*/?>check_email"
                                            data-parsley-remote-options='{ "type": "POST", "dataType": "jsonp" }'
                                             data-parsley-remote-validator="remotevalidator"
                                            data-parsley-remote-message=" "
                                            data-parsley-trigger="change"-->

                                    </div>
                                 <div class="error" id="email_err"></div>
                                 </div>
                                 <div class="form-input">
                                     <label class="mnd-lbl">Phone Number:<span>*</span></label>
                                     <div class="lbl-field">
                                         <input type="text" class="form-control" name="phone" id="phone" placeholder=""
                                                data-parsley-required-message="Phone Number field is required."
                                                pattern="[0-9+\s]+"
                                                data-parsley-pattern-message="Only plus sign and number are allowed."
                                                data-parsley-focus="first"
                                                maxlength="13"
                                                required
                                                tabindex="4" >
                                     </div>
                                     <div class="error" id="phone"></div>
                                 </div>


                             <?php } ?>

                                 <div class="form-input" style="position: relative;">
                                     <div class="tooltip-form-share" style="top:30px;left: 25px;">?
                                         <span class="tooltiptext">
                                        Please copy and paste the link of the video you were contacted about. This would have been shared in the chat or you can get it from your social media account . Video links typically look like below
                                        <br>Youtube: https://youtu.be/ffFCTen9g1c
                                        <br>TikTok: https://vm.tiktok.com/ZSqVnYRp/
                                        <br>Instagram: https://www.instagram.com/p/CFhngTlD16Y/
                                    </span>
                                     </div>
                                    <label class="mnd-lbl">Video Link:<span>*</span></label>
                                    <div class="lbl-field">
                                     <input type="text" class="form-control" name="video_url" id="video_url" placeholder="Enter your IG/TikTok/YouTube etc. video link"
                                            data-parsley-type="url"
                                            data-parsley-required-message="URL field is required."
                                            data-parsley-focus="first"
                                            required
                                            tabindex="5">
                                    </div>
                                 <div class="error" id="video_url_err"></div>
                                 </div>

                             <!--pattern="[a-zA-Z0-9\s]+"-->
                             <div class="form-input">
                                <label class="mnd-lbl">Video Title:<span>*</span></label>
                                
                                <div class="lbl-field">
                                 <input type="text" class="form-control" name="video_title" id="video_title" placeholder=""
                                        data-parsley-required-message="Video Title field is required."
                                        data-parsley-focus="first"
                                        required
                                        tabindex="6"

                                        data-parsley-pattern-message="Only alphabet and number are allowed."
                                     <?php if($this->sess->userdata('isClientLogin') != ''){?>
                                         autofocus
                                     <?php } ?>
                                 >
                                </div>
                                <!-- pattern="[a-zA-Z0-9\s]+"
                                 data-parsley-pattern-message="Only alphabet and number are allowed."-->
                                 <div class="error" id="video_title_err"></div>
                             </div>
                                 <div class="form-input">
                                    <label class="mnd-lbl">Message:</label>
                                    <div class="lbl-field">
                                     <textarea  class="form-control" style="line-height: 1.5;" name="message" id="message_a" placeholder=""
                                     tabindex="7"

                                     data-parsley-pattern-message="Only alphabet and number are allowed."
                                     data-parsley-focus="first"
                                     ></textarea>
                                    </div>
                                     <div class="error" id="message_err"></div>
                                 </div>
                             <div class="form-input" id="img" style="display: none"></div>
                             <!--pattern="[a-zA-Z0-9\s]+"
                             data-parsley-pattern-message="Only alphabet and number are allowed."-->
                                 <!--popup check box starts here-->
                                 <div class="pop-checkbx">

                                     <div class="checkbox">
                                         <input name="shotVideo" type="checkbox" class="shotVideo" value="Yes" tabindex="7"
                                          data-parsley-required-message="Please confirm to proceed."
                                          data-parsley-focus="first"      
                                          required/>
                                         Shot the video yourself?<span class="mnd-lbl-str">*</span>
                                     </div>

                                     <div class="checkbox">
                                         <input name="haveOrignalVideo" type="checkbox" class="haveOrignalVideo" value="Yes" tabindex="8"
                                                data-parsley-required-message="Please confirm to proceed."
                                                data-parsley-focus="first"
                                                required
                                         />
                                         Have the original unedited video and haven’t given it to anyone? <span class="mnd-lbl-str">*</span>
                                     </div>

                                     <div class="checkbox">
                                         <input name="terms" type="checkbox" value="" tabindex="9"
                                                data-parsley-required-message="Please confirm to proceed."
                                                data-parsley-focus="first"
                                                required
                                         />
                                         I have read and agree to the <a href="<?php echo $url;?>terms_of_use" target="_blank">Terms of use</a>, <a href="<?php echo $url;?>terms_of_submission" target="_blank">Terms of submission</a> and <a href="<?php echo $url;?>appearancerelease" target="_blank">Appearance Release</a><span class="mnd-lbl-str">*</span>
                                     </div>

                                     <div class="checkbox">
                                         <input name="newsletter" type="checkbox" value="" tabindex="10" />
                                         Sign me up for the newsletter.
                                     </div>

                                 </div>
                                 <!--popup check box ends here-->

                                 <!--<div class="g-recaptcha" data-sitekey="6Lfc6n0UAAAAACLgEjaXFdpuG3SgsEQ10L_44AF0"></div>
                                 <br>
                                 <div class="error" id="g-recaptcha-response_err"></div>-->
                                
                                <div class="submit-input">
                                    <input type="submit" class="btn" name="video-upload-form-submit" value="Submit" tabindex="11">
                                </div>
                                        

                            </div> 


                                     
                                </form>
                            <!-- /.right-panel -->

                     </section><!-- /.form-elements -->
                </div>
        </div>
    </div>
<div id="blockModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <section class="form-elements style-2">

                    <h4>Block User Request</h4><br>

                    <form action="#" class="" id="user-block-request-form">


                        <!--Email-->
                        <div class="form-input">
                            <label class="mnd-lbl">Email Address:<span>*</span></label>
                            <div class="lbl-field">
                                <input type="email" class="form-control" name="email" id="email_es" placeholder=""
                                       data-parsley-required-message="Email Address field is required."
                                       data-parsley-type-message="Please enter the valid email address."
                                       required tabindex="3"
                                       data-parsley-trigger="change"
                                >
                            </div>
                            <div class="error" id="email_err"></div>
                        </div> 


                        <div class="submit-input">
                            <input type="submit" class="btn" name="user-block-request-form-submit" value="Submit Request"
                                   tabindex="11">
                        </div>


            </div>



            </form>
            <!-- /.right-panel -->

            </section><!-- /.form-elements -->
        </div>
    </div>
</div>
</div>



<footer class="site-footer">


    <div class="footer-bottom">
        <div class="padding">
            <div class="container">
                
                <div class="row">
                    <div class="col-md-8">
                        <nav>
                            <ul>
                                <!-- <li><a href="<?php echo $url;?>">Home</a></li>
                                <li><a href="<?php echo $url;?>partner/categories">Buy Videos</a></li>
                                <li><a href="<?php echo $url;?>about-us">About Us</a></li> -->
                                <li><a href="<?php echo $url;?>#">Terms of Reference</a></li>
                                <li><a href="<?php echo $url;?>privacy">Privacy Policy</a></li>
                                <li><a href="<?php echo $url;?>faq">Faqs</a></li>
                                <li><a href="<?php echo $url;?>contact-us">Contact Us</a></li>
                            </ul>
                        </nav>
                        <div class="copyright">
                            © <a href="<?php $url;?>"><?php echo $site_title;?></a> <?php echo date('Y');?>
                        </div>
                    </div>
                    <div class="col-md-4">
						
						<!--newsletter starts here-->
						<h4 class="sub-txt">Subscribe To Our Email And Get Updated</h4>
						<div class="ftr-newsletter">
							
							<input type="email" placeholder="email@example.com" name="mail_newsletter" id="mail_newsletter">
							<button type="button" id="button_newsletter" class="ftr-newsletter-btn" id="button-addon2"><i class="fa fa-paper-plane"></i>
							</button>

						</div>
                        <div class="error" id="err_newsletter" style="text-align: center;"></div>
    					<!--newsletter ends here-->

                        <div class="top-sitemap right">
                            
                            <a class="youtube" href="https://www.youtube.com/channel/UCbbtHuBeqqlRB9yNr_Hpc7w" target="_blank">
                                <i class="fa">
                                    <img src="//uat.technoventive.com/admin/assets/assets/icons/youtube_social_circle_red.png" />
                                </i>
                            </a>
                            <a class="facebook" href="https://www.facebook.com/WooGlobe/" target="_blank">
                                <i class="fa fa-facebook"></i>
                            </a>
                            <!--<a class="instagram" href="https://www.instagram.com/wooglobe/?hl=en" target="_blank">-->
                            <a class="instagram" href="Https://www.instagram.com/wooglobe.media" target="_blank">
                                <i class="fa fa-instagram"></i>
                            </a>
                            <a class="twitter" href="https://twitter.com/wooglobe?lang=en" target="_blank">
                                <i class="fa fa-twitter"></i>
                            </a>
                            
                            <!-- <a href="#" target="_blank"><i class="fa fa-vimeo-square"></i></a> -->
                            <!-- <a href="#" target="_blank"><i class="fa fa-pinterest"></i></a> -->
                            <!-- <a href="#" target="_blank"><i class="fa fa-google-plus-square"></i></a> -->
                        </div>

                </div>

            </div><!-- /.container -->
        </div><!-- /.padding -->
    </div><!-- /.footer-bottom -->

    <div class="beta_version" style="display: none">Beta Version</div>

</footer><!-- /.footer -->

<!-- Global Site Tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=GA_TRACKING_ID"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'GA_TRACKING_ID');
</script>


<!--1095283883953521  178411556375653-->

<!--140953096488193-->


<div id="fb-root"></div>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId            : '1095283883953521',
            xfbml            : true,
            version          : 'v3.2'
        });

    };

    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

<!-- Your customer chat code -->
<div class="fb-customerchat"
     attribution=setup_tool
     page_id="140953096488193"
     minimized=true>
</div>
