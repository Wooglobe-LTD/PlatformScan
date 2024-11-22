<style>
textarea{
	scroll :none;
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
#video_url, #url, #email{
	word-break: break-all;
}
.staff_div {
    background: #117961;
    color: #fff;
    padding: 5px 10px;
}

.staff_div {
    background: #117961;
    color: #fff;
    padding: 5px 10px;
}

.staff_div_blue{
    background: #C8C8C8;
    color: #fff;
    padding: 5px 10px;
}
#save-url, #cancel-url,#save-title, #cancel-title {
    display: none;
}
a#video_url {
    color: #000;
    font-size: 13px;
}
a#url {
    color: #000;
    font-size: 13px;
}

</style>

<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span>Video Leads Management</span></li>
        </ul>
    </div>
<div id="page_content_inner">

        <h4 class="heading_a uk-margin-bottom"><?php echo $title;?>
            <div class="uk-clearfix" style="display:none;">
                <div class="uk-float-right">
                    <a class="md-btn md-btn-primary md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="<?php echo $url.'import_leads';?>">
                        <i class="material-icons md-24">import_export</i>
                        Leads Import From Zoho
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
                    <th data-name="action">Actions</th>
                    <th data-name="created_at">Created At</th>
                    <th data-name="first_name">Client Name</th>
                    <th data-name="party">Party Name</th>
                    <th data-name="email">Client Email</th>
                    <th data-name="video_title">Video Title</th>
                    <th data-name="unique_key">Unique Key</th>

                   <!-- <th data-name="video_url ">Video Url</th>-->


                </tr>
                </thead>

                <tfoot>
                <tr>
                    <th data-name="action">Actions</th>
                    <th data-name="created_at">Created At</th>
                    <th data-name="first_name">Client Name</th>
                    <th data-name="party">Party Name</th>
                    <th data-name="email">Client Email</th>
                    <th data-name="video_title">Video Title</th>
                    <th data-name="unique_key">Unique Key</th>



                    <!--<th data-name="video_url">Video Url</th>-->


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

    </div>
</div>
<div class="uk-modal" id="detial" >
    <div class="uk-modal-dialog" style="width: 60%;">



        <div class="uk-modal-header">
            <h3 class="uk-modal-title" style="display: inline-block">Video Lead Detail</h3>

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
                                <hr class="uk-grid-divider">
                                <div class="uk-grid uk-grid-small">
                                    <div class="uk-width-large-1-3">
                                        <span class="uk-text-muted uk-text-small">Shot the video yourself?</span>
                                    </div>
                                    <div class="uk-width-large-2-3" id="shotVideo">

                                    </div>
                                </div>
                                <hr class="uk-grid-divider">
                                <div class="uk-grid uk-grid-small">
                                    <div class="uk-width-large-1-3">
                                        <span class="uk-text-muted uk-text-small">Have the original unedited video and haven’t given it to anyone?

