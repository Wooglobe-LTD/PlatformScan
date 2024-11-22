
<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
		<li><span>Configurations</span></li>
           
            
        </ul>
    </div>
    <div id="page_content_inner">


        <h3 class="heading_b uk-margin-bottom"><?php echo $title;?></h3>


        <div class="md-card">
            <div class="md-card-content large-padding">
                <form id="form_validation2" class="uk-form-stacked" method="post" action="<?php echo site_url('store_configurations');?>">

                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="title">Reminder Welcome<span class="req">*</span></label>
                                <input type="text" name="reminder_welcome" id="title"  data-parsley-pattern-message="Only number are allowed." data-parsley-required-message="This field is required." required class="md-input" value="<?php echo $data->reminder_welcome?>"/>
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="title">Reminder Contract Sent<span class="req">*</span></label>
                                <input type="text" name="reminder_contract_sent" id="title"  data-parsley-pattern-message="Only number are allowed." data-parsley-required-message="This field is required." required class="md-input" value="<?php echo $data->reminder_contract_sent?>"/>
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="title">Reminder Pending<span class="req">*</span></label>
                                <input type="text" name="reminder_pending" id="title"  data-parsley-pattern-message="Only number are allowed." data-parsley-required-message="This field is required." required class="md-input" value="<?php echo $data->reminder_pending?>"/>
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="revenue">Revenue %<span class="req">*</span></label>
                                <input type="number" name="revenue" id="revenue"  data-parsley-pattern-message="Only  number are allowed." data-parsley-required-message="This field is required." required class="md-input" value="<?php echo $data->revenue?>"/>
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="universal_description_footer" class="uk-form-label">Universal Description Footer<span class="req">*</span></label></label>
                                <textarea id="universal_description_footer" name="universal_description_footer" class="md-input" ><?php echo $data->universal_description_footer?></textarea>
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="title_2_prompt" class="uk-form-label">Prompt for Title 2</label>
                                <textarea id="title_2_prompt" name="title_2_prompt" class="md-input" ><?php echo $data->title_2_prompt?></textarea>
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="youtube_seo_prompt" class="uk-form-label">Prompt for Youtube SEO</label>
                                <textarea id="youtube_seo_prompt" name="youtube_seo_prompt" class="md-input" ><?php echo $data->youtube_seo_prompt?></textarea>
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>

                    <div class="uk-grid">
                        <div class="uk-width-1-1">
                            <button type="submit" class="md-btn md-btn-primary">Update Configurations</button>
                        </div>
                    </div>
                </form>
                <br><br>

                <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-3">
                            <div class="parsley-row">
                                <label for="default_currency" class="uk-form-label">Default Currency<span class="req">*</span></label>
                                <select id="default_currency" name="default_currency" required data-parsley-required-message="This field is required."  data-md-selectize>
                                    <option value="">Default Currency*</option>
                                        <?php foreach ($currencies as $currency){ ?>
                                            <option <?php if($currency["id"] == $default_currency->id){ echo "selected"; }?> 
                                                value="<?php echo $currency["id"];?>" 
                                            >
                                                <?php echo $currency["code"];?>
                                            </option>
                                        <?php } ?>
                                </select>
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-6">
                            <div class="parsley-row">
                                <label for="usdtogbp">USD to GBP<span class="req">*</span></label>
                                <input type="number" step="0.01" name="usdtogbp" id="usdtogbp" data-parsley-pattern-message="Only number are allowed."
                                data-parsley-required-message="This field is required." required class="md-input" value="<?php echo $usdtogbp ?>" />
                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-6">
                            <div class="parsley-row">
                                <label for="gbptousd">GBP to USD<span class="req">*</span></label>
                                <input type="number" step="0.01" name="gbptousd" id="gbptousd" data-parsley-pattern-message="Only number are allowed."
                                data-parsley-required-message="This field is required." required class="md-input" value="<?php echo $gbptousd ?>" />
                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-6">
                            <button type="button" id="update_rates" class="md-btn md-btn-primary">Update Rates</button>
                        </div>
                    </div>
                    

            </div>
        </div>
    </div>
</div>

