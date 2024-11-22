<?php
/**
 * Created by PhpStorm.
 * User: Usman Ali Sarwar
 * Date: 16/01/2018
 * Time: 2:47 PM
 */
?>
<header class="header">
    <div class="header-top">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="top-sitemap text-left">

                        <?php if($this->sess->userdata('isClientLogin') == ''){?>
                            <span> <a href="<?php echo $url;?>signin"><i class="fa fa-sign-in"></i> Sign In</a></span>
                          <!--  <span><a href="<?php echo $url;?>signup"><i class="fa fa-user-plus"></i> Register</a></span> -->
                            <span><a href="<?php echo $url;?>partner"><i class="fa fa-users"></i> Partner Portal</a></span>
                            <span><a href="javascript:void(0);" class="upload"><i class="fa fa-upload"></i> Upload Videos</a></span>

                        <?php }else {?>

                            <span>Welcome Back!</span>
                            <span><?php echo $this->sess->userdata('clientName');?></span>
                            <span><a href="javascript:void(0);" class="upload"><i class="fa fa-upload"></i> Upload Videos</a></span>
                            <span><a href="<?php echo $url;?>logout"><i class="fa fa-sign-out"></i> Logout</a></span>
                        <?php } ?>

                    </div><!-- /.top-sitemap -->
                </div>

                <div class="col-sm-6">
                    <div class="top-sitemap text-right">
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-pinterest"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-vimeo-square"></i></a>
                        <a href="#"><i class="fa fa-google-plus-square"></i></a>
                        <a href="#"><i class="fa fa-youtube"></i></a>
                    </div><!-- /.top-sitemap -->
                </div>
            </div><!-- /.row -->
        </div><!-- /.container -->
    </div><!-- /.header-top -->

    <div class="header-middle">
        <div class="container">
            <div class="row">
                <div class="col-sm-2">
                    <div class="navbar-brand hidden-xs"><a href="<?php echo $url;?>"><img src="<?php echo $image;?>logo.png" alt="Site Logo" class="site-logo"></a></div>
                </div>
                <div class="col-sm-10" style="height: 95px"><!-- <a href="#" class="banner-ad"></a> -->
                    <!-- <div class="topnav">
                        <div class="search-container">
                        <form action="/action_page.php">
                        <input type="text" placeholder="Search.." name="search">
                        <button type="submit-srch"><span><i class="fa fa-search"></i></span></button>
                        </form>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div><!-- /.header-middle -->

    <div class="header-bottom">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <nav class="navbar navbar-default">
                        <div class="navbar-header visible-xs">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu" aria-expanded="false">
                                <i class="fa fa-bars"></i>
                            </button>
                            <a class="navbar-brand" href="<?php echo $url;?>"><img src="<?php echo $image;?>logo.png" alt="Logo"></a>
                        </div>

                        <div id="menu" class="main-menu collapse navbar-collapse pull-left">

                            <ul class="nav navbar-nav">

                                <li class="menu-item <?php if($active == 'home'){ echo 'active';}?>">
                                    <a href="<?php echo $url;?>">Home</a>
                                    
                                </li>

                                <li class="menu-item menu-item-has-children">
                                    <a href="#">Categories</a>
                                    <ul class="sub-menu children">
                                        <?php $parents = parent_categories();
                                         foreach ($parents->result() as $parent){
                                                $childs = child_categories($parent->id);
                                             ?>
                                             <li class="menu-item <?php if($childs->num_rows() > 0){?>menu-item-has-children-right <?php } ?>"><a href="<?php echo $url.$parent->slug;?>"><?php echo $parent->title;?></a>
                                                 <?php if($childs->num_rows() > 0){?>
                                                 <ul class="sub-menu children">
                                                     <?php foreach ($childs->result() as $child){?>
                                                         <li><a href="<?php echo $url.$child->slug;?>"><?php echo $child->title;?></a></li>
                                                     <?php } ?>


                                                 </ul>
                                                 <?php } ?>
                                             </li>
                                         <?php } ?>

                                    </ul>
                                </li>

                                <li class="menu-item <?php if($active == 'about-us'){ echo 'active';}?>">
                                    <a href="<?php echo $url;?>about-us">About Us</a>

                                </li>

                                <li class="menu-item <?php if($active == 'contact-us'){ echo 'active';}?>">
                                    <a href="<?php echo $url;?>contact-us">Contact Us</a>

                                </li>
                                <?php if(($this->sess->userdata('isClientLogin') != '')){?>
                                <li class="menu-item menu-item-has-children <?php if($active == 'profile'){ echo 'active';}?>">
                                    <a href="javascript:void(0);">My account</a>
                                    <ul class="sub-menu children">
                                            <li <?php if($title == 'Dashboard'){ echo 'class="active"';}?>><a href="<?php echo $url;?>dashboard">Dashboard</a></li>
                                            <li <?php if($title == 'Profile'){ echo 'class="active"';}?>><a href="<?php echo $url;?>profile">Profile</a></li>
                                            <li <?php if($title == 'Change Password'){ echo 'class="active"';}?>><a href="<?php echo $url;?>change-password">Change Password</a></li>
                                            <!--<li <?php /*if($title == 'Upload Video'){ echo 'class="active"';}*/?>><a href="<?php /*echo $url;*/?>upload-video">Upload Video</a></li>-->
                                    </ul>
                                </li>
                                <?php } ?>
                            </ul>
                        </div><!-- /.navbar-collapse -->
                    </nav><!-- /.navbar -->
                </div>
                <div class="col-sm-4">
                    <form class="search-form" method="get" action="<?php echo $url."search"?>">
                        <div class="ui-widget">
                            <?php   $search = "";
                            if(isset($_GET['search']) && !empty($_GET['search'])) {
                                $search = $_GET['search'];
                            }
                            ?>
                            <input name="search" type="text" class="search_input" id="search_input" size="20" maxlength="20" placeholder="Search Here ...." value="<?php $search;?>">

                        </div>
                    </form><!-- /.search-form -->
                </div>


            </div>
        </div><!-- /.container -->
    </div><!-- /.header-bottom -->
</header><!-- /.header -->
