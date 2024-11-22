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
            <li><span>Menu Management</span></li>
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
                    <th data-name="sort_no">Sort No</th>
                    <th data-name="menu_name">Menu Name</th>
                    <th data-name="controller_uri">Controller URI</th>
                    <th data-name="icon_code">Menu Icon</th>
                    <th data-name="active_class">Active Class</th>
                    <th data-name="status">Status</th>

                </tr>
                </thead>

                <tfoot>
                <tr>
                    <?php if(role_permitted_html(false)) {?>
                        <th data-name="action">Actions</th>
                    <?php } ?>
                    <th data-name="sort_no">Sort No</th>
                    <th data-name="menu_name">Menu Name</th>
                    <th data-name="controller_uri">Controller URI</th>
                    <th data-name="icon_code">Menu Icon</th>
                    <th data-name="active_class">Active Class</th>
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
        <a title="Add New Menu" class="md-fab md-fab-accent md-fab-wave waves-effect waves-button" id="add" href="javascript:void(0);">
        <i class="material-icons">&#xE145;</i>
        </a>
    </div>
    <div class="uk-modal" id="add_model">
		<div class="uk-modal-dialog">
			<div class="uk-modal-header">
				<h3 class="uk-modal-title">Add New Menu </h3>
			</div>
			<div class="md-card-content large-padding">
				<form id="form_validation2" class="uk-form-stacked">
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-2">
							<div class="parsley-row">
								<label for="menu_name">Menu Name<span class="req">*</span></label>
                                <input type="text"
                                       data-parsley-required-message="This field is required."
                                       name="menu_name" id="menu_name"
                                       required
                                       class="md-input"
                                       pattern="[a-zA-Z0-9\s]+" data-parsley-pattern-message="Only alphabet and number are allowed."
                                />
                                <div class="error"></div>
							</div>
                        </div>

                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <label for="controller_uri">Contoller URI<span class="req">*</span></label>
                                <input type="text"
                                       data-parsley-required-message="This field is required."
                                       name="controller_uri" id="controller_uri"
                                       required
                                       class="md-input"
                                       pattern="[a-zA-Z_]+" data-parsley-pattern-message="Only alphabeta and underscore are allowed."
                                />
                                <div class="error"></div>
                            </div>
                        </div>
                       
					</div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <label for="icon_code">Icon Code<span class="req">*</span></label>
                                <input type="text"
                                       data-parsley-required-message="This field is required."
                                       name="icon_code" id="icon_code"
                                       required
                                       class="md-input"
                                />
                                <div class="error"></div>
                            </div>
                        </div>

                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <label for="active_class">Active Class<span class="req">*</span></label>
                                <input type="text"
                                       data-parsley-required-message="This field is required."
                                       name="active_class" id="active_class"
                                       required
                                       class="md-input"
                                />
                                <div class="error"></div>
                            </div>
                        </div>

                    </div>
                   
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <label for="sort_no">Sort No<span class="req">*</span></label>
                                <input type="text"
                                       data-parsley-required-message="This field is required."
                                       name="sort_no" id="sort_no"
                                       required
                                       class="md-input"
                                       data-parsley-type="number"
                                       data-parsley-type-message="Only Numbers are allowed."
                                />
                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">

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
				<h3 class="uk-modal-title">Edit Menu </h3>
			</div>
            <div class="md-card-content large-padding">
                <form id="form_validation3" class="uk-form-stacked">
                    <input type="hidden" name="id" id="id_e" value="">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <label for="menu_name">Menu Name<span class="req">*</span></label>
                                <input type="text"
                                       data-parsley-required-message="This field is required."
                                       name="menu_name" id="menu_name_e"
                                       required
                                       class="md-input"
                                       pattern="[a-zA-Z0-9\s]+" data-parsley-pattern-message="Only alphabet and number are allowed."
                                />
                                <div class="error"></div>
                            </div>
                        </div>

                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <label for="controller_uri">Contoller URI<span class="req">*</span></label>
                                <input type="text"
                                       data-parsley-required-message="This field is required."
                                       name="controller_uri" id="controller_uri_e"
                                       required
                                       class="md-input"
                                       pattern="[a-zA-Z_]+" data-parsley-pattern-message="Only alphabeta and underscore are allowed."
                                />
                                <div class="error"></div>
                            </div>
                        </div>

                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <label for="icon_code">Icon Code<span class="req">*</span></label>
                                <input type="text"
                                       data-parsley-required-message="This field is required."
                                       name="icon_code" id="icon_code_e"
                                       required
                                       class="md-input"
                                />
                                <div class="error"></div>
                            </div>
                        </div>

                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <label for="active_class">Active Class<span class="req">*</span></label>
                                <input type="text"
                                       data-parsley-required-message="This field is required."
                                       name="active_class" id="active_class_e"
                                       required
                                       class="md-input"
                                />
                                <div class="error"></div>
                            </div>
                        </div>

                    </div>

                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <label for="sort_no">Sort No<span class="req">*</span></label>
                                <input type="text"
                                       data-parsley-required-message="This field is required."
                                       name="sort_no" id="sort_no_e"
                                       required
                                       class="md-input"
                                       data-parsley-type="number"
                                       data-parsley-type-message="Only Numbers are allowed."
                                />
                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">

                                <select id="status_e" name="status" required data-parsley-required-message="This field is required."  data-md-selectize select>
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