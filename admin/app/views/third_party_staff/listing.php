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
            <li><span>Third Party Staff Member Management</span></li>
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
                    <th data-name="name">Name</th>
                    <th data-name="link">Link</th>
                    <th data-name="username">Username</th>
                    <th data-name="email">Email</th>
                    <th data-name="status">Status</th>
                    <th data-name="c.image">Image</th>
                    <th data-name="header_text">Header Text</th>

                </tr>
                </thead>

                <tfoot>
                <tr>
                    <?php if(role_permitted_html(false)) {?>
                        <th data-name="action">Actions</th>
                    <?php } ?>
                    <th data-name="name">Name</th>
                    <th data-name="link">Link</th>
                    <th data-name="username">Username</th>
                    <th data-name="email">Email</th>
                    <th data-name="status">Status</th>
                    <th data-name="c.image">Image</th>
                    <th data-name="header_text">Header Text</th>
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
        <a title="Add New Memebr" class="md-fab md-fab-accent md-fab-wave waves-effect waves-button" id="add" href="javascript:void(0);">
        <i class="material-icons">&#xE145;</i>
        </a>
    </div>
    <div class="uk-modal" id="add_model">
		<div class="uk-modal-dialog">
			<div class="uk-modal-header">
				<h3 class="uk-modal-title">Add New Member </h3>
			</div>
			<div class="md-card-content large-padding">
				<form id="form_validation2" class="uk-form-stacked">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">

                            <div class="parsley-row">
                                <label for="name">Member Name<span class="req">*</span></label>
                                <input type="text"
                                       data-parsley-required-message="This field is required."
                                       name="name" id="name"
                                       required
                                       class="md-input"
                                       pattern="[a-zA-Z0-9\s]+" data-parsley-pattern-message="Only alphabet and number are allowed."
                                />
                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <label for="username">Username<span class="req">*</span></label>
                                <input type="text"
                                       data-parsley-required-message="This field is required."
                                       name="username" id="username"
                                       required
                                       class="md-input"
                                       pattern="[a-zA-Z0-9.]+" data-parsley-pattern-message="Only alphabets,numbers and dot are allowed."
                                />
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>


                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <label for="email">Email<span class="req">*</span></label>
                                <input type="email"
                                       data-parsley-required-message="This field is required."
                                       name="email" id="email"
                                       required
                                       class="md-input"
                                       data-parsley-type-message="Please enter the valid email address."
                                />
                                <div class="error"></div>
                            </div>
                        </div>

                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">

                                <select id="status" name="status" required data-parsley-required-message="This field is required."  data-md-selectize select>
                                    <option value="">Status*</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">

                            <div class="parsley-row">
                                <label for="link">Link Name<span class="req">*</span></label>
                                <input type="text"
                                       data-parsley-required-message="This field is required."
                                       name="link" id="link"
                                       required
                                       class="md-input"
                                       pattern="[a-zA-Z0-9\s]+" data-parsley-pattern-message="Only alphabet and number are allowed."
                                />
                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <input type="file" name="image" value="" required data-parsley-required-message="This field is required." class="md-input">
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <label for="header_text">Header Text<span class="req">*</span></label>
                                <textarea type="text" class="form-control" name="header_text" id="header_text" placeholder="Please provide header text which appear on submit form header"
                                          data-parsley-required-message="This field is Mandatory."
                                          required tabindex="7" style="margin: 0px; width: 505px; height: 127px;"></textarea>
                                <div class="error" id="header_text_err"></div>
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
				<h3 class="uk-modal-title">Edit Member </h3>
			</div>
            <div class="md-card-content large-padding">
                <form id="form_validation3" class="uk-form-stacked">
                    <input type="hidden" name="id" id="id_e" value="">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">

                            <div class="parsley-row">
                                <label for="name_e">Member Name<span class="req">*</span></label>
                                <input type="text"
                                       data-parsley-required-message="This field is required."
                                       name="name" id="name_e"
                                       required
                                       class="md-input"
                                       pattern="[a-zA-Z0-9\s]+" data-parsley-pattern-message="Only alphabet and number are allowed."
                                />
                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <label for="username_e">Username<span class="req">*</span></label>
                                <input type="text"
                                       data-parsley-required-message="This field is required."
                                       name="username" id="username_e"
                                       required
                                       class="md-input"
                                       pattern="[a-zA-Z0-9.]+" data-parsley-pattern-message="Only alphabets,numbers and dot are allowed."
                                />
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <label for="email">Email<span class="req">*</span></label>
                                <input type="email"
                                       data-parsley-required-message="This field is required."
                                       name="email" id="email_e"
                                       required
                                       class="md-input"
                                       data-parsley-type-message="Please enter the valid email address."
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
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">

                            <div class="parsley-row">
                                <label for="link_e">Link Name<span class="req">*</span></label>
                                <input type="text"
                                       data-parsley-required-message="This field is required."
                                       name="link" id="link_e"
                                       required
                                       class="md-input"
                                       pattern="[a-zA-Z0-9\s]+" data-parsley-pattern-message="Only alphabet and number are allowed."
                                />
                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <input type="file" name="image" value="" required data-parsley-required-message="This field is required." class="md-input">
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="parsley-row">
                            <img src="" id="image_e">
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">
                                <label for="header_text">Header Text<span class="req">*</span></label>
                                <textarea type="text" class="form-control" name="header_text_e" id="header_text" placeholder="Please provide header text which appear on submit form header"
                                          data-parsley-required-message="This field is Mandatory."
                                          required tabindex="7" style="margin: 0px; width: 505px; height: 127px;"></textarea>
                                <div class="error" id="header_text_err"></div>
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