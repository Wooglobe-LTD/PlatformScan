<style>
textarea{
    scroll :none;
}

</style>

<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span>Content Management</span></li>
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
                    <?php if($assess['can_edit'] || $assess['can_delete']) { ?>
                        <th data-name="c.action">Actions</th>
                    <?php } ?>
                    <th data-name="c.title">Page Title</th>
                    <th data-name="c.status">Status</th>

                </tr>
                </thead>

                <tfoot>
                <tr>
                    <?php if($assess['can_edit'] || $assess['can_delete']) { ?>
                        <th data-name="c.action">Actions</th>
                    <?php } ?>
                    <th data-name="c.title">Video Title</th>
                    <th data-name="c.status">Status</th>

                </tr>
                </tfoot>

                <tbody>
               
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>
<?php if($assess['can_add']) { ?>
<div class="md-fab-wrapper">
        <a title="Add New Page" class="md-fab md-fab-accent md-fab-wave waves-effect waves-button" href="<?php $url;?>content_add">
        <i class="material-icons">&#xE145;</i>
        </a>
    </div>
<?php } ?>
    <script>
        var ukey = "";
    </script>
