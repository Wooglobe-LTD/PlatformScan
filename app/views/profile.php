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
    #file{
        max-width: 100% !important;
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
                                <form action="#" class="upload-form" id="profile">
                                    <div class="col-md-12">
                                        <?php $pic_url = $data->picture;
                                        if(strpos($pic_url, "http") === false){
                                            $pic_url = $url.$pic_url;
                                        }
                                        ?>
                                        <div class="form-element file-type">
                                            <label for="file">Profile Picture</label>
                                            <input type="file" name="picture" class="file form-control dropify" id="file"
                                                   data-default-file="<?php echo $pic_url;?>"
                                                   data-show-remove="false"
                                                   data-errors-position="outside"
                                                   data-allowed-file-extensions="jpg jpeg png gif"
                                                   data-max-file-size="3M"
                                                   data-min-width="50"
                                                   data-max-width="300"
                                                   data-min-height="50"
                                                   data-max-height="300"
                                            >
                                            <span>Supported format jpg, jpeg, png, gif; Max File size 3 MB</span>
                                        </div><br/>
                                    </div>
                                    <div class="col-md-12">

                                        <div class="form-element">
                                            <label for="full_name">Full Name</label>
                                            <input type="text" name="full_name" id="full_name" class="full_name form-control" placeholder="Full Name"
                                                   data-parsley-required-message="Full Name field is required."
                                                   pattern="[a-zA-Z\s]+"
                                                   data-parsley-pattern-message="Only alphabet are allowed."
                                                   required
                                                   tabindex="1"
                                                   value="<?php echo $data->full_name;?>"
                                            >
                                        <div class="error" id="full_name_err"></div>
                                        </div><br/>

                                    </div>
                                    <div class="col-md-12">

                                        <div class="form-element">
                                            <label for="full_name">PayPal Email</label>
                                            <input type="email" name="paypal_email" id="paypal_email" class="email form-control" placeholder="PayPal Email"
                                                   data-parsley-required-message="Email  is required."
                                                   required
                                                   tabindex="1"
                                                   value="<?php echo $data->paypal_email;?>"
                                            >
                                            <div class="error" id="email_err"></div>
                                        </div><br/>

                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-element">
                                            <label for="gender">Gender</label>
                                            <select name="gender" class="gender form-control" id="gender"
                                                    data-parsley-required-message="Gender field is required."
                                                    required
                                                    tabindex="2"
                                            >
                                                <option value="">Select a Gender</option>
                                                <option <?php if ($data->gender == 'Male'){ echo 'selected="selected"';}?> value="Male">Male</option>
                                                <option <?php if ($data->gender == 'Female'){ echo 'selected="selected"';}?> value="Female">Female</option>
                                                <option <?php if ($data->gender == 'Transgender Female'){ echo 'selected="selected"';}?> value="Transgender Female">Transgender Female</option>
                                                <option <?php if ($data->gender == 'Transgender Male'){ echo 'selected="selected"';}?> value="Transgender Male">Transgender Male</option>
                                                <option <?php if ($data->gender == 'Gender Variant/Non-Conforming'){ echo 'selected="selected"';}?> value="Gender Variant/Non-Conforming">Gender Variant/Non-Conforming</option>
                                                <option <?php if ($data->gender == 'Not Listed'){ echo 'selected="selected"';}?> value="Not Listed">Not Listed</option>
                                                <option <?php if ($data->gender == 'Prefer Not to Answer'){ echo 'selected="selected"';}?> value="Prefer Not to Answer">Prefer Not to Answer</option>

                                            </select>
                                        <div class="error" id="gender_err"></div>
                                        </div><br/>
                                    </div>
                                    <div class="col-md-12">

                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-element">
                                            <label for="dob">Date Of Birth</label>
                                            <input type="text" name="dob" id="dob" class="dob form-control datepicker" placeholder="Date Of Birth"
                                                   data-parsley-required-message="Date Of Birth field is required."
                                                   tabindex="3"
                                                   value="<?php echo $data->dob;?>"
                                            >
                                        <div class="error" id="dob_err"></div>
                                        </div><br/>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-element">
                                            <label for="country_id">Country</label>
                                            <select name="country_id" class="country_id form-control" id="country_id"
                                                    data-parsley-required-message="Country field is required."
                                                    required
                                                    tabindex="4"
                                            >
                                                <option value="">Select A Country</option>
                                                <?php foreach($countries->result() as $country){ ?>
                                                    <option <?php if ($data->country_id == $country->id){ echo 'selected="selected"';}?> value="<?php echo $country->id;?>"><?php echo $country->name;?></option>
                                                <?php } ?>
                                            </select>
                                        <div class="error" id="full_name_err"></div>
                                        </div><br/>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-element">
                                            <label for="state_id">State</label>

                                            <input type="text" name="state_id" id="state_id" class="state_id form-control" placeholder="State"
                                                   data-parsley-required-message="State field is required."
                                                   required
                                                   tabindex="3"
                                                   value="<?php echo $data->state_id;?>"
                                            >
                                        <div class="error" id="state_id_err"></div>
                                        </div><br/>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-element">
                                            <label for="city_id">City</label>
                                            <input type="text" name="city_id" id="city_id" class="city_id form-control" placeholder="City"
                                                   data-parsley-required-message="City field is required."
                                                   required
                                                   tabindex="3"
                                                   value="<?php echo $data->city_id;?>"
                                            >
                                        <div class="error" id="city_id_err"></div>
                                        </div><br/>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-element file-type">
                                            <label for="address">Address</label>
                                            <textarea name="address" class="address form-control" id="address" placeholder="Address"
                                                      data-parsley-required-message="Address field is required."
                                                      required
                                                      tabindex="7"
                                            ><?php echo $data->address;?></textarea>
                                        <div class="error" id="address_err"></div>
                                        </div><br/>
                                    </div>
                                    <div class="col-md-12">

                                        <div class="form-element">
                                            <label for="mobile">Mobile Number</label>
                                            <div class="col-md-4 res-pad" style="padding-left: 0px;">
                                                <select name="country_code" class="country_code form-control" id="country_code"
                                                        data-parsley-required-message="Country Code field is required."
                                                        required
                                                        tabindex="8"
                                                >
                                                    <option value="">Select A Country Code</option>
                                                    <?php foreach($countries->result() as $country){ ?>
                                                        <option <?php if ($data->country_code == '+'.$country->phonecode){ echo 'selected="selected"';}?> value="+<?php echo $country->phonecode;?>"><?php echo $country->name;?> (+<?php echo $country->phonecode;?>)</option>
                                                    <?php } ?>
                                                </select>
                                            <div class="error" id="country_code_err"></div>
                                            </div>
                                            <div class="col-md-8 res-pad" style="padding-right: 0px;">
                                                <input type="text" name="mobile" id="mobile" class="mobile form-control" placeholder="Mobile Number"
                                                       data-parsley-required-message="Mobile Nmuber field is required."
                                                       required
                                                       tabindex="9"
                                                       data-parsley-type="number"
                                                       data-parsley-type-message="Please enter the valid mobile number."
                                                       value="<?php echo $data->mobile;?>" >
                                                <div class="error" id="mobile_err"></div>
                                            </div>

                                        </div>

                                    </div><br/><br/>
                                    <div class="col-md-12">
                                        <div class="form-element">
                                            <label for="zip_code">Zipcode</label>
                                            <input type="text" name="zip_code" id="zip_code" class="zip_code form-control" placeholder="Zipcode"
                                                   data-parsley-required-message="Zipcode field is required."
                                                   pattern="[a-zA-Z0-9\s]+"
                                                   data-parsley-pattern-message="Only alphabets and number are allowed."
                                                   required
                                                   tabindex="10"
                                                   value="<?php echo $data->zip_code;?>"
                                            >
                                        <div class="error" id="zip_code_err"></div>
                                        </div>
                                    </div><br/>


                                    <div class="col-md-12">
                                        <input type="submit" value="Save" class="submit" name="submit" tabindex="10">
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
<script>
    var country_id = "<?php echo $data->country_id;?>";
    var state_id = "<?php echo $data->state_id;?>";
    var city_id = "<?php echo $data->city_id;?>";
</script>