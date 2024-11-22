<style>
	.new-md-card-content {
		width: 100%;
		box-sizing: border-box;
		display: inline-block;
		padding: 10px 10px 0px !important;
	}
	.new-md-card-content .md-list>li {
		padding: 5px 5px;
		margin: 0;
		list-style: none;
		border: 0px solid #eee;
		display: block;
		height: auto;
		float: left;
		width: 100%;
	}
	.md-card-head-avatar {
		width: 130px !important;

		border-radius: 0px !important;
		margin-top: 0px !important;
	}
	.thumbnail_sec{
		float: left;
		margin-right: 20px;
	}
	.thumbnail_sec .uk-margin-medium-top {
		margin-top: 0px!important;
	}
	.uk-grid-medium+.uk-grid-medium, .uk-grid-medium>*>.uk-panel+.uk-panel, .uk-grid-medium>.uk-grid-margin {
		margin-top: 1px;
	}
	.title_sec{
		float: left;
		width: 75%;
		padding-top: 10px;
	}
	.date_sec{
		float: right;
	}
	.uk-margin-medium-top {
		margin-top: 5px!important;
	}
</style>
<div id="page_content">
    <div id="page_content_inner">
        <?php 
        $array = array_map('json_encode', $videos['videos']);
        $array = array_unique($array);
        $array = array_map('json_decode', $array);
        foreach ($array as $video){?>
            <?php
            /*if($video->thumbnail != ''){
                if($video->bulk == 1){
                    $thumb = $video->thumbnail;
                }else{
                    $thumb = $url . $video->thumbnail;
                }
            }else{
                $thumb = "https://i.vimeocdn.com/video/614933078.jpg?mw=960&mh=540";
            }*/
            $thumb = "https://i.vimeocdn.com/video/614933078.jpg?mw=960&mh=540";


            /*if($video->bulk == 1){*/
                if(isset($video->thumbnail)){
                    $thumb = $video->thumbnail;
                }
                if (!empty($video->youtube_id)){
                    $thumb = 'https://img.youtube.com/vi/'.$video->youtube_id.'/hqdefault.jpg';
                }else{
                     $thumb = "https://i.vimeocdn.com/video/614933078.jpg?mw=960&mh=540";
                }
            /*}*/


            ?>
        <div class="uk-grid uk-grid-width-xlarge-1-1 uk-grid-medium listNavWrapper"  data-uk-grid-match="{target:'.md-card'}">
            <div class="uk-margin-medium-top">
                <div class="md-card md-card-hover">

                    <div class="md-card-content new-md-card-content">
                        <ul class="md-list">
                            <li>
                                <div class="thumbnail_sec">
                                    <a href="<?php echo $url?>deal-detail/<?php echo $video->lead_id;?>">
                                    <img class="md-card-head-avatar" src="<?php echo $thumb;?>" alt=""/>
                                    </a>
                                </div>
                                <div class="title_sec">
                                    <!--<span class="md-list-heading">Title</span>-->
                                    <span class="uk-text-small uk-text-muted">
                                            <a href="<?php echo $url?>deal-detail/<?php echo $video->lead_id;?>">
                                                <b><?php echo ucwords($video->title);?></a></b>
                                            </a>
                                            </span>
                                </div>
                                <!--<div class="date_sec">
                                    <span class="uk-text-small uk-text-muted">20-12-2018</span>
                                </div>-->
                            </li>



                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>

    </div>
</div>
