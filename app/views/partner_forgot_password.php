<?php
/**
 * Created by PhpStorm.
 * User: Usman Ali Sarwar
 * Date: 19/01/2018
 * Time: 12:50 PM
 */
?>
<section class="form-elements style-2">
    <div class="section-padding">
        <div class="right-panel">
            <h4>Forgot Password</h4>
            <form class="sign-in-form" id="forgot-password-form" action="#" method="post">
                <p class="form-input name">
                    <input type="email" name="email" id="email" class="input form-control" value="" placeholder="Email Address"
                           data-parsley-required-message="The Email Address field is required."
                           data-parsley-forgot
                           data-parsley-forgot-message="This email address does not exist in our system!"
                           required tabindex="1"
                    />
                <div class="error" id="email_err"></div>
                </p>

                <p class="submit-input">
                    <input type="submit" name="wp-submit" id="wp-submit" class="btn" value="Submit" tabindex="2" />
                </p>


            </form>
        </div><!-- /.right-panel -->

        <span class="bottom-text">
        <a href="<?php echo $url;?>partner-signin">Sign In</a>
      </span>
    </div><!-- /.section-padding -->
</section><!-- /.form-elements -->
