<style>
    textarea{
        scroll :none;
    }
    .selectize-control.plugin-remove_button [data-value] .remove:after {
        content: ''!important;
    }
    .selectize-control.plugin-remove_button [data-value] .remove {
        padding: 0px 10px 0 0 !important;
        font-size: 10px;
        top: -2px;
    }
    .selectize-control.plugin-remove_button [data-value] {
        padding-right: 24px!important;
    }
    .single:nth-child(n+4){
        z-index: 99999 !important;
    }
</style>

<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span>Mobile App Videos Complaints Management</span></li>
        </ul>
    </div>
    <div id="page_content_inner">



        <h4 class="heading_a uk-margin-bottom"><?php echo $title;?></h4>
        <div class="md-card uk-margin-medium-bottom">
            <div class="md-card-content">
                <!--<div class="uk-width-medium-1-4" style="float: right;">
                    <div class="parsley-row">
                        <label for="video_type" class="uk-form-label"><b>Videos</b></label>
                        <select id="video_type" name="video_type" data-md-selectize>
                            <option value="">Choose..</option>
                            <?php /*$vtype = 1;
                            if(isset($_GET['video_type'])){
                                $vtype = $_GET['video_type'];
                            }*/?>
                            <option <?php /*if($vtype == 2 ){ echo 'selected';}*/?> value="2">All</option>
                            <option <?php /*if($vtype == 1 ){ echo 'selected';}*/?> value="1">WooGlobe Videos</option>
                            <option <?php /*if($vtype == 0 ){ echo 'selected';}*/?> value="0">APP Videos</option>

                        </select>
                        <div class="error"></div>
                    </div>
                </div>-->
                <div class="dt_colVis_buttons"></div>
                <table id="dt_tableExport_video_complaints" class="uk-table" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th data-name="v.action">Actions</th>
                        <th data-name="vc.ticket_number">Ticket Number</th>
                        <th data-name="vc.message">Message</th>
                        <th data-name="v.title">Video Title</th>
                        <th data-name="v.url">Video URL</th>
                        <th data-name="vc.ticket_number">Complaint URL</th>


                        <!-- <th>Original Files</th>-->
                        <th data-name="u.email">User Email</th>
                      <!--  <th data-name="t.title">Type</th>-->
                        <th data-name="vc.created_at">Created At</th>
                        <th data-name="vc.status">Status</th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th data-name="v.action">Actions</th>
                        <th data-name="vc.ticket_number">Ticket Number</th>
                        <th data-name="vc.message">Message</th>
                        <th data-name="v.title">Video Title</th>
                        <th data-name="v.url">Video URL</th>
                        <th data-name="vc.ticket_number">Complaint URL</th>


                        <!-- <th>Original Files</th>-->
                        <th data-name="u.email">User Email</th>
                        <!--  <th data-name="t.title">Type</th>-->
                        <th data-name="vc.created_at">Created At</th>
                        <th data-name="vc.status">Status</th>

                    </tr>
                    </tfoot>

                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
<?php /*if($assess['can_add']) { */?><!--
    <div class="md-fab-wrapper">
        <a title="Add New Video" class="md-fab md-fab-accent md-fab-wave waves-effect waves-button" href="<?php /*$url;*/?>video_add">
            <i class="material-icons">&#xE145;</i>
        </a>
    </div>
