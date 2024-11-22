<?php
/**
 * Created by PhpStorm.
 * User: T3500
 * Date: 2/8/2018
 * Time: 5:28 PM
 */
?>
<!--<section id="google-map">
    <div class="map-container">
        <div id="googleMaps" class="google-map-container"></div>
    </div><!-- /.map-container -->
<!--</section>--><!-- /#google-map-->

<style>
    .contact-details .wpcf7-form input[type="text"]{
         width: 100%;
         margin-bottom: 15px;
     }
    .contact-details .wpcf7-form input[type="email"]{
        width: 100%;
        margin-bottom: 15px;
    }
    .contact-details .wpcf7-form textarea{
        width: 100%;
        margin-bottom: 15px;
    }
    .parsley-errors-list li{
        margin-bottom: 15px;
    }
</style>


<section class="contact-details">
    <div class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">

                    <h2 class="section-title">Contact us</h2><!-- /.section-title -->

                    <form action="" method="post" id="contact-form" class="wpcf7-form contact-form">
                        <div class="col-md-6">
                            <input type="text" id="name" name="name" value="" class="wpcf7-form-control" placeholder="Name (required)"
                                   data-parsley-required-message="Name field is required."
                                   pattern="[a-zA-Z\s]+"
                                   data-parsley-pattern-message="Only alphabet are allowed."
                                   required
                                   tabindex="1"
                            >
                        </div>
                        <div class="col-md-6">

                            <input type="email" id="email" name="email" value="" class="wpcf7-form-control" placeholder="Email (required)"
                                   data-parsley-required-message="Email field is required."
                                   data-parsley-type-message="Please enter the valid email address."
                                   required tabindex="2"
                                   data-parsley-trigger="change"
                            >
                        </div>
                        <div class="col-md-12">
                            <input type="text" id="subject" name="subject" value="" class="wpcf7-form-control" placeholder="Subject (required)"
                                   data-parsley-required-message="Subject field is required."
                                   pattern="[a-zA-Z0-9\s]+"
                                   data-parsley-pattern-message="Only alphabet and numbers are allowed."
                                   required
                                   tabindex="3"
                            >
                        </div>
                        <div class="col-md-12">
                            <textarea id="message" name="message" class="wpcf7-form-control" placeholder="Message (required)"
                                      data-parsley-required-message="Message field is required."
                                      required
                                      tabindex="4"
                            ></textarea>
                        </div>
                        <div class="col-md-12">
                            <div class="g-recaptcha" data-sitekey="6Lfc6n0UAAAAACLgEjaXFdpuG3SgsEQ10L_44AF0"></div>
                            <br>
                            <div class="error" id="g-recaptcha-response_err"></div>
                        </div>
                        <div class="col-md-6">
                            <input type="submit" id="submit" name="submit" class="wpcf7-form-control btn" value="Send Message" tabindex="5">
                        </div>
                        <div class="contact-message"></div>
                        <div class="error-message"></div>
                    </form>
                </div>

                <div class="col-sm-4">
                    <aside class="sidebar">
                        <div class="widget widget_address">
                            <!--<div class="item media">-->
                               <!-- <div class="item-icon media-left"><i class="ti-location-pin"></i></div>--><!-- /.item-icon -->
                                <!--<div class="item-details media-body">-->
                                   <!-- <h3 class="item-title">Address</h3>--><!-- /.item-title -->
                                 <!--   <span>
                     <?php /*echo $setting->site_address;*/?>
                    </span>-->
                                <!--</div>--><!-- /.item-details -->
                            <!--</div>--><!-- /.item -->

                            <div class="item media">
                                <div class="item-icon media-left"><i class="ti-mobile"></i></div><!-- /.item-icon -->
                                <div class="item-details media-body">
                                    <h3 class="item-title">Phone</h3><!-- /.item-title -->
                                    <span>
                                        <?php echo $setting->site_mobile;?>
                                    </span>
                                    <span>
                                        <?php echo $setting->site_mobile2;?>
                                    </span>

                                </div><!-- /.item-details -->
                            </div><!-- /.item -->

                            <div class="item media">
                                <div class="item-icon media-left"><i class="ti-email"></i></div><!-- /.item-icon -->
                                <div class="item-details media-body">
                                    <h3 class="item-title">Email</h3><!-- /.item-title -->
                                    <span>
                      <a href="mailto:<?php echo $setting->site_email;?>"><?php echo $setting->site_email;?></a>
                    </span>
                                </div><!-- /.item-details -->
                            </div><!-- /.item -->
                        </div><!-- /.widget -->
                    </aside><!-- /.inner-bg -->
                </div>
            </div><!-- /.row -->
        </div><!-- /.container -->
    </div><!-- /.section-padding -->
</section><!-- /.contact-details -->
<script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyA-Aer7z0YulZKO-ks2kUjxrXE3kf7XMfk"></script>