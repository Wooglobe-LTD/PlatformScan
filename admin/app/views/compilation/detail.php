<?php
/**
 * Created by PhpStorm.
 * User: T3500
 * Date: 4/24/2018
 * Time: 11:40 AM
 */
?>
<style>

</style>
<style type="text/css">
    .timeline-process ol {
        position: relative;
        display: inline-block;
        margin: 0px;
        height: 4px;
        background: #ffffff;
        padding: 0px;
        width: 100%;
    }

    .timeline-process {
        width: 160%;
        float: left;
        height: auto;
        margin: 30px 1% 115px;
    }

    /* ---- Timeline elements ---- */
    .timeline-process li {
        position: relative;
        display: inline-block;
        float: left;
        width: 8.33%;
        font: bold 14px arial;
        height: 10px;
    }

    .timeline-process li .diplome {
        position: absolute;
        top: -47px;
        left: 36%;
        color: #000000;
    }

    .timeline-process li .point {
        content: "";
        top: -10px;
        left: 50%;
        margin-left: -11px;
        display: block;
        width: 17px;
        height: 17px;
        border: 3px solid #ffffff;
        border-radius: 50px;
        background: #1976d2;
        position: absolute;
    }

    .timeline-process li.active .point {
        background-color: #FFF !important;
    }

    .timeline-process ol:after {
        content: '';
        width: 4px;
        height: 15px;
        background-color: #fff;
        position: absolute;
        top: -5px;
        left: 0px;
    }

    .timeline-process ol:before {
        content: '';
        width: 4px;
        height: 15px;
        background-color: #fff;
        position: absolute;
        top: -5px;
        right: 0px;
    }

    .description::before {
        content: '';
        width: 0;
        height: 0;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-bottom: 5px solid #f4f4f4;
        position: absolute;
        top: -5px;
        left: 43%;
    }

    .timeline-process li .description {
        display: none;
        background-color: #f4f4f4;
        padding: 10px;
        margin-top: 25px;
        position: relative !important;
        font-weight: 600;
        z-index: 1;
        font-size: 12px;
        left: 50%;
        margin-left: -72px;
        width: 135px;
        text-align: center;
        border-radius: 5px;
        color: #444;
    }

    .active.last .description {
        display: block !important;
    }

    .timeline-process li:nth-child(2n) .description {
        /* margin-top: -71px !important; */
        bottom: 85px !important;
        /*margin-left: -79px !important;*/
    }

    .timeline-process li:nth-child(2n) .description::before {
        width: 0;
        height: 0;
        border-left: 5px solid transparent !important;
        border-right: 5px solid transparent !important;
        border-top: 5px solid #f4f4f4;
        border-bottom: unset !important;
        top: unset !important;
        bottom: -5px;
    }

    /* ---- Hover effects ---- */
    .timeline-process li:hover {
        cursor: pointer;
        color: #48A4D2;
    }

    .timeline-process li:hover .description {
        display: block;
    }
