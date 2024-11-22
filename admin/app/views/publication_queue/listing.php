<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span>Publication Queue</span></li>
        </ul>
    </div>
    <div id="page_content_inner">
        <h4 class="heading_a uk-margin-bottom"><?php echo $title;?></h4>
        <div class="md-card uk-margin-medium-bottom">
            <div class="md-card-content">

                <div class="table_option_heading">
                    <h4 class="uk-width-medium-2-3">Filters</h4>
                    <h4 class="uk-width-medium-1-3">Sort By</h4>
                </div>
                <div class="md-card-content table_options">
                    <div class="sheet_filters uk-width-medium-2-3">
                        <form id="form_search" class="uk-form-stacked">
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <select id="date-type-fltr" name="date_type[]" placeholder="Select Date Type" data-md-selectize>
                                            <option value="vl.verification_date">Verification Date</option>
                                            <option value="vl.edited_datetime">Edited Date</option>
                                            <option value="vl.cn_datetime">Content/Newswire Date</option>
                                            <!-- <option value="vps.publish_datetime">YouTube Publish Date</option> -->
                                        </select>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="uk-width-medium-1-2" style="display:inline-block; width:49%;">
                                        <div class="parsley-row">
                                            <div class="md-input-wrapper">
                                                <input class="md-input date_range_input" id="date-from" placeholder="Date From" data-uk-datepicker="{format:'YYYY-MM-DD',maxDate:''}" type="text" name="date_from" data-parsley-required-message="" value="" readonly>
                                                <span class="md-input-bar "></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-width-medium-1-2" style="display:inline-block; width:49%;">
                                        <div class="parsley-row">
                                            <div class="md-input-wrapper">
                                                <input class="md-input date_range_input" id="date-to" placeholder="Date To" data-uk-datepicker="{format:'YYYY-MM-DD',maxDate:''}" type="text" name="date_to" data-parsley-required-message="" value="" readonly>
                                                <span class="md-input-bar "></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br/><br/>
                            <div class="uk-width-1 filter_buttons">
                                <button type="button" id="sheet-filter-reset" class="md-btn md-btn-info">Reset</button>
                                <button type="button" id="sheet-filter-apply" class="md-btn md-btn-primary">Search</button>
                            </div>
                        </form>
                    </div>
                    <div class="sheet_filters uk-width-medium-1-3">
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <select id="sort-by" name="sort_by[]" placeholder="Select Date Type" data-md-selectize>
                                        <option value="vl.verification_date">Verification Date</option>
                                        <option value="vl.edited_datetime">Edited Date</option>
                                        <option value="vl.cn_datetime">Content/Newswire Date</option>
                                        <option value="editor">Editor Preference</option>
                                        <!-- <option value="vps.publish_datetime">YouTube Publish Date</option> -->
                                    </select>
                                </div>
                            </div>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <select id="sort-dir" name="sort_dir[]" placeholder="Select Sort Direction" data-md-selectize>
                                        <option value="DESC">Latest First</option>
                                        <option value="ASC">Oldest First</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dt_colVis_buttons"></div>
                <table id="publication_queue_table" class="uk-table publication_queue_table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th data-name="vl.unique_key">WGID</th>
                            <th data-name="vl.priority">Priority</th>
                            <th data-name="vl.verification_date">Verification Date</th>
                            <th data-name="vl.is_cn_updated">Content/Newswire</th>
                            <th data-name="vl.uploaded_edited_videos">Video Edited</th>
                            <th data-name="vps.publish_datetime">Youtube Published</th>
                            <!-- <th data-name="vps.publish_now">Youtube Scheduled</th> -->
                            <th data-name="rv.dropbox_status">Dropbox</th>
                            <th data-name="mp.publication_date">MRSS</th>
                            <th data-name="vl.rating_point">Rating</th>
                            <th data-name="content_writer">Assigned Content Writer</th>
                            <th data-name="mf.title">Categories</th>
                            <th data-name="vl.deleted">Rights Management</th>
                            <th data-name="dc.researcher_comment">Researcher Comments</th>
                            <th data-name="dc.manager_comment">Manager Comments</th>
                        </tr>
                    </thead>

                    <tfoot>
                        <tr>
                            <th data-name="vl.unique_key">WGID</th>
                            <th data-name="vl.priority">Priority</th>
                            <th data-name="vl.verification_date">Verification Date</th>
                            <th data-name="vl.is_cn_updated">Content/Newswire</th>
                            <th data-name="vl.uploaded_edited_videos">Video Edited</th>
                            <th data-name="vps.publish_datetime">Youtube Published</th>
                            <!-- <th data-name="vps.publish_now">Youtube Scheduled</th> -->
                            <th data-name="rv.dropbox_status">Dropbox</th>
                            <th data-name="mp.publication_date">MRSS</th>
                            <th data-name="vl.rating_point">Rating</th>
                            <th data-name="content_writer">Assigned Content Writer</th>
                            <th data-name="mf.title">Categories</th>
                            <th data-name="vl.deleted">Rights Management</th>
                            <th data-name="dc.researcher_comment">Researcher Comments</th>
                            <th data-name="dc.manager_comment">Manager Comments</th>
                        </tr>
                    </tfoot>

                    <tbody>
                
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="uk-modal dropbox_modal" id="dropbox-modal">
        <div class="uk-modal-dialog" style="width: 50%">
            <div class="uk-modal-header uk-width-1-1">
                <h3 class="uk-modal-title">Dropbox Instances</h3>
                <div class="error" id="dropbox-error"></div>
            </div>

            <div class="uk-grid" id="dropbox-search-result" data-uk-grid-margin>
                <div class="uk-width-medium-1-1">
                    <div class="parsley-row">
                        <h5 id="dropbox-search-status"></h5>
                        <ul id="dropbox-path-list"></ul>
                    </div>
                </div>
            </div>

            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Ok</button>
            </div>
        </div>
    </div>
</div>