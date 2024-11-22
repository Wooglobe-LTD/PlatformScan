<style>
textarea{
    scroll :none;
}

    .selectize-input{
        border-width : 0 0 0px !important;
    }

</style>

<div id="page_content">
<div id="page_content_inner">

    



        <h4 class="heading_a uk-margin-bottom"><?php echo $title;?>
            <div class="uk-clearfix">
                <div class="uk-float-right">
                    <a class="md-btn md-btn-primary md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="<?php echo $url.'import_leads';?>">
                        <i class="material-icons md-24">import_export</i>
                        Deals Import From Zoho
                    </a>
                </div>
            </div>
        </h4>


    <div class="md-card uk-margin-medium-bottom">
        <div class="md-card-content">
            <div class="dt_colVis_buttons"></div>
            <table id="dt_tableExport" class="uk-table" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th data-name="first_name">Client Name</th>
                    <th data-name="email">Client Email</th>
                    <th data-name="video_title">Video Title</th>

                    <th data-name="action">Actions</th>

                </tr>
                </thead>

                <tfoot>
                <tr>
                    <th data-name="first_name">Client Name</th>
                    <th data-name="email">Client Email</th>
                    <th data-name="video_title">Video Title</th>
                    <th data-name="action">Actions</th>

                </tr>
                </tfoot>

                <tbody>
               
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>


<div class="uk-modal" id="play_model">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h3 class="uk-modal-title" id="vt"></h3>
        </div>
        <div class="md-card-content large-padding" id="play">

        </div>
        <div class="uk-modal-footer uk-text-right">
            <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
        </div>
    </div>
</div>
<div class="uk-modal" id="detial" >
    <div class="uk-modal-dialog" style="width: 70%;">
        <div class="uk-modal-header">
            <h3 class="uk-modal-title" >Video Deal Detail</h3>
        </div>
        <div class="md-card-content large-padding" >
            <div class="uk-width-xLarge-8-12  uk-width-large-7-12">
                <div class="md-card">
                    <div class="md-card-toolbar">
                        <h3 class="md-card-toolbar-heading-text">
                            Client Details
                        </h3>
                    </div>
                    <div class="md-card-content large-padding">
                        <div class="uk-grid uk-grid-divider uk-grid-medium">
                            <div class="uk-width-large-1-2">
                                <div class="uk-grid uk-grid-small">
                                    <div class="uk-width-large-1-3">
                                        <span class="uk-text-muted uk-text-small">Client First Name</span>
                                    </div>
                                    <div class="uk-width-large-2-3">
                                        <span class="uk-text-large uk-text-middle" id="first_name"></span>
                                    </div>
                                </div>
                                <hr class="uk-grid-divider">
                                <div class="uk-grid uk-grid-small">
                                    <div class="uk-width-large-1-3">
                                        <span class="uk-text-muted uk-text-small">Client Last Name</span>
                                    </div>
                                    <div class="uk-width-large-2-3">
                                        <span class="uk-text-large uk-text-middle" id="last_name"></span>
                                    </div>
                                </div>
                                <hr class="uk-grid-divider">
                                <div class="uk-grid uk-grid-small">
                                    <div class="uk-width-large-1-3">
                                        <span class="uk-text-muted uk-text-small">Client Email</span>
                                    </div>
                                    <div class="uk-width-large-2-3" id="email">

                                    </div>
                                </div>


                                <hr class="uk-grid-divider uk-hidden-large">
                            </div>
                            <div class="uk-width-large-1-2">
                                <p>
                                    <span class="uk-text-muted uk-text-small uk-display-block uk-margin-small-bottom">Video Title</span>

                                    <span class="uk-badge uk-badge-success" id="video_title"></span>

                                </p>
                                <hr class="uk-grid-divider">
                                <p>
                                    <span class="uk-text-muted uk-text-small uk-display-block uk-margin-small-bottom">Video URL</span>

                                    <span class="uk-badge uk-badge-success"><a id="video_url" href="" target="_blank"></a> </span>

                                </p>
                                <hr class="uk-grid-divider">
                                <p>
                                    <span class="uk-text-muted uk-text-small uk-display-block uk-margin-small-bottom">Message</span>
                                    <p id="message"></p>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php //if($assess['can_rate']){?>
                    <div class="md-card">
                    <div class="md-card-toolbar">
                        <h3 class="md-card-toolbar-heading-text">
                            Video Rating
                        </h3>
                    </div>
                    <div class="md-card-content large-padding">

                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-2-10">
                                <span class="uk-display-block uk-margin-small-top uk-text-large">Rate This Video</span>
                            </div>
                            <div class="uk-width-medium-8-10">
                                <form id="form_validation3" class="uk-form-stacked">
                                    <input type="hidden" name="id" value="" id="id" />
                                    <table class="uk-table">
                                    <thead>
                                    <tr>
                                        <th class="uk-width-1-4">Rating Point</th>
                                        <th class="uk-width-1-4">Comments</th>
                                        <th class="uk-width-1-4">Revenue Share - %</th>
                                        <th class="uk-width-1-4">Deal Closing Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr >

                                        <td style="border-bottom: none;">
                                            <span class="uk-text-large uk-text-middle" id="rating_point"></span>
                                        </td>
                                        <td style="border-bottom: none;">
                                            <span class="uk-text-large uk-text-middle" id="rating_comments"></span>
                                        </td>
                                        <td style="border-bottom: none;">
                                            <span class="uk-text-large uk-text-middle" id="revenue_share"></span>
                                        </td>
                                        <td style="border-bottom: none;">
                                            <span class="uk-text-large uk-text-middle" id="closing_date"></span>
                                        </td>


                                    </tr>

                                    </tbody>
                                </table>

                                    <!--<div class="uk-grid" style="float: right;">
                                        <div class="uk-width-1-1">
                                            <button type="submit" class="md-btn md-btn-primary">Rate It</button>
                                        </div>
                                    </div>-->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php //} ?>
            </div>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
        </div>
    </div>
</div>
