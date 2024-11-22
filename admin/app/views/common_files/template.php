<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Remove Tap Highlight on Windows Phone IE -->
    <meta name="msapplication-tap-highlight" content="no"/>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="https://wooglobe.com">
    <link rel="icon" type="image/png" href="<?php echo $url;?>assets/favi/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="<?php echo $url;?>assets/favi/favicon-32x32.png" sizes="32x32">

    <title><?php echo $site_title;?> | <?php echo $title;?></title>

    <!--chosen.css multiselect-->
    <link rel="stylesheet" href="<?php echo $url;?>assets/chosen_selector/chosen.css">

    <!--chosen.css multiselect-->

	
    <?php foreach($commomCss as $css){?>
    	<link rel="stylesheet" href="<?php echo $asset.$css;?>" media="all"/>
    <?php } ?>

    <!-- matchMedia polyfill for testing media queries in JS -->
    <!--[if lte IE 9]>
        <script type="text/javascript" src="<?php echo $url;?>bower_components/matchMedia/matchMedia.js"></script>
        <script type="text/javascript" src="<?php echo $url;?>bower_components/matchMedia/matchMedia.addListener.js"></script>
        <link rel="stylesheet" href="<?php echo $url;?>assets/css/ie.css" media="all">
    <![endif]-->


	<style>
		.error{
			color: #e53935;
		}
		</style>
