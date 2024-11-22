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
            <li><span>Menu Actions Management</span></li>
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
                    <?php if(role_permitted_html(false)) {?>
                        <th data-name="action">Actions</th>
                    <?php } ?>
                    <th data-name="menu_name">Menu Name</th>
                    <th data-name="action_name">Action Name</th>
                    <th data-name="action_uri">Action URI</th>
                    <th data-name="status">Status</th>

                </tr>
                </thead>

                <tfoot>
                <tr>
                    <?php if(role_permitted_html(false)) {?>
                        <th data-name="action">Actions</th>
                    <?php } ?>
                    <th data-name="menu_name">Menu Name</th>
                    <th data-name="action_name">Action Name</th>
                    <th data-name="action_uri">Action URI</th>
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
<?php if(role_permitted_html(false)){?>
<div class="md-fab-wrapper">
        <a title="Add New Menu Action" class="md-fab md-fab-accent md-fab-wave waves-effect waves-button" id="add" href="javascript:void(0);">
        <i class="material-icons">&#xE145;</i>
        </a>
    </div>
    <div class="uk-modal" id="add_model">
		<div class="uk-modal-dialog">
			<div class="uk-modal-header">
				<h3 class="uk-modal-title">Add New Menu Action </h3>
			</div>
			<div class="md-card-content large-padding">
				<form id="form_validation2" class="uk-form-stacked">
                    <div class="uk-grid" data-uk-grid-margin>

                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">

                                <select id="menu_id" name="menu_id" required data-parsley-required-message="This field is required." data-md-selectize >
                                    <option value="">Menu*</option>
                                    <?php foreach($menus->result() as $menu){?>
                                        <option value="<?php echo $menu->id;?>"><?php echo $menu->menu_name;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <label for="action_name">Action Name<span class="req">*</span></label>
                                <input type="text"
                                       data-parsley-required-message="This field is required."
                                       name="action_name" id="action_name"
                                       required
                                       class="md-input"
                                       pattern="[a-zA-Z0-9\s]+" data-parsley-pattern-message="Only alphabet and number are allowed."
                                />
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-2">
							<div class="parsley-row">
								<label for="action_uri">Action URI<span class="req">*</span></label>
                                <input type="text"
                                       data-parsley-required-message="This field is required."
                                       name="action_uri" id="action_uri"
                                       required
                                       class="md-input"
                                       pattern="[a-zA-Z_]+" data-parsley-pattern-message="Only alphabeta and underscore are allowed."
                                />
                                <div class="error"></div>
							</div>
                        </div>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">

                                <select id="status" name="status" required data-parsley-required-message="This field is required." data-md-selectize>
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
				<h3 class="uk-modal-title">Edit Menu Action </h3>
			</div>
			<div class="md-card-content large-padding">
				<form id="form_validation3" class="uk-form-stacked">
                    <input type="hidden" name="id" id="id_e" value="">
                    <div class="uk-grid" data-uk-grid-margin>

                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">

                                <select id="menu_id_e" name="menu_id" required data-parsley-required-message="This field is required." data-md-selectize select-c >
                                    <option value="">Menu*</option>
                                    <?php foreach($menus->result() as $menu){?>
                                        <option value="<?php echo $menu->id;?>"><?php echo $menu->menu_name;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <label for="action_name_e">Action Name<span class="req">*</span></label>
                                <input type="text"
                                       data-parsley-required-message="This field is required."
                                       name="action_name" id="action_name_e"
                                       required
                                       class="md-input"
                                       pattern="[a-zA-Z0-9\s]+" data-parsley-pattern-message="Only alphabet and number are allowed."
                                />
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
					<div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <label for="action_uri_e">Action URI<span class="req">*</span></label>
                                <input type="text"
                                       data-parsley-required-message="This field is required."
                                       name="action_uri" id="action_uri_e"
                                       required
                                       class="md-input"
                                       pattern="[a-zA-Z_]+" data-parsley-pattern-message="Only alphabeta and underscore are allowed."
                                />
                                <div class="error"></div>
                            </div>
                        </div>

                            <div class="uk-width-medium-1-2">
                                <div class="parsley-row">

                                    <select id="status_e" name="status" required data-parsley-required-message="This field is required." data-md-selectize select >
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
<?php } ?>