--><?php /*} */?>
<?php /*if($assess['can_add_earning']) { */?><!--
    <div class="uk-modal" id="earning_model">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title" >Add New Earning (<span id="video-title" style="display: inline;"></span>)</h3>
            </div>
            <div class="md-card-content large-padding">
                <form id="form_validation4" class="uk-form-stacked">
                    <input type="hidden" id="video_id" name="video_id" value="">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <label for="earning_amount">Earning</label>
                                <input type="text" id="earning_amount" name="earning_amount" required data-parsley-required-message="This field is required." data-parsley-pattern-message="Please enter the valid earning like 1 or 1.00 " data-parsley-pattern="^\d*(\.\d{0,2})?$" class="md-input" >

                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <div class="uk-input-group">
                                    <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                    <div class="md-input-wrapper">
                                        <label for="closing">Select Earning Date</label>
                                        <input class="md-input" id="earning_date" data-uk-datepicker="{format:'YYYY-MM-DD',minDate:'<?php /*echo date('Y-m-d',strtotime(date('Y-m-d').' +1 Day'));*/?>'}" type="text" name="earning_date" data-parsley-required-message="This field is required." readonly required>
                                        <span class="md-input-bar "></span>
                                    </div>
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <select id="earning_type_id" name="earning_type_id" required data-parsley-required-message="This field is required."  data-md-selectize>
                                    <option value="">Earning Type*</option>
                                    <?php /*foreach ($earning_type->result() as $type){*/?>
                                        <option value="<?php /*echo $type->id;*/?>"><?php /*echo $type->earning_type;*/?></option>
                                    <?php /*} */?>
                                </select>
                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2 ms" id="ss" style="display: none;">
                            <div class="parsley-row">
                                <select id="social_source_id" name="social_source_id" data-parsley-required-message="This field is required."  data-md-selectize>
                                    <option value="">Social Sources*</option>
                                    <?php /*foreach ($sources->result() as $source){*/?>
                                        <option value="<?php /*echo $source->id;*/?>"><?php /*echo $source->sources;*/?></option>
                                    <?php /*} */?>
                                </select>
                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2 ms" id="p" style="display: none;">
                            <div class="parsley-row">
                                <select id="partner_id" name="partner_id" data-parsley-required-message="This field is required."  data-md-selectize>
                                    <option value="">Partner*</option>
                                    <?php /*foreach ($partners->result() as $partner){*/?>
                                        <option value="<?php /*echo $partner->id;*/?>"><?php /*echo $partner->full_name.' ('.$partner->email.')';*/?></option>
                                    <?php /*} */?>
                                </select>
                                <div class="error"></div>
                            </div>
                        </div>


                    </div>



                </form>
            </div>
            <div class="uk-modal-footer uk-text-right" style="margin-top:20px; ">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="add_earning_from" class="md-btn md-btn-flat md-btn-flat-primary">Add</button>
            </div>
        </div>
    </div>
--><?php /*} */?>

    <div class="uk-modal" id="mrss_model">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title" >MRSS Detail (<span id="video1-title" style="display: inline;"></span>)</h3>
            </div>
            <div class="md-card-content large-padding">
                <form id="form_validation6" class="uk-form-stacked">
                    <input type="hidden" id="video1_id" name="video_id" value="">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">
                            <div class="uk-width-medium-1-1" style="padding: 15px 0px;">
                                <input type="checkbox" name="is_mrss" id="is_mrss" value="" data-md-icheck />
                                <label for="mrss" class="inline-label"><b>Publish to MRSS</b>
                                </label>
                            </div>
                        </div>



                    </div>




                </form>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="mrss_feed_form" class="md-btn md-btn-flat md-btn-flat-primary">Update</button>
            </div>
        </div>
    </div>

<!--<style>
	.selectize-dropdown {
		z-index: 1012 !important;
	}
	.selectize-dropdown {
		margin-top: 0px;
	}
</style>-->

<!--MRSS feed section starts here-->

<!--<div class="uk-modal" id="mrss_feed">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title" >MRSS (<span id="video-title" style="display: inline;"></span>)</h3>
            </div>
            <div class="md-card-content large-padding">
                <form id="form_validation4" class="uk-form-stacked">
                    <input type="hidden" id="video_id" name="video_id" value="">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <label for="earning_amount">Earning</label>
                                <input type="text" id="earning_amount" name="earning_amount" required data-parsley-required-message="This field is required." data-parsley-pattern-message="Please enter the valid earning like 1 or 1.00 " data-parsley-pattern="^\d*(\.\d{0,2})?$" class="md-input" >

                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <div class="uk-input-group">
                                    <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                    <div class="md-input-wrapper">
                                        <label for="closing">Select Earning Date</label>
                                        <input class="md-input" id="earning_date" data-uk-datepicker="{format:'YYYY-MM-DD',minDate:'<?php /*echo date('Y-m-d',strtotime(date('Y-m-d').' +1 Day'));*/?>'}" type="text" name="earning_date" data-parsley-required-message="This field is required." readonly required>
                                        <span class="md-input-bar "></span>
                                    </div>
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <select id="earning_type_id" name="earning_type_id" required data-parsley-required-message="This field is required."  data-md-selectize>
                                    <option value="">Earning Type*</option>

                                </select>
                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2 ms" id="ss" style="display: none;">
                            <div class="parsley-row">
                                <select id="social_source_id" name="social_source_id" data-parsley-required-message="This field is required."  data-md-selectize>
                                    <option value="">Social Sources*</option>

                                </select>
                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2 ms" id="p" style="display: none;">
                            <div class="parsley-row">
                                <select id="partner_id" name="partner_id" data-parsley-required-message="This field is required."  data-md-selectize>
                                    <option value="">Partner*</option>

                                </select>
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>



                </form>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="add_earning_from" class="md-btn md-btn-flat md-btn-flat-primary">Add</button>
            </div>
        </div>
    </div>-->

<!--MRSS feed section ends here-->




