<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 3/19/2018
 * Time: 9:51 AM
 */?>
<style>
    a:hover {
        color: blue;
    }
</style>
<div class="container">
    <div class="row">

        <section class="form-elements style-2">
            <div class="process-block05" id="fm-block5">
                <div class="right-panel">
                    <h2 style="margin-left: 0px;margin-top: 0px;">Thank You for the submission</h2>
                    <br/>
                    <h5 style="line-height: 22px;">We will get back to you shortly, in the meantime please copy and paste the following information in the description of your original video on YouTube / Instagram / Facebook etc (whichever applicable).</h5>
                    <span style="color: #f5544d;"><b>@WooGlobe Verified (Original) *For licensing / permission to use: Contact - licensing(at)WooGlobe(dot)com</b></span>
                    <h5 style="line-height: 22px;">Click <a href="<?php print $video_url; ?>" target="_blank">here</a> to update the video description</h5>
                    <h5 style="line-height: 22px;">Please note that many licensors will not pay for the use of the clip unless they see the above  statement in the description.</h5>
                    <span class="join_social">Join us on social media platform</span>
                    <br/>

                    <div class="thank-u-social">
                        <div class="top-sitemap">
                            <a class="youtube" href="https://www.youtube.com/channel/UCbbtHuBeqqlRB9yNr_Hpc7w" target="_blank"><i class="fa fa-youtube"></i></a>
                            <a class="facebook" href="https://www.facebook.com/WooGlobe/" target="_blank"><i class="fa fa-facebook"></i></a>
                            <a class="instagram" href="https://www.instagram.com/wooglobe/?hl=en" target="_blank"><i class="fa fa-instagram"></i></a>
                            <a class="twitter" href="https://twitter.com/wooglobe?lang=en" target="_blank"><i class="fa fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
  <!--  <div class="row">

        <section class="form-elements style-2">-->
     <!--       <br>
            <ul id="progressbar">
                <li class="active">Submit Orignal Video</li>
                <li class="active">Video Details</li>
                <li>Paymet Detail</li>
                <li>Thank You</li>
            </ul>
            <div class="right-panel vid-2">
                <form action="#" class="" id="video-submit-form">
                    <input type="hidden" value="<?php /*echo $videos['id']*/?>" name="lead_id" id="lead_id">
                    <input type="hidden" value="<?php /*echo $slug*/?>" name="slug" id="slug">
                    <div class="form-input">
                        <input type="text" class="form-control" name="question1" id="question1" placeholder="Where was this taken? Country/City etc."
                               data-parsley-required-message="This field is Mandatory."
                               pattern="[a-zA-Z0-9\s]+"
                               data-parsley-pattern-message="Only alphabet and number are allowed."
                               required
                               tabindex="1">
                    <div class="error" id="question1_err"></div>
                    </div>
                    <div class="form-input">
                        <input type="text" class="form-control" name="question2" id="question2" placeholder="What was the context?"
                               data-parsley-required-message="This field is Mandatory."
                               pattern="[a-zA-Z0-9\s]+"
                               data-parsley-pattern-message="Only alphabet and number are allowed."
                               required
                               tabindex="2">
                    <div class="error" id="question2_err"></div>
                    </div>

                    <div class="form-input">
                      <div class="pick-ico"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                      <div class="">
                        <input type="text" class="form-control" name="question3" id="question3" placeholder="When was this video Taken?"
                                  data-parsley-required-message="This field is Mandatory."
                                  
                                  required tabindex="3"><!-- question3 -->
                     <!-- </div>-->
                      <!-- pattern="[a-zA-Z0-9\s]+"  data-parsley-pattern-message="Only alphabet and number are allowed."-->
                   <!--   <div class="error" id="question3_err"></div>
                    </div>

                    <div class="form-input">
                        <textarea type="text" class="form-control vid-sub-txt" name="question4" id="question4" placeholder="Any information such as names, locations, or any other interesting elements are important to us so that we can pitch your clip with as much information as possible. This way our clients are able to create something that allows your video to be viewed as a story and not just a clip! "
                               data-parsley-required-message="This field is Mandatory." style="margin: 0px 0px 20px; height: 165px; width: 100%;"
                               required tabindex="4"></textarea>
                    <div class="error" id="question4_err"></div>
                    </div>

                    <div class="form-input">
                        <input type="hidden" class="form-control" name="id" id="id" value="< ?php /*/*echo $video['id']*/*/?>">
                    </div>
                    <div class="form-input">
                        <input type="hidden" class="form-control" name="view" id="view" value="submit_video3">
                    </div>
                    <input type="submit" name="" class="btn" name="video-submit-form-submit"value="NEXT"/>

                </form>-->
           <!-- </div>-->
       <!-- </section>
    </div>-->
</div>
