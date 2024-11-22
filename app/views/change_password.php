<?php
/**
 * Created by PhpStorm.
 * User: T3500
 * Date: 1/29/2018
 * Time: 12:44 PM
 */
?>

<?php $this->load->view('common_files/profile_header');?>

<style>
    .Zebra_DatePicker_Icon_Wrapper{
        width: 100%;
    }
	.field-icon {
	   float: right;
	   margin-left: -25px;
	   margin-right: 5px;
	   margin-top: 12px;
	   position: relative;
	   cursor:pointer;
	   z-index: 2;
	} 
</style>



<section class="author-page-contents">
    <div class="section-padding">
        <div class="container">
            <?php $this->load->view('common_files/profile_nav');?>

            <div class="author-contents">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="about-author">
                            <div class="upload-video">
                                <form action="#" class="upload-form" id="change">

                                    <div class="col-md-12">

                                        <p class="form-element">
                                            <label for="full_name">Old Password</label>
                                            <input type="password" name="old" id="old" class="old form-control" placeholder="Old Password"
                                                   data-parsley-required-message="Old Password field is required."
                                                   required
                                                   data-parsley-old
                                                   data-parsley-old-message="Invalid old password."
                                                   tabindex="1"
                                                   value=""
                                            >
											<!--<span toggle="#new" class="fa fa-fw fa-eye field-icon toggle-password"></span>-->
                                        <div class="error" id="old_err"></div>
                                        </p>

                                    </div>
                                    <div class="col-md-12">
                                        <p class="form-element">
                                            <label for="new">New Password</label>
                                            <input type="password" name="new" id="new" class="new form-control" placeholder="New Password"
                                                   data-parsley-required-message="New Password field is required."
                                                   required
                                                   pattern="(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$"
                                                   data-parsley-pattern-message="Password must contain UpperCase, LowerCase, Number, SpecialChar and min 8 Chars."
                                                   tabindex="2"
                                                   value=""
                                            >
                                        <div class="error" id="new_err"></div>
                                        </p>
                                    </div>
                                    <div class="col-md-12">
                                        <p class="form-element">
                                            <label for="confirm">Confirm New Password</label>
                                            <input type="password" name="confirm" id="confirm" class="confirm form-control" placeholder="Confirm New Password"
                                                   data-parsley-required-message="Confirm New Password field is required."
                                                   required
                                                   tabindex="3"
                                                   value=""
                                                   data-parsley-equalto="#new"
                                                   data-parsley-equalto-message="Confirm new password does not match with new password."
                                            >
                                        <div class="error" id="confirm_err"></div>
                                        </p>
                                    </div>




                                    <div class="col-md-12">
                                        <input type="submit" value="Change Password" class="submit" name="submit" tabindex="4">
                                    </div>
                                </form>
                            </div><!-- /.upload-video -->
                        </div><!-- /.about-author -->
                    </div>

                    <?php //$this->load->view('common_files/profile_right');?>
                </div>
            </div>
        </div><!-- /.container -->
    </div><!-- /.section-padding -->
</section><!-- /.author-page-contents -->
