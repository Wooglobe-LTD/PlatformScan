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
						<div>
							<form id="form_search" class="uk-form-stacked">
								<div class="uk-grid" data-uk-grid-margin>

										<div class="uk-width-medium-1-3">
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
																<option value="" >Published</option>
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
										</div>


								</div>

								<br/><br/>

								<div class="uk-grid">
										<div class="uk-width-1-1">
												<button type="button" id="search" class="md-btn md-btn-primary check" style="float: right;">Search</button>
										</div>
								</div>
							</form>
						</div>

					</div>


            <div class="dt_colVis_buttons"></div>
            <table id="dt_tableExport" class="uk-table" cellspacing="0" width="100%">
                <thead>
                <tr>
					<th data-name="vl.unique_key">Deal Id</th>
					<th data-name="deal_name" data-orderable="false">Deal Name</th>
					<th data-name="vl.closing_date">Close Date</th>
					<th data-name="vl.status">Stage</th>
					<th data-name="vl.created_at">Created Time</th>
					<!--<th data-name="updated_at">Modified Time</th>
                    <th data-name="activity_at">Last Activity Time</th>-->
					<th data-name="vl.video_title">Video Title</th>
					<th data-name="v.description">Description</th>
					<th data-name="v.tags">Tags</th>
					<th data-name="vl.video_url">Video URL</th>
					<th data-name="v.video_description_updated">Video Description Updated</th>
					<th data-name="vl.first_name">Client Name</th>
					<!--<th data-name="lead_conversion_ime">Lead Conversion Time</th>-->
					<th data-name="sales_cycle_duration" data-orderable="false">Sales Cycle Duration</th>
					<th data-name="overall_dales_duration" data-orderable="false">Overall Sales Duration</th>
					<th data-name="social_network" data-orderable="false">Social Network</th>
					<th data-name="vl.video_rating">Video Rating</th>

					<th data-name="vl.revenue_share">Revenue Share - %</th>
					<!--<th data-name="last_activity">Last Activity</th>
                    <th data-name="last_customer_activity">Last Customer Activity</th>
                    <th data-name="move_to_stage">Move to Stage</th>-->
					<th data-name="vl.email">Client Email</th>
					<th data-name="vl.phone">Client Mobile</th>
					<th data-name="u.paypal_email">Client Paypal Email</th>
					<th data-name="youtube_link" data-orderable="false">Published YT Link</th>
					<th data-name="facebook_link" data-orderable="false">Published FB Link</th>
					<th data-name="v.mrss">MRSS Status</th>

					<!-- <th data-name="video_url ">Video Url</th>-->


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
                    <th data-name="activity_at">Last Activity Time</th>-->
                    <th data-name="vl.video_title">Video Title</th>
                    <th data-name="v.description">Description</th>
                    <th data-name="v.tags">Tags</th>
                    <th data-name="vl.video_url">Video URL</th>
                    <th data-name="v.video_description_updated">Video Description Updated</th>
                    <th data-name="vl.first_name">Client Name</th>
                    <!--<th data-name="lead_conversion_ime">Lead Conversion Time</th>-->
                    <th data-name="sales_cycle_duration" data-orderable="false">Sales Cycle Duration</th>
                    <th data-name="overall_dales_duration" data-orderable="false">Overall Sales Duration</th>
                    <th data-name="social_network" data-orderable="false">Social Network</th>
                    <th data-name="vl.video_rating">Video Rating</th>

                    <th data-name="vl.revenue_share">Revenue Share - %</th>
                    <!--<th data-name="last_activity">Last Activity</th>
                    <th data-name="last_customer_activity">Last Customer Activity</th>
                    <th data-name="move_to_stage">Move to Stage</th>-->
                    <th data-name="vl.email">Client Email</th>
                    <th data-name="vl.phone">Client Mobile</th>
                    <th data-name="u.paypal_email">Client Paypal Email</th>
                    <th data-name="youtube_link" data-orderable="false">Published YT Link</th>
                    <th data-name="facebook_link" data-orderable="false">Published FB Link</th>
                    <th data-name="v.mrss">MRSS Status</th>

                    <!-- <th data-name="video_url ">Video Url</th>-->
                </tr>
                </tfoot>

                <tbody>

                </tbody>
            </table>
        </div>
    </div>

</div>
</div>
