<style>
textarea{
    scroll :none;
}
.single {
    z-index: 9999;
}
</style>

<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span>Staff Members Roles Management</span></li>
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
                    <?php /*if(role_permitted_html(false)) {*/?>
                        <th data-name="action">Actions</th>
                   <!----><!-- --><?php /*} */?>
                    <th data-name="title">Group Title</th>
                    <th data-name="staff_member">Staff Member</th>
                    <th data-name="status">Status</th>

                </tr>
                </thead>

                <tfoot>
                <tr>
                    <?php /*if(role_permitted_html(false)) {*/?>
                        <th data-name="action">Actions</th>
                    <?php /*} */?>
                    <th data-name="title">Group Title</th>
                    <th data-name="staff_member">Staff Member</th>
                    <th data-name="status">Status</th>

                </tr>
                </tfoot>

                <tbody>
               
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>
<?php /*if(role_permitted_html(false)){*/?>
<div class="md-fab-wrapper">
        <a title="Add New Role" class="md-fab md-fab-accent md-fab-wave waves-effect waves-button" id="add" href="javascript:void(0);">
        <i class="material-icons">&#xE145;</i>
        </a>
    </div>
    <div class="uk-modal" id="add_model">
		<div class="uk-modal-dialog">
			<div class="uk-modal-header">
				<h3 class="uk-modal-title">Add New Staff Member In group</h3>
			</div>
			<div class="md-card-content large-padding">
				<form id="form_validation2" class="uk-form-stacked">
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1">
							<div class="parsley-row">
                                <label for="title">Group Title<span class="req">*</span></label>
                                <select id="title" name="title" required data-parsley-required-message="This field is required."  data-md-selectize>
                                    <option value="">Group Title*</option>
                                    <?php foreach($groups->result() as $group){ ?>
                                        <option <?php if($group->id ){ echo 'selected';} ?> value="<?php echo $group->id;?>"> <?php echo $group->title;?></option>
                                    <?php } ?>
                                </select>
							</div>
                        </div>
					</div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="title">Add Staff Member Group<span class="req">*</span></label>
                                <select id="staff_member" name="staff_member[]" class="selectize-control"  required data-parsley-required-message="This field is required."  data-md-selectize  multiple="multiple" style="width: 100%; ">
                                    <option value="">Add Staff Member Group*</option>
                                    <?php foreach($staffs->result() as $staff){ ?>
                                        <option <?php if($staff->id == $dealData->staff_id){ echo 'selected';} ?> value="<?php echo $staff->id;?>"> <?php echo $staff->name;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                   
                    <div class="uk-grid" data-uk-grid-margin>

                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="title">Status<span class="req">*</span></label>
                                <select id="status" name="status" required data-parsley-required-message="This field is required."  data-md-selectize>
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

    <div class="uk-modal" id="edit_model">
		<div class="uk-modal-dialog">
			<div class="uk-modal-header">
				<h3 class="uk-modal-title">Edit Staff Member </h3>
			</div>
			<div class="md-card-content large-padding">
				<form id="form_validation3" class="uk-form-stacked">
                    <input type="hidden" name="id" id="id_e" value="">
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="title">Title<span class="req">*</span></label>
                                <input type="text"
                                       data-parsley-required-message="This field is required."
                                       name="title" id="title_e"
                                       required
                                       class="md-input"
                                       pattern="[a-zA-Z0-9\s]+" data-parsley-pattern-message="Only alphabet and number are allowed."
                                />
                                <div class="error"></div>
                            </div>
                        </div>

						
                       
					</div>

                   
                    <div class="uk-grid" data-uk-grid-margin>

                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                
                                <select id="status_e" name="status" required data-parsley-required-message="This field is required." data-md-selectize select>
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
				<button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="edit_from" class="md-btn md-btn-flat md-btn-flat-primary">Save</button>
			</div>
		</div>
	</div>
<?php /*} */?>