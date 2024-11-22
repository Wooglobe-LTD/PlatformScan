<style>
textarea{
    scroll :none;
}

</style>

<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span>Earning History</span></li>
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
                    <th data-name="e.earning_date">Earning Date</th>
                    <th data-name="vl.unique_key">WGID</th>
                    <th data-name="e.earning_amount">Earning</th>
                    <th data-name="e.paid">Earning Mode</th>
                    <th data-name="et.earning_type">Earning Source</th>
                    <th data-name="e.status">Status</th>
                    <th data-name="e.transaction_id">Partner Earning Id</th>
                    <th data-name="e.transaction_detail">Partner Earning Detail</th>
                    <th data-name="e.expense">Expense</th>
                    <th data-name="e.expense_detail">Expense Detail</th>

                </tr>
                </thead>

                <tfoot>
                <tr>
                    <?php if($assess['can_edit'] || $assess['can_delete']) { ?>
                        <th data-name="c.action">Actions</th>
                    <?php } ?>
                    <th data-name="e.earning_date">Earning Date</th>
                    <th data-name="vl.unique_key">WGID</th>
                    <th data-name="e.earning_amount">Earning</th>
                    <th data-name="e.paid">Earning Mode</th>
                    <th data-name="et.earning_type">Earning Source</th>
                    <th data-name="e.status">Status</th>
                    <th data-name="e.transaction_id">Partner Earning Id</th>
                    <th data-name="e.transaction_detail">Partner Earning Detail</th>
                    <th data-name="e.expense">Expense</th>
                    <th data-name="e.expense_detail">Expense Detail</th>

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
<!--<div class="md-fab-wrapper">
        <a title="Add New Category" class="md-fab md-fab-accent md-fab-wave waves-effect waves-button" id="add" href="javascript:void(0);">
        <i class="material-icons">&#xE145;</i>
        </a>
    </div>-->
  <?php } ?>

<?php if($assess['can_edit']) { ?>
    <div class="uk-modal" id="edit_model">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title" >Edit Earning (<span id="video-title" style="display: inline;"></span>)</h3>
            </div>
            <div class="md-card-content large-padding">
                <form id="form_validation3" class="uk-form-stacked">
                    <input type="hidden" name="id" id="id_e" value="">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <label for="earning_amount">Earning</label>
                                <input type="text" id="earning_amount_e" name="earning_amount" required data-parsley-required-message="This field is required." data-parsley-pattern-message="Please enter the valid earning like 1 or 1.00 " data-parsley-pattern="^\d*(\.\d{0,2})?$" class="md-input" >

                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <div class="uk-input-group">
                                    <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                    <div class="md-input-wrapper">
                                        <label for="closing">Select Earning Date</label>
                                        <input class="md-input" id="earning_date_e" data-uk-datepicker="{format:'YYYY-MM-DD',minDate:'<?php echo date('Y-m-d',strtotime(date('Y-m-d').' +1 Day'));?>'}" type="text" name="earning_date" data-parsley-required-message="This field is required." readonly required>
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
                                <select id="earning_type_id_e" name="earning_type_id" required data-parsley-required-message="This field is required."  data-md-selectize select1>
                                    <option value="">Earning Type*</option>
                                    <?php foreach ($earning_type->result() as $type){?>
                                        <option value="<?php echo $type->id;?>"><?php echo $type->earning_type;?></option>
                                    <?php } ?>
                                </select>
                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2 ms" id="ss" style="display: none;">
                            <div class="parsley-row">
                                <select id="social_source_id_e" name="social_source_id" data-parsley-required-message="This field is required."  data-md-selectize select2>
                                    <option value="">Social Sources*</option>
                                    <?php foreach ($sources->result() as $source){?>
                                        <option value="<?php echo $source->id;?>"><?php echo $source->sources;?></option>
                                    <?php } ?>
                                </select>
                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2 ms" id="p" style="display: none;">
                            <div class="parsley-row">
                                <select id="partner_id_e" name="partner_id" data-parsley-required-message="This field is required."  data-md-selectize select3>
                                    <option value="">Partner*</option>
                                    <?php foreach ($partners->result() as $partner){?>
                                        <option value="<?php echo $partner->id;?>"><?php echo $partner->full_name.' ('.$partner->email.')';?></option>
                                    <?php } ?>
                                </select>
                                <div class="error"></div>
                            </div>
                        </div>


                    </div>



                </form>
            </div>
            <div class="uk-modal-footer uk-text-right" style="margin-top: 20px;">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="edit_from" class="md-btn md-btn-flat md-btn-flat-primary">Save</button>
            </div>
        </div>
    </div>
<?php } ?>

<script>
    var video_id = '<?php echo $video_id;?>';
</script>
