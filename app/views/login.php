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
            <h4>Have a client account?</h4>
            <form class="sign-in-form" id="sign-in-form" action="#" method="post">


                <div class="form-input">
                    <input type="email" name="email" id="email" class="input form-control" value="" placeholder="Email Address"

                           data-parsley-required-message="The Email Address field is required."
                           data-parsley-type-message="Please enter the valid email address."
                           required tabindex="1"
                    />
                <div class="error" id="email_err"></div>
                </div>

                <div class="form-input pswd">
                    <input type="password" name="password" id="password" class="input form-control" value="" placeholder="Password"
                           data-parsley-required-message="The password field is required."
                           required tabindex="2"

                    />
                    <!--data-parsley-users
                    data-parsley-users-message="Invalid email address or password."-->
                    <div class="error" id="password_err"></div>
                </div>
                <?php if($fail_try >= 2){?>
                <div class="form-input">
                    <div class="g-recaptcha" data-sitekey="6Lfc6n0UAAAAACLgEjaXFdpuG3SgsEQ10L_44AF0"></div>
                    <br>
                    <div class="error" id="g-recaptcha-response-login_err"></div>
                </div>
                <?php } ?>

                <div class="submit-input">
                    <input type="submit" name="wp-submit" id="wp-submit" class="btn" value="Log In" tabindex="3" />
                </div>
                <div class="checkbox">
                    <input name="rememberme" type="checkbox" class="rememberme" value="Remember Me" tabindex="4"/>
                    Keep me Logged In
                    <a href="<?php echo $url;?>forgot-password" class="pull-right" title="Forgot Password"> Forgot password?</a>
                </div>
                <div class="form-input">
                <!-- <span class="alt-methods">
                  <span>Or Sign in using</span>
                  <a class="facebook" href="<?php echo $fburl;?>"><i class="fa fa-facebook-official"></i></a>
                  <a class="google" href="<?php echo $gurl;?>"><i class="fa fa-google-plus"></i></a>
                </span> -->
                </div>
            </form>
        </div><!-- /.right-panel -->

        <!--<span class="bottom-text">
        Don’t Have a Client Account? <a href="<?php /*echo $url;*/?>signup">Sign Up</a>
      </span>-->
    </div><!-- /.section-padding -->
</section><!-- /.form-elements -->
<script>
    var fail_try = '<?php echo $fail_try;?>';
</script>
<script>
    <?php if (isset($account_unblock_msg)): ?>
        var load_msg = '<?php echo $account_unblock_msg; ?>';
    <?php endif; ?>
</script>