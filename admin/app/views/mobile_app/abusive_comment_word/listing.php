<style>
    textarea {
        scroll: none;
    }

</style>

<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url; ?>">Dashboard</a></li>
            <li><span>Mobile APP Comments Words Management</span></li>
        </ul>
    </div>
    <div id="page_content_inner">


        <h4 class="heading_a uk-margin-bottom"><?php echo $title; ?></h4>
        <div class="md-card uk-margin-medium-bottom">
            <div class="md-card-content">
                <div class="dt_colVis_buttons"></div>
                <table id="ma_dt_tableExport" class="uk-table" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <?php if ($assess['can_edit'] || $assess['can_delete']) { ?>
                            <th data-name="acw.action">Actions</th>
                        <?php } ?>
                        <th data-name="acw.word">Word</th>
                        

                        <th data-name="acw.status">Status</th>

                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <?php if ($assess['can_edit'] || $assess['can_delete']) { ?>
                            <th data-name="acw.action">Actions</th>
                        <?php } ?>
                        <th data-name="acw.word">Word</th>
                        

                        <th data-name="acw.status">Status</th>

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
        <a title="Add New Keyword" class="md-fab md-fab-accent md-fab-wave waves-effect waves-button" id="add"
           href="javascript:void(0);">
            <i class="material-icons">&#xE145;</i>
        </a>
    </div>
<?php } ?>
<div class="uk-modal" id="add_model">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h3 class="uk-modal-title">Add New Word</h3>
        </div>
        <div class="md-card-content large-padding">
            <form id="ma_form_validation2" class="uk-form-stacked">

                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="word">Word<span class="req">*</span></label>
                            <input type="text"
                                  
                                   data-parsley-required-message="This field is required." name="word" id="word"
                                   required class="md-input"/>
                            <div class="error"></div>
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


                <!--   <div class="uk-grid" data-uk-grid-margin>
                       <div class="uk-width-medium-1-1">
                           <div class="md-card">
                               <div class="md-card-content">
                                   <h3 class="heading_a uk-margin-small-bottom">
                                       Image
                                   </h3>
                                   <input type="file" id="input-file-b" name="image" class="dropify" data-show-errors="true" data-allowed-file-extensions="jpeg jpg png gif" data-default-file="" />
                               </div>
                           </div>
                       </div>


                   </div>
-->

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
            <h3 class="uk-modal-title">Edit keyword </h3>
        </div>
        <div class="md-card-content large-padding">
            <form id="ma_form_validation3" class="uk-form-stacked">
                <input type="hidden" name="id" id="id_e" value="">

                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="word">Word<span class="req">*</span></label>
                            <input type="text"
                                  
                                   data-parsley-required-message="This field is required." name="word" id="word_e"
                                   required class="md-input"/>
                            <div class="error"></div>
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
            <button type="button" id="edit_from" class="md-btn md-btn-flat md-btn-flat-primary">Save</button>
        </div>
    </div>
</div>