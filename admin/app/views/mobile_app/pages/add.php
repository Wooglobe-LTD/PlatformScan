
    <div id="page_content">
        <div id="top_bar">
            <ul id="breadcrumbs">
                <li><a href="<?php echo $url;?>">Dashboard</a></li>
                <li><a href="<?php echo $url;?>mobile-pages">Mobile Pages Management</a></li>
                <li><span>Add New Page</span></li>
            </ul>
        </div>
        <div id="page_content_inner">

            <h3 class="heading_b uk-margin-bottom"><?php echo $title;?></h3>

            <div class="md-card">
                <div class="md-card-content large-padding">
                    <form id="form_validation2" class="uk-form-stacked">

                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="title">Page Title<span class="req">*</span></label>
                                    <input type="text" name="title" id="title" pattern="[a-zA-Z0-9\s]+" data-parsley-pattern-message="Only alphabet and number are allowed." data-parsley-required-message="This field is required." required class="md-input" />
                                    <div class="error"></div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="body" class="uk-form-label">Body<span class="req">*</span></label>
                                    <textarea id="body" name="body" cols="30" rows="20" class="wysiwyg_tinymce"></textarea>
                                    <div class="error"></div>
                                </div>
                            </div>
                            
                        </div>
                      

                        
                        <div class="uk-grid" data-uk-grid-margin>

                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="status" class="uk-form-label">Status<span class="req">*</span></label>
                                    <select id="status" name="status" data-parsley-required-message="This field is required." required data-md-selectize>
                                        <option value="">Choose..</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        

                       
                        
                        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                <button type="submit" class="md-btn md-btn-primary">Add</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        var assets = '<?php echo $asset;?>';
    </script>