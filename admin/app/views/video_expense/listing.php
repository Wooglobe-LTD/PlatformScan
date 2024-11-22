<style>
textarea{
    scroll :none;
}

</style>

<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span>Video Expense Management</span></li>
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
                    <?php if($video_id == 0){?>
                        <th data-name="v.title">Video</th>
                    <?php } ?>
                    <th data-name="ve.expense_date">Expense Date</th>
                    <th data-name="ve.expense_amount">Expense</th>
                    <th data-name="ve.expense_detail">Detail</th>

                </tr>
                </thead>

                <tfoot>
                <tr>
                    <?php if($assess['can_edit'] || $assess['can_delete']) { ?>
                        <th data-name="c.action">Actions</th>
                    <?php } ?>
                    <?php if($video_id == 0){?>
                        <th data-name="v.title">Video</th>
                    <?php } ?>
                    <th data-name="ve.expense_date">Expense Date</th>
                    <th data-name="ve.expense_amount">Expense</th>
                    <th data-name="ve.expense_detail">Detail</th>

                </tr>
                </tfoot>

                <tbody>
               
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>
<?php if($assess['can_add'] && $video_id == 0 ) { ?>
<div class="md-fab-wrapper">
        <a title="Add New Expense" class="md-fab md-fab-accent md-fab-wave waves-effect waves-button" id="add" href="javascript:void(0);">
        <i class="material-icons">&#xE145;</i>
        </a>
    </div>
    <div class="uk-modal" id="add_model">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title" >Add New Expense </h3>
            </div>
            <div class="md-card-content large-padding">
                <form id="form_validation2" class="uk-form-stacked">
                    <input type="hidden" id="video_id_expense" name="video_id" value="">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">

                                <select id="video_id" name="video_id" required data-parsley-required-message="This field is required."  data-md-selectize>
                                    <option value="">Choose Video*</option>
                                    <?php foreach($videosAcitve->result() as $video){?>
                                    <option value="<?php echo $video->id;?>"><?php echo $video->title;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                    </div>
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
                                        <input class="md-input" id="expense_date" data-uk-datepicker="{format:'YYYY-MM-DD',minDate:'<?php echo date('Y-m-d',strtotime(date('Y-m-d').' +1 Day'));?>'}" type="text" name="expense_date" data-parsley-required-message="This field is required." readonly required>
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
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="add_from" class="md-btn md-btn-flat md-btn-flat-primary">Add</button>
            </div>
        </div>
    </div>
  <?php } ?>

<?php if($assess['can_edit']) { ?>
    <div class="uk-modal" id="edit_model">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title" >Edit Expense (<span id="video-title" style="display: inline;"></span>)</h3>
            </div>
            <div class="md-card-content large-padding">
                <form id="form_validation3" class="uk-form-stacked">
                    <input type="hidden" name="id" id="id_e" value="">
                    <?php if($video_id == 0){?>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">

                                <select id="video_id_e" name="video_id" required data-parsley-required-message="This field is required."  data-md-selectize select1>
                                    <option value="">Choose Video*</option>
                                    <?php foreach($videosAcitve->result() as $video){?>
                                        <option value="<?php echo $video->id;?>"><?php echo $video->title;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                    </div>
                    <?php } ?>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <label for="expense_amount">Expense</label>
                                <input type="text" id="expense_amount_e" name="expense_amount" required data-parsley-required-message="This field is required." data-parsley-pattern-message="Please enter the valid earning like 1 or 1.00 " data-parsley-pattern="^\d*(\.\d{0,2})?$" class="md-input" >

                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <div class="uk-input-group">
                                    <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                    <div class="md-input-wrapper">
                                        <label for="expense_date">Select Expense Date</label>
                                        <input class="md-input" id="expense_date_e" data-uk-datepicker="{format:'YYYY-MM-DD',minDate:'<?php echo date('Y-m-d',strtotime(date('Y-m-d').' +1 Day'));?>'}" type="text" name="expense_date" data-parsley-required-message="This field is required." readonly required>
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
                                <textarea id="expense_detail_e" name="expense_detail"  class="md-input" required data-parsley-required-message="This field is required."></textarea>
                                <div class="error"></div>
                            </div>
                        </div>

                    </div>



                </form>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="edit_from" class="md-btn md-btn-flat md-btn-flat-primary">Save</button>
            </div>
        </div>
    </div>
<?php } ?>

<script>
    var video_id = '<?php echo $video_id;?>';
</script>