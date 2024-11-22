<style>
textarea{
	scroll :none;
}
.selectize-input{
	border-width : 0 0 0px !important;
}
.selectize-dropdown {
	margin-top: 0px !important;
}
*+.uk-table {
margin-top: 0px !important;
}
#video_url, #email{
	word-break: break-all;
}
.rpt-select{
	border-bottom: 1px solid #000;
}
.selectize-input {
  border-width: 0 0 1px !important;
}
#search{
	cursor: pointer;
	float: right;
}
#reset_input_fields{
	margin-right: 10px;
	cursor: pointer;
	float: right;
}
.dt_csv {
	color:#fff;
	background-color:#2196f3;
	margin-left:8px;
}
</style>

<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span>Report</span></li>
        </ul>
    </div>
<div id="page_content_inner">

  	<h4 class="heading_a uk-margin-bottom"><?php echo $title;?></h4>


    <div class="md-card uk-margin-medium-bottom">
        <div class="md-card-content">
					<div class="md-card-content">
						<h4 class="heading_a uk-margin-bottom">Filters</h4>
                        <?php if($role != 11){ ?>
                        <a style="display: none;" href="<?php echo base_url() ?>array_to_csv_download" class="md-btn buttons-csv buttons-html5 dt_csv">Overall csv download</a>
                        <?php }?>
						<div>
							<form id="form_search" class="uk-form-stacked">
                                <input type="hidden" id="search_field" name="search" value="1">
                                <input type="hidden" id="lead_type" name="lead_type" value="-1">
                               <?php if ($role == 11){ ?>
                                        <div class="uk-grid" data-uk-grid-margin>
                                        <!-- Hidden fields start  -->
                                        <input type="hidden" value="<?php echo $role;?>" name="role" id="role">
                                        <div class="uk-width-medium-1-2">
                                            <div class="parsley-row">

                                                <div class="parsley-row">
                                                    <select id="rating" name="rating" required data-parsley-required-message=""  data-md-selectize>
                                                        <option value="">Video Rating</option>
                                                        <option value="1">Less than 5</option>
                                                        <option value="5">5 and Above</option>
                                                        <option value="6">6 and Above</option>
                                                        <option value="7">7 and Above</option>
                                                        <option value="8">8 and Above</option>
                                                        <option value="9">9 and Above</option>
                                                    </select>
                                                    <div class="error"></div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="uk-width-medium-1-2">
                                            <div class="parsley-row">

                                                <div class="parsley-row">
                                                    <select id="published" name="published" required data-parsley-required-message=""  data-md-selectize>
                                                        <option value="" >Social Media Platform</option>
                                                        <option value="1">All</option>
                                                        <option value="2">TikTok</option>
                                                        <option value="3">Twitter</option>
                                                        <option value="4">Facebook</option>
                                                        <option value="5">Instagram</option>
                                                    </select>
                                                    <div class="error"></div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="uk-width-medium-1-2">
                                            <div class="parsley-row">

                                                <div class="parsley-row">
                                                    <select id="date_period" name="date_period" required data-parsley-required-message=""  data-md-selectize>
                                                        <option value="" >Lead Submission Date</option>
                                                        <option value="1">Today</option>
                                                        <option value="2">Yesterday</option>
                                                        <option value="3">This Week</option>
                                                        <option value="4">This Month</option>
                                                        <option value="5">Last Month</option>
                                                    </select>
                                                    <div class="error"></div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="uk-width-medium-1-2 ">

                                            <div class="uk-width-medium-1-3 period" style="display:inline-block;width: 49%;">
                                                <div class="parsley-row">
                                                    <div class="md-input-wrapper">
                                                        <label for="date_from">Date From</label>
                                                        <input class="md-input" id="date_from" data-uk-datepicker="{format:'YYYY-MM-DD',maxDate:''}" type="text" name="date_from" data-parsley-required-message="" value="" readonly>
                                                        <span class="md-input-bar "></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-3" style="display:inline-block;width: 49%;">
                                                <div class="parsley-row">
                                                    <div class="md-input-wrapper">
                                                        <label for="date_to">Date To</label>
                                                        <input class="md-input" id="date_to" data-uk-datepicker="{format:'YYYY-MM-DD',maxDate:''}" type="text" name="date_to" data-parsley-required-message="" value="" readonly>
                                                        <span class="md-input-bar "></span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="uk-width-medium-1-2">
                                            <div class="parsley-row">

                                                <div class="parsley-row">
                                                    <select id="date_aqution" name="date_period" required data-parsley-required-message=""  data-md-selectize>
                                                        <option value="" >Video Acquisition Date (Signed and Approved)</option>
                                                        <option value="1">Today</option>
                                                        <option value="2">Yesterday</option>
                                                        <option value="3">This Week</option>
                                                        <option value="4">This Month</option>
                                                        <option value="5">Last Month</option>
                                                    </select>
                                                    <div class="error"></div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="uk-width-medium-1-2 ">

                                            <div class="uk-width-medium-1-3 aqution" style="display:inline-block;width: 49%;">
                                                <div class="parsley-row">
                                                    <div class="md-input-wrapper">
                                                        <label for="date_from">Date From</label>
                                                        <input class="md-input" id="date_from_aqution" data-uk-datepicker="{format:'YYYY-MM-DD',maxDate:''}" type="text" name="date_from" data-parsley-required-message="" value="" readonly>
                                                        <span class="md-input-bar "></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-3" style="display:inline-block;width: 49%;">
                                                <div class="parsley-row">
                                                    <div class="md-input-wrapper">
                                                        <label for="date_to">Date To</label>
                                                        <input class="md-input" id="date_to_aqution" data-uk-datepicker="{format:'YYYY-MM-DD',maxDate:''}" type="text" name="date_to" data-parsley-required-message="" value="" readonly>
                                                        <span class="md-input-bar "></span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="uk-grid">
                                        <div class="uk-width-1-6">
                                            <button type="button" id="search" class="md-btn md-btn-primary check">Search</button>
                                        </div>
                                        <!--<div class="uk-width-1-6">
                                            <button type="button" id="staff_search_reset" class="md-btn md-btn-primary">Reset</button>
                                        </div>-->
                                    </div>

                                <?php }else{ ?>
                                <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-width-medium-1-3">
                                        <div class="parsley-row">

                                            <div class="parsley-row">
                                                <select id="type" name="type[]" multiple required data-parsley-required-message=""  data-md-selectize>
                                                    <option value="" selected>Select Scouts </option>
                                                    <option value="-1" >WooGlobe</option>
                                                    <?php foreach($staff_name as $staff){?>
                                                    <option value="<?php echo $staff->id; ?>"><?php echo $staff->name; ?></option>
                                                    <?php }?>
                                                </select>
                                                <div class="error"></div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="uk-width-medium-1-3">
                                        <div class="parsley-row">

                                            <div class="parsley-row">
                                                <select id="rating" name="rating" required data-parsley-required-message=""  data-md-selectize>
                                                    <option value="">Video Rating</option>
                                                    <option value="1">Less than 5</option>
                                                    <option value="5">5 and Above</option>
                                                    <option value="6">6 and Above</option>
                                                    <option value="7">7 and Above</option>
                                                    <option value="8">8 and Above</option>
                                                    <option value="9">9 and Above</option>
                                                </select>
                                                <div class="error"></div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="uk-width-medium-1-3">
                                        <div class="parsley-row">

                                            <div class="parsley-row">
                                                <select id="published" name="published" required data-parsley-required-message=""  data-md-selectize>
                                                    <option value="" >Social Media Platform</option>
                                                    <option value="1">All</option>
                                                    <option value="2">TikTok</option>
                                                    <option value="3">Twitter</option>
                                                    <option value="4">Facebook</option>
                                                    <option value="5">Instagram</option>
                                                </select>
                                                <div class="error"></div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                    <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-width-medium-1-2">
                                        <div class="parsley-row">

                                            <div class="parsley-row">
                                                <select id="date_period" name="date_period" required data-parsley-required-message=""  data-md-selectize>
                                                    <option value="" >Lead Submission Date</option>
                                                    <option value="1">Today</option>
                                                    <option value="2">Yesterday</option>
                                                    <option value="3">This Week</option>
                                                    <option value="4">This Month</option>
                                                    <option value="5">Last Month</option>
                                                </select>
                                                <div class="error"></div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="uk-width-medium-1-2 ">

                                        <div class="uk-width-medium-1-3 period" style="display:inline-block;width: 49%;">
                                            <div class="parsley-row">
                                                <div class="md-input-wrapper">
                                                    <label for="date_from">Date From</label>
                                                    <input class="md-input" id="date_from" data-uk-datepicker="{format:'YYYY-MM-DD',maxDate:''}" type="text" name="date_from" data-parsley-required-message="" value="" readonly>
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-width-medium-1-3 period" style="display:inline-block;width: 49%;">
                                            <div class="parsley-row">
                                                <div class="md-input-wrapper">
                                                    <label for="date_to">Date To</label>
                                                    <input class="md-input" id="date_to" data-uk-datepicker="{format:'YYYY-MM-DD',maxDate:''}" type="text" name="date_to" data-parsley-required-message="" value="" readonly>
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    </div>
                                   <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-width-medium-1-2">
                                        <div class="parsley-row">

                                            <div class="parsley-row">
                                                <select id="date_aqution" name="date_period" required data-parsley-required-message=""  data-md-selectize>
                                                    <option value="" >Video Acquisition Date (Signed and Approved)</option>
                                                    <option value="1">Today</option>
                                                    <option value="2">Yesterday</option>
                                                    <option value="3">This Week</option>
                                                    <option value="4">This Month</option>
                                                    <option value="5">Last Month</option>
                                                </select>
                                                <div class="error"></div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="uk-width-medium-1-2 ">

                                        <div class="uk-width-medium-1-3 aqution" style="display:inline-block;width: 49%;">
                                            <div class="parsley-row">
                                                <div class="md-input-wrapper">
                                                    <label for="date_from">Date From</label>
                                                    <input class="md-input" id="date_from_aqution" data-uk-datepicker="{format:'YYYY-MM-DD',maxDate:''}" type="text" name="date_from" data-parsley-required-message="" value="" readonly>
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-width-medium-1-3 aqution" style="display:inline-block;width: 49%;">
                                            <div class="parsley-row">
                                                <div class="md-input-wrapper">
                                                    <label for="date_to">Date To</label>
                                                    <input class="md-input" id="date_to_aqution" data-uk-datepicker="{format:'YYYY-MM-DD',maxDate:''}" type="text" name="date_to" data-parsley-required-message="" value="" readonly>
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                   <!-- <div class="uk-width-medium-1-3">
                                        <div class="parsley-row">

                                            <div class="parsley-row">
                                                <select id="type" name="type" required data-parsley-required-message=""  data-md-selectize>
                                                    <option value="" >Type</option>
                                                    <option value="1">Leads</option>
                                                    <option value="2">Deals</option>
                                                </select>
                                                <div class="error"></div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="uk-width-medium-1-3">

                                        <div class="uk-width-medium-1-3" style="display:inline-block;width: 49%;">
                                            <div class="parsley-row">
                                                <div class="md-input-wrapper">
                                                    <label for="date_from">Date From</label>
                                                    <input class="md-input" id="date_from" data-uk-datepicker="{format:'YYYY-MM-DD',maxDate:''}" type="text" name="date_from" data-parsley-required-message="" value="" readonly>
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-width-medium-1-3" style="display:inline-block;width: 49%;">
                                            <div class="parsley-row">
                                                <div class="md-input-wrapper">
                                                    <label for="date_to">Date To</label>
                                                    <input class="md-input" id="date_to" data-uk-datepicker="{format:'YYYY-MM-DD',maxDate:''}" type="text" name="date_to" data-parsley-required-message="" value="" readonly>
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="uk-width-medium-1-3">

                                        <div class="uk-width-medium-1-3" style="display:inline-block;width: 49%;">
                                            <div class="parsley-row">

                                                <div class="md-input-wrapper">
                                                    <label for="closing_date_from">Closing Date From</label>
                                                    <input class="md-input" id="closing_date_from"
                                                           name="closing_date_from"
                                                           data-uk-datepicker="{format:'YYYY-MM-DD',maxDate:''}" type="text"  data-parsley-required-message="" value="" readonly>
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-width-medium-1-3" style="display:inline-block;width: 49%;">
                                            <div class="parsley-row">

                                                <div class="md-input-wrapper">
                                                    <label for="closing_date_to">Closing Date To</label>
                                                    <input class="md-input" id="closing_date_to" name="closing_date_to" data-uk-datepicker="{format:'YYYY-MM-DD',maxDate:''}" type="text"  data-parsley-required-message="" value="" readonly>
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="uk-width-medium-1-3">
                                        <div class="parsley-row">
                                            <select id="stage" name="stage" required data-parsley-required-message=""  data-md-selectize>
                                                <option value="">Stage</option>
                                                <option value="0">Pending Lead</option>
                                                <option value="10">Lead Rated</option>
                                                <option value="5">Poor Rating Video</option>
                                                <option value="2">Contract Sent</option>
                                                <option value="3">Contract Signed</option>
                                                <option value="4">Account Created</option>
                                                <option value="7">Deal Information Pending</option>
                                                <option value="13">Deal Information Received</option>
                                                <option value="6">Upload Edited Videos</option>
                                                <option value="12">Distribute Edited Videos</option>
                                                <option value="8">Closed Won</option>
                                                <option value="9">Closed Lost</option>
                                                <option value="11">Not Interested</option>

                                            </select>
                                            <div class="error"></div>
                                        </div>
                                    </div>

                                    <div class="uk-width-medium-1-3">
                                        <div class="parsley-row">

                                            <div class="parsley-row">
                                                <select id="rating" name="rating" required data-parsley-required-message=""  data-md-selectize>
                                                    <option value="">Rating</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                    <option value="10">10</option>
                                                </select>
                                                <div class="error"></div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="uk-width-medium-1-3">
                                        <div class="parsley-row">

                                            <div class="parsley-row">
                                                <select id="published" name="published" required data-parsley-required-message=""  data-md-selectize>
                                                    <option value="" >Select Published Value</option>
                                                    <option value="1">Youtube</option>
                                                    <option value="2">Facebook</option>
                                                </select>
                                                <div class="error"></div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="uk-width-medium-1-3">
                                        <div class="parsley-row">

                                            <div class="parsley-row">
                                                <select id="mrss" name="mrss" required data-parsley-required-message=""  data-md-selectize>
                                                    <option value="" >MRSS</option>
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                                <div class="error"></div>
                                            </div>

                                        </div>
                                    </div>-->


                                </div>
                                <div class="uk-grid" >
                                    <div class="uk-width-1-6">
                                        <button  type="button" id="search" class="md-btn md-btn-primary check">Search</button>
                                    </div>
                                    <!--<div class="uk-width-1-6">
                                        <button type="button" id="staff_search_reset" class="md-btn md-btn-primary">Reset</button>
                                    </div>-->
                                </div>
                                <?php } ?>
								<br/><br/>


							</form>
						</div>

					</div>


            <div class="dt_colVis_buttons"></div>
            <table id="dt_tableExport" class="uk-table" cellspacing="0" width="100%">


                <?php /*if ($role == 11){ */?>
                    <thead>
                    <tr>
                        <th data-name="date_time">Staff</th>
                        <th data-name="total_leads">Leads</th>
                        <th data-name="total_leads">Not Rated</th>
                        <th data-name="total_leads">Action Required</th>
                        <th data-name="total_signed">Signed (Awaiting Approval)</th>
                        <th data-name="rejected_deals">Signed (Rejected, Awaiting Resolution)</th>
                        <th data-name="canceled_deals">Acquired Deals</th>
                        <th data-name="canceled_deals">Canceled Deals</th>
                        <th data-name="canceled_deals">Poor Deals</th>
                        <th data-name="canceled_deals">Not Interested Deals</th>
                        <th data-name="conversion">Conversion Ratio</th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th data-name="date_time">Staff</th>
                        <th data-name="total_leads">Leads</th>
                        <th data-name="total_leads">Not Rated</th>
                        <th data-name="total_leads">Action Required</th>
                        <th data-name="total_signed">Signed (Awaiting Approval)</th>
                        <th data-name="rejected_deals">Signed (Rejected, Awaiting Resolution)</th>
                        <th data-name="canceled_deals">Acquired Deals</th>
                        <th data-name="canceled_deals">Canceled Deals</th>
                        <th data-name="canceled_deals">Poor Deals</th>
                        <th data-name="canceled_deals">Not Interested Deals</th>
                        <th data-name="conversion">Conversion Ratio</th>
                    </tr>
                    </tfoot>
                <?php if($role != 11){ ?>
                <!--<thead>
                <tr>
                    <th data-name="vl.unique_key">Deal Id</th>
                    <th data-name="deal_name" data-orderable="false">Deal Name</th>
                    <th data-name="vl.closing_date">Close Date</th>
                    <th data-name="vl.status">Stage</th>
                    <th data-name="vl.created_at">Created Time</th>
                    <!--<th data-name="updated_at">Modified Time</th>
                    <th data-name="activity_at">Last Activity Time</th>
                    <th data-name="vl.video_title">Video Title</th>
                    <th data-name="v.description">Description</th>
                    <th data-name="v.tags">Tags</th>
                    <th data-name="v.confidence_level">Confidence Level</th>
                    <th data-name="v.contract_link">Contract Link</th>
                    <th data-name="vl.portal_thumnail">Portal Thumbnail</th>
                    <th data-name="vl.video_url">Video URL</th>
                    <th data-name="vl.video_url">Raw Video URL</th>
                    <th data-name="vl.s3_video_url">S3 Video URL</th>
                    <th data-name="v.video_description_updated">Video Description Updated</th>
                    <th data-name="vl.first_name">Client Name</th>
                    <!--<th data-name="lead_conversion_ime">Lead Conversion Time</th>
                    <th data-name="sales_cycle_duration" data-orderable="false">Sales Cycle Duration</th>
                    <th data-name="overall_dales_duration" data-orderable="false">Overall Sales Duration</th>
                    <th data-name="social_network" data-orderable="false">Social Network</th>
                    <th data-name="vl.video_rating">Video Rating</th>

                    <th data-name="vl.revenue_share">Revenue Share - %</th>
                    <!--<th data-name="last_activity">Last Activity</th>
                    <th data-name="last_customer_activity">Last Customer Activity</th>
                    <th data-name="move_to_stage">Move to Stage</th>
                    <th data-name="vl.email">Client Email</th>
                    <th data-name="vl.phone">Client Mobile</th>
                    <th data-name="u.paypal_email">Client Paypal Email</th>
                    <th data-name="youtube_link" data-orderable="false">Published YT Link</th>
                    <th data-name="facebook_link" data-orderable="false">Published FB Link</th>
                    <th data-name="v.mrss">MRSS Status</th>

                    <!-- <th data-name="video_url ">Video Url</th>


                </tr>
                </thead>

                <tfoot>
                <tr>
                    <th data-name="vl.unique_key">Deal Id</th>
                    <th data-name="deal_name" data-orderable="false">Deal Name</th>
                    <th data-name="vl.closing_date">Close Date</th>
                    <th data-name="vl.status">Stage</th>
                    <th data-name="vl.created_at">Created Time</th>
                    <!--<th data-name="updated_at">Modified Time</th>
                    <th data-name="activity_at">Last Activity Time</th>
                    <th data-name="vl.video_title">Video Title</th>
                    <th data-name="v.description">Description</th>
                    <th data-name="v.tags">Tags</th>
                    <th data-name="v.confidence_level">Confidence Level</th>
                    <th data-name="v.contract_link">Contract Link</th>
                    <th data-name="vl.portal_thumnail">Portal Thumbnail</th>
                    <th data-name="vl.video_url">Video URL</th>
                    <th data-name="vl.raw_video_url">Raw Video URL</th>
                    <th data-name="vl.s3_video_url">S3 Video URL</th>
                    <th data-name="v.video_description_updated">Video Description Updated</th>
                    <th data-name="vl.first_name">Client Name</th>
                    <!--<th data-name="lead_conversion_ime">Lead Conversion Time</th>
                    <th data-name="sales_cycle_duration" data-orderable="false">Sales Cycle Duration</th>
                    <th data-name="overall_dales_duration" data-orderable="false">Overall Sales Duration</th>
                    <th data-name="social_network" data-orderable="false">Social Network</th>
                    <th data-name="vl.video_rating">Video Rating</th>

                    <th data-name="vl.revenue_share">Revenue Share - %</th>
                    <!--<th data-name="last_activity">Last Activity</th>
                    <th data-name="last_customer_activity">Last Customer Activity</th>
                    <th data-name="move_to_stage">Move to Stage</th>
                    <th data-name="vl.email">Client Email</th>
                    <th data-name="vl.phone">Client Mobile</th>
                    <th data-name="u.paypal_email">Client Paypal Email</th>
                    <th data-name="youtube_link" data-orderable="false">Published YT Link</th>
                    <th data-name="facebook_link" data-orderable="false">Published FB Link</th>
                    <th data-name="v.mrss">MRSS Status</th>

                    <!-- <th data-name="video_url ">Video Url</th>
                </tr>
                </tfoot>-->
                <?php } ?>
                <tbody>

                </tbody>
            </table>
           <table id="dt_tableExport_details" class="uk-table" cellspacing="0" width="100%">

                    <thead>
                    <tr>
                        <th datar-name="vl.unique_key">Deal Id</th>
                        <th data-name="deal_name" data-orderable="false">Deal Name</th>
                        <th data-name="vl.closing_date">Close Date</th>
                        <th data-name="vl.status">Stage</th>
                        <th data-name="vl.created_at">Created Time</th>


                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th datar-name="vl.unique_key">Deal Id</th>
                        <th data-name="deal_name" data-orderable="false">Deal Name</th>
                        <th data-name="vl.closing_date">Close Date</th>
                        <th data-name="vl.status">Stage</th>
                        <th data-name="vl.created_at">Created Time</th>
                    </tr>
                    </tfoot>

            </table>


            <h3 class="heading_a">Graphs Data</h3>
                <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-3">
                    <div class="parsley-row">

                        <div class="parsley-row">
                            <select id="graph_type" name="graph_type" required data-parsley-required-message=""  data-md-selectize>
                                <option value="1">Today</option>
                                <option value="2">Yesterday</option>
                                <option selected value="3">This Week</option>
                                <option value="4">Lsat Week</option>
                                <option value="5">This Month</option>
                                <option value="6">Last Month</option>
                                <option value="7">Last 3 Month</option>
                                <option value="8">Last  6 Month</option>
                                <option value="9">This Year</option>
                            </select>
                            <div class="error"></div>
                        </div>

                    </div>
                </div>
                </div>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                                    <span class="icheck-inline">
                                        <input type="radio" name="graph" value="acquired" id="radio_demo_inline_1" data-md-icheck checked />
                                        <label for="radio_demo_inline_1" class="inline-label">Acquired</label>
                                    </span>
                <span class="icheck-inline">
                                        <input type="radio" name="graph" value="signed" id="radio_demo_inline_2" data-md-icheck />
                                        <label for="radio_demo_inline_2" class="inline-label">Signed (Awaiting Approval)</label>
                                    </span>
                <span class="icheck-inline">
                                        <input type="radio" name="graph" value="signed_reject" id="radio_demo_inline_3" data-md-icheck />
                                        <label for="radio_demo_inline_3" class="inline-label">Signed (Rejected, Awaiting Resolution)</label>
                                    </span>
                <span class="icheck-inline">
                                        <input type="radio" name="graph" value="lead" id="radio_demo_inline_4" data-md-icheck />
                                        <label for="radio_demo_inline_4" class="inline-label">Leads</label>
                                    </span>
                <span class="icheck-inline">
                                        <input type="radio" name="graph" value="not_rate" id="radio_demo_inline_5" data-md-icheck />
                                        <label for="radio_demo_inline_5" class="inline-label">Not Rate</label>
                                    </span>
                <span class="icheck-inline">
                                        <input type="radio" name="graph" value="action" id="radio_demo_inline_6" data-md-icheck />
                                        <label for="radio_demo_inline_6" class="inline-label">Action Required</label>
                                    </span>
                <span class="icheck-inline">
                                        <input type="radio" name="graph" value="cancel" id="radio_demo_inline_7" data-md-icheck />
                                        <label for="radio_demo_inline_7" class="inline-label">Canceled</label>
                                    </span>
                <span class="icheck-inline">
                                        <input type="radio" name="graph" value="poor" id="radio_demo_inline_8" data-md-icheck />
                                        <label for="radio_demo_inline_8" class="inline-label">Poor</label>
                                    </span>
                <span class="icheck-inline">
                                        <input type="radio" name="graph" value="not" id="radio_demo_inline_9" data-md-icheck />
                                        <label for="radio_demo_inline_9" class="inline-label">Not Interested</label>
                                    </span>

            </div>
                </div>
            
            <div class="uk-grid" data-uk-grid-margin>
                <div id="chartContainerPie" style="height: 370px; width: 100%;"></div>
            </div>
            <div class="uk-grid" data-uk-grid-margin>
                <div id="chartContainer" style="height: 370px; width: 100%;"></div>
            </div>

            <div class="uk-grid" data-uk-grid-margin>
                <div id="monthchartContainer" style="height: 370px; width: 100%;"></div>
            </div>
        </div>
    </div>

</div>
</div>
<?php  ?>

<?php  ?>