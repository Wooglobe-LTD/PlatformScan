
<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><a href="<?php echo $url;?>pages">Pages Management</a></li>
            <li><span>Edit Page (<?php echo $data->title;?>)</span></li>
        </ul>
    </div>
    <div id="page_content_inner">

        <h3 class="heading_b uk-margin-bottom"><?php echo $title;?></h3>

        <div class="md-card">
            <div class="md-card-content large-padding">
                <form id="form_validation3" class="uk-form-stacked">
                    <input type="hidden" name="id" value="<?php echo $data->id;?>" id="id" />
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="title">Page Title<span class="req">*</span></label>
                                <input value="<?php echo $data->title;?>" type="text" name="title" id="title_e" pattern="[a-zA-Z0-9\s]+" data-parsley-pattern-message="Only alphabet and number are allowed." data-parsley-required-message="This field is required." required class="md-input" />
                                <div class="error"></div>
                            </div>
                        </div>

                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="description" class="uk-form-label">Page Description<span class="req">*</span></label>
                                <textarea id="description" name="description" cols="30" rows="20" class="wysiwyg_tinymce"><?php echo $data->description;?></textarea>
                                <div class="error"></div>
                            </div>
                        </div>

                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="meta_keywords" class="uk-form-label">Meta Keywords</label>
                                <textarea id="meta_keywords_e" name="meta_keywords" class="md-input"><?php echo $data->meta_keywords;?></textarea>
                                <div class="error"></div>
                            </div>
                        </div>

                    </div>

                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="meta_description_e" class="uk-form-label">Meta Description</label>
                                <textarea id="meta_description_e" name="meta_description" class="md-input"><?php echo $data->meta_description;?></textarea>
                                <div class="error"></div>
                            </div>
                        </div>

                    </div>
                    <div class="uk-grid" data-uk-grid-margin>

                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <select id="status_u" name="status_u" data-parsley-required-message="This field is required." required data-md-selectize>
                                    <option value="">Choose..</option>
                                    <?php $status = '';
                                    if($data->status == 0){
                                        $status = 'Inactive';
                                        ?>
                                        <option value="1">Active</option>
                                    <?php } ?>
                                    <?php if($data->status == 1){
                                        $status = 'Active';
                                        ?>
                                        <option value="0">Inactive</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>




                    <div class="uk-grid">
                        <div class="uk-width-1-1">
                            <button type="submit" class="md-btn md-btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var edit_data = '<?php echo $edit_data;?>';

    var sts = '<?php echo $status;?>';

    var assets = '<?php echo $asset;?>';
</script>