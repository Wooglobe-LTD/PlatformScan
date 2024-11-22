<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span>RSS Article Feeds Management</span></li>
        </ul>
    </div>
    <div id="page_content_inner">

        

        <h4 class="heading_a uk-margin-bottom"><?php echo $title;?></h4>
        <div class="md-card uk-margin-medium-bottom">
            <div class="md-card-content">
                <div class="dt_colVis_buttons"></div>
                <table id="rss_article_feeds" class="uk-table" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <?php if($assess['can_act']) { ?>
                            <th data-name="c.action">Actions</th>
                        <?php } ?>
                        <th data-name="raf.title">Feed Title</th>
                        <th data-name="u.full_name">Partner</th>
                        <th data-name="feed_delay">Feed Delay</th>
                        <th data-name="feed_time">Feed Time Range (GMT+1)</th>
                        <th data-name="raf.feed_url">Feed URL</th>
                        <th data-name="c.add_article">Add Article</th>

                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <?php if($assess['can_act']) { ?>
                            <th data-name="c.action">Actions</th>
                        <?php } ?>
                        <th data-name="raf.title">Feed Title</th>
                        <th data-name="u.full_name">Partner</th>
                        <th data-name="feed_delay">Feed Delay</th>
                        <th data-name="feed_time">Feed Time Range (GMT+1)</th>
                        <th data-name="raf.feed_url">Feed URL</th>
                        <th data-name="c.add_article">Add Article</th>

                    </tr>
                    </tfoot>

                    <tbody>
                
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
<div class="md-fab-wrapper">
    <a title="Add New RSS Feed" class="md-fab md-fab-accent md-fab-wave waves-effect waves-button" id="add_article_feed_btn" href="javascript:void(0);">
    <i class="material-icons">&#xE145;</i>
    </a>
</div>

<div class="uk-modal" id="add_article_feed_modal">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h3 class="uk-modal-title">Create New RSS Feed</h3>
        </div>
        <div class="md-card-content large-padding">
            <form id="add_article_feed_form" class="uk-form-stacked">

                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="title">Feed Title<span class="req">*</span></label>
                            <input type="text" pattern="[a-zA-Z0-9\s]+" data-parsley-pattern-message="Only alphabet and number are allowed." data-parsley-required-message="This field is required." name="feed_title" id="feed_title" required class="md-input" />
                            <div class="error"></div>
                        </div>
                    </div>
                </div>

                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="url">Feed URL<span class="req">*</span></label>
                            <input type="text" pattern="[a-zA-Z0-9_-]+" data-parsley-pattern-message="Only alphabet, number and dash are allowed." data-parsley-required-message="This field is required." name="feed_url" id="feed_url" required class="md-input" />
                            <div class="error"></div>
                        </div>
                    </div>
                </div>
                
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="feed_partner" class="uk-form-label uk-text-primary">Feed Partners</label>
                            <select id="feed_partner" name="feed_partner[]" class="uk-width-medium-1-1" data-parsley-required-message="This field is required." multiple required>
                                <?php
                                foreach ($partners->result() as $partner) { ?>
                                    <option value="<?php echo $partner->id; ?>"><?php echo $partner->full_name; ?></option>
                                <?php } ?>
                            </select>
                            <div class="error"></div>
                        </div>
                    </div>
                </div>
                
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="feed_delay">Feed Delay Time (minutes)</label>
                            <input type="text" name="feed_delay" id="feed_delay" class="md-input" />
                            <div class="error"></div>
                        </div>
                    </div>
                </div>

                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-2">
                        <div class="parsley-row">
                            <label for="feed_time_from">24Hr GMT+1 - Time Range (From)</label>
                            <input type="text" name="feed_time_from" id="feed_time_from" class="md-input" data-uk-timepicker="{format:'24h'}" />
                            <div class="error"></div>
                        </div>
                    </div>
                    <div class="uk-width-medium-1-2">
                        <div class="parsley-row">
                            <label for="feed_time_to">24Hr GMT+1 - Time Range (To)</label>
                            <input type="text" name="feed_time_to" id="feed_time_to" class="md-input" data-uk-timepicker="{format:'24h'}" />
                            <div class="error"></div>
                        </div>
                    </div>
                </div>

                <h4 style="color: black;">Feed Days</h4>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-4">
                            <input type="checkbox" name="desc_info[]" value="mon" data-md-icheck />
                            <label>Monday</label>
                    </div>
                    <div class="uk-width-medium-1-4">
                            <input type="checkbox" name="desc_info[]" value="tue" data-md-icheck />
                            <label>Tuesday</label>
                    </div>
                    <div class="uk-width-medium-1-4">
                            <input type="checkbox" name="desc_info[]" value="wed" data-md-icheck />
                            <label>Wednesday</label>
                    </div>
                    <div class="uk-width-medium-1-4">
                            <input type="checkbox" name="desc_info[]" value="thu" data-md-icheck />
                            <label>Thursday</label>
                    </div>
                </div>

                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-4">
                            <input type="checkbox" name="desc_info[]" value="fri" data-md-icheck />
                            <label>Friday</label>
                    </div>
                    <div class="uk-width-medium-1-4">
                            <input type="checkbox" name="desc_info[]" value="sat" data-md-icheck />
                            <label>Saturday</label>
                    </div>
                    <div class="uk-width-medium-1-4">
                            <input type="checkbox" name="desc_info[]" value="sun" data-md-icheck />
                            <label>Sunday</label>
                    </div>
                    
                </div>

            </form>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="add_article_feed" class="md-btn md-btn-flat md-btn-flat-primary">Add</button>
        </div>
    </div>
