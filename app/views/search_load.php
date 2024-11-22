
<?php
$i = 1;
?>
<?php

if(count($videos['videos']) > 0){

    foreach ($videos['videos'] as $video){
      if ($i == 1 || (($i % 3) == 1)) {
          ?>
          <div class="row">
      <?php } ?>
        <div class="col-md-4 margin-bottom col-sm-4">


            <article class="post type-post">
                <div class="entry-thumbnail">
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
                        $thumb = $video->thumbnail;
                        $thumb = 'https://img.youtube.com/vi/'.$video->youtube_id.'/hqdefault.jpg';
                    $slug=$video->slug;
                    if($slug == 0){
                        $string = strtolower($slug);
                        $slug = preg_replace("/[\s_]/", "-", $string);
                    }
                   /* }*/
                    ?>
                    <a href="<?php echo $url?>?video=<?php echo $slug;?>">
                        <img src="<?php echo $thumb;?>" alt="Video Thumbnail">

                        <span class="play-video"><i class="fa fa-play-circle-o"></i></span>
                    </a>
                </div><!-- /.entry-thumbnail -->
                <div class="entry-content">
                    <h4 class="entry-title"><a href="javascript:void(0);"><?php echo ucwords($video->title);?></a></h4><!-- /.entry-title -->
                    <!-- <div class="entry-meta">
                         <span><a href="#"><i class="fa fa-comment-o"></i> <span class="count">351</span></a></span>
                         <span><i class="fa fa-eye"></i> <span class="count">17,989</span></span>
                     </div>-->
                </div><!-- /.entry-content -->
            </article><!-- /.type-post -->

        </div>


        <?php if ($i == $videos || (($i % 3) == 0)) { ?>

        </div>

        <?php }
        $i++;
    }

}

    ?>
