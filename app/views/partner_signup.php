<?php
/**
 * Created by PhpStorm.
 * User: Usman Ali Sarwar
 * Date: 17/01/2018
 * Time: 3:12 PM
 */
?>
<section class="form-elements style-2">
    <div class="section-padding">
        <div class="right-panel">
            <h4>Create Your Partner Account</h4>

            <form action="#" class="register-form" id="sign-up-form">
                <p class="form-input">
                    <input type="text" class="form-control" name="full_name" id="full_name" placeholder="Full Name"
                           data-parsley-required-message="Full Name field is required."
                           pattern="[a-zA-Z0-9\s]+"
                           data-parsley-pattern-message="Only alphabet and number are allowed."
                           required
                           tabindex="1">
                <div class="error" id="full_name_err"></div>
                </p>

                <p class="form-input">
                    <input type="email" class="form-control" name="email" id="email" placeholder="Email Address"
                           data-parsley-required-message="Email Address field is required."
                           data-parsley-type-message="Please enter the valid email address."
                           required tabindex="2"
                           data-parsley-remote="<?php echo $url;?>check_email"
                           data-parsley-remote-options='{ "type": "POST", "dataType": "jsonp" }'
                           data-parsley-remote-message="This email address already exist in our system."
                           data-parsley-trigger="change"
                    >
                <div class="error" id="email_err"></div>
                </p>

                <p class="form-input">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password"
                           data-parsley-required-message="Password field is required."
                           required
                           tabindex="3"
                           pattern="(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$"
                           data-parsley-pattern-message="Password must contain UpperCase, LowerCase, Number, SpecialChar and min 8 Chars."
                           data-parsley-trigger="change"
                    >
                <div class="error" id="password_err"></div>
                </p>
                <p class="form-input">
                    <input type="password" class="form-control" name="cpassword" id="cpassword" placeholder="Confirm Password"
                           data-parsley-required-message="Confirm Password field is required."
                           required
                           tabindex="4"
                           data-parsley-equalto="#password"
                           data-parsley-equalto-message="Confirm password does not match with password."
                    >
                <div class="error" id="cpassword_err"></div>
                </p>


                <p class="checkbox">
                    <input name="rememberme" type="checkbox" class="rememberme pull-left"
                           data-parsley-required-message="Accept the terms and conditions."
                           required
                           tabindex="5"/>
                    I agree the
                    <a href="#"> terms and conditions</a>
                </p>
                <p class="submit-input">
                    <input type="submit" class="btn" name="signup-form-submit" value="Register Now"
                           data-parsley-required-message="This field is required."
                           required
                           tabindex="6"
                    >
                    <span class="alt-methods">
              <span>Or Register With</span>
             <a class="facebook" href="<?php echo $fburl;?>"><i class="fa fa-facebook-official"></i></a>
              <a class="google" href="<?php echo $gurl;?>"><i class="fa fa-google-plus"></i></a>
            </span>
                </p>
            </form>
        </div><!-- /.right-panel -->
        <span class="bottom-text">
        Already Have a Partner Account? <a href="<?php echo $url;?>signin">Sign In</a>
      </span>
    </div><!-- /.section-padding -->
</section><!-- /.form-elements -->
