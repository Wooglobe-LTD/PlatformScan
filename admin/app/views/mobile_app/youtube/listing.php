<style>
textarea{
    scroll :none;
}
.youtube-modaal ul#channels_list {
    padding: 10px 16px;
    max-height: 163px;
    overflow: auto;
    /* border: 1px solid rgba(0,0,0,.12); */
    border-radius: 5px;
}

.youtube-modaal ul#channels_list li {
    list-style-type: none;
    padding-bottom: 10px;
}

.youtube-modaal ul#channels_list li img {
    max-width: 48px;
    height: auto;
    margin-right: 10px;
}

</style>

<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
                <li><span>Mobile Youtube Channels Management</span></li>
        </ul>
    </div>
<div id="page_content_inner">

    

    <h4 class="heading_a uk-margin-bottom"><?php echo $title;?></h4>
    <div class="md-card uk-margin-medium-bottom">
        <div class="md-card-content">
            <div class="dt_colVis_buttons"></div>
            <table id="ma_dt_tableExport" class="uk-table" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <?php if($assess['can_edit'] || $assess['can_delete']) { ?>
                        <th data-name="action">Actions</th>
                    <?php } ?>
                    <th data-name="channel_title">Channel Title</th>


                    <th data-name="channel_id">Channel ID</th>
                    <th data-name="youtube_videos_views_limit">Views Limit</th>
                    <th data-name="youtube_videos_comments_limit">Comments Limit</th>
                    <th data-name="youtube_videos_likes_limit">Likes Limit</th>
                    <th data-name="run_limit">Run Limit</th>
                   <!--  <th data-name="api_key">API Key</th> -->

                    <th data-name="status">Status</th>

                </tr>
                </thead>

                <tfoot>
                <tr>
                    <?php if($assess['can_edit'] || $assess['can_delete']) { ?>
                        <th data-name="action">Actions</th>
                    <?php } ?>
                    <th data-name="channel_title">Channel Title</th>
                   
                    <th data-name="channel_id">Channel ID</th>
                    <th data-name="youtube_videos_views_limit">Views Limit</th>
                    <th data-name="youtube_videos_comments_limit">Comments Limit</th>
                    <th data-name="youtube_videos_likes_limit">Likes Limit</th>
                    <th data-name="run_limit">Run Limit</th>
                    <th data-name="status">Status</th>

                </tr>
                </tfoot>

                <tbody>
               
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>
<?php if($assess['can_add']) { ?>
<div class="md-fab-wrapper">
        <a title="Add New MRSS Link" class="md-fab md-fab-accent md-fab-wave waves-effect waves-button" id="add" href="javascript:void(0);">
        <i class="material-icons">&#xE145;</i>
        </a>
    </div>
  <?php } ?>
    <div class="uk-modal youtube-modaal" id="add_model">
		<div class="uk-modal-dialog">
			<div class="uk-modal-header">
				<h3 class="uk-modal-title">Add New Youtube Channel</h3>
			</div>
			<div class="md-card-content large-padding">
				<form id="ma_form_validation2" class="uk-form-stacked">
                   <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="channel_title">Youtube Channel Title<span class="req">*</span></label>
                                <input type="text"  data-parsley-required-message="This field is required." name="channel_title" id="channel_title" required class="md-input" />
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1">
                            <label for="channel_id">Youtube Channel ID<span class="req">*</span></label>

							<div class="parsley-row">
                                <input type="text"  data-parsley-required-message="This field is required." name="channel_id" id="channel_id" required class="md-input" />
                                <div class="error"></div>
							</div>
                        </div>

                    </div>
                     <button type="button"  class="get_channel_id md-btn md-btn-flat md-btn-flat-primary">Get Channel ID</button>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                         <div class="parsley-row">
                         <ul class="list-group" id="channels_list">
                          
                          
                        </ul>
                        </div>
                        </div>

                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="youtube_videos_views_limit">Views Limit<span class="req">*</span></label>
                                <input type="number"  data-parsley-required-message="This field is required." name="youtube_videos_views_limit" id="youtube_videos_views_limit" required class="md-input" min="0" value="0" />
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>

                     <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="youtube_videos_comments_limit">Comments Limit<span class="req">*</span></label>
                                <input type="number"  data-parsley-required-message="This field is required." name="youtube_videos_comments_limit" id="youtube_videos_comments_limit" required class="md-input" min="0" value="0" />
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                     <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="youtube_videos_likes_limit">Likes Limit<span class="req">*</span></label>
                                <input type="number"  data-parsley-required-message="This field is required." name="youtube_videos_likes_limit" id="youtube_videos_likes_limit" required class="md-input" min="0" value="0" />
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                     <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="run_limit">Run Limit<span class="req">*</span></label>
                                <input type="number"  data-parsley-required-message="This field is required." name="run_limit" id="run_limit" required class="md-input" min="1" value="1" />
                                <span>How many times it should run in 24 hours</span>
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                     <!-- <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="api_key">API Key<span class="req">*</span></label>
                                <input type="text"  data-parsley-required-message="This field is required." name="api_key" id="api_key" required class="md-input"  />
                                <div class="error"></div>
                            </div>
                        </div>
                    </div> -->
                    <div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                
                                <select id="status" name="status" required data-parsley-required-message="This field is required." class="md-input">
                                    <option value="">Status*</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                       
					</div>






				</form>
			</div>
			<div class="uk-modal-footer uk-text-right">
				<button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="add_from" class="md-btn md-btn-flat md-btn-flat-primary">Add</button>
			</div>
		</div>
	</div>
    <div class="uk-modal youtube-modaal" id="edit_model">
		<div class="uk-modal-dialog">
			<div class="uk-modal-header">
				<h3 class="uk-modal-title">Edit Youtube Channel</h3>
			</div>
			<div class="md-card-content large-padding">
				<form id="ma_form_validation3" class="uk-form-stacked">
                    <input type="hidden" name="id" id="id_e" value="">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="channel_title">Youtube Channel Title<span class="req">*</span></label>
                                <input type="text"  data-parsley-required-message="This field is required." name="channel_title" id="channel_title_e" required class="md-input" />
                                <div class="error"></div>
                            </div>
                        </div>
                         
                    </div>
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1">
							<div class="parsley-row">
								<label for="channel_id">Channel ID<span class="req">*</span></label>
                                <input type="text"   data-parsley-required-message="This field is required." name="channel_id" id="channel_id_e" required class="md-input" />
                                <div class="error"></div>
							</div>
                        </div>
                    </div>
                     <button type="button"  class="get_channel_id md-btn md-btn-flat md-btn-flat-primary">Get Channel ID</button>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                         <div class="parsley-row">
                         <ul class="list-group" id="channels_list">
                          
                          
                        </ul>
                        </div>
                        </div>

                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="youtube_videos_views_limit">Views Limit<span class="req">*</span></label>
                                <input type="number"  data-parsley-required-message="This field is required." name="youtube_videos_views_limit" id="youtube_videos_views_limit_e" required class="md-input" min="0" value="0" />
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                     <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="youtube_videos_comments_limit">Comments Limit<span class="req">*</span></label>
                                <input type="number"  data-parsley-required-message="This field is required." name="youtube_videos_comments_limit" id="youtube_videos_comments_limit_e" required class="md-input" min="0" value="0" />
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                     <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="youtube_videos_likes_limit">Likes Limit<span class="req">*</span></label>
                                <input type="number"  data-parsley-required-message="This field is required." name="youtube_videos_likes_limit" id="youtube_videos_likes_limit_e" required class="md-input" min="0" value="0" />
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="run_limit">Run Limit<span class="req">*</span></label>
                                <input type="number"  data-parsley-required-message="This field is required." name="run_limit" id="run_limit_e" required class="md-input" min="1" value="1" />
                                <span>How many times it should run in 24 hours</span>
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    <!--  <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="api_key">API Key<span class="req">*</span></label>
                                <input type="text"  data-parsley-required-message="This field is required." name="api_key" id="api_key_e" required class="md-input"  />
                                <div class="error"></div>
                            </div>
                        </div>
                    </div> -->
                    <div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                
                                <select id="status_e" name="status" required data-parsley-required-message="This field is required." class="md-input">
                                    <option value="">Status*</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                       
					</div>

                    
				</form>
			</div>
			<div class="uk-modal-footer uk-text-right">
				<button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="edit_from" class="md-btn md-btn-flat md-btn-flat-primary">Save</button>
			</div>
		</div>
	</div>
   