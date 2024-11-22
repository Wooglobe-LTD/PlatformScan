<div class="uk-modal" id="youtube-modal">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h3 class="uk-modal-title">Publish At YouTube </h3>
        </div>

        <div class="md-card-content large-padding">
            <form id="publish-yt-form" class="uk-form-stacked">
                <input type="hidden" id="ed_video_id" name="video_id" value="">
                <input type="hidden" id="ed_wgid" name="wgid" value="">
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="error" id="yt-err"></div>
                    </div>
                </div>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="youtube_channel" class="uk-form-label">Channel<span class="req">*</span></label>
                            <select id="youtube_channel" name="youtube_channel" data-parsley-required-message="This field is required." required>
                            </select>
                            <div class="error"></div>
                        </div>
                    </div>
                </div>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="youtube_category" class="uk-form-label">Category<span class="req">*</span></label>
                            <select id="youtube_category" name="youtube_category" data-parsley-required-message="This field is required." required>
                            </select>
                            <div class="error"></div>
                        </div>
                    </div>
                </div>

                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="youtube_video_type" class="uk-form-label">Publish Video Type<span class="req">*</span></label>
                            <select id="youtube_video_type" name="youtube_video_type" data-parsley-required-message="This field is required." required>
                                <option value="">Choose..</option>
                                <option value="1" selected>Watermark Video</option>
                                <option value="2">Edited Video</option>

                            </select>
                            <div class="error"></div>
                        </div>
                    </div>
                </div>

                <div class="publish_yt_sch">
                    <label for="publish_now_youtube" class="radio">
                        <input <?php if ($videoData->youtube_id) echo "disabled"; ?> type="radio" checked id="publish_now_youtube" name="publish_now_youtube" value="1" />
                        <span><b>Publish Now</b></span>
                    </label>
                    <label for="publish_schedule_youtube" class="radio" style="margin-left: 10px;">
                        <input <?php if ($videoData->youtube_id) echo "disabled"; ?> type="radio" id="publish_schedule_youtube" name="publish_now_youtube" value="0" />
                        <span><b>Scheduling</b></span>
                    </label>

                    <div id="dvPinNo2" style="display: none;">
                        <div class="uk-grid">

                            <div class="uk-width-large-1-3 uk-width-1-1">
                                <div class="uk-input-group">
                                    <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                    <label for="youtube_publish_date">Select date</label>
                                    <input <?php if ($videoData->youtube_id) echo "disabled"; ?> class="md-input" type="text" name="youtube_publish_date" id="youtube_publish_date" data-uk-datepicker="{format:'YYYY-MM-DD'}" required>
                                </div>
                            </div>

                            <div class="uk-width-large-1-3 uk-width-1-1">
                                <div class="uk-input-group">
                                    <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-clock-o"></i></span>
                                    <label for="youtube_publish_time">Select time</label>
                                    <input <?php if ($videoData->youtube_id) echo "disabled"; ?> class="md-input" type="text" name="youtube_publish_time" id="youtube_publish_time" data-uk-timepicker data-parsley-required-message="This field is required." required>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <br /><br />

                <div class="seo_optimized" data-uk-grid-margin>
                    <div class="error"></div>
                    <label for="seo-optimized-check">
                        <span style="margin-left:10px"><b>SEO Optimized</b></span>
                        <input type="checkbox" name="seo_optimized" id="seo-optimized-check" value="1" data-id="<?php echo $dealData->id; ?>" />
                    </label>
                </div>

                <div class="uk-grid default_input dynamic" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="youtube_publish_title">Video Title<span class="req">*</span></label>
                            <input type="text" name="youtube_publish_title" value="" id="youtube_publish_title" data-parsley-required-message="This field is required." class="md-input" />
                            <div id="cont-p" style="float: right;font-size: 14px;font-weight: 200;">
                                <span class="cont-p">Character Limit :</span>
                                <span class="counter-title"></span>
                            </div>
                            <div class="error"></div>
                        </div>
                    </div>
                </div>
                <div class="uk-grid seo_optimized_input dynamic" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="youtube_publish_title_seo">Video Title<span class="req">*</span></label>
                            <input type="text" name="youtube_publish_title_seo" value="" id="youtube_publish_title_seo" data-parsley-required-message="This field is required." class="md-input" />
                            <div id="cont-p" style="float: right;font-size: 14px;font-weight: 200;">
                                <span class="cont-p">Character Limit :</span>
                                <span class="counter-title"></span>
                            </div>
                            <div class="error"></div>
                        </div>
                    </div>
                </div>

                <div class="uk-grid dynamic" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <div class="default_input">
                                <label for="youtube_publish_description" class="uk-form-label">Video Description</label>
                                <textarea id="youtube_publish_description" name="youtube_publish_description" cols="30" rows="10" class="md-input" data-parsley-required-message="This field is required."></textarea>
                            </div>
                            <div class="seo_optimized_input">
                                <label for="youtube_publish_description_seo" class="uk-form-label">Video Description</label>
                                <textarea id="youtube_publish_description_seo" name="youtube_publish_description_seo" cols="30" rows="10" class="md-input" data-parsley-required-message="This field is required."></textarea>
                            </div>
                            <input type="hidden" name="video_link" id="video_link" value="<?php echo 'https://wooglobe.com/videodetail/' . $dealData->unique_key . '/' . $slug ?>">
                            <input type="hidden" name="yt_desc_footer" id="yt_desc_footer">
                            <div id="yt_footer">
                                <p>►SUBSCRIBE for more Awesome Videos: <a href="https://goo.gl/uDVc4n">https://goo.gl/uDVc4n</a><br>
                                    -----------------------</p>
                                <p>Copyright - #WooGlobe.</p>
                                <p>
                                    We bring you the most trending internet videos!<br>
                                    For licensing and to use this video, please visit:<br>
                                    <a target="_blank" href="<?php echo 'https://wooglobe.com/videodetail/' . $dealData->unique_key . '/' . $slug ?>"><?php echo 'https://wooglobe.com/videodetail/' . $dealData->unique_key . '/' . $slug ?></a><br>
                                    or email: licensing(at)Wooglobe(dot)com<br>
                                </p>
                                <p>Video ID: <span id="video_id"><?php echo $dealData->unique_key ?></span></p>
                                <p>
                                    Twitter: <a href="https://twitter.com/WooGlobe"> https://twitter.com/WooGlobe</a><br>
                                    Facebook: <a href="https://fb.com/Wooglobe"> https://fb.com/Wooglobe</a><br>
                                    Instagram : <a href="https://www.instagram.com/WooGlobe/"> https://www.instagram.com/WooGlobe/</a>
                                </p>
                                <blockquote style="border-left: 5px solid #d3d3d3; font-size: 14px;font-style:normal">
                                    <div style="width: 80%; float: left">
                                        <img src="https://s.ytimg.com/yts/img/favicon-vfl8qSV2F.ico" width="16"> <b>YouTube</b><br>
                                        <b><a href="https://goo.gl/uDVc4n">WooGlobe</a></b><br>
                                        WooGlobe is a leader in user-generated content, connecting creators and distributors around the world. Our channel lists the exclusively managed viral video ...
                                    </div>
                                    <div style="width: 19%; float: right">
                                        <img width="85" src="https://lh3.googleusercontent.com/a-/AAuE7mCP5HfXDhx4sFwhiz3pcyNS8cQzk-jRfqSN-9zV=s88-c-k-c0x00ffffff-no-rj-mo">
                                    </div>
                                    <div style="clear: both"></div>

                                </blockquote>
                                <blockquote style="border-left: 5px solid #d3d3d3; font-size: 14px;font-style:normal">
                                    <img src="https://cdn0.iconfinder.com/data/icons/twitter-ui-flat/48/Twitter_UI-01-512.png" width="32"><b>twitter.com</b><br>
                                    <b><a href="https://twitter.com/WooGlobe"> WooGlobe (@WooGlobe) | Twitter</a></b><br>
                                    The latest Tweets from WooGlobe (@WooGlobe). Ultimate resource for trending videos. We help you earn from your videos <img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/120/google/223/film-projector_1f4fd.png" width="24" alt="video projection"> . Submit yours <img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/120/google/223/white-down-pointing-backhand-index_emoji-modifier-fitzpatrick-type-3_1f447-1f3fc_1f3fc.png" width="24" alt="point down"><img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/120/google/223/white-down-pointing-backhand-index_emoji-modifier-fitzpatrick-type-3_1f447-1f3fc_1f3fc.png" width="24" alt="point down"><img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/120/google/223/white-down-pointing-backhand-index_emoji-modifier-fitzpatrick-type-3_1f447-1f3fc_1f3fc.png" width="24" alt="point down">. London, England</p>
                                </blockquote>
                                <blockquote style="border-left: 5px solid #d3d3d3; font-size: 14px;font-style:normal">
                                    <div style="width: 80%; float: left">
                                        <img src="https://static.xx.fbcdn.net/rsrc.php/yz/r/KFyVIAWzntM.ico" width="16"> <b>facebook.com</b><br>
                                        <a href="https://fb.com/Wooglobe"> <b>WooGlobe</b></a><br>
                                        WooGlobe. 4K likes. Bringing you the most awesome user generated videos from around the world <img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/160/google/223/trophy_1f3c6.png" width="24" alt="point down"> WooGlobe is a trusted leader user generated content. Our mission is to help creators and publishers...

                                    </div>
                                    <div style="width: 19%; float: right">
                                        <img width="85" src="https://scontent.flhe5-1.fna.fbcdn.net/v/t1.0-1/p200x200/48120821_345147692735398_1506976261774245888_o.jpg?_nc_cat=100&_nc_ohc=ZPRehYkxYKQAQkl_h9hIVBxljDV1KKt06mGmW_0OzKS3m6IpQ5-gOixKA&_nc_ht=scontent.flhe5-1.fna&oh=720c2c3c01df73aa20a170dc9c4c7c29&oe=5E86730C">
                                    </div>
                                    <div style="clear: both"></div>
                                </blockquote>
                                <blockquote style="border-left: 5px solid #d3d3d3; font-size: 14px;font-style:normal">
                                    <img src="https://www.instagram.com/static/images/ico/apple-touch-icon-76x76-precomposed.png/666282be8229.png" width="16"> <b>instagram.com</b><br>
                                    <a href="https://www.instagram.com/WooGlobe/"> <b>Login • Instagram</b></a><br>
                                    Welcome back to Instagram. Sign in to check out what your friends, family & interests have been capturing & sharing around the world.
                                </blockquote>
                            </div>

                            <div id="cont-p" style="float: right;font-size: 14px;font-weight: 200;">

                                <span class="footer-desc"><a href="javascript:void(0);" id="add_footer" data-footer="<?php echo settings()->description_footer; ?>">Add Footer</a> </span>

                            </div>
                            <div class="error"></div>
                        </div>
                    </div>

                </div>
                <div class="uk-grid default_input dynamic" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="youtube_publish_tags" class="uk-form-label">Video Tags</label>
                            <textarea id="youtube_publish_tags" name="youtube_publish_tags" class="md-input default_input" data-parsley-required-message="This field is required."></textarea>
                            <div id="cont-p" style="float: right;font-size: 14px;font-weight: 200;">
                                <span class="cont-desc">Character Limit :</span>
                                <span class="counter-tags"></span>
                            </div>
                            <div class="error"></div>
                        </div>
                    </div>
                </div>
                <div class="uk-grid seo_optimized_input dynamic" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="youtube_publish_tags_seo" class="uk-form-label">Video Tags</label>
                            <textarea id="youtube_publish_tags_seo" name="youtube_publish_tags_seo" class="md-input" data-parsley-required-message="This field is required."></textarea>
                            <div id="cont-p" style="float: right;font-size: 14px;font-weight: 200;">
                                <span class="cont-desc">Character Limit :</span>
                                <span class="counter-tags"></span>
                            </div>
                            <div class="error"></div>
                        </div>
                    </div>
                </div>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="youtube_publish_status" class="uk-form-label">Publish Status<span class="req">*</span></label>
                            <select id="youtube_publish_status" name="youtube_publish_status" data-parsley-required-message="This field is required." data-md-selectize>
                                <option value="private">Private</option>
                                <option value="unlisted">Unlisted</option>
                                <option value="public">Public</option>
                            </select>
                            <div class="error"></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
            <button type="button" id="btn_update_auth_token" class="md-btn md-btn-flat md-btn-flat-danger">Update Auth Token</button>
            <button type="button" id="publish-yt-btn" class="md-btn md-btn-flat md-btn-flat-primary">
                <?php
                if ($videoData->youtube_id) {
                    echo "Update";
                } else {
                    echo "Publish";
                }
                ?>
            </button>
        </div>
    </div>
</div>