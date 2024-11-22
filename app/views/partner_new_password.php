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
            <h4>Reset Password</h4>

            <form action="#" class="register-form" id="reset-form">


                <input type="hidden" name="token" value="<?php echo $token;?>" >
                <input type="hidden" name="otp" value="<?php echo $otp;?>" >

                <p class="form-input">
                    <input type="password" class="form-control" name="password" id="password" placeholder="New Password"
                           data-parsley-required-message="New Password field is required."
                           required
                           tabindex="1"
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
                           tabindex="2"
                           data-parsley-equalto="#password"
                           data-parsley-equalto-message="Confirm password does not match with new password."
                    >
                <div class="error" id="cpassword_err"></div>
                </p>



                <p class="submit-input">
                    <input type="submit" class="btn" name="signup-form-submit" value="Reset Now"
                           tabindex="3"
                    >

                </p>
            </form>
        </div><!-- /.right-panel -->
        <span class="bottom-text">
       <a href="<?php echo $url;?>partner-signin">Sign In</a>
      </span>
    </div><!-- /.section-padding -->
</section><!-- /.form-elements -->
