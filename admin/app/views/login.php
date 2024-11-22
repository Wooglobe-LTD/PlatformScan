<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Remove Tap Highlight on Windows Phone IE -->
    <meta name="msapplication-tap-highlight" content="no"/>

    <link rel="icon" type="image/png" href="<?php echo $url;?>assets/favi/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="<?php echo $url;?>assets/favi/favicon-32x32.png" sizes="32x32">

    <title><?php echo $site_title;?> | <?php echo $title;?> </title>

    <link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500' rel='stylesheet' type='text/css'>

    <!-- uikit -->
    <link rel="stylesheet" href="<?php echo $asset;?>bower_components/uikit/css/uikit.almost-flat.min.css"/>

    <!-- altair admin login page -->
    <link rel="stylesheet" href="<?php echo $asset;?>assets/css/login_page.min.css" />
    <link rel="stylesheet" href="<?php echo $asset;?>assets/css/progress_style.css" />
    
     <!-- altair admin -->
    <link rel="stylesheet" href="<?php echo $asset;?>assets/css/main.min.css" media="all">
    <link rel="stylesheet" href="<?php echo $asset;?>assets/css/custom.css" media="all">

    <!-- themes -->
    <link rel="stylesheet" href="<?php echo $asset;?>assets/css/themes/themes_combined.min.css" media="all">

</head>
	<style>
		.error{
			color: #e53935;
		}
	</style>
