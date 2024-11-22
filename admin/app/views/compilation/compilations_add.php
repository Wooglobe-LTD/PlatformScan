<style>
    textarea{
        scroll :none;
        resize: none !important;
    }
    .selectize-input{
        border-width : 0 0 0px !important;
    }
    .selectize-dropdown {
        margin-top: 0px !important;
    }
    *+.uk-table {
        margin-top: 0px !important;
    }
    #video_url, #email{
        word-break: break-all;
    }
    .get_thumb{
        width: 100%;
        float: left;
        text-align: center;
    }
</style>
<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span>Compilations Management</span></li>
        </ul>
    </div>
    <div id="page_content_inner">
        <div class="md-card uk-margin-medium-bottom">

            <div class="md-card-content" style="padding: 20px;">

                <div>

                    <h2>Add  Compilations</h2>

                    <br/>
                    <form id="form_validation2" class="uk-form-stacked" action="<?php echo base_url('compilations_urls');?>" method="post">


                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <div class="parsley-row">
                                    <label for="title">Compilation Name<span class="req">*</span></label>
                                    <input type="text" data-parsley-required-message="This field is required." name="title" id="title" required class="md-input" />
                                    <div class="error"></div>
                                </div>
                            </div>

                            <div class="uk-width-medium-1-2">
                                <div class="parsley-row">
                                    <label for="url">Compilation Link</label>
                                    <input type="url" data-parsley-required-message="This field is required." name="url" id="url" class="md-input" data-parsley-type-message="Please enter the valid URL."/>
                                    <div class="error"></div>
                                </div>
                            </div>


                        </div>


                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="videos">WooGlobe Video IDs <span class="req">*</span></label>
                                    <textarea name="wg_ids" id="wg_ids" required data-parsley-required-message="This field is required." class="md-input no_autosize"></textarea>

                                    <div class="error"></div>
                                </div>
                            </div>

                            <!--<div class="uk-width-medium-1-2">
                                <div class="parsley-row">
                                    <label for="url">Compilation Link Platform<span class="req">*</span></label>
                                    <input type="text" data-parsley-required-message="This field is required." name="plateforms[]" id="plateforms" required class="md-input" data-parsley-type-message="Please enter the valid URL."/>
                                    <div class="error"></div>
                                </div>
                            </div>
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <label for="url">Published Date<span class="req">*</span></label>
                                        <input type="text" data-parsley-required-message="This field is required." name="date" id="date" required class="md-input" data-parsley-type-message="Please enter the valid URL."/>
                                        <div class="error"></div>
                                    </div>
                                </div>
                            </div>-->
                        </div>


                </div>


                <br/>

                <div class="uk-grid">
                    <div class="uk-width-1-1">
                        <button type="submit" class="md-btn md-btn-primary check">Submit</button>
                    </div>
                </div>
                </form>
            </div>

        </div>
    </div>
</div>