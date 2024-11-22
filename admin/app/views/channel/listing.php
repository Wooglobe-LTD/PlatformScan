<style>
textarea{
    scroll :none;
}
    .plugin-tooltip{
        z-index: 9999;
    }

</style>

<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span>Channel Management</span></li>
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
                    <th data-name="c.action">Actions</th>
                    <th data-name="c.name">Channel Name</th>
                    <th data-name="u.full_name">User Name</th>
                    <th data-name="c.status">Status</th>
               </tr>
                </thead>

                <tfoot>
                <tr>
                    <th data-name="c.action">Actions</th>
                    <th data-name="c.name">Channel Name</th>
                    <th data-name="u.full_name">User Name</th>
                    <th data-name="c.status">Status</th>

                </tr>
                </tfoot>

                <tbody>
               
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>
<div class="md-fab-wrapper">
        <a title="Add New Channel" class="md-fab md-fab-accent md-fab-wave waves-effect waves-button" id="add" href="javascript:void(0);">
        <i class="material-icons">&#xE145;</i>
        </a>
    </div>
    <div class="uk-modal" id="add_model">
		<div class="uk-modal-dialog">
			<div class="uk-modal-header">
				<h3 class="uk-modal-title">Add New Channel </h3>
			</div>
			<div class="md-card-content large-padding">
				<form id="form_validation2" class="uk-form-stacked">
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1">
							<div class="parsley-row">
								<label for="name">Channel Name<span class="req">*</span></label>
                                <input type="text" data-parsley-required-message="This field is required." name="name" id="name" required class="md-input" pattern="[a-zA-Z0-9\s]+" data-parsley-pattern-message="Only alphabet and number are allowed." />
                                <div class="error"></div>
							</div>
                        </div>

						
                       
					</div>
					<div class="uk-grid" data-uk-grid-margin>

                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="user_id" class="uk-form-label">Users<span class="req">*</span></label>
                                <select id="user_id" name="user_id" data-parsley-required-message="This field is required." required data-md-selectize>
                                    <option value="">Choose..</option>
                                    <?php foreach($users->result() as $user){?>
                                        <option value="<?php echo $user->id;?>"><?php echo $user->full_name;?></option>
                                    <?php } ?>
                                </select>
                                <div class="error"></div>
                            </div>
                        </div>

                    </div>
                   
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="status_u" class="uk-form-label">Status<span class="req">*</span></label>
                                <select id="status_u" name="status" data-parsley-required-message="This field is required." required data-md-selectize>
                                    <option value="">Choose..</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
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
    <div class="uk-modal" id="edit_model">
		<div class="uk-modal-dialog">
			<div class="uk-modal-header">
				<h3 class="uk-modal-title">Edit Channel</h3>
			</div>
			<div class="md-card-content large-padding">
				<form id="form_validation3" class="uk-form-stacked">
                    <input type="hidden" name="id" id="id_e" value="">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="name_e">Channel Name<span class="req">*</span></label>
                                <input type="text" data-parsley-required-message="This field is required." name="name" id="name_e" required class="md-input" pattern="[a-zA-Z0-9\s]+" data-parsley-pattern-message="Only alphabet and number are allowed." />
                                <div class="error"></div>
                            </div>
                        </div>



                    </div>
                    <div class="uk-grid" data-uk-grid-margin>

                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="user_id_e" class="uk-form-label">Users<span class="req">*</span></label>
                                <select id="user_id_e" name="user_id" data-parsley-required-message="This field is required." required data-md-selectize>
                                    <option value="">Choose..</option>
                                    <?php foreach($users->result() as $user){?>
                                        <option value="<?php echo $user->id;?>"><?php echo $user->full_name;?></option>
                                    <?php } ?>
                                </select>
                                <div class="error"></div>
                            </div>
                        </div>

                    </div>

                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="status_e" class="uk-form-label">Status<span class="req">*</span></label>
                                <select id="status_e" name="status" data-parsley-required-message="This field is required." required data-md-selectize>
                                    <option value="">Choose..</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
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
<script>
    var jUsers = '<?php echo $jUser;?>';
    var jStatus = '<?php echo $jStatus;?>';
    jUsers = JSON.parse(jUsers);
    jStatus = JSON.parse(jStatus);
</script>