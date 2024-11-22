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
            <div class="dt_colVis_buttons"></div>
            <table id="dt_tableExport" class="uk-table" cellspacing="0" width="100%">
                <thead>
                <tr>
					<th data-name="deal_id">Deal Id</th>
					<th data-name="deal_name">Deal Name</th>
					<th data-name="closing_date">Close Date</th>
					<th data-name="stage">Stage</th>
					<th data-name="created_at">Created Time</th>
					<!--<th data-name="updated_at">Modified Time</th>
					<th data-name="activity_at">Last Activity Time</th>-->
					<th data-name="video_title">Video Title</th>
					<th data-name="description">Description</th>
					<th data-name="tags">Tags</th>
					<th data-name="video_url">Video URL</th>
					<th data-name="video_description_updated">Video Description Updated</th>
					<th data-name="client_name">Client Name</th>
					<!--<th data-name="lead_conversion_ime">Lead Conversion Time</th>-->
					<th data-name="sales_cycle_duration">Sales Cycle Duration</th>
					<th data-name="overall_sales_duration">Overall Sales Duration</th>
					<th data-name="social_network">Social Network</th>
					<th data-name="video_rating">Video Rating</th>

					<th data-name="revenue_share">Revenue Share - %</th>
					<!--<th data-name="last_activity">Last Activity</th>
					<th data-name="last_customer_activity">Last Customer Activity</th>
					<th data-name="move_to_stage">Move to Stage</th>-->
					<th data-name="email">Client Email</th>
					<th data-name="client_mobile">Client Mobile</th>
					<th data-name="client_paypal">Client Paypal Email</th>
					<th data-name="youtube_link">Published YT Link</th>
					<th data-name="facebook_link">Published FB Link</th>
					<th data-name="mrss_status">MRSS Status</th>

                   <!-- <th data-name="video_url ">Video Url</th>-->


                </tr>
                </thead>

                <tfoot>
                <tr>
					<th data-name="deal_id">Deal Id</th>
					<th data-name="deal_name">Deal Name</th>
					<th data-name="closing_date">Close Date</th>
					<th data-name="stage">Stage</th>
					<th data-name="created_at">Created Time</th>
					<!--<th data-name="updated_at">Modified Time</th>
					<th data-name="activity_at">Last Activity Time</th>-->
					<th data-name="description">Description</th>
					<th data-name="client_name">Client Name</th>
					<!--<th data-name="lead_conversion_ime">Lead Conversion Time</th>-->
					<th data-name="sales_cycle_duration">Sales Cycle Duration</th>
					<th data-name="overall_sales_duration">Overall Sales Duration</th>
					<th data-name="tags">Tags</th>
					<th data-name="social_network">Social Network</th>
					<th data-name="video_rating">Video Rating</th>
					<th data-name="video_url">Video URL</th>
					<th data-name="revenue_share">Revenue Share - %</th>
					<th data-name="video_title">Video Title</th>
					<!--<th data-name="last_activity">Last Activity</th>
					<th data-name="last_customer_activity">Last Customer Activity</th>
					<th data-name="move_to_stage">Move to Stage</th>-->
					<th data-name="email">Client Email</th>
					<th data-name="client_mobile">Client Mobile</th>
					<th data-name="client_paypal">Client Paypal Email</th>
					<th data-name="youtube_link">Published YT Link</th>
					<th data-name="facebook_link">Published FB Link</th>
					<th data-name="mrss_status">MRSS Status</th>
					<th data-name="video_description_updated">Video Description Updated</th>
                </tr>
                </tfoot>

                <tbody>
               
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>

