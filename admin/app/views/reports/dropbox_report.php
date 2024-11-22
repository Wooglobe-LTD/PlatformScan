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
            <li><span>Dropbox Report</span></li>
        </ul>
    </div>
<div id="page_content_inner">

  	<h4 class="heading_a uk-margin-bottom"><?php echo $title;?></h4>


    <div class="md-card uk-margin-medium-bottom">
        <div class="md-card-content">
					<div class="md-card-content">
						<h4 class="heading_a uk-margin-bottom">Filters</h4>
                        <?php if($role == 1){ ?>
                        <a style="display: none;" href="<?php echo base_url() ?>array_to_csv_download" class="md-btn buttons-csv buttons-html5 dt_csv">Overall csv download</a>
                        <?php }?>
						<div>
							<form id="form_search" class="uk-form-stacked">
                                <input type="hidden" id="search_field" name="search" value="2">
                                <input type="hidden" id="lead_type" name="lead_type" value="-1">
                               <?php if ($role != 1){ ?>
                                       <input type="hidden" name="type" id="type" value="<?php echo $this->sess->userdata('adminId'); ?>">
                                    <div class="uk-grid" data-uk-grid-margin>
                                        <!-- Hidden fields start  -->
                                        <input type="hidden" value="<?php echo $role;?>" name="role" id="role">
                                        

                                        <div class="uk-width-medium-1-3">
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

                                        <div class="uk-width-medium-1-3">
                                            <div class="parsley-row">
                                                <div class="parsley-row">
                                                    <select id="if_verified" name="if_verified" required data-parsley-required-message=""  data-md-selectize>
                                                        <option value="" >All</option>
                                                        <option value="1">Verified</option>
                                                        <option value="2">Non Verified</option>
                                                    </select>
                                                    <div class="error"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-width-medium-1-3 date_from_to">

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
                                    </div>

                                    <div class="uk-grid" data-uk-grid-margin>
                                        <!-- Hidden fields start  -->
                                        <input type="hidden" value="<?php echo $role;?>" name="role" id="role">
                                        

                                        <div class="uk-width-medium-1-3">
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
                                        <div class="uk-width-medium-1-3">
                                            <div class="parsley-row">
                                                <div class="parsley-row">
                                                    <select id="if_verified" name="if_verified" required data-parsley-required-message=""  data-md-selectize>
                                                        <option value="" >All</option>
                                                        <option value="1">Verified</option>
                                                        <option value="2">Non Verified</option>
                                                    </select>
                                                    <div class="error"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="uk-width-medium-1-3 date_from_to">

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
                                    <!-- <div class="uk-width-medium-1-3">
                                        <div class="parsley-row">

                                            <div class="parsley-row">
                                                <select id="type" name="type" required data-parsley-required-message=""  data-md-selectize>
                                                    <option value="" selected>All</option>
                                                    <option selected value="-1" >WooGlobe</option>
                                                    <?php foreach($staff_name as $staff){?>
                                                    <option value="<?php echo $staff->id; ?>"><?php echo $staff->name; ?></option>
                                                    <?php }?>
                                                </select>
                                                <div class="error"></div>
                                            </div>

                                        </div>
                                    </div> -->
                                    

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
                                    <div class="uk-width-medium-1-4">
                                        <div class="parsley-row">
                                            <div class="parsley-row">
                                                <select id="if_verified" name="if_verified" required data-parsley-required-message=""  data-md-selectize>
                                                    <option value="" >All</option>
                                                    <option value="1">Verified</option>
                                                    <option value="2">Non Verified</option>
                                                </select>
                                                <div class="error"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-width-medium-1-2 date_from_to">

                                        <div class="uk-width-small-1-2" style="display:inline-block;width: 49%;">
                                            <div class="parsley-row">
                                                <div class="md-input-wrapper">
                                                    <label for="date_from">Date From</label>
                                                    <input class="md-input" id="date_from" data-uk-datepicker="{format:'YYYY-MM-DD',maxDate:''}" type="text" name="date_from" data-parsley-required-message="" value="" readonly>
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-width-small-1-2" style="display:inline-block;width: 49%;">
                                            <div class="parsley-row">
                                                <div class="md-input-wrapper">
                                                    <label for="date_to">Date To</label>
                                                    <input class="md-input" id="date_to" data-uk-datepicker="{format:'YYYY-MM-DD',maxDate:''}" type="text" name="date_to" data-parsley-required-message="" value="" readonly>
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    <br><br>
                                    <div class="uk-width-small-1-1 duration_from_to">

                                        <div class="uk-width-small-1-2" style="display:inline-block;width: 49%;">
                                                <div class="parsley-row">
                                                    <div class="md-input-wrapper">
                                                        <label for="duration_from">Duration From</label>
                                                        <input class="md-input" id="duration_from" type="text" name="duration_from" data-parsley-required-message="" value="" >
                                                        <span class="md-input-bar "></span>
                                                    </div>
                                                </div>
                                            </div>
                                        <div class="uk-width-small-1-2" style="display:inline-block;width: 49%;">
                                            <div class="parsley-row">
                                                <div class="md-input-wrapper">
                                                    <label for="duration_to">Duration To</label>
                                                    <input class="md-input" id="duration_to" type="text" name="duration_to" data-parsley-required-message="" value="" >
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    </div>


                                </div>
                                <div class="uk-grid">
                                    <div class="uk-width-1-6">
                                        <button  type="button" id="search" class="md-btn md-btn-primary check">Search</button>
                                    </div>
                                </div>
                                <?php } ?>
								<br/><br/>


							</form>
						</div>

					</div>


            <div class="dt_colVis_buttons"></div>
           
                <?php if($role != 1){ ?>
                <?php } ?>
                <tbody>

                </tbody>
            </table> 
            <div class="uk-grid">
                <div class="uk-width-1-6">
                    <button type="button" class="md-btn md-btn-primary" id="bulk-upload-btn">Bulk Upload Selected</button></br></br></br>
                </div>
                <div class="uk-width-1-6">
                    <button type="button" class="md-btn md-btn-primary" id="update-duration">Update Durations</button></br></br></br>
                </div>
            </div>

            <table id="dt_tableExport_details" class="uk-table" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th></th>
                        <th data-name="vl.unique_key">Deal Id</th>
                        <th data-name="vl.video_title">Video Title</th>
                        <th data-name="rv.dropbox_status">Upload Status</th>
                        <th data-name="rv.video_duration">Duration</th>
                        <!-- <th data-name="vl.created_at">Created Time</th> -->
                        <th data-name="vl.created_at">Created Time</th>

                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th></th>

                        <th data-name="vl.unique_key">Deal Id</th>
                        <th data-name="vl.video_title">Video Title</th>
                        <th data-name="rv.dropbox_status">Upload Status</th>
                        <th data-name="rv.video_duration">Duration</th>
                        <th data-name="vl.created_at">Created Time</th>
                        <!-- <th data-name="vl.created_at">Created Time</th> -->
                    </tr>
                    </tfoot>
                <?php /*} */?>
            </table>
        
        </div>
    </div>

</div>
</div>
<?php  ?>

<?php  ?>