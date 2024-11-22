<style>
textarea{
    scroll :none;
}

    .selectize-input{
        border-width : 0 0 0px !important;
    }
    .textarea{

    }
</style>

<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span>Video License Management</span></li>
        </ul>
    </div>
<div id="page_content_inner">

    

    <h4 class="heading_a uk-margin-bottom"><?php echo $title;?></h4>
    <div class="md-card uk-margin-medium-bottom">
        <div class="md-card-content">
            <div class="dt_colVis_buttons"></div>
            <table id="dt_tableExport" class="uk-table" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th data-name="vl.action">Actions</th>
                    <th data-name="vl.mobile">Partner Mobile</th>
                    <th data-name="vl.email">Partner Email</th>
                    <th data-name="v.title">Video Title</th>
                    <th data-name="lt.type">Video License</th>


                </tr>
                </thead>

                <tfoot>
                <tr>
                    <th data-name="vl.action">Actions</th>
                    <th data-name="u.full_name">Partner Name</th>
                    <th data-name="u.email">Partner Email</th>
                    <th data-name="v.title">Video Title</th>
                    <th data-name="lt.type">Video License</th>

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
            <h3 class="uk-modal-title" >Video Lincense Detail</h3>
        </div>
        <div class="md-card-content large-padding" >
            <div class="uk-width-xLarge-8-12  uk-width-large-7-12">
                <div class="md-card">
                    <div class="md-card-toolbar">
                        <h3 class="md-card-toolbar-heading-text">
                            Partner Details
                        </h3>
                    </div>
                    <div class="md-card-content large-padding">
                        <div class="uk-grid uk-grid-divider uk-grid-medium">
                            <div class="uk-width-large-1-2">
                                <div class="uk-grid uk-grid-small">
                                    <div class="uk-width-large-1-3">
                                        <span class="uk-text-muted uk-text-small">Partner Mobile Number</span>
                                    </div>
                                    <div class="uk-width-large-2-3">
                                        <span class="uk-text-large uk-text-middle" id="mobile"></span>
                                    </div>
                                </div>
                                <hr class="uk-grid-divider">
                                <div class="uk-grid uk-grid-small">
                                    <div class="uk-width-large-1-3">
                                        <span class="uk-text-muted uk-text-small">Partner Email</span>
                                    </div>
                                    <div class="uk-width-large-2-3">
                                        <span class="uk-text-large uk-text-middle" id="email"></span>
                                    </div>
                                </div>



                                <hr class="uk-grid-divider uk-hidden-large">
                            </div>
                            <div class="uk-width-large-1-2">
                                <p>
                                    <span class="uk-text-muted uk-text-small uk-display-block uk-margin-small-bottom">Video Title</span>

                                    <span class="uk-badge uk-badge-success" id="title"></span>

                                </p>
                                <hr class="uk-grid-divider">
                                <p>
                                    <span class="uk-text-muted uk-text-small uk-display-block uk-margin-small-bottom">Video</span>

                                    <span <a id="video_id" data-id=""  href="javascript:void(0);" class="play-video"><i class="material-icons">&#xE04A;</i></a> </span>

                                </p>
                                <hr class="uk-grid-divider">
                                <p>
                                    <span class="uk-text-muted uk-text-small uk-display-block uk-margin-small-bottom">Description</span>
                                    <p id="vdescription"></p>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                    <div class="md-card">
                    <div class="md-card-toolbar">
                        <h3 class="md-card-toolbar-heading-text">
                            Video License Detail
                        </h3>
                    </div>
                    <div class="md-card-content large-padding">
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-width-medium-4-10">
                                        <span class="uk-display-block uk-margin-small-top uk-text-large">Territory</span>
                                    </div>
                                    <div class="uk-width-medium-6-10">
                                        <span class="uk-display-block uk-margin-small-top" id="territory"></span>
                                    </div>
                                </div>
                                <div class="uk-grid" data-uk-grid-margin id="country-div1" style="display: none;">
                                    <div class="uk-width-medium-4-10">
                                        <span class="uk-display-block uk-margin-small-top uk-text-large">Country</span>
                                    </div>
                                    <div class="uk-width-medium-6-10">
                                        <span class="uk-display-block uk-margin-small-top" id="name"></span>
                                    </div>
                                </div>
                                <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-width-medium-4-10">
                                        <span class="uk-display-block uk-margin-small-top uk-text-large">Duration</span>
                                    </div>
                                    <div class="uk-width-medium-6-10">
                                        <span class="uk-display-block uk-margin-small-top" id="duration"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="uk-width-medium-1-2">
                                <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-width-medium-4-10">
                                        <span class="uk-display-block uk-margin-small-top uk-text-large">Programme / Publication</span>
                                    </div>
                                    <div class="uk-width-medium-6-10">
                                        <span class="uk-display-block uk-margin-small-top" id="programme_or_publication"></span>
                                    </div>
                                </div>
                                <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-width-medium-4-10">
                                        <span class="uk-display-block uk-margin-small-top uk-text-large">Exclusivity</span>
                                    </div>
                                    <div class="uk-width-medium-6-10">
                                        <span class="uk-display-block uk-margin-small-top" id="exclusivity"></span>
                                    </div>
                                </div>
                                <div class="uk-grid" data-uk-grid-margin id="surl-div">
                                    <div class="uk-width-medium-4-10">
                                        <span class="uk-display-block uk-margin-small-top uk-text-large">Media</span>
                                    </div>
                                    <div class="uk-width-medium-6-10">
                                        <span class="uk-display-block uk-margin-small-top" id="type"></span>
                                    </div>

                                </div>
                            </div>
                        </div>





                    </div>
                </div>

            </div>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
        </div>
    </div>
</div>
<script>
    var root ='<?php echo $root;?>';
</script>