</div>

<div class="uk-modal" id="rss_article_modal">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header uk-text-left">
            <h3 class="uk-modal-title"> Add Rss Article </h3>
        </div>
        <div class="uk-width-large-1-1" id="story-feed-div" style="display:block" ;>
            <div class="md-list-content">
                <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px;">
                    <form id="rss_article_form" style="width:100%; margin:0; padding:0;" enctype="multipart/file-data">

                        <input type="hidden" id="article_slide_num" name="article_slide_num" value="0">
                        <input type="hidden" id="article_id" name="article_id" value="0">
                        <input type="hidden" id="article_edit" name="article_edit" value="0">

                        <!-- Languages -->
                        <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <div class="md-input-wrapper">
                                        <div class="uk-autocomplete">
                                            <input class="uk-width-1-3" id="selected_language" name="selected_language" type="text" value="English" placeholder="Search..." />
                                            <button type="button" id="translate_send_button" style="float:right;"><i class="uk-icon-send"></i></button>
                                            <ul id="language_dropdown" class="dropdown-list"></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Article Title -->
                        <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <div class="md-input-wrapper">
                                        <label for="article_title" class=" uk-form-label uk-text-primary">Article Title</label>
                                        <input type="text" id="article_title" name="article_title" class="md-input" data-parsley-required-message="This field is required." />
                                        <span class="md-input-bar "></span>
                                    </div>
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Article Description -->
                        <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <div class="md-input-wrapper">
                                        <label for="article_description" class="uk-form-label uk-text-primary">Article Decription</label>
                                        <textarea id="article_description" name="article_description" class="md-input" data-parsley-required-message="This field is required."></textarea>
                                        <span class="md-input-bar "></span>
                                    </div>
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>

                        <!-- RSS Feeds -->
                        <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="article_feeds" class="uk-form-label uk-text-primary">RSS Feeds</label>
                                    <select id="article_feeds" name="article_feeds[]" class="uk-width-medium-1-1" data-parsley-required-message="This field is required." multiple>
                                        <?php
                                        foreach ($feeds->result() as $feed) { ?>
                                            <option value="<?php echo $feed->id; ?>"><?php echo $feed->feed_url; ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Categories -->
                        <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="article_category" class="uk-form-label uk-text-primary">Category</label>
                                    <select id="article_category" name="article_category" class="uk-width-medium-1-1" data-parsley-required-message="This field is required.">
                                        <option value="">Select Category</option>
                                        <?php
                                        foreach ($categories->result() as $cat) { ?>
                                            <option value="<?php echo $cat->id; ?>"><?php echo $cat->title; ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Keywords -->
                        <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">
                            <div class="uk-width-medium-1-1" id="rss_article_keywords">
                                <div class="parsley-row">
                                    <div class="md-input-wrapper">
                                        <label for="rss_keywords" class="uk-form-label uk-text-primary">Keywords</label>
                                        <textarea id="article_keywords" name="article_keywords" class="md-input" data-parsley-required-message="This field is required."></textarea>
                                        <span class="md-input-bar "></span>
                                    </div>
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Article Credit -->
                        <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <div class="md-input-wrapper">
                                        <label for="article_credit" class=" uk-form-label uk-text-primary">Article Credit</label>
                                        <input type="text" id="article_credit" name="article_credit" class="md-input" data-parsley-required-message="This field is required." />
                                        <span class="md-input-bar "></span>
                                    </div>
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Article Slides Navigation -->
                        <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row" id="rss_article_slides">
                                    <select id="slide_ids" name="slide_ids[]" multiple hidden></select>
                                    <div class="add_article_slide" id="add_article_slide">
                                        <button type="button" id="mrss-upload-btn" class="md-btn-info" title="Add Article Slide">
                                            <i class="material-icons">add</i>
                                        </button>
                                    </div>
                                </div>
                                <div class="error" style="margin:15px 0 0 15px;"></div>
                            </div>
                        </div>

                        <!-- Slide Preview -->
                        <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">
                            <div class="uk-width-medium-1-1" id="article_slides_preview">
                                <div class="parsley-row" id="slides">
                                    <!-- <div id="<?php //echo 'mrss-task-' . $mbf->id ?>" class="slide_preview">

                                        <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">
                                            <div class="uk-width-medium-1-1">
                                                <div class="parsley-row">
                                                    <div class="md-input-wrapper">
                                                        <label for="slide_title" class=" uk-form-label uk-text-primary">Image Title</label>
                                                        <input type="text" id="slide_title" name="slide_title" class="md-input" data-parsley-required-message="This field is required." required />
                                                        <span class="md-input-bar "></span>
                                                    </div>
                                                    <div class="error"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">
                                            <div class="uk-width-medium-1-1">
                                                <div class="parsley-row">
                                                    <div class="md-input-wrapper">
                                                        <label for="slide_headline" class=" uk-form-label uk-text-primary">Image Headline</label>
                                                        <input type="text" id="slide_headline" name="slide_headline" class="md-input" data-parsley-required-message="This field is required." required />
                                                        <span class="md-input-bar "></span>
                                                    </div>
                                                    <div class="error"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">
                                            <div class="uk-width-medium-1-1">
                                                <div class="parsley-row">
                                                    <div class="md-input-wrapper">
                                                        <label for="slide_description" class="uk-form-label uk-text-primary">Image Decription</label>
                                                        <textarea id="slide_description" name="slide_description" class="md-input" data-parsley-required-message="This field is required." style="max-height:150px; margin-top:10px;" required><?php echo $videoData->description; ?></textarea>
                                                        <span class="md-input-bar "></span>
                                                    </div>
                                                    <div class="error"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">
                                            <div class="uk-width-medium-1-1">
                                                <div class="parsley-row">
                                                    <div class="md-input-wrapper">
                                                        <label for="slide_credit" class=" uk-form-label uk-text-primary">Image Credit</label>
                                                        <input type="text" id="slide_credit" name="slide_credit" class="md-input" data-parsley-required-message="This field is required." required />
                                                        <span class="md-input-bar "></span>
                                                    </div>
                                                    <div class="error"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">
                                            <div class="uk-width-medium-1-1">
                                                <div class="parsley-row">
                                                    <div class="md-input-wrapper">
                                                        <label for="image_upload" class="drop-container" id="dropcontainer">
                                                            <span class="drop-title">Drop File Here</span>
                                                            or
                                                            <input type="file" id="image_upload" accept="image/*" required>
                                                        </label>
                                                        <div class="error"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div> -->
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button type="button" id="rss_article_form_cncl" class="md-btn md-btn-flat md-btn-danger uk-margin-medium-right uk-modal-close">Cancel</button><button type="button" id="rss_article_form_save" class="md-btn md-btn-flat md-btn-primary">Save</button>
        </div>
    </div>
</div>