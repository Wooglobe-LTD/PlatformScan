<?php
/**
 * Created by PhpStorm.
 * User: Usman Ali Sarwar
 * Date: 16/01/2018
 * Time: 3:17 PM
 */?>


<!--videos content section starts here-->
<section class="video-contents">
    <div class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">

                    <?php foreach ($categories as $category) {

                        $total = count($category->videos);

                        if ($total > 0) {
                            ?>

                            <h2 class="section-title"><?php echo $category->title; ?></h2><!-- /.section-title -->


                            <?php
                            $i = 1;
                            ?>
                            <?php

                            foreach ($category->videos as $video) {
                                if ($i == 1 || (($i % 4) == 1)) {
                                    ?>
                                    <div class="row">
                                <?php } ?>

                                <div class="col-md-3 margin-bottom" data-loader="showDiv">


                                    <article class="post type-post">
                                        <div class="entry-thumbnail">
                                            <?php
                                            if ($video->thumbnail != '') {
                                                $thumb = $url . $video->thumbnail;
                                            } else {
                                                $thumb = "https://i.vimeocdn.com/video/614933078.jpg?mw=960&mh=540";
                                            }
                                            ?>
                                            <a href="<?php echo $url ?>?video=<?php echo $video->slug; ?>">
                                                <img src="<?php echo $thumb; ?>" alt="Video Thumbnail">


                                                <span class="play-video"><i class="fa fa-play-circle-o"></i></span>
                                            </a>
                                        </div><!-- /.entry-thumbnail -->
                                        <div class="entry-content-new entry-content">
                                            <h4 class="entry-title"><a href="javascript:void(0);"
                                                                       title="<?php echo ucwords($video->title); ?>"><?php if (strlen($video->title) < 18) {
                                                        echo ucwords($video->title);
                                                    } else {
                                                        echo ucwords(substr($video->title, 0, 18)) . '...';
                                                    } ?></a></h4><!-- /.entry-title -->
                                        </div><!-- /.entry-content -->


                                    </article><!-- /.type-post -->
                                </div>


                                <?php if ($i == $total || (($i % 4) == 0)) { ?>
                                    </div>

                                <?php }
                                $i++;
                            }
                        }
                    }
                    ?>

                </div>

            </div><!-- /.row -->
        </div><!-- /.container -->
    </div><!-- /.section-padding -->
</section><!-- /.video-content -->
<!--videos content section ends here-->

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-122486429-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-122486429-1');
</script>
