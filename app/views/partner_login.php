<?php
/**
 * Created by PhpStorm.
 * User: Usman Ali Sarwar
 * Date: 17/01/2018
 * Time: 11:28 AM
 */
?>
<section class="form-elements style-2">
    <div class="section-padding">
        <div class="right-panel">
            <h4>Have a partner account? Sign in</h4>
            <form class="sign-in-form" id="sign-in-form-partner" action="#" method="post">
                <p class="form-input name">
                    <input type="email" name="email" id="email" class="input form-control" value="" placeholder="Email Address"
                           data-parsley-required-message="The Email Address field is required."
                           data-parsley-type-message="Please enter the valid email address."
                           required tabindex="1"
                    />
                <div class="error" id="email_err"></div>
                </p>
                <p class="form-input pswd">
                    <input type="password" name="password" id="password" class="input form-control" value="" placeholder="Password"
                           data-parsley-required-message="The password field is required."
                           required tabindex="2"
                           data-parsley-users
                           data-parsley-users-message="Invalid email address or password."
                    />
                    <div class="error" id="password_err"></div>
                </p>
                <p class="submit-input">
                    <input type="submit" name="wp-submit" id="wp-submit" class="btn" value="Log In Now" tabindex="3" />
                </p>
                <p class="checkbox">
                    <input name="rememberme" type="checkbox" class="rememberme" value="Remember Me" tabindex="4"/>
                    Keep Me Signed in
                    <a href="<?php echo $url;?>partner-forgot-password" class="pull-right" title="Forgot Password"> Forgot password?</a>
                </p>
                <p class="form-input">
            <span class="alt-methods">
              <span>Or Sign in using</span>
              <a class="facebook" href="<?php echo $fburl;?>"><i class="fa fa-facebook-official"></i></a>
              <a class="google" href="<?php echo $gurl;?>"><i class="fa fa-google-plus"></i></a>
            </span>
                </p>
            </form>
        </div><!-- /.right-panel -->

        <span class="bottom-text">
        Don’t Have a Partner Account? <a href="<?php echo $url;?>partner-signup">Sign Up</a>
      </span>
    </div><!-- /.section-padding -->
</section><!-- /.form-elements -->
