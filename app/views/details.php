<style>
@media (max-width: 992px) and (min-width: 767px){
    .entry-thumbnail img {
        margin-top: -71px !important;
    }
    .entry-thumbnail {
        height: 82px !important;
    }
}
.ref_div h5 {
    float: left;
    margin: 0 5px 0 0;
    padding: 0;
}
.ref_div span {
    float: left;
    line-height: 1 !important;
}
</style>
<?php

?>
<section class="video-contents">
    <div class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 col-sm-12 col-xs-12">
                    <div class="side-nav">
                        <div class="side-nav-content">
                            <ul>
                                <li><a href="<?php echo $url;?>"><span><i class="fa fa-home" aria-hidden="true"></i></span>Home</a></li>
                                <li><a href="javascript:void(0);" class="upload"><span><i class="fa fa-upload" aria-hidden="true"></i></span>Upload
                                </a></li>
                                <li><a href="<?php echo $url;?>partner/categories"><span><i class="fa fa-file-video-o" aria-hidden="true"></i></span>Media Gallery
                                </a></li>
                                <li><a href=""><span><i class="fa fa-line-chart" aria-hidden="true"></i></span>Trending</a></li>
                                <li class="last"><a href=""><span><i class="fa fa-star-o" aria-hidden="true"></i>
                                </span>Recommended</a></li>
                                <?php if($this->sess->userdata('isClientLogin') == ''){?>
                                <li class="last" style="display: none"><a href="<?php echo $url;?>login"><span><i class="fa fa-user" aria-hidden="true"></i>
                                </span>Log In</a>
                                </li>
                                <?php } ?>
                                <?php if(($this->sess->userdata('isClientLogin') != '')){?>
                                <li class="last"><a href="javascript:void(0)" class="drop-ico2"><span><i class="fa fa-user" aria-hidden="true"></i>
                                </span><?php echo $this->sess->userdata('clientName');?></a>
                                    <ul class="side-login" style="display: block;">
                                        <li><a href="<?php echo $url;?>dashboard" <?php if($title == 'Dashboard'){ echo 'class="active"';}?>>
                                            <span><i class="fa fa-tachometer" aria-hidden="true"></i>
                                            </span>Dashboard
                                        </a></li>
                                        <li><a href="<?php echo $url;?>profile" <?php if($title == 'Profile'){ echo 'class="active"';}?>>
                                            <span><i class="fa fa-file-text-o" aria-hidden="true"></i></span>
                                            Profile</a></li>
                                        <li><a href="<?php echo $url;?>change-password" <?php if($title == 'Change Password'){ echo 'class="active"';}?>>
                                            <span><i class="fa fa-key" aria-hidden="true"></i></span>
                                        Change Password</a></li>
                                        <li class="last"><a href="<?php echo $url;?>logout" class="signout"><span><i class="fa fa-sign-out" aria-hidden="true"></i></span>Logout</a></li>
                                    </ul>
                                </li>
                                <?php } ?>
                            </ul>

                        </div>
                    </div>
                </div>

                <div class="col-sm-9 col-sm-12 col-xs-12">

                    <div class="row">
                        <div class="col-sm-3">

                            <div class="cate-drop-down">

                                <a href="javascript:void(0)" class="cate-drop-btn">Categories
                                    <span>
                                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </span>
                                </a>

                                <?php $this->load->view('common_files/categories');?>

                            </div>
                        </div>

                        <div class="col-sm-9">
                            <div>
                                <form class="page-search-form" method="get" style="z-index: 999;" action="<?php echo $url."search"?>">
                                    <div class="page-search">
                                        <?php   $search = "";
                                            if(isset($_GET['search']) && !empty($_GET['search'])) {
                                                $search = $_GET['search'];
                                            }
                                        ?>
                                        <input type="text" name="search" id="search_input" placeholder="SEARCH" value="<?php echo $search;?>" />
                                       <span class="search-icon"><i class="fa fa-search" aria-hidden="true"></i></span>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="movie-contents-area">
                                <div class="movie-single">
                                    <article class="post type-post">
                                        <div class="entry-thumbnail">
                                            <div id="movie-video-slider" class="movie-video-slider carousel slide">
                                                <div class="carousel-inner">
                                                    <div class="item active">

                                                        <?php if($result['embed'] == 0){?>
                                                            <!--<video poster="<?php /*echo $url.$result['thumbnail'];*/?>" width="770px" height="330px" controls controlsList="nodownload"><source src="<?php /*echo $url.$result['url'];*/?>">Your browser does not support HTML5 video.</video>-->

        													<!--<iframe width="560" height="315" src="https://www.youtube.com/embed/Jzc2qgnArSc?&amp;modestbranding=1&amp;autohide=2" allowfullscreen allowtransparency allow="autoplay"></iframe>-->
        													<iframe width="100%" height="320" src="https://www.youtube.com/embed/<?php echo $result['youtube_id'];?>?rel=0&showinfo=1&controls=1&modestbranding=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        												<?php } else {?>

                                                            <?php echo $result['url'];?>
                                                        <?php }?>

                                                    </div>
                                                </div><!-- /.carousel-inner -->



                                            </div><!-- /.movie-video-slider -->
                                            <div class="buy-video">
                                                <div class="Int_lic">Interested in licensing?</div>
                                                <button type="button" data-toggle="modal" data-target="#myModal-buy">
                                                    <span>
                                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                                    </span>Place Inquiry
                                                </button>

                                                <div class="entry-meta">

                                                    <?php
                                                    $date = $result['question_when_video_taken'];
                                                    $date1=date_create($date);
                                                    ?>
                                                    <span><time datetime="">Filmed on <?php echo date_format($date1,"l jS F Y"); ?></time>
                                                        <div class="ref_div"><h5>WooGlobe Reference ID:</h5><span><?php echo $result['unique_key'];?></span></div>
                                                    <!--<span class="category tag"><a href="<?php /*echo $result['full_name']*/?>"></a>, <a href="#"><?php /*echo $result['full_name']*/?></a></span>-->
                                                </div><!-- /.entry-meta -->
                                            </div>
                                        </div><!-- /.entry-thumbnail -->

                                        <div class="entry-content media">

                                            <?php ?>
                                            <!-- <div class="movie-thumb media-left"><img src="<?php echo $url;?>images/sampleimage.png" alt="Movie Poster">
                                            </div> --><!-- /.movie-thumb -->
                                            <?php
                                            $cat_url = '';
                                            if(isset($cate_url)){
                                                $cat_url = $cate_url;
                                            }?>

                                            <div class="movie-details media-body">
                                                <h2 class="entry-title"><?php echo ucwords($result['vtitle']);?></h2><!-- /.entry-title -->
                                                <div class="cate-tags tags">
                                                    <h5>Categories</h5>
                                                    <?php foreach ($categories_videos as $categ){?>
                                                        <a href="<?php echo $url.$cat_url.'categories/'.$categ->slug;?>"><?php echo $categ->title;?></a>
                                                    <?php } ?>


                                                </div><!-- /.Category -->

                                                <div class="tags">
                                                    <h5>Description</h5>
                                                    <p><?php echo $result['description']?></p>
                                                </div>


                                                <div class="movie-meta">
                                                    <ul>

                                                    </ul>

                                                </div><!-- /.movie-meta -->

                                                <div class="tags">
                                                    <h5>Tags</h5>
                                                    <?php $tags = explode(',',$result['tags']);
                                                    foreach ($tags as $tag){?>
                                                        <a href="<?php echo $url;?>search?search=<?php echo $tag;?>" data-tags="<?php echo $tag;?>" class="tag_search">
                                                            <?php if($tag){ ?>
                                                                #<?php echo $tag;?>
                                                            <?php }?>
                                                        </a>
                                                    <?php }
                                                    ?>

                                                </div><!-- /.tags -->
                                            </div><!-- /.movie-details -->
                                        </div><!-- /.entry-content -->
                                    </article><!-- /.post -->


                                </div><!-- /.movie-single -->
                            </div><!-- /.movie-contents-area -->
                        </div>

                        <div class="col-sm-4">
                            <?php //if($buy_button == true){?>
                            <div class="widget buy-sec relate-vid">
                                <h3 class="widget-title">Related Videos</h3><!-- /.widget-title -->
                                <div id="overlayer"></div>
                                <span class="loader">
                                   <span class="loader-inner"></span>
                                </span>
                                </div>
                               <!-- <h3 class="widget-title">Suggested Videos</h3>--><!-- /.widget-title -->
                                <?php /* foreach ($suggested->result() as $video) {*/?><!--
                                    --><?php
                                    /*if ($video->thumbnail != '') {
                                        if($video->bulk == 1){
                                            $thumb = $video->thumbnail;
                                        }else{
                                            $thumb = $url . $video->thumbnail;
                                        }
                                        //$thumb = $url . $video->thumbnail;
                                    } else {
                                        $thumb = "https://i.vimeocdn.com/video/614933078.jpg?mw=960&mh=540";
                                    }*/
                                  /*  $thumb = "https://i.vimeocdn.com/video/614933078.jpg?mw=960&mh=540";

                                    if($video->bulk == 1){
                                        $thumb = $video->thumbnail;
                                        $thumb = 'https://img.youtube.com/vi/'.$video->youtube_id.'/hqdefault.jpg';
                                    }*/
                                    ?>
                       <!--         <div class="row">
                                    <div class="col-md-12">

                                        <a href="<?php /*echo $url */?>?video=<?php /*echo $video->slug; */?>" title="<?php /*echo ucwords($video->title); */?>">
        									<div class="entry-thumbnail" style="margin-bottom: 0px !important;">
        										<img src="<?php /*echo $thumb; */?>" alt="Video Thumbnail">
        									</div>
                                            <span class="rel-title"><?php /*if (strlen(stripslashes ($video->title)) <= 20) {
                                                    echo ucwords(stripslashes ($video->title));
                                                }else{
                                                    echo ucwords(substr(stripslashes ($video->title),0,20));
                                                } */?></span>
                                        </a>

                                    </div>
                                </div>
                                --><?php /*}*/?>
                            </div>
                            <?php //} ?>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container -->
    </div><!-- /.section-padding -->


