<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 4/25/2018
 * Time: 5:09 PM
 */
?>
<div id="page_content">
    <div id="page_content_inner">

        <h3 class="heading_b uk-margin-bottom">Send Email By Existing Templates</h3>

        <div class="md-card">
            <div class="md-card-content large-padding">
                <form id="form_validation2" class="uk-form-stacked">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-5-6">
                            <div class="parsley-row">
                                <select id="email_template" name="email_template" class="md-input" data-parsley-required-message="This field is required." required >
                                    <option value="">Choose..</option>
                                    <?php  foreach($templates as $template){?>
                                        <option value="<?php echo $template['id'];?>" data-id="<?php echo $template['email_template_id'];?>"><?php echo $template['email_title'];?></option>
                                    <?php  } ?>
                                </select>
                                <div class="error"></div>
                                <input type="hidden" value="<?php echo $templates[0]['lead_id']?>" name="lead_id" id="lead_id">
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" id="checkbox" data-uk-grid-margin style="display: none;">
                        <div class="uk-width-medium-1-1">
                            <p>
                                <input type="checkbox" name="is_url" id="is_url" data-md-icheck />
                                <label for="is_url" class="inline-label"><b>Want to Include URL?</b></label>
                            </p>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <button type="submit" class="md-btn md-btn-primary" >Send</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="page_content">
    <div id="page_content_inner">
        <h3 class="heading_b uk-margin-bottom" style="margin-top: -98px;">Send Custom Email</h3>
        <div class="md-card">
            <div class="md-card-content">
                <form id="form_validation3" class="uk-form-stacked">

                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="subject">Email Subject<span class="req">*</span></label>
                                <input type="text" name="subject" id="subject" pattern="[a-zA-Z0-9\s]+" data-parsley-pattern-message="Only alphabet and number are allowed." data-parsley-required-message="This field is required." required class="md-input" />
                                <div class="error"></div>
                            </div>
                        </div>

                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="message" class="uk-form-label">Page Description<span class="req">*</span></label>
                                <textarea id="message" name="message" cols="30" rows="20" class="wysiwyg_tinymce"></textarea>
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" value="<?php echo $templates[0]['lead_id']?>" name="leadId" id="leadId">
                    <div class="uk-grid">
                        <div class="uk-width-1-1">
                            <button type="submit" class="md-btn md-btn-primary">Send</button>
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
