<?php
if($slug == 'trending'){?>
    <?php $total = $categories->num_rows();
    if ($total > 0) {
        ?>

        <?php
        $i = 1;
        ?>
        <?php

        foreach ($categories->result() as $video) {

            if ($i == 1 || (($i % 3) == 1)) {
                ?>

                <div class="row">
            <?php } ?>

            <div class="col-md-4 margin-bottom col-sm-4" data-loader="showDiv">


                <article class="post type-post">
                    <div class="entry-thumbnail">
                        <?php
                       /* if ($video->thumbnail != '') {
                            if($video->bulk == 1){
                                $thumb = $video->thumbnail;
                            }else{
                                $thumb = $url . $video->thumbnail;
                            }
                        } else {
                            $thumb = "https://i.vimeocdn.com/video/614933078.jpg?mw=960&mh=540";
                        }*/
                        $thumb = "https://i.vimeocdn.com/video/614933078.jpg?mw=960&mh=540";

                        /*if($video->bulk == 1){*/
                            $thumb = $video->thumbnail;
                            $thumb = 'https://img.youtube.com/vi/'.$video->youtube_id.'/hqdefault.jpg';
                        /*}*/
                        ?>
                        <a href="<?php echo $url ?>partner?video=<?php echo $video->slug; ?>">
                            <img src="<?php echo $thumb; ?>" alt="Video Thumbnail">


                            <span class="play-video"><i class="fa fa-play-circle-o"></i></span>
                        </a>
                    </div><!-- /.entry-thumbnail -->
                    <div class="entry-content-new entry-content">
                        <h4 class="entry-title"><a href="<?php echo $url ?>partner?video=<?php echo $video->slug; ?>"
                                                   title="<?php echo ucwords($video->title); ?>"><?php if (strlen($video->title)) {
                                    echo ucwords($video->title);
                                } ?></a>
                        </h4><!-- /.entry-title -->
                    </div><!-- /.entry-content -->


                </article><!-- /.type-post -->

            </div>


            <?php if ($i == $total || (($i % 3) == 0)) { ?>

                </div>

            <?php }
            $i++;
        }?>
    <?php } ?>
<?php }elseif($slug == 'latest'){ ?>
    <?php $total = $categories->num_rows();
    if ($total > 0) {
        ?>

        <?php
        $i = 1;
        ?>
        <?php

        foreach ($categories->result() as $video) {

            if ($i == 1 || (($i % 3) == 1)) {
                ?>
                <div class="row">
            <?php } ?>

            <div class="col-md-4 margin-bottom col-sm-4" data-loader="showDiv">


                <article class="post type-post">
                    <div class="entry-thumbnail">
                        <?php
                        /*if ($video->thumbnail != '') {
                            if($video->bulk == 1){
                                $thumb = $video->thumbnail;
                            }else{
                                $thumb = $url . $video->thumbnail;
                            }
                        } else {
                            $thumb = "https://i.vimeocdn.com/video/614933078.jpg?mw=960&mh=540";
                        }*/
                        $thumb = "https://i.vimeocdn.com/video/614933078.jpg?mw=960&mh=540";

                        /*if($video->bulk == 1){*/
                            $thumb = $video->thumbnail;
                            $thumb = 'https://img.youtube.com/vi/'.$video->youtube_id.'/hqdefault.jpg';
                       /* }*/
                        ?>
                        <a href="<?php echo $url ?>partner?video=<?php echo $video->slug; ?>">
                            <img src="<?php echo $thumb; ?>" alt="Video Thumbnail">


                            <span class="play-video"><i class="fa fa-play-circle-o"></i></span>
                        </a>
                    </div><!-- /.entry-thumbnail -->
                    <div class="entry-content-new entry-content">
                        <h4 class="entry-title"><a href="<?php echo $url ?>partner?video=<?php echo $video->slug; ?>"
                                                   title="<?php echo ucwords($video->title); ?>"><?php if (strlen($video->title)) {
                                    echo ucwords($video->title);
                                } ?></a>
                        </h4><!-- /.entry-title -->
                    </div><!-- /.entry-content -->


                </article><!-- /.type-post -->

            </div>


            <?php if ($i == $total || (($i % 3) == 0)) { ?>

                </div>

            <?php }
            $i++;
        }?>
    <?php } ?>

<?php }else{ ?>
    <?php
    if(count($categories) > 0) {
        foreach ($categories as $category) {

            $total = count($category->videos);

            if ($total > 0) {
                ?>



                <?php
                $i = 1;
                ?>
                <?php

                foreach ($category->videos as $video) {
                    if ($i == 1 || (($i % 3) == 1)) {
                        ?>
                        <div class="row">
                    <?php } ?>

                    <div class="col-md-4 margin-bottom col-sm-4" data-loader="showDiv">


                        <article class="post type-post">
                            <div class="entry-thumbnail">
                                <?php
                                /*if ($video->thumbnail != '') {
                                    if ($video->bulk == 1) {
                                        $thumb = $video->thumbnail;
                                    } else {
                                        $thumb = $url . $video->thumbnail;
                                    }
                                } else {
                                    $thumb = "https://i.vimeocdn.com/video/614933078.jpg?mw=960&mh=540";
                                }*/
                                $thumb = "https://i.vimeocdn.com/video/614933078.jpg?mw=960&mh=540";

                               /* if($video->bulk == 1){*/
                                    $thumb = $video->thumbnail;
                                    $thumb = 'https://img.youtube.com/vi/'.$video->youtube_id.'/hqdefault.jpg';
                                /*}*/
                                ?>
                                <a href="<?php echo $url ?>partner?video=<?php echo $video->slug; ?>">
                                    <img src="<?php echo $thumb; ?>" alt="Video Thumbnail">


                                    <span class="play-video"><i
                                                class="fa fa-play-circle-o"></i></span>
                                </a>
                            </div><!-- /.entry-thumbnail -->
                            <div class="entry-content-new entry-content">
                                <h4 class="entry-title"><a
                                            href="<?php echo $url ?>partner?video=<?php echo $video->slug; ?>"
                                            title="<?php echo ucwords($video->title); ?>"><?php if (strlen($video->title)) {
                                            echo ucwords($video->title);
                                        } ?></a>
                                </h4><!-- /.entry-title -->
                            </div><!-- /.entry-content -->


                        </article><!-- /.type-post -->

                    </div>


                    <?php if ($i == $total || (($i % 3) == 0)) { ?>

                        </div>

                    <?php }
                    $i++;
                }
            } ?>

                    <?php

        }
    }else{?>
        <div class="not-found">

            <span style="margin-bottom: 30px">No Video Found</span>
            <span><img src="<?php echo $url;?>images/nosearch.png" alt=""></span>

        </div>
    <?php }

    ?>
<?php } ?>