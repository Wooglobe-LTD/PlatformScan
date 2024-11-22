<style>
    textarea {
        scroll: none;
    }

</style>

<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url; ?>">Dashboard</a></li>
            <li><span>Mobile Events Management</span></li>
        </ul>
    </div>
    <div id="page_content_inner">


        <h4 class="heading_a uk-margin-bottom"><?php echo $title; ?></h4>
        <div class="md-card uk-margin-medium-bottom">
            <div class="md-card-content">
                <div class="dt_colVis_buttons"></div>
                <table id="dt_tableExport" class="uk-table" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <?php if ($assess['can_edit'] || $assess['can_delete']) { ?>
                            <th data-name="action">Actions</th>
                        <?php } ?>
                        <th data-name="name">Name</th>
                        <th data-name="description">Description</th>
                        <th data-name="starting_date">Starting_date</th>
                        <th data-name="ending_date">Ending_date</th>
                        <th data-name="country_of_event">Country of Event</th>
                        <th data-name="category_id">Category</th>

                        <th data-name="status">Status</th>

                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <?php if ($assess['can_edit'] || $assess['can_delete']) { ?>
                            <th data-name="action">Actions</th>
                        <?php } ?>
                        <th data-name="name">Name</th>
                        <th data-name="description">Description</th>
                        <th data-name="starting_date">Starting_date</th>
                        <th data-name="ending_date">Ending_date</th>
                        <th data-name="country_of_event">Country of Event</th>
                        <th data-name="category_id">Category</th>
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
<?php if ($assess['can_add']) { ?>
    <div class="md-fab-wrapper">
        <a title="Add New Category" class="md-fab md-fab-accent md-fab-wave waves-effect waves-button" id="add"
           href="javascript:void(0);">
            <i class="material-icons">&#xE145;</i>
        </a>
    </div>
<?php } ?>
<div class="uk-modal" id="add_model">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h3 class="uk-modal-title">Add New Event</h3>
        </div>
        <div class="md-card-content large-padding">
            <form id="event_form_validation2" class="uk-form-stacked">

                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">


                        </div>
                    </div>

                </div>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="title">Event Title<span class="req">*</span></label>
                            <input type="text" pattern="[a-zA-Z0-9\s]+"
                                   data-parsley-pattern-message="Only alphabet and number are allowed."
                                   data-parsley-required-message="This field is required." name="name" id="name"
                                   required class="md-input"/>
                            <div class="error"></div>
                        </div>
                    </div>
                </div>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="title">Description<span class="req">*</span></label>
                            <input type="text" pattern="[a-zA-Z0-9\s]+"
                                   data-parsley-pattern-message="Only alphabet and number are allowed."
                                   data-parsley-required-message="This field is required." name="description"
                                   id="description" required class="md-input"/>
                            <div class="error"></div>
                        </div>
                    </div>
                </div>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="title">Starting Date<span class="req">*</span></label>
                            <input type="date"
                                   data-parsley-pattern-message="Only alphabet and number are allowed."
                                   data-parsley-required-message="This field is required." name="starting_date"
                                   id="starting_date" required class="md-input"/>
                            <div class="error"></div>
                        </div>
                    </div>
                </div>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="title">Ending Date<span class="req">*</span></label>
                            <input type="date"
                                   data-parsley-pattern-message="Only alphabet and number are allowed."
                                   data-parsley-required-message="This field is required." name="ending_date"
                                   id="ending_date" required class="md-input"/>
                            <div class="error"></div>
                        </div>
                    </div>
                </div>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="title">Country of Event<span class="req">*</span></label>
                            <input type="text" pattern="[a-zA-Z0-9\s]+"
                                   data-parsley-pattern-message="Only alphabet and number are allowed."
                                   data-parsley-required-message="This field is required." name="country_of_event"
                                   id="country_of_event" required class="md-input"/>
                            <div class="error"></div>
                        </div>
                    </div>
                </div>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">

                            <select id="category_id" name="category_id" required data-parsley-required-message="This field is required." class="md-input">
                                <option value="">Category*</option>

                                <?php foreach($categories as $cat){?>
                                    <option value="<?php echo $cat->id?>"><?php echo $cat->title?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">

                            <select id="status" name="status" required
                                    data-parsley-required-message="This field is required." class="md-input">
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
            <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
            <button type="button" id="add_from" class="md-btn md-btn-flat md-btn-flat-primary">Add</button>
        </div>
    </div>
</div>
<div class="uk-modal" id="edit_model">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h3 class="uk-modal-title">Edit Event </h3>
        </div>
        <div class="md-card-content large-padding">
            <form id="event_form_validation3" class="uk-form-stacked">
                <input type="hidden" name="id" id="id_e" value="">
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">


                        </div>
                    </div>

                </div>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="title">Event Title<span class="req">*</span></label>
                            <input type="text" pattern="[a-zA-Z0-9\s]+"
                                   data-parsley-pattern-message="Only alphabet and number are allowed."
                                   data-parsley-required-message="This field is required." name="name" id="name_e"
                                   required class="md-input"/>
                            <div class="error"></div>
                        </div>
                    </div>
                </div>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="title">Description<span class="req">*</span></label>
                            <input type="text" pattern="[a-zA-Z0-9\s]+"
                                   data-parsley-pattern-message="Only alphabet and number are allowed."
                                   data-parsley-required-message="This field is required." name="description"
                                   id="description_e" required class="md-input"/>
                            <div class="error"></div>
                        </div>
                    </div>
                </div>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="title">Starting Date<span class="req">*</span></label>
                            <input type="date"
                                   data-parsley-pattern-message="Only alphabet and number are allowed."
                                   data-parsley-required-message="This field is required." name="starting_date"
                                   id="starting_date_e" required class="md-input"/>
                            <div class="error"></div>
                        </div>
                    </div>
                </div>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="title">Ending Date<span class="req">*</span></label>
                            <input type="date"
                                   data-parsley-pattern-message="Only alphabet and number are allowed."
                                   data-parsley-required-message="This field is required." name="ending_date"
                                   id="ending_date_e" required class="md-input"/>
                            <div class="error"></div>
                        </div>
                    </div>
                </div>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="title">Country of Event<span class="req">*</span></label>
                            <input type="text" pattern="[a-zA-Z0-9\s]+"
                                   data-parsley-pattern-message="Only alphabet and number are allowed."
                                   data-parsley-required-message="This field is required." name="country_of_event"
                                   id="country_of_event_e" required class="md-input"/>
                            <div class="error"></div>
                        </div>
                    </div>
                </div>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">

                            <select id="category_id_e" name="category_id" required data-parsley-required-message="This field is required." class="md-input">
                                <option value="">Category*</option>

                                <?php foreach($categories as $cat){?>
                                    <option value="<?php echo $cat->id?>"><?php echo $cat->title?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">

                            <select id="status_e" name="status" required
                                    data-parsley-required-message="This field is required." class="md-input">
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
            <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
            <button type="button" id="edit_form" class="md-btn md-btn-flat md-btn-flat-primary">Save</button>
        </div>
    </div>
</div>