?</span>
                                    </div>
                                    <div class="uk-width-large-2-3" id="haveOrignalVideo">

                                    </div>
                                </div>


                                <hr class="uk-grid-divider uk-hidden-large">
                            </div>
                            <div class="uk-width-large-1-2">
                                <p>
                                    <span class="uk-text-muted uk-text-small uk-display-block uk-margin-small-bottom">Video Title</span>

                                    <span class="uk-badge uk-badge-success" id="video_title"></span>
                                    <span id="title_edit" class="md-list-heading title_edit_area" data-val=""></span>
                                    <button class="btn btn-info btn-edit" id="edit-title">
                                        <span class="glyphicon glyphicon-edit"></span><i
                                                class="material-icons">edit</i></button>
                                    <button class="btn btn-success btn-save"
                                            id="save-title"><span
                                                class="glyphicon glyphicon-save"></span><i
                                                class="material-icons">done</i></button>
                                    <button class="btn btn-success btn-cancel"
                                            id="cancel-title"><span
                                                class="glyphicon glyphicon-save"></span><i
                                                class="material-icons">close</i></button>

                                </p>
                                <hr class="uk-grid-divider">
                                <p>
                                    <span class="uk-text-muted uk-text-small uk-display-block uk-margin-small-bottom">Video URL</span>
                                    <span class="uk-badge uk-badge-success"><a id="video_url" href="" target="_blank"></a> </span>
                                    <span id="url_edit" class="md-list-heading url_edit_area" data-val=""></span>
                                    <button class="btn btn-info btn-edit" id="edit-url">
                                        <span class="glyphicon glyphicon-edit"></span><i
                                                class="material-icons">edit</i></button>
                                    <button class="btn btn-success btn-save"
                                            id="save-url"><span
                                                class="glyphicon glyphicon-save"></span><i
                                                class="material-icons">done</i></button>
                                    <button class="btn btn-success btn-cancel"
                                            id="cancel-url"><span
                                                class="glyphicon glyphicon-save"></span><i
                                                class="material-icons">close</i></button>

                                </p>
                                <p id="raw_video_url">
                                    <span class="uk-text-muted uk-text-small uk-display-block uk-margin-small-bottom">Raw Video URL</span>
                                    <span class="uk-badge uk-badge-success"><a id="url" target="_blank"></a> </span>
                                    <span id="url_edit" class="md-list-heading url_edit_area" data-val=""></span>
                                    <button class="btn btn-info btn-edit" id="edit-url">
                                        <span class="glyphicon glyphicon-edit"></span><i
                                                class="material-icons">edit</i></button>
                                    <button class="btn btn-success btn-save"
                                            id="save-url"><span
                                                class="glyphicon glyphicon-save"></span><i
                                                class="material-icons">done</i></button>
                                    <button class="btn btn-success btn-cancel"
                                            id="cancel-url"><span
                                                class="glyphicon glyphicon-save"></span><i
                                                class="material-icons">close</i></button>

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
                <?php if($assess['can_rate']){?>
                    <div class="md-card">
                    <div class="md-card-toolbar">
                        <h3 class="md-card-toolbar-heading-text">
                            Video Rating
                        </h3>
                    </div>
                    <div class="md-card-content large-padding">

                        <div class="uk-grid" data-uk-grid-margin>
                            <!--<div class="uk-width-medium-2-10">
                                <span class="uk-display-block uk-margin-small-top uk-text-large">Rate This Video</span>
                            </div>-->
                            <div class="uk-width-medium-10-10">
                                <form id="form_validation3" class="uk-form-stacked">
                                    <input type="hidden" name="id" value="" id="id" />
                                    <table class="uk-table">
                                    <thead>
                                    <tr>
                                        <th class="uk-width-1-3">Rating Point*</th>
                                        <th class="uk-width-1-1">Your Comments</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr >

                                        <td style="border-bottom: none;">
                                            <div class="parsley-row">
                                                <select  name="rating_point" id="rating" data-parsley-required-message="Rating point is required." required data-md-selectize>
                                                    <option value="">Choose..</option>
                                                    <?php
                                                    for($i=1; $i<=10;$i++){?>
                                                         <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="error"></div>
                                            </div>
                                        </td>
                                        <td style="border-bottom: none;">
                                            <div class="parsley-row">
                                                <textarea id="comments" name="rating_comments" style="" class="md-input textarea" rows="4"></textarea>
                                                <div class="error"></div>
                                            </div>

                                        </td>

                                    </tr>

                                    </tbody>
                                </table>
                                    <table class="uk-table" id="rating_detail_div" style="display: none;">
                                        <thead>
                                        <tr>
                                            <th class="uk-width-1-3">Deal Closing Date*</th>
                                            <th class="uk-width-1-1">Revenue Share - %*</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr style="border-bottom: none;">

                                            <td style="border-bottom: none;">


                                                <div class="parsley-row">
                                                    <div class="uk-input-group">
                                                        <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                                        <div class="md-input-wrapper">
                                                            <label for="closing">Select date</label>
                                                            <input class="md-input" id="closing" data-uk-datepicker="{format:'YYYY-MM-DD',minDate:'<?php echo date('Y-m-d',strtotime(date('Y-m-d').' +3 Day'));?>'}" type="text" name="closing" data-parsley-required-message="Deal Closing Date is required." value="<?php echo date('Y-m-d',strtotime(date('Y-m-d').' +3 Day'));?>" readonly>
                                                            <span class="md-input-bar "></span>
                                                        </div>
                                                        <div class="error"></div>
                                                    </div>
                                                </div>


                                            </td>
                                            <td style="border-bottom: none;">
                                                <div class="parsley-row">
                                                    <input id="revenue" name="revenue" class="md-input"
                                                           data-parsley-required-message="Revenue Share is required."
                                                           data-parsley-type="integer"
                                                           data-parsley-type-message="Please enter the valid value."
                                                           data-parsley-range="[0, 100]"
                                                           data-parsley-range-message="Revenue Share must be between
                                                           0 to 100."
                                                    />
                                                    <div class="error"></div>
                                                </div>

                                            </td>

                                        </tr>

                                        </tbody>
                                    </table>
                                    <div class="uk-grid" style="float: right;">

                                        <div class="uk-width-1-1">
                                            <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
                                            <button type="submit" class="md-btn md-btn-primary">Rate It</button>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>

    </div>
</div>
