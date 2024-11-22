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
                <li class="active">Video Story</li>
                <li>Thank You</li>
            </ul>
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
        </section>
    </div>
</div>

