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
    .selectize-control.plugin-remove_button [data-value] .remove:after {
        content: ''!important;
    }
    .selectize-control.plugin-remove_button [data-value] .remove {
        padding: 0px 10px 0 0 !important;
        font-size: 10px;
        top: -2px;
    }
    .dataTables_scrollHead {
        position: sticky !important;
        top: 88px;
        background: #fff;
        z-index: 1;

    }
    .dataTables_scrollBody {

        z-index: 0;

    }
</style>
<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span>MRSS Reports</span></li>
        </ul>
    </div>
    <div id="page_content_inner">

        <h4 class="heading_a uk-margin-bottom"><?php //echo $title;

            $stages = (object) array(
                '0' => array('id'=>'0','stage' => 'Pending Lead'),
                '1' => array('id'=>'10','stage' => 'Lead Rated'),
                '2' => array('id'=>'5','stage' =>'Poor Rating Video'),
                '3' => array('id'=>'3','stage' => 'Deal Information Received'),
                '4' => array('id'=>'6','stage' => 'Upload Edited Videos'),
                '5' => array('id'=>'12','stage' =>'Distribute Edited Videos'),
                '6' => array('id'=>'8','stage' =>'Closed Won'),
                '7' => array('id'=>'9','stage' =>'Closed Lost'),
                '8' => array('id'=>'11','stage' =>'Not Interested' ),
            );
            $rating = (object) array(
                '0' => array('id'=>'0','rate' => '0'),
                '1' => array('id'=>'1','rate' => '1'),
                '2' => array('id'=>'2','rate' => '2'),
                '3' => array('id'=>'3','rate' => '3'),
                '4' => array('id'=>'4','rate' => '4'),
                '5' => array('id'=>'5','rate' => '5'),
                '6' => array('id'=>'6','rate' => '6'),
                '7' => array('id'=>'7','rate' => '7'),
                '8' => array('id'=>'8','rate' => '8'),
                '9' => array('id'=>'8','rate' => '9'),
                '10' => array('id'=>'8','rate' => '10'),
            );

            ?></h4>


        <div class="md-card uk-margin-medium-bottom">
            <div class="md-card-content">
                <div class="md-card-content">
                    <h4 class="heading_a uk-margin-bottom">Filters</h4>
                    <div>
                        <form id="form_search" class="uk-form-stacked">
                            <div class="uk-grid" data-uk-grid-margin>

                                <div class="uk-width-medium-1-3" id="cartegory_status">
                                    <div class="parsley-row">
                                        <label>Categories</label>
                                        <select id="mrss_categories" name="mrss_categories[]" class="selectize-control" data-parsley-required-message="Rating point is required." multiple>
                                            <?php
                                                foreach ($mrss_categories as $mrss) {
                                                    $selected = array();
                                                    foreach ($selected_mrss_categories as $video_cat) {
                                                        if ($mrss['title'] === $video_cat['title']) {
                                                            $selected = 'selected="selected"';
                                                        }
                                                    }
                                                ?>
                                            <option value="<?php echo $mrss['id']; ?>" <?php print_r($selected); ?>><?php echo $mrss['title']; ?></option>
                                            <?php }?>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div>

                                <div class="uk-width-medium-1-3" id="all_mrss_partner_cat" >
                                    <div class="parsley-row">
                                        <label>All Partners</label>
                                        <select id="all_mrss_cat" name="all_mrss_cat[]" class="selectize-control" data-parsley-required-message="Rating point is required." multiple>
                                            <?php
                                                foreach($mrss_partners->result() as $mrss){
                                                    $allselectednon = '';
                                                    if($mrss_feed_data){
                                                        foreach($non_exclusive_partner_data as $non_ex_feed){
                                                            if($non_ex_feed['id'] == $mrss->id){
                                                                $allselectednon = 'selected="selected"';
                                                                break;
                                                            }
                                                        }
                                                    }
                                            ?>
                                            <option value="<?php echo $mrss->id;?>" <?php echo ($allselectednon);?>><?php echo $mrss->url;?></option>
                                            <?php } ?>
                                        </select>
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
                                    <div class="parsley-row">
                                        <label>Published Status</label>
                                        <div class="parsley-row">
                                            <select id="publish_status" name="publish_status[]" data-md-selectize>
                                                    <option value="1" selected="selected">Published</option>
                                                    <option value="2">Not Published</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="uk-width-medium-1-3">
                                    <div class="parsley-row">
                                        <label>Stages</label>
                                        <select id="all_stage" name="all_stage[]" class="selectize-control" data-parsley-required-message="Rating point is required." multiple>
                                            <?php  //foreach ($stages as $stage){?>
                                                <option value="<?php //echo $stage['id'];?>" ><?php //echo $stage['stage'];?></option>
                                            <?php //} ?>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div> -->

                                <!-- <div class="uk-width-medium-1-3">
                                    <div class="parsley-row">
                                        <label>Rating</label>
                                        <select id="all_rating" name="all_rating[]" class="selectize-control" data-parsley-required-message="Rating point is required." multiple>
                                            <?php  //foreach ($rating as $rate){?>
                                                <option value="<?php //echo $rate['id'];?>" ><?php //echo $rate['rate'];?></option>
                                            <?php //} ?>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div> -->
                                <!-- <div class="uk-width-medium-1-3">
                                    <div class="parsley-row">
                                        <label>Mrss Partner Status</label>
                                        <select id="mrss_partner_status" name="mrss_partner_status" required data-parsley-required-message=""  data-md-selectize>
                                            <option value="" >Select Partner Status Value</option>
                                            <option value="2">Select Partner</option>
                                            <option value="1">All</option>
                                            <option value="3">none</option>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-3">
                                    <div class="parsley-row">
                                        <label>Mrss Category Status</label>
                                        <select id="mrss_category_status" name="mrss_category_status" required data-parsley-required-message=""  data-md-selectize>
                                            <option value="" >Select Category Status Value</option>
                                            <option value="2">Select Category</option>
                                            <option value="1">All</option>
                                            <option value="3">none</option>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div> -->
                                <!--<div class="uk-width-medium-1-3">
                                    <div class="parsley-row">
                                        <label>Mrss Publish Status</label>
                                        <select id="mrss" name="mrss" required data-parsley-required-message=""  data-md-selectize>
                                            <option value="" >Select Mrss Publish Status Value</option>
                                            <option value="1">Yes</option>
                                            <option value="2">No</option>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div>-->
                                <!-- <div class="uk-width-medium-1-3">
                                    <div class="parsley-row">
                                        <label>Exclusivety Status</label>
                                        <select id="exmrss" name="exmrss" required data-parsley-required-message=""  data-md-selectize>
                                            <option value="" >Select Exclusivety Status Value</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-3">
                                    <div class="parsley-row">
                                        <label>Confidence level</label>
                                        <select id="conmrss" name="conmrss" required data-parsley-required-message=""  data-md-selectize>
                                            <option value="" >Select Confidence level Value</option>
                                            <option value="High">High</option>
                                            <option value="Medium">Med</option>
                                            <option value="Low">Low</option>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-3">
                                    <div class="parsley-row">
                                        <label>Video Quality</label>
                                        <select id="vqmrss" name="vqmrss" required data-parsley-required-message=""  data-md-selectize>
                                            <option value="" >Select Video Quality Value</option>
                                            <option value="1">High</option>
                                            <option value="2">Low</option>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-3">
                                    <div class="parsley-row">
                                        <label>Thumbnail</label>
                                        <select id="thmrss" name="thmrss" required data-parsley-required-message=""  data-md-selectize>
                                            <option value="" >Select Thumbnail Value</option>
                                            <option value="1">Yes</option>
                                            <option value="2">No</option>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div>
                            </div> -->

                            <br/><br/>

                            <div class="uk-grid">
                                <div class="uk-width-1-1">
                                    <button type="button" id="search" class="md-btn md-btn-primary check" style="position:absolute;right:10px;">Search</button>
                                    <button type="button" id="search_reset" class="md-btn md-btn-primary">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>


                <div class="dt_colVis_buttons"></div>
                <table id="dt_tableExport" class="uk-table" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th data-name="vl.unique_key">Unique Link</th>
                        <th data-name="ed.portal_thumb">Portal Thumbnail</th>
                        <th data-name="vl.rating_point">Rating</th>
                        <th data-name="vl.video_title">Title</th>
                        <th data-name="v.description">Description</th>
                        <th data-name="v.tags">Tags</th>
                        <th data-name="vl.video_url">Video Url</th>
                        <th data-name="vl.revenue_share">Revenue Share %</th>
                        <th data-name="vl.feed_id">Gerenal Category</th>
                        <th data-name="vl.partner_id">Parnter Category</th>
                        <th data-name="mf.exclusive_status">Exclusive Status</th>
                        <th data-name="vl.confidence_level">Confidence Level</th>
                        <th data-name="vl.video_comment">Video Comment</th>
                        <th data-name="pub_date">Publication Date</th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th data-name="vl.unique_key">Unique Link</th>
                        <th data-name="ed.portal_thumb">Portal Thumbnail</th>
                        <th data-name="vl.rating_point">Rating</th>
                        <th data-name="vl.video_title">Title</th>
                        <th data-name="v.description">Description</th>
                        <th data-name="v.tags">Tags</th>
                        <th data-name="vl.video_url">Video Url</th>
                        <th data-name="vl.revenue_share">Revenue Share %</th>
                        <th data-name="vl.feed_id">Gerenal Category</th>
                        <th data-name="vl.partner_id">Parnter Category</th>
                        <th data-name="mf.exclusive_status">Exclusive Status</th>
                        <th data-name="vl.confidence_level">Confidence Level</th>
                        <th data-name="vl.video_comment">Video Comment</th>
                        <th data-name="pub_date">Publication Date</th>
                    </tr>
                    </tfoot>

                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
