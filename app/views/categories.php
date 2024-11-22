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
                <div class="col-md-3 col-xs-12">
                    <div class="side-nav">
                        <div class="side-nav-content">
                            <ul>
                                <li><a href="<?php echo $url;?>"><span><i class="fa fa-home" aria-hidden="true"></i></span>Home</a></li>
                                <li><a href="javascript:void(0);" class="upload"><span><i class="fa fa-upload" aria-hidden="true"></i></span>Upload
                                    </a></li>
                                <li><a href="<?php echo $url;?>partner/categories"><span><i class="fa fa-file-video-o" aria-hidden="true"></i></span>Media Gallery
                                    </a></li>
                                <li class="last"><a href="<?php echo $url;?>partner/categories/trending"><span><i class="fa fa-line-chart" aria-hidden="true"></i></span>Trending</a></li>
                                <!--<li><a href=""><span><i class="fa fa-star-o" aria-hidden="true"></i>
                                </span>Recommended</a></li>-->
                                <?php if($this->sess->userdata('isClientLogin') == ''){?>
                                    <li class="last" style="display: none"><a href="<?php echo $url;?>login"><span><i class="fa fa-user" aria-hidden="true"></i>
                                </span>Log In</a></li>
                                <?php } ?>
                                <?php if(($this->sess->userdata('isClientLogin') != '')){?>
                                    <li class="last"><a href="javascript:void(0)" class="drop-ico2"><span><i class="fa fa-user" aria-hidden="true"></i>
                                </span><?php echo $this->sess->userdata('clientName');?></a>
                                        <ul class="side-login" style="display: block;">
                                            <li><a href="<?php echo $url;?>dashboard" <?php if($title == 'Dashboard'){ echo 'class="active"';}?>>
                                            <span><i class="fa fa-tachometer" aria-hidden="true"></i>
                                            </span>Dashboard
                                                </a></li>
                                            <li><a href="<?php echo $url;?>profile" <?php if($title == 'Profile'){ echo 'class="active"';}?>>
                                                    <span><i class="fa fa-file-text-o" aria-hidden="true"></i></span>
                                                    Profile</a></li>
                                            <li><a href="<?php echo $url;?>change-password" <?php if($title == 'Change Password'){ echo 'class="active"';}?>>
                                                    <span><i class="fa fa-key" aria-hidden="true"></i></span>
                                                    Change Password</a></li>
                                            <li class="last"><a href="<?php echo $url;?>logout" class="signout"><span><i class="fa fa-sign-out" aria-hidden="true"></i></span>logout</a></li>
                                        </ul>
                                    </li>
                                <?php } ?>
                            </ul>

                        </div>
                    </div>
                </div>

                <div class="col-md-9 col-xs-12">

                    <div class="row">
                        <div class="col-sm-3 margin-top-20">

                            <div class="cate-drop-down">

                                <a href="javascript:void(0)" class="cate-drop-btn">Categories
                                    <span>
                                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </span>
                                </a>
                                <?php $this->load->view('common_files/categories');?>

                            </div>
                        </div>

                        <div class="col-sm-9 margin-top-20">
                            <div>
                                <form class="page-search-form" method="get" style="z-index: 999;" action="<?php echo $url."search"?>">
                                    <div class="page-search">
                                        <?php   $search = "";
                                        if(isset($_GET['search']) && !empty($_GET['search'])) {
                                            $search = $_GET['search'];
                                        }
                                        ?>
                                        <input type="text" name="search" id="search_input" placeholder="SEARCH" value="<?php echo $search;?>" />
                                        <span class="search-icon"><i class="fa fa-search" aria-hidden="true"></i></span>

                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                    <?php

                    $total_latest = $latest->num_rows();

                    if ($total_latest > 0 && empty($slug)) {
                        ?>
                        <div class="view-more">
                            <h2 class="section-title">Latest</h2><!-- /.section-title -->
                            <a href="<?php echo $url.'partner/categories/latest';?>"><i class="fa fa-eye" aria-hidden="true"></i>View More</a>
                        </div>


                        <?php
                        $i = 1;
                        ?>
                        <?php

                        foreach ($latest->result() as $video) {
                            if ($i == 1 || (($i % 3) == 1)) {
                                ?>
                                <div class="row">
                            <?php } ?>

                            <div class="col-md-4 margin-bottom" data-loader="showDiv">


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

                                       /* if($video->bulk == 1){*/
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


                            <?php if ($i == $total_latest || (($i % 3) == 0)) { ?>

                                </div>

                            <?php }
                            $i++;
                        }
                    }

                    ?>

                    <?php

                    $total_trending = $trending->num_rows();

                    if ($total_trending > 0 && empty($slug)) {
                        ?>
                        <div class="view-more">
                            <h2 class="section-title">Trending</h2><!-- /.section-title -->
                            <a href="<?php echo $url.'partner/categories/trending';?>"><i class="fa fa-eye" aria-hidden="true"></i>View More</a>
                        </div>


                        <?php
                        $i = 1;
                        ?>
                        <?php

                        foreach ($trending->result() as $video) {
                            if ($i == 1 || (($i % 3) == 1)) {
                                ?>
                                <div class="row">
                            <?php } ?>

                            <div class="col-md-4 margin-bottom" data-loader="showDiv">


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

                                       /* if($video->bulk == 1){*/
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


                            <?php if ($i == $total_trending || (($i % 3) == 0)) { ?>

                                </div>

                            <?php }
                            $i++;
                        }
                    }

                    ?>

                    <?php
                    if($slug == 'trending'){?>
                        <?php $total = $categories->num_rows();
                        if ($total > 0) {
                            ?>
                            <div class="view-more">
                                <h2 class="section-title">Trending</h2><!-- /.section-title -->

                            </div>
                            <?php
                            $i = 1;
                            ?>
                            <?php

                            foreach ($categories->result() as $video) {

                                if ($i == 1 || (($i % 3) == 1)) {
                                    ?>
                                    <div class="row">
                                <?php } ?>

                                <div class="col-md-4 margin-bottom" data-loader="showDiv">


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
                                          /*  }*/
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
                            <div class="view-more">
                                <h2 class="section-title">Latest</h2><!-- /.section-title -->

                            </div>
                            <?php
                            $i = 1;
                            ?>
                            <?php

                            foreach ($categories->result() as $video) {

                                if ($i == 1 || (($i % 3) == 1)) {
                                    ?>
                                    <div class="row">
                                <?php } ?>

                                <div class="col-md-4 margin-bottom" data-loader="showDiv">


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

                    <?php }else{ ?>
                        <?php foreach ($categories as $category) {

                            $total = count($category->videos);

                            if ($total > 0) {
                                ?>

                                <div class="view-more">
                                    <h2 class="section-title"><?php echo $category->title; ?></h2><!-- /.section-title -->
                                    <?php if(empty($slug)){?>
                                        <a href="<?php echo $url.'partner/categories/'.$category->slug;?>"><i class="fa fa-eye" aria-hidden="true"></i>View More</a>
                                    <?php } ?>
                                </div>




                                <?php
                                $i = 1;
                                ?>
                                <?php

                                foreach ($category->videos as $video) {
                                    if ($i == 1 || (($i % 3) == 1)) {
                                        ?>
                                        <div class="row">
                                    <?php } ?>

                                    <div class="col-md-4 margin-bottom" data-loader="showDiv">


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

                                              /*  if($video->bulk == 1){*/
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
                                }
                            }else{?>

                                <?php }
                        }
                        ?>
                    <?php } ?>


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