<?php /*if($assess['can_add_expense']) { */?><!--
    <div class="uk-modal" id="expense_model">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title" >Add New Expense (<span id="video-title-expense" style="display: inline;"></span>)</h3>
            </div>
            <div class="md-card-content large-padding">
                <form id="form_validation5" class="uk-form-stacked">
                    <input type="hidden" id="video_id_expense" name="video_id" value="">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <label for="expense_amount">Expense</label>
                                <input type="text" id="expense_amount" name="expense_amount" required data-parsley-required-message="This field is required." data-parsley-pattern-message="Please enter the valid earning like 1 or 1.00 " data-parsley-pattern="^\d*(\.\d{0,2})?$" class="md-input" >

                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <div class="uk-input-group">
                                    <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                    <div class="md-input-wrapper">
                                        <label for="closing">Select Expense Date</label>
                                        <input class="md-input" id="expense_date" data-uk-datepicker="{format:'YYYY-MM-DD',minDate:'<?php /*echo date('Y-m-d',strtotime(date('Y-m-d').' +1 Day'));*/?>'}" type="text" name="expense_date" data-parsley-required-message="This field is required." readonly required>
                                        <span class="md-input-bar "></span>
                                    </div>
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>



                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="expense_detail" class="uk-form-label">Expense Detail</label>
                                <textarea id="expense_detail" name="expense_detail"  class="md-input" required data-parsley-required-message="This field is required."></textarea>
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>



                </form>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="add_expense_from" class="md-btn md-btn-flat md-btn-flat-primary">Add</button>
            </div>
        </div>
    </div>
--><?php /*} */?>
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
 <!-- <div class="uk-modal" id="promote_model">
        <div class="uk-modal-dialog">
            
            <div class="md-card-content large-padding">
                <form id="promote_form" class="uk-form-stacked">
                    <input type="hidden" id="video_id" name="id" value="">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">
                            <div class="uk-width-medium-1-1" style="padding: 15px 0px;">
                            <label for="position" class="inline-label"><b>position</b>
                                <input type="number" name="position" id="position" require/>
                                
                                </label>
                            </div>
                        </div>
                      


                    </div>




                </form>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="postion_form" class="md-btn md-btn-flat md-btn-flat-primary">Promote</button>
            </div>
        </div>
    </div> -->

<div class="uk-modal" id="edit_video_complaint_model">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h3 class="uk-modal-title">Edit Video Complaint </h3>
        </div>
        <div class="md-card-content large-padding">
            <form id="video_complaint_update_form" class="uk-form-stacked">
                <input type="hidden" name="id" id="id_e" value="">
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="ticket_number">Ticket Number<span class="req">*</span></label>
                            <input type="text" pattern="[a-zA-Z0-9\s]+"
                                   data-parsley-pattern-message="Only alphabet and number are allowed."
                                   data-parsley-required-message="This field is required." name="ticket_number" id="ticket_number_e"
                                   required class="md-input" readonly="readonly"/>
                            <div class="error"></div>
                        </div>
                    </div>
                </div>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="message">Message<span class="req">*</span></label>
                            <input type="text" 
                                   data-parsley-required-message="This field is required." name="message" id="message_e"
                                   required class="md-input" readonly="readonly" />
                            <div class="error"></div>
                        </div>
                    </div>
                </div>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="title">Video title<span class="req">*</span></label>
                            <input type="text" 
                                   data-parsley-required-message="This field is required." name="title" id="title_e"
                                   required class="md-input" readonly="readonly" />
                            <div class="error"></div>
                        </div>
                    </div>
                </div>
                 <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="url">Video URL<span class="req">*</span></label>
                            <input type="text" 
                                   data-parsley-required-message="This field is required." name="url" id="url_e"
                                   required class="md-input" readonly="readonly" />
                            <div class="error"></div>
                        </div>
                    </div>
                </div>
                 <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="email">User Email<span class="req">*</span></label>
                            <input type="text" 
                                   data-parsley-required-message="This field is required." name="email" id="email_e"
                                   required class="md-input" readonly="readonly" />
                            <div class="error"></div>
                        </div>
                    </div>
                </div>




                    <div class="uk-width-medium">
                        <div class="parsley-row">

                            <select id="status_e" name="status" required data-parsley-required-message="This field is required." class="md-input" select >
                                <option value="">Status*</option>
                                <option value="0">Inactive</option>
                                <option value="1">Active</option>
                                <option value="2">Video Down</option>

                            </select>
                        </div>
                    </div>


            </form>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="video_complaint_edit_form" class="md-btn md-btn-flat md-btn-flat-primary">Save</button>
        </div>
    </div>
</div>