</head>
<body class="disable_transitions sidebar_main_open sidebar_main_swipe <?php if($this->uri->segment(1) == 'video_deals' || $this->uri->segment(1) == 'video_rights' || $this->uri->segment(1) == 'youtube_compilations'){ echo 'uk-height-1-1';}?> ">
  <!--<div class='progress' id="progress_div">
    <div class='bar' id='bar1'></div>
    <div class='percent' id='percent1'></div>
  </div>-->
  <input type="hidden" id="progress_width" value="0">
  <div class="preloadr-div"></div>
   <?php $this->load->view('common_files/header');?>
   <?php $this->load->view('common_files/sidebar');?>

   <?php echo $content;?>

   <?php //$this->load->view('common_files/rightbar');?>

    <!-- google web fonts -->
 <!-- <script href="https://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js"></script>-->
  <link href="https://fonts.googleapis.com/css?family=Roboto:500&display=swap" rel="stylesheet">
    <script>
        var base_url = '<?php echo $url;?>';

        /*WebFontConfig = {
            google: {
                families: [
                    'Source+Code+Pro:400,700:latin',
                    'Roboto:400,300,500,700,400italic:latin'
                ]
            }
        };
        (function() {
            var wf = document.createElement('script');
            wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
            '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
            wf.type = 'text/javascript';
            wf.async = 'true';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(wf, s);
        })();*/
    </script>
	
   
   <?php foreach($commonJs as $js){?>
    	<script src="<?php echo $asset.$js;?>"></script>
    <?php } ?>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

  <!--<script type="text/javascript">
      window.onload=function(){
          $(function() {
              $("input[name='publish_now_facebook']").click(function() {
                  if ($("#publish_now_facebook").is(":checked")) {
                      $("#dvPinNo").slideUp();
                  } else {
                      $("#dvPinNo").slideDown();
                  }
              });
          });

          $(function() {
              $("input[name='publish_now_youtube']").click(function() {
                  if ($("#publish_now_youtube").is(":checked")) {
                      $("#dvPinNo2").slideUp();
                  } else {
                      $("#dvPinNo2").slideDown();
                  }
              });
          });
      }
  </script>-->

    <script>

        $(function() {
			
            if(isHighDensity()) {
                $.getScript( base_url+"assets/js/custom/dense.min.js", function(data) {
                    // enable hires images
                    altair_helpers.retina_images();
                });
            }
            if(Modernizr.touch) {
                // fastClick (touch devices)
                FastClick.attach(document.body);
            }
        });
        $window.load(function() {
            // ie fixes
            altair_helpers.ie_fix();
            <?php if($this->sess->flashdata('msg') != ''){?>
            $('#suc-msg').attr('data-message','<?php echo $this->sess->flashdata('msg');?>');
            $('#suc-msg').click();
            <?php } ?>
            <?php if($this->sess->flashdata('err') != ''){?>
            $('#err-msg').attr('data-message','<?php echo $this->sess->flashdata('err');?>');
            $('#err-msg').click();
            <?php } ?>
        });
    </script>


  <!--<script src="<?php /*echo $url;*/?>assets/chosen_selector/chosen.jquery.js" type="text/javascript"></script>
  <!--<script>
      $(".chosen-select").chosen();
  </script>-->

  <script>
      $('.md-input-wrapper').click(function() {
          $('.md-input-wrapper').addClass('uk-dropdown-shown');
      });

  </script>
   <?php //$this->load->view('common_files/switcher');?>
   <div class="uk-modal" id="password_model">
		<div class="uk-modal-dialog">
			<div class="uk-modal-header">
				<h3 class="uk-modal-title">Change Password </h3>
			</div>
			<div class="md-card">
				
			</div>
			<div class="md-card-content large-padding">
				<form id="form_validation" class="uk-form-stacked">
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1">
							<div class="parsley-row">
								<label for="old_password">Old Password<span class="req">*</span></label>
								<input type="password" data-parsley-required-message="This field is required." name="old_password" id="old_password" required class="md-input" />
								<div class="error"></div>
							</div>
						</div>

					</div>
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-2">
							<div class="parsley-row">
								<label for="new_password">New Password<span class="req">*</span></label>
								<input type="password" data-parsley-required-message="This field is required." name="new_password" id="new_password" required class="md-input" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" data-parsley-pattern-message="Your password must contain at least (1) lowercase, (1) uppercase, , (1) number, (1) special character and minmum 8 letter."/>
								<div class="error"></div>
							</div>
						</div>
						<div class="uk-width-medium-1-2">
							<div class="parsley-row">
								<label for="confirm_password">Confirm Password<span class="req">*</span></label>
								<input type="password" data-parsley-required-message="This field is required." name="confirm_password" id="confirm_password" required  class="md-input" data-parsley-equalto-message="Confirm password doesn't match with new password." data-parsley-equalto="#new_password" />
								<div class="error"></div>
							</div>
						</div>
					</div>

				</form>
			</div>
		
			<div class="uk-modal-footer uk-text-right">
				<button type="button" class="md-btn md-btn-flat uk-modal-close">Cancel</button><button type="button" id="password_save" class="md-btn md-btn-flat md-btn-flat-primary">Save</button>
			</div>
		</div>
	</div>
	   <div class="uk-modal" id="profile">
		<div class="uk-modal-dialog">
			<div class="uk-modal-header">
				<h3 class="uk-modal-title">Profile <i class="material-icons" data-uk-tooltip="{pos:'top'}" title="headline tooltip">&#xE8FD;</i></h3>
			</div>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias, aliquid amet animi aspernatur aut blanditiis doloribus eligendi est fugiat iure iusto laborum modi mollitia nemo pariatur, rem tempore. Dolor, excepturi.</p>
			<div class="uk-modal-footer uk-text-right">
				<button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button data-uk-modal="{target:'#modal_new'}" type="button" class="md-btn md-btn-flat md-btn-flat-primary">Open New Modal</button>
			</div>
		</div>
	</div>

  <div class="uk-modal" id="settings_model">
      <div class="uk-modal-dialog">
          <div class="uk-modal-header">
              <h3 class="uk-modal-title">Site Settings </h3>
          </div>
          <div class="md-card-content large-padding">
              <form id="form_validation1" class="uk-form-stacked">
                  <div class="uk-grid" data-uk-grid-margin>
                      <div class="uk-width-medium-1-2">
                          <div class="parsley-row">
                              <label for="site_title">Site Title<span class="req">*</span></label>
                              <input type="text" data-parsley-required-message="This field is required." name="site_title" id="site_title" required class="md-input" />
                              <div class="error"></div>
                          </div>
                      </div>
                      <div class="uk-width-medium-1-2">
                          <div class="parsley-row">
                              <label for="site_email">Site Email<span class="req">*</span></label>
                              <input type="email" data-parsley-required-message="This field is required." name="site_email" id="site_email" required class="md-input" data-parsley-type-message="Please enter the valid email address."/>
                              <div class="error"></div>
                          </div>
                      </div>

                  </div>
                  <div class="uk-grid" data-uk-grid-margin>
                      <div class="uk-width-medium-1-2">
                          <div class="parsley-row">
                              <label for="site_mobile">Site Mobile<span class="req">*</span></label>
                              <input type="text" data-parsley-required-message="This field is required." name="site_mobile" id="site_mobile" required class="md-input" />
                              <div class="error"></div>
                          </div>
                      </div>
                      <div class="uk-width-medium-1-2">
                          <div class="parsley-row">
                              <label for="site_mobile2">Site Mobile 2<span class="req">*</span></label>
                              <input type="text" data-parsley-required-message="This field is required." name="site_mobile2" id="site_mobile2" required class="md-input" />
                              <div class="error"></div>
                          </div>
                      </div>

                  </div>
                  <div class="uk-grid" data-uk-grid-margin>

                      <div class="uk-width-medium-1-2">
                          <div class="parsley-row">
                              <label for="site_address">Site Address<span class="req">*</span></label>
                              <input type="text" data-parsley-required-message="This field is required." name="site_address" id="site_address" required  class="md-input"  />
                              <div class="error"></div>
                          </div>
                      </div>
                      <div class="uk-width-medium-1-2">
                          <div class="parsley-row">
                              <label for="expense">Global Expense - %<span class="req">*</span></label>
                              <input type="text" data-parsley-required-message="This field is required." name="expense" id="expense" required  class="md-input"  />
                              <div class="error"></div>
                          </div>
                      </div>
                  </div>
                  <div class="uk-grid" data-uk-grid-margin>
                      <!--<div class="uk-width-medium-1-2">
                          <div class="parsley-row">

                              <select id="default_sr_template" name="default_sr_template" required data-parsley-required-message="This field is required." class="md-input">
                                  <option value="">Default SignRequest Template*</option>

                              </select>
                          </div>
                      </div>-->
                      <div class="uk-width-medium-1-2">
                          <div class="parsley-row">
                              <label for="payment_threshold">Payment Threshold<span class="req">*</span></label>
                              <input type="text" data-parsley-required-message="This field is required." name="payment_threshold" id="payment_threshold" required class="md-input" />
                              <div class="error"></div>
                          </div>
                      </div>
                      <div class="uk-width-medium-1-2">
                          <div class="parsley-row">
                              <label for="minimum_payment_yearly_threshhold">Payment Threshold Yearly<span class="req">*</span></label>
                              <input type="text" data-parsley-required-message="This field is required." name="minimum_payment_yearly_threshhold" id="minimum_payment_yearly_threshhold" required class="md-input" />
                              <div class="error"></div>
                          </div>
                      </div>

                  </div>
                  <div class="uk-grid" data-uk-grid-margin>

                      <div class="uk-width-medium-1-2">
                          <div class="parsley-row">
                              <label for="payment_yearly_duration">Payment Yearly Duration<span class="req">*</span></label>
                              <input type="text" data-parsley-required-message="This field is required." name="payment_yearly_duration" id="payment_yearly_duration" required class="md-input" />
                              <div class="error"></div>
                          </div>
                      </div>

                  </div>
                  <div class="uk-grid" data-uk-grid-margin>
                      <div class="uk-width-medium-1-1">
                          <div class="parsley-row">
                              <label for="description_footer">Video Description Footer<span class="req">*</span></label>
                              <textarea data-parsley-required-message="This field is required." name="description_footer" id="description_footer" required  class="md-input"  ></textarea>
                              <div class="error"></div>
                          </div>
                      </div>
                  </div>
                  <!--<div class="uk-grid" data-uk-grid-margin>
                      <div class="uk-width-medium-1-1">
                          <label for="description_footer">Related Videos Filters<span class="req">*</span></label>
                          <div class="uk-grid">
                              <div class="uk-width-medium-1-1">
                                  <input type="checkbox" name="related_category" id="related_category" value="1" data-md-icheck />
                                  <label for="related_category" class="inline-label"><b>Categories</b>
                                  </label>
                              </div>
                              <div class="uk-width-medium-1-1">
                                  <input type="checkbox" name="related_tags" id="related_tags" value="1" data-md-icheck />
                                  <label for="related_tags" class="inline-label"><b>Tags</b>
                                  </label>
                              </div>
                              <div class="error"></div>
                          </div>
                      </div>
                  </div>
                  <div class="uk-grid" data-uk-grid-margin>
                      <div class="uk-width-medium-1-1">
                          <label for="description_footer">Suggest Videos Filters<span class="req">*</span></label>
                          <div class="uk-grid">
                              <div class="uk-width-medium-1-1">
                                  <input type="checkbox" name="suggest_category" id="suggest_category" value="1" data-md-icheck />
                                  <label for="suggest_category" class="inline-label"><b>Categories</b>
                                  </label>
                              </div>
                              <div class="uk-width-medium-1-1">
                                  <input type="checkbox" name="suggest_tags" id="suggest_tags" value="1" data-md-icheck />
                                  <label for="suggest_tags" class="inline-label"><b>Tags</b>
                                  </label>
                              </div>
                              <div class="error"></div>
                          </div>
                      </div>
                  </div>-->
              </form>
          </div>
          <div class="uk-modal-footer uk-text-right">
              <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="settins_save" class="md-btn md-btn-flat md-btn-flat-primary">Save</button>
          </div>
      </div>
  </div>

	<button style="display: none" id="err-msg" class="md-btn" data-message="<a href='#' class='notify-action'>Undo</a> Danger message" data-status="danger" data-pos="bottom-center">Danger</button>
        <button style="display: none" id="suc-msg" class="md-btn" data-message="<a href='#' class='notify-action'>Clear</a> Success Message" data-status="success" data-pos="bottom-center">Success</button>
        <button style="display: none" id="war-msg" class="md-btn" data-message="<a href='#' class='notify-action'>Refresh</a> Warning Message" data-status="warning" data-pos="bottom-center">Warning</button>
        <button style="display: none" id="pas-msg" class="md-btn" data-message="<a href='#' class='notify-action'>Refresh</a> Password Reset" data-status="warning" data-pos="bottom-center">Password Reset</button>