</style>
<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url; ?>">Dashboard</a></li>
            <li><a href="<?php echo $url; ?>compilations">Compilation Management</a></li>
            <li><span>Compilation Detail</span></li>
        </ul>
    </div>
    <div id="page_content_inner">
        <div class="uk-grid" data-uk-grid-margin="">
            <div class="uk-width-medium-1-2 uk-row-first">
                <h4 class="heading_c uk-margin-small-bottom">Compilation Detail</h4>
                <ul class="md-list">
                    <li>
                        <div class="md-list-content">
                            <span class="uk-text-small uk-text-muted">Title</span>
                            <span id="edit-title-area" class="md-list-heading"
                                  data-val="<?php echo $data->title; ?>"><?php echo $data->title; ?></span>
                        </div>
                    </li>
                    <li>
                        <div class="md-list-content">
                            <span class="uk-text-small uk-text-muted">URL</span>
                            <span id="edit-title-area" class="md-list-heading"
                                  data-val=""><a href="<?php echo $data->url ?>" target="_blank"><?php echo $data->url ?></a> </span>
                        </div>
                    </li>

                    <li>
                        <div class="md-list-content">
                            <span class="uk-text-small uk-text-muted">Created Date</span>
                            <span class="md-list-heading"><?php echo $data->created_at; ?></span>
                        </div>
                    </li>




                </ul>







                    <!--<li>
                        <div class="md-list-content">
                            <span class="uk-text-small uk-text-muted">Scout Name</span>
                            <span id=""
                                  class="md-list-heading rating-comments-area"
                                  ></span>

                        </div>
                    </li>-->
                </ul>
            </div>
            <div class="uk-width-medium-1-2 uk-row-first">
                <form id="form_validation4">
                    <input type="hidden" id="compilation_id" name="compilation_id" value="<?php echo $data->id;?>">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="title">Use Title<span class="req">*</span></label>
                                <input type="text" data-parsley-required-message="This field is required." name="title" id="title" required class="md-input" />
                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="url">Use URL<span class="req">*</span></label>
                                <input type="url" data-parsley-required-message="This field is required." name="url" id="url" required class="md-input" data-parsley-type-message="Please enter the valid URL."/>
                                <div class="error"></div>
                            </div>
                        </div>


                    </div>
                    <ul class="md-list">


                    <li>
                        <div class="md-list-content">

                            <span class="md-list-heading">

                                <div class="uk-width-1-1">
                                <button type="button" class="md-btn md-btn-primary" id="add_use" style="float: right;">Add Use</button>
                            </div>
                            </span>
                        </div>
                    </li>

                </ul>
                </form>
            </div>
        </div>
        <div class="uk-grid" data-uk-grid-margin="">
            <div class="uk-width-medium-1-1 uk-row-first">
                <h2>Videos</h2>
                <div class="md-card">

                    <div class="md-card-content">
                    <div class="dt_colVis_buttons"></div>
                    <table id="" class="uk-table" width="100%" cellspacing="0">
                        <thead>
                        <tr style="line-height: 40px;">

                            <th data-name="" style="text-align: left;">WGA ID</th>
                            <th data-name="" style="text-align: left;">Video Title</th>
                            <th data-name="" style="text-align: left;">Video Email</th>
                            <th data-name="" style="text-align: left;">Name</th>



                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($leads->result() as $lead) { ?>
                            <tr>
                                <td><?php echo $lead->wg_id;?></td>
                                <td><?php echo $lead->title;?></td>
                                <td><?php echo $lead->email;?></td>
                                <td><?php echo $lead->first_name;?> <?php echo $lead->last_name;?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                        <tfoot>
                        <tr style="line-height: 40px;">
                            <th data-name="" style="text-align: left;">WGA ID</th>
                            <th data-name="" style="text-align: left;">Video Title</th>
                            <th data-name="" style="text-align: left;">Video Email</th>
                            <th data-name="" style="text-align: left;">Name</th>

                        </tr>
                        </tfoot>




                    </table>
                </div>
                </div>
            </div>
        </div>
        <div class="uk-grid" data-uk-grid-margin="">
            <div class="uk-width-medium-1-1 uk-row-first">
                <h2>Compilation Use</h2>
                <div class="md-card">

                    <div class="md-card-content">
                        <div class="dt_colVis_buttons"></div>
                        <table id="" class="uk-table" width="100%" cellspacing="0">
                            <thead>
                            <tr style="line-height: 40px;">

                                <th data-name="" style="text-align: left;">Title</th>
                                <th data-name="" style="text-align: left;">URL</th>



                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($uses->result() as $use) { ?>
                                <tr>
                                    <td><?php echo $use->title;?></td>
                                    <td><a href="<?php echo $use->url;?>" target="_blank"><?php echo $use->url;?></a> </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                            <tfoot>
                            <tr style="line-height: 40px;">
                                <th data-name="" style="text-align: left;">Title</th>
                                <th data-name="" style="text-align: left;">URL</th>

                            </tr>
                            </tfoot>




                        </table>
                    </div>
                </div>
            </div>
        </div>
  </div>

</div>

