<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 3/15/2018
 * Time: 10:58 AM
 */
?>
<section class="form-elements style-2">
    <div class="section-padding">
        <div class="right-panel">
            <h4>Set Your Password</h4>

            <form action="#" class="register-form" id="set-form">

                <input type="hidden" name="token" value="<?php echo $token;?>" >

                <div class="form-input">
                    <input type="email" class="form-control" name="email" id="emailn" placeholder="Email"
                        value="<?php echo $email;?>"
                           disabled
                    >
                    <div class="error" id="email_err"></div>
                </div>

                <div class="form-input">
                    <input type="password" class="form-control" name="password" id="password" placeholder="New Password"
                           data-parsley-required-message="New Password field is required."
                           required
                           tabindex="1"
                           pattern="(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$"
                           data-parsley-pattern-message="Password must contain UpperCase, LowerCase, Number, SpecialChar and min 8 Chars."
                           data-parsley-trigger="change"
                    >
                <div class="error" id="password_err"></div>
                </div>
                <div class="form-input">
                    <input type="password" class="form-control" name="cpassword" id="cpassword" placeholder="Confirm Password"
                           data-parsley-required-message="Confirm Password field is required."
                           required
                           tabindex="2"
                           data-parsley-equalto="#password"
                           data-parsley-equalto-message="Confirm password does not match with new password."
                    >
                <div class="error" id="cpassword_err"></div>
                </div>



                <div class="submit-input">
                    <input type="submit" class="btn" name="passwordset-form-submit" value="Set Password"
                           tabindex="3"
                    >

                </div>
            </form>
        </div><!-- /.right-panel -->
        <span class="bottom-text">

      </span>
    </div><!-- /.section-padding -->
</section><!-- /.form-elements -->

