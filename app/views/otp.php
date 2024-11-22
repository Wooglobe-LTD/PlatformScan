<?php
/**
 * Created by PhpStorm.
 * User: Usman Ali Sarwar
 * Date: 19/01/2018
 * Time: 4:49 PM
 */?>
<section class="form-elements style-2">
    <div class="section-padding">
        <div class="right-panel">
            <h4>Enter The OTP</h4>
            <form class="sign-in-form" id="otp-form" action="#" method="post">
                <p class="form-input name">
                    <input type="hidden" name="token" value="<?php echo $token;?>">
                    <input type="text" name="otp" id="otp" class="input form-control" value="" placeholder="OTP Code"
                           data-parsley-required-message="The OTP Code field is required."
                           data-parsley-otp
                           data-parsley-otp-message="Please enter the valid OTP!"
                           required tabindex="1"
                           data-parsley-type="integer"
                           data-parsley-type-message="OTP must be integer!"
                    />
                <div class="error" id="otp_err"></div>
                </p>

                <p class="submit-input">
                    <input type="submit" name="wp-submit" id="wp-submit" class="btn" value="Submit" tabindex="2" />
                </p>

                <a href="javascript:void(0);" class="pull-right" id="regenerate_otp" title="Regenerate OTP"> Regenerate OTP</a>

            </form>
        </div><!-- /.right-panel -->

        <span class="bottom-text">
        <a href="<?php echo $url;?>signin">Sign In</a>
      </span>
    </div><!-- /.section-padding -->
</section><!-- /.form-elements -->
<script>
    var token = "<?php echo $token;?>";
</script>