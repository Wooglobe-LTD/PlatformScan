<style>
    textarea{
        scroll :none;
    }

</style>

<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span>Not Interested Management</li>
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
                        <th data-name="unblockaction">Actions</th>
                        <th data-name="first_name">First Name</th>
                        <th data-name="unique_key">Unique Key</th>
                        <th data-name="video_url">Video Url</th>
                        <th data-name="mobile">Video Title</th>

                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th data-name="unblockaction">Actions</th>
                        <th data-name="first_name">First Name</th>
                        <th data-name="unique_key">Unique Key</th>
                        <th data-name="video_url">Video Url</th>
                        <th data-name="video_title">Video Title</th>


                    </tr>
                    </tfoot>

                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>