<style>
    textarea{
        scroll :none;
    }

</style>

<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span>Close Won Management</li>
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
                        <th data-name="unique_key">Unique Key</th>
                        <th data-name="created_at">Received at</th>
                        <th data-name="video_url">Video Url</th>
                        <th data-name="vl.status">Status</th>
                        <th data-name="video_title">Video Title</th>
                        <th data-name="email">Email</th>
                        <th data-name="published_yt">Publish Youtube</th>
                        <th data-name="youtube_id">Youtube id</th>
                        <th data-name="published_fb">Publish Faceebook</th>
                        <th data-name="facebook_id">Facebook id</th>

                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th data-name="unique_key">Unique Key</th>
                        <th data-name="created_at">Received at</th>
                        <th data-name="video_url">Video Url</th>
                        <th data-name="vl.status">Status</th>
                        <th data-name="video_title">Video Title</th>
                        <th data-name="email">Email</th>
                        <th data-name="published_yt">Publish Youtube</th>
                        <th data-name="youtube_id">Youtube id</th>
                        <th data-name="published_fb">Publish Faceebook</th>
                        <th data-name="facebook_id">Facebook id</th>
                    </tr>
                    </tfoot>

                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>