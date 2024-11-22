<?php
/**
 * Created by PhpStorm.
 * User: T3500
 * Date: 3/16/2018
 * Time: 11:07 AM
 */
?>
<style>
    .selectize-dropdown{
        margin-top: 0px;
    }
</style>
<?php $baseurl=base_url(); ?>
<div id="page_content" class="uk-height-1-1 video_deals_list_page">
    <div id="top_bar">
        <div class="top_bar_left">
            <ul id="breadcrumbs">
                <li><a href="<?php echo $url;?>">Dashboard</a></li>
                <li><span>Video Deals Management</span></li>
            </ul>
        </div>
        <div class="top_bar_right">
            <p class="reminder_color_1">First Reminder</p>
            <p class="reminder_color_2">Last Reminder</p>
            <p class="rejection_color">Rejection</p>
        </div>
    </div>
    <div class="scrum_board_overflow">
        <div id="scrum_board" class="uk-clearfix">
            <!-- <?php if($assess['deals']){?>
                <div>
                    <div class="scrum_column_heading_wrapper">
                        <div class="scrum_column_heading"> Deals (<span id="deals-rated-count"><?php echo $num_deals_rated; ?></span>)
                        </div>
                        <div class="uk-button-dropdown" style="right: 30px;" data-uk-dropdown="{mode:'click',pos:'bottom-right'}">
                            <i class="md-icon material-icons">sort</i>
                            <div class="uk-dropdown uk-dropdown-small">
                                <ul class="uk-nav uk-nav-dropdown">
                                    <li><a href="javascript:void(0);" class="refresh-sort-" data-id="scrum_column_deals_rated" data-count="deals-rated-count" data-sort="ASC" data-col="vl.created_at" data-url="deal-rated-refresh">Sort <i class="material-icons">swap_vert</i></a></li>
                                    <li><input id="toogle-asc-0"  type="radio" name="toogle-order-0" class="toogle-order" data-num="0" data-id="scrum_column_deals_rated" data-sort="ASC"  data-url="deal-rated-refresh" data-colvalue="vl.created_at">ASC</li>
                                    <li><input id="toogle-desc-0" type="radio" name="toogle-order-0" class="toogle-order" data-num="0" data-id="scrum_column_deals_rated" data-sort="DESC" data-url="deal-rated-refresh" data-colvalue="vl.created_at" checked>DESC</li>									
									<li class="uk-nav-divider"></li>
                                    <li><a href="javascript:void(0);" class="refresh-column"      data-id="scrum_column_deals_rated" data-count="deals-rated-count" data-column="vl.closing_date" data-url="deal-rated-refresh">Closing Time </a></li>
                                    <li><a href="javascript:void(0);" class="refresh-column"      data-id="scrum_column_deals_rated" data-count="deals-rated-count" data-column="vl.rating_point" data-url="deal-rated-refresh">Rating </a></li>
                                    <li><a href="javascript:void(0);" class="refresh-column"      data-id="scrum_column_deals_rated" data-count="deals-rated-count" data-column="vl.updated_at" data-url="deal-rated-refresh">Rated Time</a></li>
									<li><a href="javascript:void(0);" class="last-activity"       data-id="scrum_column_deats_rated" data-column="at.created_at">Last Activity</a></li>
									<li><a href="javascript:void(0);" class="curr-stage-updated"  data-id="scrum_column_deats_rated" data-column="at.created_at">Stage Updated</a></li>
                                </ul>
                            </div>
                        </div>
                        <a class="uk-button-dropdown refresh-column-data"  data-id="scrum_column_deats_rated" data-count="deals-rated-count" data-url="deal-rated-refresh" style="top: 9px;" href="javascript:void(0);">
                            <i class="material-icons">&#xE5D5;</i>
                        </a>
                    </div>
                    <div class="scrum_column">
                        <div id="scrum_column_deats_rated" class="board-elemets">

                            <?php echo $deals_rated; ?>

                        </div>
                    </div>
                </div>
            <?php } ?> -->
            <?php if($assess['information_received']){?>
                <div>
                    <div class="scrum_column_heading_wrapper">
                        <div class="scrum_column_heading">Deal Information Received (<span id="information-received-count"><?php echo $num_deal_received; ?></span>)</div>
                        <div class="uk-button-dropdown" style="right: 30px;" data-uk-dropdown="{mode:'click',pos:'bottom-right'}">
                            <i class="md-icon material-icons" style="margin-right: 4px;">sort</i>
                            <div class="uk-dropdown uk-dropdown-small">
                                <ul class="uk-nav uk-nav-dropdown">
                                    <li><a href="javascript:void(0);" class="refresh-sort" data-id="scrum_column_account_deal_information_received" data-count="information-received-count" data-sort="DESC" data-col="vl.closing_date" data-url="information-received-refresh">Sort <i class="material-icons">swap_vert</i></a></li>
                                    <li><input id="toogle-asc-1"  type="radio"  name="toogle-order-1" class="toogle-order" data-num="1" data-id="scrum_column_deals_rated" data-sort="ASC"  data-url="information-received-refresh" data-colvalue="vl.closing_date">ASC</li>
                                    <li><input id="toogle-desc-1" type="radio"  name="toogle-order-1" class="toogle-order" data-num="1" data-id="scrum_column_deals_rated" data-sort="DESC" data-url="information-received-refresh" data-colvalue="vl.closing_date" checked>DESC</li>	                                    
									<li class="uk-nav-divider"></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_account_deal_information_received" data-count="information-received-count" data-column="vl.closing_date" data-url="information-received-refresh">Closing Time </a></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_account_deal_information_received" data-count="information-received-count" data-column="vl.rating_point" data-url="information-received-refresh">Rating </a></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_account_deal_information_received" data-count="information-received-count" data-column="vl.updated_at" data-url="information-received-refresh">Rated Time</a></li>
									<li><a href="javascript:void(0);" class="last-activity"  data-id="scrum_column_account_deal_information_received" data-column="at.created_at">Last Activity</a></li>	
									<li><a href="javascript:void(0);" class="curr-stage-updated"  data-id="scrum_column_account_deal_information_received" data-column="at.created_at">Stage Updated</a></li>
                                </ul>
                            </div>
                        </div>
                        <a class="uk-button-dropdown refresh-column-data"  data-id="scrum_column_account_deal_information_received" data-count="information-received-count" data-url="information-received-refresh" style="top: 9px;" href="javascript:void(0);">
                            <i class="material-icons">&#xE5D5;</i>
                        </a>

                    </div>
                    <div class="scrum_column">
                        <div id="scrum_column_account_deal_information_received" class="board-elemets">
                            <?php echo $deal_received; ?>
                        </div>
                    </div>
                </div>

            <?php } ?>
            <!-- <?php //if($assess['upload_video']){?>
                <div>
                    <div class="scrum_column_heading_wrapper">
                        <div class="scrum_column_heading">Upload Editied Videos (<span id="upload-count"><?php //echo $num_upload_videos; ?></span>)</div>
                        <div class="uk-button-dropdown" style="right: 30px;" data-uk-dropdown="{mode:'click',pos:'bottom-right'}">
                            <i class="md-icon material-icons" style="margin-right: 4px;">sort</i>
                            <div class="uk-dropdown uk-dropdown-small">
                                <ul class="uk-nav uk-nav-dropdown">
                                    <li><a href="javascript:void(0);" class="refresh-sort" data-id="scrum_column_account_upload" data-count="upload-count" data-sort="ASC" data-col="vl.created_at" data-url="upload-refresh">Sort <i class="material-icons">swap_vert</i></a></li>
                                    <li><input id="toogle-asc-2"  type="radio" name="toogle-order-2" class="toogle-order" data-num="2" data-id="scrum_column_deals_rated" data-sort="ASC"  data-url="deal-rated-refresh" data-colvalue="vl.created_at">ASC</li>
                                    <li><input id="toogle-desc-2" type="radio" name="toogle-order-2" class="toogle-order" data-num="2" data-id="scrum_column_deals_rated" data-sort="DESC" data-url="deal-rated-refresh" data-colvalue="vl.created_at" checked>DESC</li>	                                    
									<li class="uk-nav-divider"></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_account_upload" data-count="upload-count" data-column="vl.closing_date" data-url="upload-refresh">Closing Time </a></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_account_upload" data-count="upload-count" data-column="vl.rating_point" data-url="upload-refresh">Rating </a></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_account_upload" data-count="upload-count" data-column="vl.updated_at" data-url="upload-refresh">Rated Time</a></li>
									<li><a href="javascript:void(0);" class="last-activity"  data-id="scrum_column_account_upload" data-column="at.created_at">Last Activity</a></li>		
									<li><a href="javascript:void(0);" class="curr-stage-updated"  data-id="scrum_column_account_upload" data-column="at.created_at">Stage Updated</a></li>
                                </ul>
                            </div>
                        </div>
                        <a class="uk-button-dropdown refresh-column-data"  data-id="scrum_column_account_upload" data-count="upload-count" data-url="upload-refresh" style="top: 9px;" href="javascript:void(0);">
                            <i class="material-icons">&#xE5D5;</i>
                        </a>

                    </div>
                    <div class="scrum_column">
                        <div id="scrum_column_account_upload" class="board-elemets">

                           <?php //echo $upload_videos; ?>

                        </div>
                    </div>
                </div>

            <?php //} ?> -->

            <?php if($assess['upload_video']){?>
                <div>
                    <div class="scrum_column_heading_wrapper">
                        <div class="scrum_column_heading">Edited Videos Status (<span id="edited-upload-count"><?php echo $num_edited_videos; ?></span>)</div>
                        <div class="uk-button-dropdown" style="right: 30px;" data-uk-dropdown="{mode:'click',pos:'bottom-right'}">
                            <i class="md-icon material-icons" style="margin-right: 4px;">sort</i>
                            <div class="uk-dropdown uk-dropdown-small">
                                <ul class="uk-nav uk-nav-dropdown">
                                    <li><a href="javascript:void(0);" class="refresh-sort" data-id="scrum_column_edited_upload" data-count="edited-upload-count" data-sort="DESC" data-col="default" data-url="edited-refresh">Sort <i class="material-icons">swap_vert</i></a></li>
                                    <li><input id="toogle-asc-2"  type="radio" name="toogle-order-2" class="toogle-order" data-num="2" data-id="scrum_column_edited_upload" data-sort="ASC"  data-url="edited-refresh" data-colvalue="vl.closing_date">ASC</li>
                                    <li><input id="toogle-desc-2" type="radio" name="toogle-order-2" class="toogle-order" data-num="2" data-id="scrum_column_edited_upload" data-sort="DESC" data-url="edited-refresh" data-colvalue="vl.closing_date">DESC</li>
                                    <li><input id="toogle-cn-2"   type="radio" name="toogle-order-2" class="toogle-order" data-num="2" data-id="scrum_column_edited_upload" data-sort="ASC"  data-url="edited-refresh" data-colvalue="vl.is_cn_updated">Pending CN</li>
                                    <li><input id="toogle-ve-2"   type="radio" name="toogle-order-2" class="toogle-order" data-num="2" data-id="scrum_column_edited_upload" data-sort="ASC"  data-url="edited-refresh" data-colvalue="vl.uploaded_edited_videos">Pending VE</li>
                                    <li><input id="toogle-pr-2"   type="radio" name="toogle-order-2" class="toogle-order" data-num="2" data-id="scrum_column_edited_upload" data-sort="ASC"  data-url="edited-refresh" data-colvalue="vl.priority">Priority</li>
                                    <li><input id="toogle-df-2"   type="radio" name="toogle-order-2" class="toogle-order" data-num="2" data-id="scrum_column_edited_upload" data-sort="ASC"  data-url="edited-refresh" data-colvalue="default" checked>Default</li>
                                    <li class="uk-nav-divider"></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_edited_upload" data-count="edited-upload-count" data-column="vl.closing_date" data-url="edited-refresh">Closing Time </a></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_edited_upload" data-count="edited-upload-count" data-column="vl.rating_point" data-url="edited-refresh">Rating </a></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_edited_upload" data-count="edited-upload-count" data-column="vl.updated_at" data-url="edited-refresh">Rated Time</a></li>
                                    <li><a href="javascript:void(0);" class="last-activity"  data-id="scrum_column_edited_upload" data-column="at.created_at">Last Activity</a></li>
                                    <li><a href="javascript:void(0);" class="curr-stage-updated"  data-id="scrum_column_edited_upload" data-column="at.created_at">Stage Updated</a></li>
                                </ul>
                            </div>
                        </div>
                        <a class="uk-button-dropdown refresh-column-data"  data-id="scrum_column_edited_upload" data-count="edited-upload-count" data-url="edited-refresh" style="top: 9px;" href="javascript:void(0);">
                            <i class="material-icons">&#xE5D5;</i>
                        </a>

                    </div>
                    <div class="scrum_column">
                        <div id="scrum_column_edited_upload" class="board-elemets">

                            <?php echo $edited_videos; ?>

                        </div>
                    </div>
                </div>

            <?php } ?>
            
            <!-- <?php if($assess['upload_video']){?>
                <div>
                    <div class="scrum_column_heading_wrapper">
                        <div class="scrum_column_heading">Upload Editied Videos (<span id="upload-count"><?php echo $num_upload_videos; ?></span>)</div>
                        <div class="uk-button-dropdown" style="right: 30px;" data-uk-dropdown="{mode:'click',pos:'bottom-right'}">
                            <i class="md-icon material-icons" style="margin-right: 4px;">sort</i>
                            <div class="uk-dropdown uk-dropdown-small">
                                <ul class="uk-nav uk-nav-dropdown">
                                    <li><a href="javascript:void(0);" class="refresh-sort" data-id="scrum_column_information_update" data-count="upload-count" data-sort="ASC" data-url="upload-refresh">Sort <i class="material-icons">swap_vert</i></a></li>
                                    <li><input id="toogle-asc-2"  type="radio" name="toogle-order-2" class="toogle-order" data-num="2" data-id="scrum_column_deals_rated" data-sort="ASC"  data-url="deal-rated-refresh">ASC</li>
                                    <li><input id="toogle-desc-2" type="radio" name="toogle-order-2" class="toogle-order" data-num="2" data-id="scrum_column_deals_rated" data-sort="DESC" data-url="deal-rated-refresh"  checked>DESC</li>	                                    
									<li class="uk-nav-divider"></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_information_update" data-count="information-update-count" data-column="vl.closing_date" data-url="information-update-refresh">Closing Time </a></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_information_update" data-count="information-update-count" data-column="vl.rating_point" data-url="information-update-refresh">Rating </a></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_information_update" data-count="information-update-count" data-column="vl.updated_at" data-url="information-update-refresh">Rated Time</a></li>
									<li><a href="javascript:void(0);" class="last-activity"  data-id="scrum_column_information_update" data-column="at.created_at">Last Activity</a></li>		
									<li><a href="javascript:void(0);" class="curr-stage-updated"  data-id="scrum_column_information_update" data-column="at.created_at">Stage Updated</a></li>																											
                                    <input type="hidden" class="sort_value" value="ASC">
                                    <input type="hidden" class="column_value" value="vl.created_at">
                                </ul>
                            </div>
                        </div>
                        <a class="uk-button-dropdown refresh-column-data"  data-id="scrum_column_information_update" data-count="information-update-count" data-url="information-update-refresh" style="top: 9px;" href="javascript:void(0);">
                            <i class="material-icons">&#xE5D5;</i>
                        </a>

                    </div>
                    <div class="scrum_column">
                        <div id="scrum_column_information_update" class="board-elemets">

                           <?php echo $deal_information_update; ?>

                        </div>
                    </div>
                </div>

            <?php } ?> -->


            <?php if($assess['can_distribute']){?>
                <div>
                    <div class="scrum_column_heading_wrapper">
                        <div class="scrum_column_heading">Distribution (<span id="distribute-count"><?php echo $num_distribute; ?></span>)</div>
                        <div class="uk-button-dropdown" style="right: 30px;" data-uk-dropdown="{mode:'click',pos:'bottom-right'}">
                            <i class="md-icon material-icons" style="margin-right: 4px;">sort</i>
                            <div class="uk-dropdown uk-dropdown-small">
                                <ul class="uk-nav uk-nav-dropdown">
                                    <li><a href="javascript:void(0);" class="refresh-sort" data-id="scrum_column_distribute" data-count="distribute-count" data-sort="DESC" data-col="vl.closing_date" data-url="distribute-refresh">Sort <i class="material-icons">swap_vert</i></a></li>
                                    <li><input id="toogle-asc-3"  type="radio" name="toogle-order-3" class="toogle-order" data-num="3"  data-id="scrum_column_deals_rated" data-sort="ASC"  data-url="distribute-refresh" data-colvalue="vl.closing_date">ASC</li>
                                    <li><input id="toogle-desc-3" type="radio" name="toogle-order-3" class="toogle-order" data-num="3"  data-id="scrum_column_deals_rated" data-sort="DESC" data-url="distribute-refresh" data-colvalue="vl.closing_date" checked>DESC</li>
                                    <li><input id="toogle-yt-3"   type="radio" name="toogle-order-3" class="toogle-order" data-num="3"  data-id="scrum_column_deals_rated" data-sort="ASC"  data-url="distribute-refresh" data-colvalue="vl.published_yt">Youtube</li>
                                    <li><input id="toogle-mrss-3" type="radio" name="toogle-order-3" class="toogle-order" data-num="3"  data-id="scrum_column_deals_rated" data-sort="ASC"  data-url="distribute-refresh" data-colvalue="v.mrss">MRSS</li>
                                    <li><input id="toogle-dpbx-3" type="radio" name="toogle-order-3" class="toogle-order" data-num="3"  data-id="scrum_column_deals_rated" data-sort="ASC"  data-url="distribute-refresh" data-colvalue="rv.dropbox_status">Dropbox</li>                                   
									<li class="uk-nav-divider"></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_distribute" data-count="distribute-count" data-column="vl.closing_date" data-url="distribute-refresh">Closing Time </a></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_distribute" data-count="distribute-count" data-column="vl.rating_point" data-url="distribute-refresh">Rating </a></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_distribute" data-count="distribute-count" data-column="vl.updated_at" data-url="distribute-refresh">Rated Time</a></li>
									<li><a href="javascript:void(0);" class="last-activity"  data-id="scrum_column_distribute" data-column="at.created_at">Last Activity</a></li>																											
    								<li><a href="javascript:void(0);" class="curr-stage-updated"  data-id="scrum_column_distribute" data-column="at.created_at">Stage Updated</a></li>
                                </ul>
                            </div>
                        </div>
                        <a class="uk-button-dropdown refresh-column-data"  data-id="scrum_column_distribute" data-count="distribute-count" data-url="distribute-refresh" style="top: 9px;" href="javascript:void(0);">
                            <i class="material-icons">&#xE5D5;</i>
                        </a>

                    </div>
                    <div class="scrum_column">
                        <div id="scrum_column_distribute" class="board-elemets">

                            <?php echo $distribute; ?>

                        </div>
                    </div>
                </div>

            <?php } ?>
            <!-- <?php //if($assess['can_distribute']){?>
                <div>
                    <div class="scrum_column_heading_wrapper">
                        <div class="scrum_column_heading">Distribution on (FB)(<span id="distribute-fb-count"><?php //echo $num_distribute_fb; ?></span>)</div>
                        <div class="uk-button-dropdown" style="right: 30px;" data-uk-dropdown="{mode:'click',pos:'bottom-right'}">
                            <i class="md-icon material-icons" style="margin-right: 4px;">sort</i>
                            <div class="uk-dropdown uk-dropdown-small">
                                <ul class="uk-nav uk-nav-dropdown">
                                    <li><a href="javascript:void(0);" class="refresh-sort" data-id="scrum_column_distribute_fb" data-count="distribute-fb-count" data-sort="ASC" data-col="vl.created_at" data-url="distribute-refresh">Sort <i class="material-icons">swap_vert</i></a></li>
                                    <li><input id="toogle-asc-4"  type="radio" name="toogle-order-5" class="toogle-order" data-num="4"  data-id="scrum_column_deals_rated" data-sort="ASC"  data-url="deal-rated-refresh" data-colvalue="vl.created_at">ASC</li>
                                    <li><input id="toogle-desc-4" type="radio" name="toogle-order-5" class="toogle-order" data-num="4"  data-id="scrum_column_deals_rated" data-sort="DESC" data-url="deal-rated-refresh" data-colvalue="vl.created_at" checked>DESC</li>	                                    
									<li class="uk-nav-divider"></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_distribute_fb" data-count="distribute-fb-count" data-column="vl.closing_date" data-url="distribute-refresh">Closing Time </a></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_distribute_fb" data-count="distribute-fb-count" data-column="vl.rating_point" data-url="distribute-refresh">Rating </a></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_distribute_fb" data-count="distribute-fb-count" data-column="vl.updated_at" data-url="distribute-refresh">Rated Time</a></li>
									<li><a href="javascript:void(0);" class="last-activity"  data-id="scrum_column_distribute_fb" data-column="at.created_at">Last Activity</a></li>																											                                    
									<li><a href="javascript:void(0);" class="curr-stage-updated"  data-id="scrum_column_distribute_fb" data-column="at.created_at">Stage Updated</a></li>
                                </ul>
                            </div>
                        </div>
                        <a class="uk-button-dropdown refresh-column-data"  data-id="scrum_column_distribute_fb" data-count="distribute-fb-count" data-url="distribute-refresh" style="top: 9px;" href="javascript:void(0);">
                            <i class="material-icons">&#xE5D5;</i>
                        </a>

                    </div>
                    <div class="scrum_column">
                        <div id="scrum_column_distribute_fb" class="board-elemets">

                            <?php //echo $distribute_fb; ?>

                        </div>
                    </div>
                </div>

            <?php //} ?> -->

            <?php if($assess['can_distribute']){?>
                <div>
                    <div class="scrum_column_heading_wrapper">
                        <div class="scrum_column_heading">Distributed on YT (<span id="distribute-yt-count"><?php echo $num_distribute_yt; ?></span>)</div>
                        <div class="uk-button-dropdown" style="right: 30px;" data-uk-dropdown="{mode:'click',pos:'bottom-right'}">
                            <i class="md-icon material-icons" style="margin-right: 4px;">sort</i>
                            <div class="uk-dropdown uk-dropdown-small">
                                <ul class="uk-nav uk-nav-dropdown">
                                    <li><a href="javascript:void(0);" class="refresh-sort" data-id="scrum_column_distribute_yt" data-count="distribute-yt-count" data-sort="DESC" data-col="vl.closing_date" data-url="distribute-refresh">Sort <i class="material-icons">swap_vert</i></a></li>
                                    <li><input id="toogle-asc-4"  type="radio" name="toogle-order-5" class="toogle-order" data-num="4"  data-id="scrum_column_deals_rated" data-sort="ASC"  data-url="deal-rated-refresh" data-colvalue="vl.closing_date">ASC</li>
                                    <li><input id="toogle-desc-4" type="radio" name="toogle-order-5" class="toogle-order" data-num="4"  data-id="scrum_column_deals_rated" data-sort="DESC" data-url="deal-rated-refresh" data-colvalue="vl.closing_date" checked>DESC</li>	                                    
									<li class="uk-nav-divider"></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_distribute_yt" data-count="distribute-yt-count" data-column="vl.closing_date" data-url="distribute-yt-refresh">Closing Time </a></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_distribute_yt" data-count="distribute-yt-count" data-column="vl.rating_point" data-url="distribute-yt-refresh">Rating </a></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_distribute_yt" data-count="distribute-yt-count" data-column="vl.updated_at" data-url="distribute-yt-refresh">Rated Time</a></li>
									<li><a href="javascript:void(0);" class="last-activity"  data-id="scrum_column_distribute_yt" data-column="at.created_at">Last Activity</a></li>																											                                    
									<li><a href="javascript:void(0);" class="curr-stage-updated"  data-id="scrum_column_distribute_yt" data-column="at.created_at">Stage Updated</a></li>
                                </ul>
                            </div>
                        </div>
                        <a class="uk-button-dropdown refresh-column-data"  data-id="scrum_column_distribute_yt" data-count="distribute-yt-count" data-url="distribute-yt-refresh" style="top: 9px;" href="javascript:void(0);">
                            <i class="material-icons">&#xE5D5;</i>
                        </a>

                    </div>
                    <div class="scrum_column">
                        <div id="scrum_column_distribute_yt" class="board-elemets">

                            <?php echo $distribute_yt; ?>

                        </div>
                    </div>
                </div>

            <?php } ?>
            <?php if($assess['can_distribute']){?>
                <div>
                    <div class="scrum_column_heading_wrapper">
                        <div class="scrum_column_heading">Published on MRSS (<span id="distribute-mrss-count"><?php echo $num_distribute_mrss; ?></span>)</div>
                        <div class="uk-button-dropdown" style="right: 30px;" data-uk-dropdown="{mode:'click',pos:'bottom-right'}">
                            <i class="md-icon material-icons" style="margin-right: 4px;">sort</i>
                            <div class="uk-dropdown uk-dropdown-small">
                                <ul class="uk-nav uk-nav-dropdown">
                                    <li><a href="javascript:void(0);" class="refresh-sort" data-id="scrum_column_distribute_mrss" data-count="distribute-mrss-count" data-sort="DESC" data-col="vl.closing_date" data-url="distribute-mrss-refresh">Sort <i class="material-icons">swap_vert</i></a></li>
                                    <li><input id="toogle-asc-5"  type="radio" name="toogle-order-5" class="toogle-order" data-num="5"  data-id="scrum_column_deals_rated" data-sort="ASC"  data-url="distribute-mrss-refresh" data-colvalue="vl.closing_date">ASC</li>
                                    <li><input id="toogle-desc-5" type="radio" name="toogle-order-5" class="toogle-order" data-num="5"  data-id="scrum_column_deals_rated" data-sort="DESC" data-url="distribute-mrss-refresh" data-colvalue="vl.closing_date" checked>DESC</li>	                                    
									<li class="uk-nav-divider"></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_distribute_mrss" data-count="distribute-mrss-count" data-column="vl.closing_date" data-url="distribute-mrss-refresh">Closing Time </a></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_distribute_mrss" data-count="distribute-mrss-count" data-column="vl.rating_point" data-url="distribute-mrss-refresh">Rating </a></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_distribute_mrss" data-count="distribute-mrss-count" data-column="vl.updated_at" data-url="distribute-mrss-refresh">Rated Time</a></li>
									<li><a href="javascript:void(0);" class="last-activity"  data-id="scrum_column_distribute_mrss" data-column="at.created_at">Last Activity</a></li>																											                                    
									<li><a href="javascript:void(0);" class="curr-stage-updated"  data-id="scrum_column_distribute_mrss" data-column="at.created_at">Stage Updated</a></li>
                                </ul>
                            </div>
                        </div>
                        <a class="uk-button-dropdown refresh-column-data"  data-id="scrum_column_distribute_mrss" data-count="distribute-mrss-count" data-url="distribute-mrss-refresh" style="top: 9px;" href="javascript:void(0);">
                            <i class="material-icons">&#xE5D5;</i>
                        </a>

                    </div>
                    <div class="scrum_column">
                        <div id="scrum_column_distribute_yt" class="board-elemets">

                            <?php echo $distribute_mrss; ?>

                        </div>
                    </div>
                </div>

            <?php } ?>
            <?php if($assess['can_distribute']){?>
                <div>
                    <div class="scrum_column_heading_wrapper">
                        <div class="scrum_column_heading">Published on Dropbox (<span id="distribute-dropbox-count"><?php echo $num_distribute_dropbox; ?></span>)</div>
                        <div class="uk-button-dropdown" style="right: 30px;" data-uk-dropdown="{mode:'click',pos:'bottom-right'}">
                            <i class="md-icon material-icons" style="margin-right: 4px;">sort</i>
                            <div class="uk-dropdown uk-dropdown-small">
                                <ul class="uk-nav uk-nav-dropdown">
                                    <li><a href="javascript:void(0);" class="refresh-sort" data-id="scrum_column_distribute_dropbox" data-count="distribute-dpbx-count" data-sort="DESC" data-col="vl.closing_date" data-url="distribute-dpbx-refresh">Sort <i class="material-icons">swap_vert</i></a></li>
                                    <li><input id="toogle-asc-6"  type="radio" name="toogle-order-6" class="toogle-order" data-num="6"  data-id="scrum_column_deals_rated" data-sort="ASC"  data-url="distribute-dpbx-refresh" data-colvalue="vl.closing_date">ASC</li>
                                    <li><input id="toogle-desc-6" type="radio" name="toogle-order-6" class="toogle-order" data-num="6"  data-id="scrum_column_deals_rated" data-sort="DESC" data-url="distribute-dpbx-refresh" data-colvalue="vl.closing_date" checked>DESC</li>	                                    
									<li class="uk-nav-divider"></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_distribute_dpbx" data-count="distribute-dropbox-count" data-column="vl.closing_date" data-url="distribute-dpbx-refresh">Closing Time </a></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_distribute_dpbx" data-count="distribute-dropbox-count" data-column="vl.rating_point" data-url="distribute-dpbx-refresh">Rating </a></li>
                                    <li><a href="javascript:void(0);" class="refresh-column" data-id="scrum_column_distribute_dpbx" data-count="distribute-dropbox-count" data-column="vl.updated_at" data-url="distribute-dpbx-refresh">Rated Time</a></li>
									<li><a href="javascript:void(0);" class="last-activity"  data-id="scrum_column_distribute_dpbx" data-column="at.created_at">Last Activity</a></li>																											                                    
									<li><a href="javascript:void(0);" class="curr-stage-updated"  data-id="scrum_column_distribute_dropbox" data-column="at.created_at">Stage Updated</a></li>
                                </ul>
                            </div>
                        </div>
                        <a class="uk-button-dropdown refresh-column-data"  data-id="scrum_column_distribute_dropbox" data-count="distribute-dpbx-count" data-url="distribute-dpbx-refresh" style="top: 9px;" href="javascript:void(0);">
                            <i class="material-icons">&#xE5D5;</i>
                        </a>

                    </div>
                    <div class="scrum_column">
                        <div id="scrum_column_distribute_yt" class="board-elemets">

                            <?php echo $distribute_dropbox; ?>

                        </div>
                    </div>
                </div>

            <?php } ?>
        </div>
    </div>

</div>
<?php if($assess['can_client_add']){?>
    <div class="uk-modal" id="add_model">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title">Add New Client</h3>
            </div>

            <div class="md-card-content large-padding">
                <form id="form_validation2" class="uk-form-stacked">
                    <input type="hidden" name="lead_id" id="lead_id" value="">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="full_name">Full Name<span class="req">*</span></label>
                                <input type="text" data-parsley-required-message="This field is required." name="full_name" id="full_name" required class="md-input" />
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="email">Email Address<span class="req">*</span></label>
                                <input type="email" data-parsley-required-message="This field is required." name="email" id="email" required class="md-input" data-parsley-type-message="Please enter the valid email address."/>
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
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
<?php } ?>
<?php if($assess['can_distribute']){?>
    <div class="uk-modal" id="youtube">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title">Publish At YouTube </h3>
            </div>

            <div class="md-card-content large-padding">
                <form id="distribute_yt" class="uk-form-stacked">
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
                                <select id="youtube_channel" name="youtube_channel" data-parsley-required-message="This field is required." required >
                                    <!-- <option value="">Choose..</option> -->
                                    <!-- <option value="UCbbtHuBeqqlRB9yNr_Hpc7w">WooGlobe Rights Management</option> -->
                                    <!-- <option value="UCElx75bqGbVPghRwL-YuKfw">CreatorTesing</option>
                                    <option value="UCHC4fJ2MkyZLPlVkHLGpGEg">CreatorTesing 2</option> -->
                                </select>
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="youtube_category" class="uk-form-label">Category<span class="req">*</span></label>
                                <select id="youtube_category" name="youtube_category" data-parsley-required-message="This field is required." required >
                                    <!-- <option value="">Choose..</option> -->
                                </select>
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>

                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="youtube_video_type" class="uk-form-label">Publish Video Type<span class="req">*</span></label>
                                <select id="youtube_video_type" name="youtube_video_type" data-parsley-required-message="This field is required." required >
                                    <option value="">Choose..</option>
                                    <option value="1" selected>Watermark Video</option>
                                    <option value="2">Edited Video</option>

                                </select>
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    

                    <div class="publish_yt_sch">
                        <label for="publish_now_youtube"  class="radio">
                            <input type="radio" checked id="publish_now_youtube" name="publish_now_youtube" value="0"/>
                            <span><b>Publish Now</b></span>
                        </label>
                        <label for="youtube_publish_date_radio" class="radio" style="margin-left: 10px;">
                            <input type="radio" id="youtube_publish_date_radio" name="publish_now_youtube" value="1"/>
                            <span><b>Scheduling</b></span>
                        </label>

                        <div id="dvPinNo2" style="display: none;"> <!--style="display: none;"-->
                            <div class="uk-grid">

                                <div class="uk-width-large-1-3 uk-width-1-1">
                                    <div class="uk-input-group">
                                        <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                        <label for="youtube_publish_date">Select date</label>
                                        <input class="md-input" type="text" name="youtube_publish_date" id="youtube_publish_date" data-uk-datepicker="{format:'YYYY-MM-DD'}" required>
                                    </div>
                                </div>

                                <div class="uk-width-large-1-3 uk-width-1-1">
                                    <div class="uk-input-group">
                                        <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-clock-o"></i></span>
                                        <label for="youtube_publish_time">Select time</label>
                                        <input class="md-input" type="text" name="youtube_publish_time" id="youtube_publish_time" data-uk-timepicker data-parsley-required-message="This field is required." required>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <br/><br/>

                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="youtube_publish_title">Video Title<span class="req">*</span></label>
                                <input type="text" name="youtube_publish_title" value="" id="youtube_publish_title"  data-parsley-required-message="This field is required." required class="md-input" />
                                <div id="cont-p" style="float: right;font-size: 14px;font-weight: 200;">

                                    <span class="cont-p">Character Limit :</span>
                                    <span class="counter-title"></span>
                                </div>
                                <div class="error"></div>
                            </div>
                        </div>

                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="youtube_publish_description" class="uk-form-label">Video Description</label>
                                <textarea id="youtube_publish_description" name="youtube_publish_description" cols="30" rows="10" class="md-input" data-parsley-required-message="This field is required." required>
                                </textarea>
                                <input type="hidden" name="yt_desc_footer" id="yt_desc_footer">
                                <div id="yt_footer">
                                    <p>►SUBSCRIBE for more Awesome Videos: <a href="https://goo.gl/uDVc4n">https://goo.gl/uDVc4n</a><br>
                                        -----------------------</p>
                                    <p>Copyright - #WooGlobe.</p>
                                    <p>We bring you the most trending internet videos!<br>
                                        For licensing and to use this video, please email licensing(at)Wooglobe(dot)com.</p>
                                    <p>Video ID: <span id="video_id"></span> </p>
                                    <p>Twitter: <a href="https://twitter.com/WooGlobe"> https://twitter.com/WooGlobe</a><br>
                                        Facebook: <a href="https://fb.com/Wooglobe"> https://fb.com/Wooglobe</a><br>
                                        Instagram : <a href="https://www.instagram.com/WooGlobe/"> https://www.instagram.com/WooGlobe/</a></p>
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

                                    <span class="footer-desc"><a href="javascript:void(0);" id="add_footer" data-footer="<?php echo settings()->description_footer;?>">Add Footer</a> </span>

                                </div>
                                <div class="error"></div>
                            </div>
                        </div>

                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="youtube_publish_tags" class="uk-form-label">Video Tags</label>
                                <textarea id="youtube_publish_tags" name="youtube_publish_tags" class="md-input" data-parsley-required-message="This field is required." required></textarea>

                                <div id="cont-p" style="float: right;font-size: 14px;font-weight: 200;">

                                    <span class="cont-desc">Character Limit :</span>
                                    <span class="counter"></span>
                                </div>
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="youtube_publish_status" class="uk-form-label">Publish Status<span class="req">*</span></label>
                                <select id="youtube_publish_status" name="youtube_publish_status" data-parsley-required-message="This field is required." required data-md-selectize>
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
                <button type="button" id="distribute_from" class="md-btn md-btn-flat md-btn-flat-primary">Publish</button>

            </div>
        </div>
    </div>
    <div class="uk-modal" id="facebook">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title">Publish At FaceBook </h3>
            </div>

            <div class="md-card-content large-padding">
                <form id="distribute_fb" class="uk-form-stacked">
                    <input type="hidden" id="ed_video_id_fb" name="video_id" value="">

                    <div class="publish_fb_sch">
                        <label for="publish_now_facebook"  class="radio">
                            <input type="radio" checked id="publish_now_facebook" name="publish_now_facebook" value="0"  />
                            <span><b>Publish Now</b></span>
                        </label>
                        <label for="facebook_publish_date_radio" class="radio" style="margin-left: 10px;">
                            <input type="radio" id="facebook_publish_date_radio" name="publish_now_facebook" value="1">
                            <span><b>Scheduling</b></span>
                        </label>

                        <div id="dvPinNo" style="display: none;"><!-- style="display: none;"-->
                            <div class="uk-grid">

                                <div class="uk-width-large-1-3 uk-width-1-1">
                                    <div class="uk-input-group">
                                        <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                        <label for="facebook_publish_date">Select date</label>
                                        <input class="md-input" type="text" name="facebook_publish_date" id="facebook_publish_date" data-uk-datepicker="{minDate:'<?=date('Y-M-d');?>'format:'YYYY-MM-DD'}"  required>
                                    </div>


                                </div>

                                <div class="uk-width-large-1-3 uk-width-1-1">
                                    <div class="uk-input-group">
                                        <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-clock-o"></i></span>
                                        <label for="facebook_publish_time">Select time</label>
                                        <input class="md-input" type="text" name="facebook_publish_time" id="facebook_publish_time" data-uk-timepicker data-parsley-required-message="This field is required." required>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="uk-grid" data-uk-grid-margin style="display: none";>
                        <div class="uk-width-medium-1-1">
                            <!--<input type="checkbox" name="publish_now_facebook" id="publish_now_facebook" value="" data-md-icheck />-->
                            <!--<label for="publish_now_facebook" class="inline-label"><b>Publish Now</b></label>-->


                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin style="display: none">
                        <div class="uk-width-medium-1-2">

                            <!--<h3 class="heading_a"><b>Scheduling</b></h3>-->
                            <div class="uk-grid">
                                <div class="uk-width-large-2-3 uk-width-1-1">
                                    <!--<div class="uk-input-group">
                                        <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                        <label for="facebook_publish_date">Select date</label>
                                        <input class="md-input" type="text" name="facebook_publish_date" id="facebook_publish_date" data-uk-datepicker="{format:'YYYY-MM-DD'}" data-parsley-required-message="This field is required." required>
                                    </div>-->
                                </div>

                            </div>
                        </div>
                        <div class="uk-width-medium-1-2">
                            <h3 class="heading_a">&nbsp;</h3>
                            <div class="uk-grid">
                                <div class="uk-width-large-2-3 uk-width-1-1">
                                    <!--<div class="uk-input-group">
                                        <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-clock-o"></i></span>
                                        <label for="facebook_publish_time">Select time</label>
                                        <input class="md-input" type="text" name="facebook_publish_time" id="facebook_publish_time" data-uk-timepicker data-parsley-required-message="This field is required." required>
                                    </div>-->
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="facebook_publish_title">Video Title<span class="req">*</span></label>
                                <input type="text" name="facebook_publish_title" value="" id="facebook_publish_title"  data-parsley-required-message="This field is required." required class="md-input" />
                                <div class="error"></div>
                            </div>
                        </div>

                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="facebook_publish_description" class="uk-form-label">Video Description</label>
                                <textarea id="facebook_publish_description" name="facebook_publish_description" cols="30" rows="10" class="md-input" data-parsley-required-message="This field is required." required></textarea>
                                <div id="cont-p" style="float: right;font-size: 14px;font-weight: 200;">

                                    <span class="footer-desc"><a href="javascript:void(0);" id="add_footer1" data-footer="<?php echo settings()->description_footer;?>">Add Footer</a> </span>

                                </div>
                                <div class="error"></div>
                            </div>
                        </div>

                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="facebook_publish_tags" class="uk-form-label">Video Tags</label>
                                <textarea id="facebook_publish_tags" name="facebook_publish_tags" class="md-input" data-parsley-required-message="This field is required." required></textarea>

                                <div id="cont-p" style="float: right;font-size: 14px;font-weight: 200;">

                                    <span class="cont-desc">Character Limit :</span>
                                    <span class="counter1"></span>
                                </div>
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="distribute_from_fb" class="md-btn md-btn-flat md-btn-flat-primary">Publish</button>
            </div>
        </div>
    </div>
<?php } ?>
<?php if($assess['list']){?>
    <div class="uk-modal" id="detial" >
        <div class="uk-modal-dialog" style="width: 70%;">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title" >Video Deal Detail</h3>
            </div>
            <div class="md-card-content large-padding" >
                <div class="uk-width-xLarge-8-12  uk-width-large-7-12">
                    <div class="md-card">
                        <div class="md-card-toolbar">
                            <h3 class="md-card-toolbar-heading-text">
                                Client Details
                            </h3>
                        </div>
                        <div class="md-card-content large-padding">
                            <div class="uk-grid uk-grid-divider uk-grid-medium">
                                <div class="uk-width-large-1-2">
                                    <div class="uk-grid uk-grid-small">
                                        <div class="uk-width-large-1-3">
                                            <span class="uk-text-muted uk-text-small">Client First Name</span>
                                        </div>
                                        <div class="uk-width-large-2-3">
                                            <span class="uk-text-large uk-text-middle" id="first_name"></span>
                                        </div>
                                    </div>
                                    <hr class="uk-grid-divider">
                                    <div class="uk-grid uk-grid-small">
                                        <div class="uk-width-large-1-3">
                                            <span class="uk-text-muted uk-text-small">Client Last Name</span>
                                        </div>
                                        <div class="uk-width-large-2-3">
                                            <span class="uk-text-large uk-text-middle" id="last_name"></span>
                                        </div>
                                    </div>
                                    <hr class="uk-grid-divider">
                                    <div class="uk-grid uk-grid-small">
                                        <div class="uk-width-large-1-3">
                                            <span class="uk-text-muted uk-text-small">Client Email</span>
                                        </div>
                                        <div class="uk-width-large-2-3" id="email-c">

                                        </div>
                                    </div>


                                    <hr class="uk-grid-divider uk-hidden-large">
                                </div>
                                <div class="uk-width-large-1-2">
                                    <p>
                                        <span class="uk-text-muted uk-text-small uk-display-block uk-margin-small-bottom">Video Title</span>

                                        <span class="uk-badge uk-badge-success" id="video_title"></span>

                                    </p>
                                    <hr class="uk-grid-divider">
                                    <p>
                                        <span class="uk-text-muted uk-text-small uk-display-block uk-margin-small-bottom">Video URL</span>

                                        <span class="uk-badge uk-badge-success"><a id="video_url" href="javascript:void(0);" class="play-video"></a> </span>

                                    </p>
                                    <hr class="uk-grid-divider">
                                    <p>
                                        <span class="uk-text-muted uk-text-small uk-display-block uk-margin-small-bottom">Message</span>
                                    <p id="message"></p>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php //if($assess['can_rate']){?>
                    <div class="md-card">
                        <div class="md-card-toolbar">
                            <h3 class="md-card-toolbar-heading-text">
                                Video Rating
                            </h3>
                        </div>
                        <div class="md-card-content large-padding">

                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-2-10">
                                    <span class="uk-display-block uk-margin-small-top uk-text-large">Rate This Video</span>
                                </div>
                                <div class="uk-width-medium-8-10">
                                    <form id="form_validation3" class="uk-form-stacked">
                                        <input type="hidden" name="id" value="" id="id" />
                                        <table class="uk-table">
                                            <thead>
                                            <tr>
                                                <th class="uk-width-1-4">Rating Point</th>
                                                <th class="uk-width-1-4">Comments</th>
                                                <th class="uk-width-1-4">Revenue Share - %</th>
                                                <th class="uk-width-1-4">Deal Closing Date</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr >

                                                <td style="border-bottom: none;">
                                                    <span class="uk-text-large uk-text-middle" id="rating_point"></span>
                                                </td>
                                                <td style="border-bottom: none;">
                                                    <span class="uk-text-large uk-text-middle" id="rating_comments"></span>
                                                </td>
                                                <td style="border-bottom: none;">
                                                    <span class="uk-text-large uk-text-middle" id="revenue_share"></span>
                                                </td>
                                                <td style="border-bottom: none;">
                                                    <span class="uk-text-large uk-text-middle" id="closing_date"></span>
                                                </td>


                                            </tr>

                                            </tbody>
                                        </table>

                                        <!--<div class="uk-grid" style="float: right;">
                                            <div class="uk-width-1-1">
                                                <button type="submit" class="md-btn md-btn-primary">Rate It</button>
                                            </div>
                                        </div>-->
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php //} ?>
                </div>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
            </div>
        </div>
    </div>
    <div class="uk-modal" id="play_model">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title" id="vt"></h3>
            </div>
            <div class="md-card-content large-padding" id="play">

            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
            </div>
        </div>
    </div>
<?php } ?>
<?php if($assess['can_revenue_update']){?>
    <div class="uk-modal" id="revenue_modal">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title">Deal Revenue Update (<label class="dt"></label> ) </h3>
            </div>

            <div class="md-card-content large-padding">
                <form id="form_validation4" class="uk-form-stacked">
                    <input type="hidden" id="ru_lead_id" name="lead_id" value="">
                    <input type="hidden" id="ru_sent" name="sent" value="">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="revenue_share" class="uk-form-label">Revenue Share - %<span class="req">*</span></label>
                                <input id="revenue_share" name="revenue_share" class="md-input"
                                       data-parsley-required-message="Revenue Share is required."
                                       data-parsley-type="integer"
                                       data-parsley-type-message="Please enter the valid value."
                                       data-parsley-range="[10, 100]"
                                       data-parsley-range-message="Revenue Share must be between 10 to 100."
                                       value=""
                                       required
                                />
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="revenue_submit" class="md-btn md-btn-flat md-btn-flat-primary">Update</button>
            </div>
        </div>
    </div>
<?php } ?>

<?php if($assess['can_delete_lead']){?>
    <div class="uk-modal" id="cancelform">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title">Cancel Contract </h3>
            </div>

            <div class="md-card-content large-padding">
                <form id="form_validation6" class="uk-form-stacked">
                    <input type="hidden" id="cf_lead_id" name="lead_id" value="">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="cancel_comments" class="uk-form-label">Cancel Contract Comments<span class="req">*</span></label>
                                <textarea id="cancel_comments" name="cancel_comments" class="md-input"
                                          data-parsley-required-message="Comments are required."
                                          value=""
                                          required> </textarea>
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="cancel_submit" class="md-btn md-btn-flat md-btn-flat-primary">Delete</button>
            </div>
        </div>
    </div>
<?php } ?>

<script>
    var access = '<?php echo json_encode($assess);?>';
    access = JSON.parse(access);
    var uid = 0;
    var watertype = 0;
</script>
