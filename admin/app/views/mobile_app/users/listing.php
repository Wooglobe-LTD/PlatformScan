<style>
textarea{
    scroll :none;
}

</style>

<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span>Mobile App Users Management</li>
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
                        <th data-name="action">Actions</th>
                    <?php } ?>
                    <th data-name="full_name">Name</th>
                    <th data-name="gender">Gender</th>
                    <th data-name="email">Email</th>
                    <th data-name="mobile">Mobile</th>
                    <th data-name="status">Status</th>

                </tr>
                </thead>

                <tfoot>
                <tr>
                    <?php if($assess['can_edit'] || $assess['can_delete']) { ?>
                        <th data-name="action">Actions</th>
                    <?php } ?>
                    <th data-name="full_name">Name</th>
                    <th data-name="gender">Gender</th>
                    <th data-name="email">Email</th>
                    <th data-name="mobile">Mobile</th>
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
<?php /*if($assess['can_add']) { */?><!--
<div class="md-fab-wrapper">
        <a title="Add New User" class="md-fab md-fab-accent md-fab-wave waves-effect waves-button" id="add" href="javascript:void(0);">
        <i class="material-icons">&#xE145;</i>
        </a>
    </div>
--><?php /*} */?>
    <div class="uk-modal" id="add_model">
		<div class="uk-modal-dialog">
			<div class="uk-modal-header">
				<h3 class="uk-modal-title">Add New User </h3>
			</div>
			<div class="md-card-content large-padding">
				<form id="form_validation2" class="uk-form-stacked">
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-2">
							<div class="parsley-row">
								<label for="full_name">Full Name<span class="req">*</span></label>
                                <input type="text" data-parsley-required-message="This field is required." name="full_name" id="full_name" required class="md-input" />
                                <div class="error"></div>
							</div>
                        </div>
                        <div class="uk-width-medium-1-2">
                                <div class="parsley-row">
                                    <label for="email">Email Address<span class="req">*</span></label>
                                    <input type="email" data-parsley-required-message="This field is required." name="email" id="email" required class="md-input" data-parsley-type-message="Please enter the valid email address."/>
                                    <div class="error"></div>
                                </div>
                            </div>
						
                       
					</div>
					<div class="uk-grid" data-uk-grid-margin>
                       
						<div class="uk-width-medium-1-2">
							<div class="parsley-row">
								<label for="mobile">Mobile<span class="req">*</span></label>
                                <input type="text" data-parsley-required-message="This field is required." name="mobile" id="mobile" required class="md-input" />
                                <div class="error"></div>
							</div>
						</div>
                        <div class="uk-width-medium-1-2">
							<div class="parsley-row">
								<label for="address">Address<span class="req">*</span></label>
                                <input type="text" data-parsley-required-message="This field is required." name="address" id="address" required  class="md-input" />
                                <div class="error"></div>
							</div>
						</div>
                    </div>
                   
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                
                                <select id="gender" name="gender" data-parsley-required-message="This field is required." required class="md-input">
                                    <option value="">Gender*</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2">
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
    <div class="uk-modal" id="edit_model">
		<div class="uk-modal-dialog">
			<div class="uk-modal-header">
				<h3 class="uk-modal-title">Edit User </h3>
			</div>
			<div class="md-card-content large-padding">
				<form id="form_validation3" class="uk-form-stacked">
                    <input type="hidden" name="id" id="id_e" value="">
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-2">
							<div class="parsley-row">
								<label for="full_name_e">Full Name<span class="req">*</span></label>
                                <input type="text" data-parsley-required-message="This field is required." name="full_name" id="full_name_e" required class="md-input" />
                                <div class="error"></div>
							</div>
                        </div>
                        <div class="uk-width-medium-1-2">
                                <div class="parsley-row">
                                    <label for="email_e">Email Address<span class="req">*</span></label>
                                    <input type="email" data-parsley-required-message="This field is required." name="email" id="email_e" required class="md-input" data-parsley-type-message="Please enter the valid email address."/>
                                    <div class="error"></div>
                                </div>
                            </div>
						
                       
					</div>
					<div class="uk-grid" data-uk-grid-margin>
                       
						<div class="uk-width-medium-1-2">
							<div class="parsley-row">
								<label for="mobile_e">Mobile<span class="req">*</span></label>
                                <input type="text" data-parsley-required-message="This field is required." name="mobile" id="mobile_e" required class="md-input" />
                                <div class="error"></div>
							</div>
						</div>
                        <div class="uk-width-medium-1-2">
							<div class="parsley-row">
								<label for="address_e">Address<span class="req">*</span></label>
                                <input type="text" data-parsley-required-message="This field is required." name="address" id="address_e" required  class="md-input" />
                                <div class="error"></div>
							</div>
						</div>
                    </div>
                   
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                
                                <select id="gender_e" name="gender" data-parsley-required-message="This field is required." required class="md-input" select >
                                    <option value="">Gender*</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                
                                <select id="status_e" name="status" required data-parsley-required-message="This field is required." class="md-input" select >
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