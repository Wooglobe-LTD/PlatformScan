<style>
textarea{
    scroll :none;
}

</style>

<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span>Compilations Video Search</span></li>
        </ul>
    </div>
<div id="page_content_inner">

    

    <h4 class="heading_a uk-margin-bottom"><?php echo $title;?></h4>
    <div class="md-card uk-margin-medium-bottom">
        <div class="md-card-content" style="padding: 15px;">
            <form id="compilation_search" class="uk-form-stacked" method="post" action="<?php echo base_url('video_compilation_search'); ?>">
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <label for="title">Video Compilation Search<span class="req">*</span></label>
                            <input type="text" data-parsley-required-message="This field is required." name="search" id="compilation_search" required class="md-input" value="<?php echo $key;?>" />
                            <div class="error"></div>
                        </div>
                    </div>
                    <div class="uk-width-medium-1-1">
                        <div class="parsley-row">
                            <button type="submit" class="md-btn md-btn-primary check">Search</button>
                        </div>
                    </div>

                </div>

            </form>

        </div>
    </div>
    <div class="md-card uk-margin-medium-bottom">
        <div class="md-card-content">
            <div class="dt_colVis_buttons"></div>
            <table class="uk-table" cellspacing="0" width="100%">
                <thead>
                <tr>

                    <th>Title</th>
                    <th>URL</th>
                    <th>WG ID</th>

                </tr>
                </thead>

                <tfoot>
                <tr>

                    <th>Title</th>
                    <th>URL</th>
                    <th>WG ID</th>

                </tr>
                </tfoot>

                <tbody>
                <?php if($items->num_rows() > 0){
                    foreach ($items->result() as $row){
                    ?>
                    <tr>
                        <td><a href="<?php echo base_url('compilation_detail/'.$row->id);?>" target="_blank"> <?php echo $row->title;?></a></td>
                        <td><a href="<?php echo $row->url;?>" target="_blank"><?php echo $row->url;?></a></td>
                        <td><?php echo $row->unique_key;?></td>
                    </tr>
                <?php
                    }
                    }else{ ?>
                <tr>
                    <td colspan="3" style="text-align: center;">No record found</td>
                </tr>
                <?php } ?>
               
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>
