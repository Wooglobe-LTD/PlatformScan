<style>
textarea{
    scroll :none;
}

</style>

<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span>Brands MRSS Feeds Management</span></li>
        </ul>
    </div>
<div id="page_content_inner">

    

    <h4 class="heading_a uk-margin-bottom"><?php echo $title;?></h4>
    <div class="md-card uk-margin-medium-bottom">
        <div class="md-card-content">
            <div class="dt_colVis_buttons"></div>
            <table id="dt_tableExport" class="uk-table" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <?php if($assess['can_edit'] || $assess['can_delete']) { ?>
                        <th data-name="c.action">Actions</th>
                    <?php } ?>
                    <th data-name="bmf.title">Feed Title</th>
                    <th data-name="u.full_name">Partner Name</th>
                    <th data-name="bmf.enqueued">Enqueued Videos</th>
                    <th data-name="mb.brand_name">Brand Name</th>
                    <th data-name="bmf.url">Feed URL</th>
                    <th data-name="bmf.status">Status</th>


                </tr>
                </thead>

                <tfoot>
                <tr>
                    <?php if($assess['can_edit'] || $assess['can_delete']) { ?>
                        <th data-name="c.action">Actions</th>
                    <?php } ?>
                    <th data-name="bmf.title">Feed Title</th>
                    <th data-name="u.full_name">Partner Name</th>
                    <th data-name="bmf.enqueued">Enqueued Videos</th>
                    <th data-name="mb.brand_name">Brand Name</th>
                    <th data-name="bmf.url">Feed URL</th>
                    <th data-name="bmf.status">Status</th>

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
        <a title="Add New MRSS Feed" class="md-fab md-fab-accent md-fab-wave waves-effect waves-button" id="add" href="javascript:void(0);">
        <i class="material-icons">&#xE145;</i>
        </a>
    </div>
  <?php } ?>
    <div class="uk-modal" id="add_model">
		<div class="uk-modal-dialog">
			<div class="uk-modal-header">
				<h3 class="uk-modal-title">Create Brands New MRSS Feed</h3>
			</div>
			<div class="md-card-content large-padding">
				<form id="form_validation2" class="uk-form-stacked">
                
                    <div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1">
							<div class="parsley-row">
								<select  id="partner" name="partner_id" required data-parsley-required-message="This field is required." class="md-input">
									<option value="">Partner*</option>
                                    <?php foreach($users as $user){?>
									<option value=<?php echo $user->id?>><?php echo $user->full_name?></option>
                                        <?php } ?>
                                </select>
							</div>
						</div>
					</div>

                    <div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1">
							<div class="parsley-row">
								<select  id="brand" name="brand_id" required data-parsley-required-message="This field is required." class="md-input">
									<option value="">Brand*</option>
                                    <?php foreach($brands as $brand){?>
									<option value=<?php echo $brand->id?>><?php echo $brand->brand_name?></option>
                                        <?php } ?>
                                </select>
							</div>
						</div>

					</div>

					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1">
							<div class="parsley-row">
								<label for="title">Feed Title<span class="req">*</span></label>
                                <input type="text" pattern="[a-zA-Z0-9\s]+" data-parsley-pattern-message="Only alphabet and number are allowed." data-parsley-required-message="This field is required." name="title" id="title" required class="md-input" />
                                <div class="error"></div>
							</div>
                        </div>
                    </div>
                    <!-- <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="url">Feed URL<span class="req">*</span></label>
                                <input type="text" pattern="[a-zA-Z0-9_-]+" data-parsley-pattern-message="Only alphabet, number and dash are allowed." data-parsley-required-message="This field is required." name="url" id="url" required class="md-input" />
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
					<!-- <div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                
                                <select id="pub_date" name="pub_date" class="md-input">
                                     <option value="">MRSS Pub Date</option>
                                     <option value="publish_date">Publish Date</option>
                                    <option value="created_at">Lead Created</option>
                                    <option value="question_when_video_taken">When Was Video Taken</option>
                                    </select>
                            </div>
                        </div>
                       
					</div> -->
                    
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

                    <h4 style="color: black;">Description Info</h4>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-3">
                                <input type="checkbox" name="desc_info[]" value="name" data-md-icheck />
                                <label for="url">Name</label>
                        </div>
                        <div class="uk-width-medium-1-3">
                                <input type="checkbox" name="desc_info[]" value="location" data-md-icheck />
                                <label>Location</label>
                        </div>
                        <div class="uk-width-medium-1-3">
                                <input type="checkbox" name="desc_info[]" value="filmed_on" data-md-icheck />
                                <label>Filmed on</label>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-3">
                                <input type="checkbox" name="desc_info[]" value="wgid" data-md-icheck />
                                <label for="url">WGID</label>
                        </div>
                        <div class="uk-width-medium-1-3">
                                <input type="checkbox" name="desc_info[]" value="license_signature" data-md-icheck />
                                <label>Wooglobe Licensing Signature</label>
                        </div>
                        <div class="uk-width-medium-1-3">
                                <input type="checkbox" name="desc_info[]" value="wooglobe_signature" data-md-icheck />
                                <label>Wooglobe Signature</label>
                        </div>
                    </div>

				</form>
			</div>
			<div class="uk-modal-footer uk-text-right">
				<button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="add_from" class="md-btn md-btn-flat md-btn-flat-primary">Add</button>
			</div>
		</div>
	</div>
    <div class="uk-modal" id="edit_model">
		<div class="uk-modal-dialog">
			<div class="uk-modal-header">
				<h3 class="uk-modal-title">Edit Brands MRSS Feed </h3>
			</div>
			<div class="md-card-content large-padding">
				<form id="form_validation3" class="uk-form-stacked">
                    <input type="hidden" name="id" id="id_e" value="">

					
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <select  id="partner_e" name="partner_id" required data-parsley-required-message="This field is required." class="md-input">
                                    <option value="">Partner*</option>
                                    <?php foreach($users as $user){?>
                                        <option value=<?php echo $user->id?>><?php echo $user->full_name?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1">
							<div class="parsley-row">
								<select  id="brand_e" name="brand_id" required data-parsley-required-message="This field is required." class="md-input">
									<option value="">Brand*</option>
                                    <?php foreach($brands as $brand){?>
									    <option value=<?php echo $brand->id?>><?php echo $brand->brand_name?></option>
                                    <?php } ?>
                                </select>
							</div>
						</div>
					</div>

					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1">
							<div class="parsley-row">
								<label for="title">Feed Title<span class="req">*</span></label>
                                <input type="text" pattern="[a-zA-Z0-9\s]+" data-parsley-pattern-message="Only alphabet and number are allowed." data-parsley-required-message="This field is required." name="title" id="title_e" required class="md-input" />
                                <div class="error"></div>
							</div>
                        </div>
                    </div>
                    <!-- <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="url">Feed URL<span class="req">*</span></label>
                                <input type="text" pattern="[a-zA-Z0-9_-]+" data-parsley-pattern-message="Only alphabet, number and dash are allowed." data-parsley-required-message="This field is required." name="url" id="url_e" required class="md-input" />
                                <div class="error"></div>
                            </div>
                        </div>
                    </div> -->
                    <div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                
                                <select id="status_e" name="status" required data-parsley-required-message="This field is required." class="md-input" select-s>
                                    <option value="">Status*</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                       
					</div>
					<!-- <div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                
                                <select id="pub_date_e" name="pub_date"  class="md-input" select-m>
                                    <option value="">MRSS Pub Date</option>
                                    <option value="publish_date">Publish Date</option>
                                    <option value="created_at">Lead Created</option>
                                    <option value="question_when_video_taken">When Was Video Taken</option>
                                </select>
                            </div>
                        </div>
                       
					</div> -->
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="feed_dely_e">Feed Delay Time (minutes)<span class="req">*</span></label>
                                <input type="text" name="feed_delay" id="feed_delay_e" class="md-input" />
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>

                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <label for="feed_time_from_e">24Hr GMT+1 - Time Range (From)</label>
                                <input type="text" name="feed_time_from" id="feed_time_from_e"  class="md-input" data-uk-timepicker="{format:'24h'}" />
                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <label for="feed_time_to_e">24Hr GMT+1 - Time Range (To)</label>
                                <input type="text" name="feed_time_to" id="feed_time_to_e" class="md-input" data-uk-timepicker="{format:'24h'}" />
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    <h4 style="color: black;">Feed Days</h4>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-4">
                                <input type="checkbox" name="desc_info[]" value="mon" id="cb_mon"  />
                                <label>Monday</label>
                        </div>
                        <div class="uk-width-medium-1-4">
                                <input type="checkbox" name="desc_info[]" value="tue" id="cb_tue"  />
                                <label>Tuesday</label>
                        </div>
                        <div class="uk-width-medium-1-4">
                                <input type="checkbox" name="desc_info[]" value="wed" id="cb_wed" />
                                <label>Wednesday</label>
                        </div>
                        <div class="uk-width-medium-1-4">
                                <input type="checkbox" name="desc_info[]" value="thu" id="cb_thu" />
                                <label>Thursday</label>
                        </div>
                    </div>

                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-4">
                                <input type="checkbox" name="desc_info[]" value="fri" id="cb_fri"  />
                                <label>Friday</label>
                        </div>
                        <div class="uk-width-medium-1-4">
                                <input type="checkbox" name="desc_info[]" value="sat" id="cb_sat"  />
                                <label>Saturday</label>
                        </div>
                        <div class="uk-width-medium-1-4">
                                <input type="checkbox" name="desc_info[]" value="sun" id="cb_sun" />
                                <label>Sunday</label>
                        </div>
                        
                    </div>

                    <h4 style="color: black;">Description Info</h4>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-3">
                                <input type="checkbox" name="desc_info[]" value="name" id="cb_name"  />
                                <label for="url">Name</label>
                        </div>
                        <div class="uk-width-medium-1-3">
                                <input type="checkbox" name="desc_info[]" value="location" id="cb_location"  />
                                <label>Location</label>
                        </div>
                        <div class="uk-width-medium-1-3">
                                <input type="checkbox" name="desc_info[]" value="filmed_on" id="cb_filmed_on" />
                                <label>Filmed on</label>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-3">
                                <input type="checkbox" name="desc_info[]" value="wgid" id="cb_wgid" />
                                <label for="url">WGID</label>
                        </div>
                        <div class="uk-width-medium-1-3">
                                <input type="checkbox" name="desc_info[]" value="license_signature" id="cb_license_signature" />
                                <label>Wooglobe Licensing Signature</label>
                        </div>
                        <div class="uk-width-medium-1-3">
                                <input type="checkbox" name="desc_info[]" value="wooglobe_signature" id="cb_wooglobe_signature" />
                                <label>Wooglobe Signature</label>
                        </div>
                    </div>
                    
				</form>
			</div>
			<div class="uk-modal-footer uk-text-right">
				<button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="edit_from" class="md-btn md-btn-flat md-btn-flat-primary">Save</button>
			</div>
		</div>
	</div>