<!-- Modal Starts-->
<div class="modal fade" id="myModal-buy" role="dialog">
    <div class="modal-dialog details">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body style-3">

                <h4 class="center">License Video</h4>
                <br/>
                <div class="about-author">
                    <div class="upload-video">
                        <form action="#" class="license-form" id="license-form">
                            <label class="mnd-lbl over">Media Type<span>*</span></label>
                            <div class="buy-check-cover">

                                <div class="buy_checkbox">
                                    <div class="buy_check">
                                        <input type="checkbox" name="selectall1" id="selectall1" class="all media-type" value="All Media">
                                        <label for="selectall1">All Media</label>
                                    </div>
                                    <div class="buy_check">
                                        <input type="checkbox" name="check[]" id="check1" class="media-type" value="Online and VOD">
                                        <label for="check1"> Online and VOD</label>
                                    </div>
                                    <div class="buy_check">
                                        <input type="checkbox" name="check[]" id="check2" class="media-type" value="Broadcast">
                                        <label for="check2"> Broadcast</label>
                                    </div>
                                    <div class="buy_check">
                                        <input type="checkbox" name="check[]" id="check3" class="media-type" value="Website">
                                        <label for="check3">Website</label>
                                    </div>
                                    <div class="buy_check">
                                        <input type="checkbox" name="check[]" id="check4" class="media-type" value="Facebook">
                                        <label for="check4">Facebook</label>
                                    </div>
                                    <div class="buy_check">
                                        <input type="checkbox" name="check[]" id="check5" class="media-type" value="Twitter">
                                        <label for="check5">Twitter</label>
                                    </div>
                                    <div class="buy_check">
                                        <input type="checkbox" name="check[]" id="check6" class="media-type" value="Instagram">
                                        <label for="check6">Instagram</label>
                                    </div>
                                    <div class="buy_check">
                                        <input type="checkbox" name="check[]" id="check7" class="media-type" value="Snapchat">
                                        <label for="check7">Snapchat</label>
                                    </div>
                                </div>
                                <div class="buy_checkbox2">
                                    <div class="buy_check">
                                        <input type="checkbox" name="chec" id="check8" class="media-type" value="Print">
                                        <label for="check8">Print</label>
                                    </div>
                                    <div class="buy_check">
                                        <input type="checkbox" name="check-other" id="check9" class="media-type" value="Other">
                                        <label for="check9">Other</label>
                                    </div>
                                </div>
                                <div class="error" id="media_type_err" style="padding-left: 0px;"></div>
                            </div>
                            <!--other required media rights starts here-->
                            <br/>
                            <div class="other-field">
                                <div class="form-element">
                                    <label for="other" class="mnd-lbl over">Other<span>*</span></label>
                                    <input type="text" name="other" class="program form-control" id="other"
                                           data-parsley-required-message="Other is Required" placeholder="Which media types not listed do you require rights for?"

                                           tabindex="">

                                    <div class="error" id="other_err" style="padding-left: 0px;"></div>
                                </div>
                            </div>

                            <!--other required media rights ends here-->


                            <div class="form_half">
                                <div class="form-element">
                                    <label for="territory" class="mnd-lbl">Name<span>*</span></label>
                                    <input type="text" name="name" id="name" class="email form-control"
                                           data-parsley-required-message="Name is Required" placeholder="Name"
                                           required tabindex="1">
                                    <div class="error" id="name_err"></div>
                                </div>

                                <div class="form-element">
                                    <label for="email" class="mnd-lbl">Contact Email<span>*</span></label>
                                    <input type="email" name="contact_email" id="contact_email" class="email form-control"
                                           data-parsley-type-message="Please enter the valid email address" data-parsley-required-message="Email is Required" placeholder="This is how we will contact you." required tabindex="2">
                                    <div class="error" id="contact_email_err"></div>
                                </div>

                                <div class="form-element full">
                                    <label for="mobile" class="mnd-lbl">Mobile Number<span>*</span></label>
                                    <div class="count-code">
                                        <select name="country_code" class="country_code form-control" id="country_code"
                                                data-parsley-required-message="Country Code required." tabindex="3" required>
                                            <option value="">Select A Country Code</option>
                                            <?php foreach($countries->result() as $country){ ?>
                                                <option value="<?php echo $country->phonecode;?>"><?php echo $country->name;?> (+<?php echo $country->phonecode;?>)</option>
                                            <?php } ?>
                                        </select>
                                        <div class="error" id="country_code_err" style="padding-left: 0px;"></div>

                                    </div>

                                    <div class="count-num">
                                        <input type="text" name="contact_mobile" id="contact_mobile" class="mobile form-control" placeholder="Mobile Number"
                                               data-parsley-required-message="Mobile Nmuber is required."
                                               required
                                               tabindex="9"
                                               data-parsley-type="number"
                                               data-parsley-type-message="Please enter the valid mobile number." tabindex="4">
                                    </div>
                                    <div class="error" id="contact_mobile_err" ></div>
                                </div>

                                <div class="form-element">
                                    <label for="territory" class="mnd-lbl">Choose Territory<span>*</span></label>
                                    <select name="territory" class="territory form-control" id="territory"
                                            data-parsley-required-message="Territory is Required"
                                            required tabindex="4">
                                        <option value="">Choose Territory</option>
                                        <option value="Regional">Regional</option>
                                        <option value="National">National</option>
                                        <option value="Worldwide">Worldwide</option>
                                    </select>

                                    <div class="error" id="territory_err" style="padding-left: 0px;"></div>
                                </div>

                                <div class="form-element" id="country-div" style="display: none;">
                                    <label for="country" class="mnd-lbl">Choose Country<span>*</span></label>
                                    <select name="buy_country" class="country form-control" id="buy_country"
                                            data-parsley-required-message="Country is Required"
                                            tabindex="1">
                                        <option value="">Choose Country</option>
                                        <?php foreach ($countries->result() as $country){?>
                                            <option value="<?php echo $country->id;?>"><?php echo $country->name;?></option>
                                        <?php }?>
                                    </select>

                                    <div class="error" id="buy_country_err" style="padding-left: 0px;"></div>
                                </div>


                                <div class="form-element">
                                    <label for="time"  class="mnd-lbl">Choose Duration<span>*</span></label>
                                    <select name="time" class="time form-control" id="time"
                                            data-parsley-required-message="Duration is Required"
                                            required
                                            tabindex="5">
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

                                <div class="form-element">
                                    <label for="program" class="mnd-lbl">Programme or Publication<span>*</span></label>
                                    <input type="text" name="programme" class="program form-control" id="programme"
                                           data-parsley-required-message="This Field is Required" required placeholder="Who do you represent?" tabindex="6" >

                                    <div class="error" id="programme_err" style="padding-left: 0px;"></div>
                                </div>

                            </div>






                            <input type="hidden" name="video_id" class="id form-control" id="video_id" value="<?php echo $result['id'];?>"
                            >

                            <div class="submit-input">
                                <input type="button" class="btn" name="video-upload-form-submit" id="buy-video-submit" value="Place Inquiry" tabindex="7">
                            </div>


                        </form>
                    </div><!-- /.upload-video -->
                </div>

            </div>
        </div>

    </div>
</div>
<!-- Modal Ends-->


</section><!-- /.video-content -->
