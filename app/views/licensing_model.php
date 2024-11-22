<section class="author-page-contents">
    <div class="section-padding">
        <div class="container">
            <div class="author-contents">
                <div class="row">

                    <div class="col-sm-3" ></div>
                    <div class="col-sm-6" ><!-- style="padding-left: 250px; -->
                        <h3 class="entry-title entry-title1" style="padding-left: 250px;padding-top: 20px">License Video</h3>
                        <div class="about-author">
                            <div class="upload-video">
                                <form action="#" class="license-form" id="license-form">

                                    <div class="row">
                                        <div class="form-element" >
                                            <label for="territory">Choose Territory</label>
                                            <select name="territory" class="territory form-control" id="territory"
                                                    data-parsley-required-message="This Field is Required"
                                                    required
                                                    tabindex="1">
                                                <option value="">Choose Teritory</option>
                                                <option value="Regional">Regional</option>
                                                <option value="National">National</option>
                                                <option value="Worldwide">Worldwide</option>
                                            </select>
                                       
                                        <div class="error" id="territory_err" style="padding-left: 0px;"></div>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="form-element" id="country-div" style="display: none;">
                                            <label for="country">Choose Country</label>
                                            <select name="country" class="country form-control" id="country"
                                                    data-parsley-required-message="This Field is Required"
                                                    required
                                                    tabindex="1">
                                                <option value="">Choose Country</option>
                                                <?php foreach ($countries as $country){?>
                                                    <option value="<?php echo $country['id'];?>"><?php echo $country['name'];?></option>
                                                <?php }?>
                                            </select>
                                            
                                            <div class="error" id="country_err" style="padding-left: 0px;"></div>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="form-element" id="exclusivity-div">
                                            <label for="category">Choose Exclusivity</label>
                                            <select name="exclusivity" class="exclusivity form-control" id="exclusivity">
                                                <option value="Exclusive">Exclusive</option>
                                                <option value="Non-exclusive">Non-exclusive</option>
                                            </select>
                                            
                                            <div class="error" id="exclusivity_err" style="padding-left: 0px;"></div>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="form-element">
                                            <label for="time">Choose Duration</label>
                                            <select name="time" class="time form-control" id="time"
                                                    data-parsley-required-message="This Field is Required"
                                                    required
                                                    tabindex="2">
                                                <option value="">Choose Duration</option>
                                                <option value="24 Hours">24 Hours</option>
                                                <option value="48 Hours">48 Hours</option>
                                                <option value="1 Week">1 Week</option>
                                                <option value="2 Week">2 Week</option>
                                                <option value="1 Month">1 Months</option>
                                                <option value="3 Month">3 Months</option>
                                                <option value="6 Month">6 Months</option>
                                                <option value="1 year">1 Year</option>
                                                <option value="3 years">3 Years</option>
                                                <option value="5 years">5 Years</option>
                                                <option value="5 years">10 Years</option>
                                                <option value="perpetuity">In prepetuity</option>
                                               <!-- <option value="othertime">other</option> -->
                                            </select>
                                            
                                            <div class="error" id="time_err" style="padding-left: 0px;"></div>
                                        </div>
                                    </div>
                                    <div class="error" id="time_err" style="padding-left: 0px;"></div>
                                   <!-- <div class="row">
                                        <div class="form-element" id="calendar" style="display: none">
                                            <label for="other">When will you be done with it?</label>
                                            <input type="text" name="datepicker" id="datepicker" class="other form-control datepicker" placeholder="Pick a Date"
                                                   data-parsley-required-message="This field is required."
                                                   required
                                                   tabindex=""
                                            >
                                        <div class="error" id="datepicker_err"></div>
                                        </div>
                                    </div>-->
                                    <br/>
                                    <div class="row">
                                        <div class="form-element">
                                            <label for="program">Programme or Publication</label>
                                            <input type="text" name="programme" class="program form-control" id="programme"
                                                   data-parsley-required-message="This Field is Required" placeholder="Who do you represent?"
                                                   required
                                                   tabindex="">
                                           
                                            <div class="error" id="programme_err" style="padding-left: 0px;"></div>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="form-element">
                                            <label for="license_type_id">Choose Media</label>
                                            <select name="license_type_id" class="license_type_id form-control" id="license_type_id"
                                                    data-parsley-required-message="This Field is Required"
                                                    required
                                                    tabindex="3">
                                               <option value="">Select</option>
                                                <?php foreach($license_type as $license){?>
                                                    <option value="<?php echo $license['id'];?>"> <?php echo $license['type'];?> </option>
                                                <?php }?>
                                            </select>
                                            <div class="error" id="license_type_id_err" style="padding-left: 0px;"></div>
                                        </div>
                                        </div>
                                    <br/>
                                    <div class="row">
                                        <div class="form-element">
                                            <label for="mobile">Mobile Number</label>
                                            <div class="col-md-4 padd-left-0">
                                                <select name="country_code" class="country_code form-control" id="country_code"
                                                        data-parsley-required-message="Country Code required."
                                                        required
                                                        tabindex="8">
                                                    <option value="">Select A Country Code</option>
                                                    <?php foreach($countries as $country){ ?>
                                                        <option value="+<?php echo $country['phonecode'];?>"><?php echo $country['name'];?> (+<?php echo $country['phonecode'];?>)</option>
                                                    <?php } ?>
                                                </select>
                                                <div class="error" id="country_code_err" style="padding-left: 0px;"></div>

                                            </div>

                                            <div class="col-md-8 padd-right-0" >
                                                <input type="text" name="mobile" id="mobile" class="mobile form-control" placeholder="Mobile Number"
                                                       data-parsley-required-message="Mobile Nmuber field is required."
                                                       required
                                                       tabindex="9"
                                                       data-parsley-type="number"
                                                       data-parsley-type-message="Please enter the valid mobile number.">
                                            </div>
                                            <div class="error" id="mobile_err" ></div>

                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="form-element">
                                            <label for="email">Contact Email</label>
                                            <input type="text" name="email" class="email form-control" id="email"
                                                   data-parsley-required-message="Email is Required" placeholder="This is how we will contact you."
                                                   required
                                                   tabindex="">
                                            </input>
                                            <div class="error" id="email_err"></div>
                                        </div>
                                    </div>
                                    


                                    <input type="text" name="video_id" class="id form-control" id="video_id" value="<?php echo $video_id['id'];?>" style="display: none">

                                    <input type="submit" value="Place Inquiry" class="submit" id="submit" name="license-form-submit" style="margin-left: -14px">
                                </form>
                            </div><!-- /.upload-video -->
                        </div><!-- /.about-author -->
                    </div>


                    <div class="col-sm-3" ></div> 




                </div>
            </div>
        </div><!-- /.container -->
    </div><!-- /.section-padding -->
</section><!-- /.author-page-contents -->