<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 3/15/2018
 * Time: 3:15 PM
 */?>
<!-- MultiStep Form -->
<div class="container">
<div class="row">

    <section class="form-elements style-2">
        <br>
        <ul id="progressbar">
            <li class="active">Submit Orignal Video</li>
            <li>Video Details</li>
            <li>Video Story</li>
            <li>Thank You</li>
        </ul>
            <div class="process-block01">
                <div class="up-head"><h4> Please Upload the Orignal Video File/Unedited.</h4></div>
                <div class="right-panel">
                    <form action="#" class="" id="video-submit-form">
                        <input type="hidden" value="<?php echo $videos['id']?>" name="lead_id" id="lead_id">
                        <input type="hidden" value="<?php echo $slug?>" name="slug" id="slug">
                        

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
                            <div id="video-div">
                                <div id="dZUpload" class="dropzone needsclick">
                                    <div class="dz-default dz-message">
                                        <span style="font-size: 18px;">Choose a file or Drag it here</span>
                                        <div class="up-icon">
                                            <img src="<?php echo $assets;?>img/upload_grey.png" alt="Upload File">
                                        </div>
                                    </div>

                                    
                                </div>
                                <span>Supported format mp4, avi, flv, 3gp, mkv; Max File size 1 GB</span>

                                <div class="dz-default dz-error">
                                </div>
                            </div>

                            <span style="font-weight: bold;color: #444;float: left;padding-bottom: 10px;display: block;text-align: left;">VIDEO TITLE  :  <p style="display: inline;font-size: large;color: #444;text-transform: capitalize;"><?php echo $videos['video_title']?></p>
                            </span>
                            <div>
                                <img src="<?php echo $iframe;?>" style="width: 100%;padding-bottom: 10px;" alt="Video Featured Image">
                            </div>
                            
                        

                        <div class="error" id="url[]_err"></div>
                        <!-- -->
                        <input type="hidden" class="form-control" name="lead_id" id="lead_id" value="<?php echo $videos['id']?>">
                        <p class="form-input" style="display: none;" id="video_urls"></p>
                        <input type="hidden" class="form-control" name="view" id="view" value="submit_video2">
                        
                        <p class="form-input">
                            <input type="hidden" class="form-control" name="video_title" id="video_title" value="<?php echo $videos['video_title']; ?>">
                        </p>
                        <input type="submit" name="" class="btn" name="video-submit-form-submit" value="NEXT"/>

                        </form>
                </div>
            </div>
            <div class="process-block02" style="display: none;">
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
            </div>

            <div class="process-block03" style="display: none;">
                <div class="right-panel">
                    <form action="#" class="" id="video-submit-form">


                        <input type="hidden" value="<?php echo $videos['id']?>" name="lead_id" id="lead_id">
                        <input type="hidden" value="<?php echo $slug?>" name="slug" id="slug">

                            <p class="form-element">
                            <div class="col-md-4" style="width: 43%;padding-left: 0px;">
                                <select name="country_code" class="country_code form-control" id="country_code"
                                        data-parsley-required-message="Country Code field is required."
                                        required
                                        tabindex="1"
                                >
                                    <option value="" <?php if($users['country_code'] == ''){ echo 'selected="selected"';}?> >Select Code</option>
                                    <?php foreach($countries->result() as $country){ ?>
                                        <option  value="+<?php echo $country->phonecode;?>" <?php if($users['country_code'] == $country->phonecode){ echo 'selected="selected"';}?>><?php echo $country->name;?> (+<?php echo $country->phonecode;?>)</option>
                                    <?php } ?>
                                </select>
                                <div class="error" id="country_code_err"><p></p></div>
                            </div>
                            <div class="col-md-8" style="width: 57%; padding-right: 0px;">
                                <input type="text" name="mobile" id="mobile" class="mobile form-control" placeholder="Mobile Number"
                                       style="width: 100%;"
                                       data-parsley-required-message="Mobile Nmuber field is required."
                                       required
                                       tabindex="2"
                                       data-parsley-type="number"
                                       data-parsley-type-message="Please enter the valid mobile number."
                                       value="<?php echo $users['mobile']?>"
                                >
                                <div class="error" id="mobile_err"></div>
                            </div>
                            </p>
                        <p class="checkbox">
                            <input name="same" type="checkbox" class="same" id="same" tabindex="4"/>
                            Same As Account Email
                        </p>
                        <p class="form-input">
                            <input type="email" class="form-control" name="email" id="email" placeholder="PayPal Email Address"
                                   data-parsley-required-message="Email Address field is required."
                                   data-parsley-type-message="Please enter the valid email address."
                                   required tabindex="3"
                                   value="<?php echo $users['paypal_email']?>"
                            >
                        <div class="error" id="email_err"></div>
                        </p>

                        <!--<p class="form-input">
                            <select name="gender" class="gender form-control" id="gender"
                                    data-parsley-required-message="Gender is Required"
                                    required
                                    tabindex="4"
                            >
                                <option value="" <?php if($users['gender'] == ''){echo 'selected="selected"';}?>>Select Gender</option>
                                    <option  value="male" <?php if($users['gender'] == 'male'){echo 'selected="selected"';}?>>Male</option>
                                    <option  value="female" <?php if($users['gender'] == 'female'){echo 'selected="selected"';}?>>Female</option>
                            </select>
                        <div class="error" id="gender_err"></div>
                        </p> -->
                        <p class="form-input">
                            <textarea type="text" class="form-control" name="address" id="address" placeholder="Address "
                                      data-parsley-required-message="This field is Mandatory." style="margin: 0px 0px 20px; height: 165px; width: 100%"
                                      pattern="[a-zA-Z0-9\s]+"
                                      data-parsley-pattern-message="Only alphabet and number are allowed."
                                      required tabindex="5"
                                      value="<?php echo $users['address'];?>"
                            ></textarea>
                        <div class="error" id="question4_err"></div>
                        </p>

                        <p class="form-input">
                            <input type="hidden" class="form-control" name="view" id="view" value="submit_video4">
                        </p>
                        <input type="submit" name="" class="btn" name="video-submit-form-submit"value="NEXT"/>
                    </form>
                </div>
            </div>

            <div class="process-block04" style="display: none;">
                <div class="right-panel">
                    <h2 style="margin-left: 0px;">Thank You For Submitting Video</h2>
                    <input type="hidden" value="<?php echo $slug?>" name="slug" id="slug">
                </div>
            </div>


    </section>
</div>
</div>
