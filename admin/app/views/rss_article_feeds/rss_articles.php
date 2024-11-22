<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span>RSS Articles Management</span></li>
        </ul>
    </div>
<div id="page_content_inner">

    <h4 class="heading_a uk-margin-bottom"><?php echo $title;?></h4>
    <div class="md-card uk-margin-medium-bottom">
        <div class="md-card-content">
            <div class="dt_colVis_buttons"></div>
            <table id="rss_articles" class="uk-table" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <?php if($assess['can_act']) { ?>
                        <th data-name="c.action">Actions</th>
                    <?php } ?>
                    <th data-name="ra.title">Article Title</th>
					<th data-name="ra.credit">Credit</th>
                    <th data-name="ra.category">Category</th>
                    <th data-name="raf.feed_url">Feed</th>
                    <th data-name="num_of_slides"># of Slides</th>

                </tr>
                </thead>

                <tfoot>
                <tr>
                    <?php if($assess['can_act']) { ?>
                        <th data-name="c.action">Actions</th>
                    <?php } ?>
                    <th data-name="ra.title">Article Title</th>
					<th data-name="ra.credit">Credit</th>
                    <th data-name="ra.category">Category</th>
                    <th data-name="raf.feed_url">Feed</th>
                    <th data-name="num_of_slides"># of Slides</th>

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
    <a title="Generate New RSS Article" class="md-fab md-fab-accent md-fab-wave waves-effect waves-button" id="generate_article" href="javascript:void(0);">
        <i class="material-icons">donut_small</i>
    </a>
    <a title="Add New RSS Article" class="md-fab md-fab-accent md-fab-wave waves-effect waves-button" id="add_article" href="javascript:void(0);">
        <i class="material-icons">&#xE145;</i>
    </a>
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


<div class="uk-modal" id="generate_article_modal">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header uk-text-left">
            <h3 class="uk-modal-title"> Generate Rss Article </h3>
        </div>
        <div class="uk-width-large-1-1" id="story-feed-div" style="display:block" ;>
            <div class="md-list-content">
                <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px;">
                    <form id="generate_article_form" style="width:100%; margin:0; padding:0;" enctype="multipart/file-data">

                        <!-- Topic -->
                        <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <div class="md-input-wrapper">
                                        <label for="article_topic" class=" uk-form-label uk-text-primary">Article Topic</label>
                                        <input type="text" id="article_topic" name="article_topic" class="md-input" data-parsley-required-message="This field is required." required />
                                        <span class="md-input-bar "></span>
                                    </div>
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Number of Slides -->
                        <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <div class="md-input-wrapper md-input-focus uk-dropdown-shown">
                                        <label for="num_of_slides" class=" uk-form-label uk-text-primary">Number of Slides</label>
                                        <input type="number" id="num_of_slides" name="num_of_slides" value="5" min="2" class="md-input" data-parsley-required-message="This field is required." required />
                                        <span class="md-input-bar "></span>
                                    </div>
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button type="button" id="generate_article_form_cncl" class="md-btn md-btn-flat md-btn-danger uk-margin-medium-right uk-modal-close">Cancel</button><button type="button" id="generate_article_form_save" class="md-btn md-btn-flat md-btn-primary">Generate</button>
        </div>
    </div>
</div>