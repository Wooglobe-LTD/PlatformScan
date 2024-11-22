<style>
textarea{
    scroll :none;
}

</style>

<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span>MRSS Feeds Management</span></li>
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
                    <th data-name="c.title">Feed Title</th>
					<th data-name="c.type">Type</th>
                    <th data-name="cc.title">Partner</th>
                    <th data-name="c.url">URL</th>
                    <th data-name="c.status">Status</th>

                </tr>
                </thead>

                <tfoot>
                <tr>
                    <?php if($assess['can_edit'] || $assess['can_delete']) { ?>
                        <th data-name="c.action">Actions</th>
                    <?php } ?>
                    <th data-name="c.title">Feed Title</th>
                    <th data-name="c.type">Type</th>
                    <th data-name="cc.title">Partner</th>
                    <th data-name="c.url">URL</th>
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
<?php if($assess['can_add']) { ?>
<div class="md-fab-wrapper">
        <a title="Add New MRSS Feed" class="md-fab md-fab-accent md-fab-wave waves-effect waves-button" id="add" href="javascript:void(0);">
        <i class="material-icons">&#xE145;</i>
        </a>
    </div>
  <?php } ?>
    <div class="uk-modal" id="add_model">
		<div class="uk-modal-dialog">
			<div class="uk-modal-header">
				<h3 class="uk-modal-title">Create New MRSS Feed</h3>
			</div>
			<div class="md-card-content large-padding">
				<form id="form_validation2" class="uk-form-stacked">

					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1">
							<div class="parsley-row">
								<select id="type" name="type" required data-parsley-required-message="This field is required." class="md-input">
									<option value="">Type*</option>
									<option value="1">Category</option>
									<option value="0">Partner</option>
								</select>
							</div>
						</div>

					</div>
                    
                    <div class="uk-grid" data-uk-grid-margin style="display: none">
						<div class="uk-width-medium-1-1">
							<div class="parsley-row">
								<select  id="partner" name="partner_id" required data-parsley-required-message="This field is required." class="md-input">
									<option value="">User*</option>
                                    <?php foreach($users as $user){?>
									<option value=<?php echo $user->id?>><?php echo $user->full_name?></option>
                                        <?php } ?>
                                </select>
							</div>
						</div>

					</div>

					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1">
							<div class="parsley-row">
								<label for="title">Feed Title<span class="req">*</span></label>
                                <input type="text" pattern="[a-zA-Z0-9\s]+" data-parsley-pattern-message="Only alphabet and number are allowed." data-parsley-required-message="This field is required." name="title" id="title" required class="md-input" />
                                <div class="error"></div>
							</div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="url">Feed URL<span class="req">*</span></label>
                                <input type="text" pattern="[a-zA-Z0-9_-]+" data-parsley-pattern-message="Only alphabet, number and dash are allowed." data-parsley-required-message="This field is required." name="url" id="url" required class="md-input" />
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1">
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
				<h3 class="uk-modal-title">Edit MRSS Feed </h3>
			</div>
			<div class="md-card-content large-padding">
				<form id="form_validation3" class="uk-form-stacked">
                    <input type="hidden" name="id" id="id_e" value="">

					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1">
							<div class="parsley-row">

								<select id="type_e" name="type" required data-parsley-required-message="This field is required." class="md-input" select-t>
									<option value="">Type*</option>
									<option value="1">Category</option>
									<option value="0">Partner</option>
								</select>
							</div>
						</div>

					</div>

                    <div class="uk-grid" data-uk-grid-margin style="display: none">
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <select  id="partner_e" name="partner_id" required data-parsley-required-message="This field is required." class="md-input">
                                    <option value="">User*</option>
                                    <?php foreach($users as $user){?>
                                        <option value=<?php echo $user->id?>><?php echo $user->full_name?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                    </div>

					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1">
							<div class="parsley-row">
								<label for="title">Feed Title<span class="req">*</span></label>
                                <input type="text" pattern="[a-zA-Z0-9\s]+" data-parsley-pattern-message="Only alphabet and number are allowed." data-parsley-required-message="This field is required." name="title" id="title_e" required class="md-input" />
                                <div class="error"></div>
							</div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="url">Feed URL<span class="req">*</span></label>
                                <input type="text" pattern="[a-zA-Z0-9_-]+" data-parsley-pattern-message="Only alphabet, number and dash are allowed." data-parsley-required-message="This field is required." name="url" id="url_e" required class="md-input" />
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                
                                <select id="status_e" name="status" required data-parsley-required-message="This field is required." class="md-input" select-s>
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