<style>
    .publish_yt_sch{
        margin-top: 25px;
    }
    .publish_fb_sch .radio, .publish_yt_sch .radio {
        display: inline-block;
        cursor: pointer;
        user-select: none;
        text-align: left;
    }

    .publish_fb_sch .radio + .radio, .publish_yt_sch .radio + .radio {
        margin-top: 7px;
    }
    .publish_fb_sch .radio input, .publish_yt_sch .radio input {
        display: none;
    }
    .publish_fb_sch .radio input + span, .publish_yt_sch .radio input + span {
        display: inline-block;
        position: relative;
        padding-left: 23px;
    }
    #dvPinNo, #dvPinNo2{
        display: inline-block;
        margin-top: 25px;
        margin-bottom: 20px;
    }

    .publish_fb_sch .radio input + span:before, .publish_yt_sch .radio input + span:before {
        content: '';
        display: block;
        position: absolute;
        top: 0px;
        left: 0px;
        border-radius: 50%;
        margin-right: 5px;
        width: 14px;
        height: 14px;
        border: 2px solid #ccc;
        background: #fff;
    }
    .publish_fb_sch .radio input + span:after, .publish_yt_sch .radio input + span:after {
        content: '';
        display: block;
        width: 10px;
        height: 10px;
        background: #009688;
        position: absolute;
        border-radius: 50%;
        top: 4px;
        left: 4px;
        opacity: 0;
        transform: scale(0, 0);
        transition: all 0.2s cubic-bezier(0.64, 0.57, 0.67, 1.53);
    }
    .publish_fb_sch .radio input:checked + span:after, .publish_yt_sch .radio input:checked + span:after  {
        opacity: 1;
        transform: scale(1, 1);
    }

    .publish_fb_sch .radio input:checked + span:before, .publish_yt_sch .radio input:checked + span:before {
        border: 2px solid #009688;
    }

</style>


</body>
</html>
