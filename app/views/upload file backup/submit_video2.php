<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 3/19/2018
 * Time: 9:51 AM
 */?>
<div class="container">
    <div class="row">

        <section class="form-elements style-2">
            <br>
            <ul id="progressbar">
                <li class="active">Submit Orignal Video</li>
                <li class="active">Video Details</li>
                <li>Video Story</li>
                <li>Thank You</li>
            </ul>
            <div class="right-panel vid-2">
                <form action="#" class="" id="video-submit-form">
                    <input type="hidden" value="<?php echo $videos['id']?>" name="lead_id" id="lead_id">
                    <input type="hidden" value="<?php echo $slug?>" name="slug" id="slug">
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
                      </div>
                      <!-- pattern="[a-zA-Z0-9\s]+"  data-parsley-pattern-message="Only alphabet and number are allowed."-->
                      <div class="error" id="question3_err"></div>
                    </div>

                    <div class="form-input">
                        <textarea type="text" class="form-control vid-sub-txt" name="question4" id="question4" placeholder="Any information such as names, locations, or any other interesting elements are important to us so that we can pitch your clip with as much information as possible. This way our clients are able to create something that allows your video to be viewed as a story and not just a clip! "
                               data-parsley-required-message="This field is Mandatory." style="margin: 0px 0px 20px; height: 165px; width: 100%;"
                               pattern="[a-zA-Z0-9\s]+"
                               data-parsley-pattern-message="Only alphabet and number are allowed."
                               required tabindex="4"></textarea>
                    <div class="error" id="question4_err"></div>
                    </div>

                    <div class="form-input">
                        <input type="hidden" class="form-control" name="id" id="id" value="<?php echo $video['id']?>">
                    </div>
                    <div class="form-input">
                        <input type="hidden" class="form-control" name="view" id="view" value="submit_video3">
                    </div>
                    <input type="submit" name="" class="btn" name="video-submit-form-submit"value="NEXT"/>

                </form>
            </div>
        </section>
    </div>
</div>
