
    <div id="page_content">
        <div id="top_bar">
            <ul id="breadcrumbs">
                <li><a href="<?php echo $url;?>">Dashboard</a></li>
                <li><a href="<?php echo $url;?>email_templates">Email Templates Management</a></li>
                <li><span>Add New Email Template</span></li>
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
                                    <label for="title">Email Template Title<span class="req">*</span></label>
                                    <input type="text" name="title" id="title" pattern="[a-zA-Z0-9\s]+" data-parsley-pattern-message="Only alphabet and number are allowed." data-parsley-required-message="This field is required." required class="md-input" />
                                    <div class="error"></div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="short_code">Email Template Short Code<span class="req">*</span></label>
                                    <input type="text" name="short_code" id="short_code" pattern="[a-zA-Z0-9_]+" readonly data-parsley-pattern-message="Only alphabet, numbers and underscore are allowed." data-parsley-required-message="This field is required." required class="md-input" value=" " />
                                    <div class="error"></div>
                                </div>
                            </div>

                        </div>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="subject">Email Subject<span class="req">*</span></label>
                                    <input type="text" name="subject" id="subject" pattern="[a-zA-Z-()_\s]+" data-parsley-pattern-message="Only alphabet , number , dash,brackets and underscore are allowed." data-parsley-required-message="This field is required." required class="md-input" />
                                    <div class="error"></div>
                                </div>
                            </div>

                        </div>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="message" class="uk-form-label">Email Content<span class="req">*</span></label>
                                    <textarea required id="message" data-parsley-required-message="This field is required." name="message" cols="30" rows="20" class="wysiwyg_tinymce"></textarea>
                                    <div class="error"></div>
                                </div>
                            </div>
                            
                        </div>


                        <div class="uk-grid" data-uk-grid-margin>

                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="status_u" class="uk-form-label">Status<span class="req">*</span></label>
                                    <select id="status_u" name="status_u" data-parsley-required-message="This field is required." required data-md-selectize>
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