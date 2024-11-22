<style>
    .dataTables_wrapper .uk-overflow-container table#feeds-list-table td, .dataTables_wrapper .uk-overflow-container table#feeds-list-table th {
        white-space: normal !important;
    }
</style>
<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span>WooGlobe Video Feed Preview</span></li>
        </ul>
    </div>
    <div id="page_content_inner">
        <div class="mrss-area">
            <div style="width: 100%">
                <div style="float: left; width: 70%">
                    <h1><?php echo $title;?> Feed</h1>
                    <?php if($partner_name){
                        echo 'Partner Name:<b>'.$partner_name.'</b>';
                    }else{
                        echo "Category Feed";
                    }?>
                </div>
                <div style="float: right">
                    <?php
                    $button_text = 'Unpublish Feed';
                    $button_class = 'danger';
                    if($feed_status == 0){
                        $button_text = 'Publish Feed';
                        $button_class = 'primary';
                    }
                    ?>
                    <button class="uk-button uk-button-<?php echo $button_class;?> publish-feed" data-fid="<?php echo $feed_id;?>" data-status="<?php echo $feed_status;?>"><?php echo $button_text;?></button>
                </div>
                <div style="clear: both"></div>
            </div>


            <div class="uk-width-1-4">
                <div class="uk-width-medium-1-1" style="padding: 15px 0px;">
                    <label for="mrss" class="inline-label"><b>Select video(s) to add in feed</b>
                    </label>
                </div>
            </div>
            <div class="uk-width-2-4" id="mrss_id" style="display:block;width:100%">
                <div class="parsley-row" data-uk-grid-margin style="width: 50%; float:left">

                    <select id="feed_videos" name="feed_videos[]" class="selectize-control" multiple>
                        <?php foreach($videos->result() as $video){
                            ?>
                            <option value="<?php echo $video->id;?>"><?php echo $video->unique_key.' - '.$video->title;?></option>
                        <?php } ?>

                    </select>

                    <div class="error"></div>
                </div>
                <div style="width: 28%; float:left; margin-left: 50px">
                    <button class="uk-button uk-button-primary add-to-feed" data-fid="<?php echo $feed_id;?>">Add selected videos to feed</button>
                </div>
                <div style="clear: both"></div>
            </div>


        <hr>
        <br>
        <?php
        $html = '';
        $table_s = '<table id="feeds-list-table" style="width:100%"><thead><th>Unique ID</th><th>Title</th><th>Feeds</th></thead><tbody>';

            foreach($feed as $item){
                $query_edited = 'SELECT ev.portal_url,ev.portal_thumb
                            FROM videos v
                            INNER JOIN edited_video ev
                            ON ev.video_id = v.id
                            AND ev.video_id = '.$item->id;

                $videos_edited = $this->db->query($query_edited);
                $vid=$videos_edited->result();
                $youtube_id = trim($item->youtube_id);
                $videourl = trim($item->youtube_id);
                $thumb='';
                if(isset($vid[0])){
                    $thumb=$vid[0]->portal_thumb;
                }
                if($thumb){
                    $thumb=$thumb;
                }else{
                    $thumb='https://img.youtube.com/vi/'.$videourl.'/hqdefault.jpg';
                }
                if($videourl){
                    $videourl="https://www.youtube.com/watch?v=$videourl";
                }else{
                    if(isset($vid[0])) {
                        $videourl = $vid[0]->portal_url;
                    }
                }
                //$ext = explode('.', $item->portal_url);
                $html .= '<tr><td>'.$item->unique_key.'</td><th>'.$item->title.'</th>
                    <td>
                        <div class="video-feed-card">
                            <a href="' . $videourl . '" target="_blank">
                                <div class="video-feed-card-thumb">
                                    <div class="video-thumb"><img src="' . $thumb . '" class="img-responsive"></div>
                                </div>
                                <div class="video-feed-card-body" style="max-width:75%">
                                    <div class="video-feed-content">
                                        <h4 class="video-feed-title">' . $item->title . '</h4>
                                        <div class="video-unique-id">'.$item->unique_key.'</div>
                                        <div class="video-feed_time">' . date('d M, Y', strtotime($item->created_at)) . '</div>
                                        <div class="video-feed-desc">' . $item->description . '</div>
                                        <div class="video-feed-tags">' . $item->tags . '</div>
                                    </div>
                                </div>
                            </a>
                            <a href="'.base_url().'deal-detail/'.$item->lead_id.'"><button class="uk-button" style="position: absolute; right: 50px;top: 10px; border-radius: 50%; width: 30px; height: 30px; padding: 0;"><i class="material-icons">create</i></button></a>
                            <button class="uk-button uk-button-danger remove-from-feed" data-vid="'.$item->id.'" data-fid="'.$item->feed_id.'"><i class="material-icons">close</i></button>
                        </div>
                    </td>
                </tr>';
            }

            if (!empty($html)) {
                $html = $table_s.$html.'</tbody></table>';
            }
            echo $html;
            if($html == ''){
                echo '<h1>No videos available for this feed.</h1>';
            }
            ?>
        </div>
    </div>
</div>