<body class="login_page" id="preloader_dynamic">
<!--<div class='progress' id="progress_div">
    <div class='bar' id='bar1'></div>
    <div class='percent' id='percent1'></div>
  </div>-->
   <input type="hidden" id="progress_width" value="0">
   <div class="preloadr-div"></div>
    <div class="login_page_wrapper" >
        <div class="md-card" id="login_card">
            <div class="md-card-content large-padding" id="login_form">
                <div class="login_heading">
                    <div class="user_avatar"></div>
                </div>
                <form id="form_validation" class="uk-form-stacked">
                    <div class="uk-form-row">
                        <div class="parsley-row">
							<label for="username">Username</label>
							<input class="md-input" type="text" id="username" name="username" data-parsley-required-message="This field is required." required tabindex="1" autofocus="autofocus"/>
							<div class="error"></div>
						</div>
                    </div>
                    <div id="g-recaptcha">
                        <div class="uk-form-row">
                            <div class="parsley-row">
                                <label for="password">Password</label>
                                <input class="md-input" type="password" id="password" name="password" data-parsley-required-message="This field is required." required tabindex="2" />
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    <!-- gcaptcha -->
                    <div class="uk-form-row" id="grecaptcha" >
                        <?php if ($fail_try > $allow_failed_login_attempts): ?>
                        <div class="parsley-row">
                            <div class="g-recaptcha" name="g-recaptcha" data-sitekey="6Lfc6n0UAAAAACLgEjaXFdpuG3SgsEQ10L_44AF0"></div>
                            <br>
                        <div class="error" id="g-recaptcha-response-login_err"></div>
                        <?php endif; // if ($fail_try > $allow_failed_login_attempts): ?>
                    </div>
                    <!-- gcaptcha -->
                    <div class="uk-margin-medium-top">
                        <a href="javascript:void(0);" id="submit_login" class="md-btn md-btn-primary md-btn-block md-btn-large" tabindex="3">Sign In</a>
                    </div>
                   <!--<div class="uk-margin-medium-top">
                        <a href="<?php /*echo $url;*/?>facebook" id="facebook" class="md-btn md-btn-primary md-btn-block md-btn-large" tabindex="4">Log In With Facebook</a>
                    </div>-->
                    
                    <div class="uk-margin-top">
                        <a href="javascript:void(0);" id="login_help_show" class="uk-float-right">Need help?</a>
                        
                    </div>
                </form>
            </div>
            <div class="md-card-content large-padding uk-position-relative" id="login_help" style="display: none">
                <button type="button" class="uk-position-top-right uk-close uk-margin-right uk-margin-top back_to_login"></button>
                <h2 class="heading_b uk-text-success">Can't log in?</h2>
                <p>Here’s the info to get you back in to your account as quickly as possible.</p>
                <p>First, try the easiest thing: if you remember your password but it isn’t working, make sure that Caps Lock is turned off, and that your username is spelled correctly, and then try again.</p>
                <p>If your password still isn’t working, it’s time to <a href="#" id="password_reset_show">reset your password</a>.</p>
            </div>
            <div class="md-card-content large-padding" id="login_password_reset" style="display: none">
                <button type="button" class="uk-position-top-right uk-close uk-margin-right uk-margin-top back_to_login"></button>
                <h2 class="heading_a uk-margin-large-bottom">Reset password</h2>
                <form id="form_validation1" class="uk-form-stacked">
                    <div class="uk-form-row">
                       <div class="parsley-row">
							<label for="email">Your Email Address</label>
							<input class="md-input" type="email" id="email" name="email" data-parsley-required-message="This field is required." data-parsley-type-message="Please enter the valid email address." required />
							<div class="error"></div>
						</div>
                    </div>
                    <div class="uk-margin-medium-top">
                        <a href="javascript:void(0);" id="reset" class="md-btn md-btn-primary md-btn-block">Reset Password</a>
                    </div>
                </form>
            </div>
            
        </div>
        <button style="display: none" id="err-msg" class="md-btn" data-message="<a href='#' class='notify-action'>Undo</a> Danger message" data-status="danger" data-pos="bottom-center">Danger</button>
        <button style="display: none" id="suc-msg" class="md-btn" data-message="<a href='#' class='notify-action'>Clear</a> Success Message" data-status="success" data-pos="bottom-center">Success</button>
        <button style="display: none" id="war-msg" class="md-btn" data-message="<a href='#' class='notify-action'>Refresh</a> Warning Message" data-status="warning" data-pos="bottom-center">Warning</button>
    </div>

    <!-- common functions -->
    <script src="<?php echo $asset;?>assets/js/common.min.js"></script>

    <!-- uikit functions -->
    <script src="<?php echo $asset;?>assets/js/uikit_custom.min.js"></script>
    <!-- altair core functions -->
    <script src="<?php echo $asset;?>assets/js/altair_admin_common.js"></script>
    <!-- page specific plugins -->
    <!-- parsley (validation) -->
    <script>
    // load parsley config (altair_admin_common.js)
    altair_forms.parsley_validation_config();
	var base_url = '<?php echo $url;?>';
    </script>
    <script src="<?php echo $asset;?>bower_components/parsleyjs/dist/parsley.min.js"></script>
    <!--  forms validation functions -->
    <script src="<?php echo $asset;?>assets/js/pages/forms_validation.js"></script>
    
    <!--  preloaders functions -->
    <script src="<?php echo $asset;?>assets/js/pages/components_preloaders.min.js"></script>
    
    <!--  notifications functions -->
    <script src="<?php echo $asset;?>assets/js/pages/components_notifications.min.js"></script>

    <!-- google recaptcha -->
    <script src="https://www.google.com/recaptcha/api.js"></script>
	
    <!-- swal msg box -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>	

    <!-- altair login page functions -->
    <script src="<?php echo $asset;?>assets/js/pages/login.js"></script>
    <!--<script src="<?php /*echo $asset;*/?>assets/js/progress.js"></script>-->
    <script>
        // check for theme
        if (typeof(Storage) !== "undefined") {
            var root = document.getElementsByTagName( 'html' )[0],
                theme = localStorage.getItem("altair_theme");
            if(theme == 'app_theme_dark' || root.classList.contains('app_theme_dark')) {
                root.className += ' app_theme_dark';
            }
        }
    </script>

</body>
</html>
