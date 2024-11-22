<?php
/**
 * Created by PhpStorm.
 * User: Usman Ali Sarwar
 * Date: 16/01/2018
 * Time: 3:17 PM
 */?>
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
                                <li><a href="<?php echo $url;?>partner/categories/trending"><span><i class="fa fa-line-chart" aria-hidden="true"></i></span>Trending</a></li>
                                <!-- <li><a href=""><span><i class="fa fa-star-o" aria-hidden="true"></i>
                                 </span>Recommended</a></li>-->
                                <?php if($this->sess->userdata('isClientLogin') == ''){?>
                                    <!-- <li class="last"><a href="<?php echo $url;?>login"><span><i class="fa fa-user"
                               aria-hidden="true"></i>
                                </span>Log In</a></li> -->
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

                <div class="col-md-9 col-xs-12" id="load-more">

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

                        <div class="col-sm-9 margin-top-20 " >
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

                    <h2 class="section-title">Search "<?php echo $search;?>"</h2><!-- /.section-title -->

                    <?php

                    $i = 1;

                    ?>
                    <?php

                    if(count($videos['videos']) > 0){

                    foreach ($videos['videos'] as $video){

                            if ($i == 1 || (($i % 3) == 1)) {
                                ?>
                                <div class="row" >
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
                                  /*}*/
                                  $slug=$video->slug;
                                  if($slug == 0){
                                      $string = strtolower($slug);
                                      $slug = preg_replace("/[\s_]/", "-", $string);
                                  }
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

                    }else{

                    ?>
                    </div>

                    <div class="not-found">
                        <span style="margin-bottom: 30px">"No Results Found"</span>
                        <span><img src="<?php echo $image;?>nosearch.png" alt="" /></span>

                    </div>
					<?php


                        $total_latest = $latest->num_rows();

                        if ($total_latest > 0 && empty($slug)) {
                            ?>
                            <div class="row">
                              <div class="col-md-12 view-more">
                                  <h2 class="section-title">Latest</h2><!-- /.section-title -->
                                  <a href="<?php echo $url.'partner/categories/latest';?>"><i class="fa fa-eye" aria-hidden="true"></i>View More</a>
                              </div>
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
                                            <a href="<?php echo $url ?>partner?video=<?php echo $slug; ?>">
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
                        <div class="row">
                            <div class="col-md-12 view-more">
                                <h2 class="section-title">Trending</h2><!-- /.section-title -->
                                <a href="<?php echo $url.'partner/categories/trending';?>"><i class="fa fa-eye" aria-hidden="true"></i>View More</a>
                            </div>
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

                                       /* if($video->bulk == 1){*/
                                            $thumb = $video->thumbnail;
                                            $thumb = 'https://img.youtube.com/vi/'.$video->youtube_id.'/hqdefault.jpg';
                                        /*}*/
                                        ?>
                                        <a href="<?php echo $url ?>partner?video=<?php echo $slug; ?>">
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
                    <?php } ?>

                </div>

            </div><!-- /.row -->
        </div><!-- /.container -->
    </div><!-- /.section-padding -->
</section>

<style>
@media screen and (max-width: 600px){
.entry-thumbnail {
    display: inline-block;
}}
</style>
<script>
    var search = "<?php echo $search;?>";
    var searchTotal = "<?php echo count($videos_total['videos']);?>";
</script>
