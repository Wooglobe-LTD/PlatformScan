
    <div id="page_content">
        <div id="top_bar">
            <ul id="breadcrumbs">
                <li><a href="<?php echo $url;?>">Dashboard</a></li>
                <li><a href="<?php echo $url;?>videos">Videos Management</a></li>
                <li><span>Add New Video</span></li>
            </ul>
        </div>
        <div id="page_content_inner">

            <h3 class="heading_b uk-margin-bottom"><?php echo $title;?></h3>

            <div class="md-card">
                <div class="md-card-content large-padding">
                    <form id="form_validation2" class="uk-form-stacked">
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <p>
                                    <input type="checkbox" name="is_wooglobe_video" id="is_wooglobe_video" data-md-icheck />
                                    <label for="is_wooglobe_video" class="inline-label"><b>Is WooGlobe Video?</b></label>
                                </p>
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <div class="parsley-row">
                                    <label for="parent" class="uk-form-label">Category<span class="req">*</span></label>
                                    <select id="parent" name="parent" data-parsley-required-message="This field is required." required data-md-selectize>
                                        <option value="">Choose..</option>
                                        <?php foreach($categories->result() as $cat){?> 
                                            <option value="<?php echo $cat->id;?>"><?php echo $cat->title;?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="error"></div>
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">
                                <div class="parsley-row">
                                    <label for="category_id" class="uk-form-label">Sub Categories<span class="req">*</span></label>
                                    <select id="category_id" name="category_id" data-parsley-required-message="This field is required." required data-md-selectize>
                                        <option value="">Choose..</option>
                                        
                                    </select>
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="title">Video Title<span class="req">*</span></label>
                                    <input type="text" name="title" id="title"  data-parsley-required-message="This field is required." required class="md-input" />
                                    <div class="error"></div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="description" class="uk-form-label">Description</label>
                                    <textarea id="description" name="description" cols="30" rows="10" class="md-input"></textarea>
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="tags" class="uk-form-label">Tags</label>
                                    <textarea id="tags" name="tags" class="md-input"></textarea>
                                    <div class="error"></div>
                                </div>
                            </div>
                            
                        </div>
                        
                        
                        <div class="uk-grid" data-uk-grid-margin>
                           
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="user_id" class="uk-form-label">Client<span class="req">*</span></label>
                                    <select id="user_id" name="user_id" data-parsley-required-message="This field is required." required data-md-selectize>
                                        <option value="">Choose..</option>
                                        <?php foreach($users->result() as $user){?> 
                                            <option value="<?php echo $user->id;?>"><?php echo $user->full_name;?> ( <?php echo $user->email?> )</option>
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
                                    <select id="status_u" name="status_u" data-parsley-required-message="This field is required." required data-md-selectize>
                                        <option value="">Choose..</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <input type="checkbox" name="real_deciption_updated" id="real_deciption_updated" value="1" data-md-icheck />
                                <label for="is_wooglobe_video" class="inline-label"><b>Is Real Video Description Updated?</b></label>
                            </div>

                        </div>
                        <div class="uk-grid" data-uk-grid-margin>
                           
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="video_type_id" class="uk-form-label">Video Type<span class="req">*</span></label>
                                    <select id="video_type_id" name="video_type_id" data-parsley-required-message="This field is required." required data-md-selectize>
                                        <option value="">Choose..</option>
                                        <?php foreach($videoTypes->result() as $type){?> 
                                            <option value="<?php echo $type->id;?>"><?php echo $type->title;?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-grid" id="upload" data-uk-grid-margin style="display:none;">
                            <div class="uk-width-medium-1-1">
                                <div id="file_upload-drop" class="uk-file-upload">
                                <p class="uk-text">Drop file to upload</p>
                                <p class="uk-text-muted uk-text-small uk-margin-small-bottom">or</p>
                                <a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file"></a>
                            </div>
                            <div id="file_upload-progressbar" class="uk-progress uk-hidden">
                                <div class="uk-progress-bar" style="width:0">0%</div>
                            </div>
                                <div class="error"></div>
                            </div>
                            
                        </div>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="url" class="uk-form-label">Embed Code/URL</label>
                                    <textarea id="url" name="url" class="md-input" data-parsley-required-message="This field is required." required></textarea>
                                    <div class="error"></div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="md-card">
                                    <div class="md-card-content">
                                        <h3 class="heading_a uk-margin-small-bottom">
                                            Video Thumbnail
                                        </h3>
                                        <input type="file" id="input-file-b" name="thumb" class="dropify" data-default-file="" required/>
                                        <div class="error"></div>
                                    </div>
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