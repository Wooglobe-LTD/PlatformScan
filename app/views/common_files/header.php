<?php
/**
 * Created by PhpStorm.
 * User: Usman Ali Sarwar
 * Date: 16/01/2018
 * Time: 2:47 PM
 */
?>




<header class="header">

    <div class="header-top" style="display: none;">
        <div class="container">
            <div class="row">
                
                <div class="col-sm-8">
                    <div class="top-sitemap text-left">

                        <?php if($this->sess->userdata('isClientLogin') == ''){?>
                            <span> <a href="<?php echo $url;?>login"><i class="fa fa-sign-in"></i> Login In</a></span>
                          <!--  <span><a href="<?php echo $url;?>signup"><i class="fa fa-user-plus"></i> Register</a></span> -->
                            <span><a href="<?php echo $url;?>partner/categories"><i class="fa fa-users"></i> Partner Portal</a></span>
                            <span><a href="javascript:void(0);" class="upload"><i class="fa fa-upload"></i> Upload Videos</a></span>

                        <?php }else {?>

                            <span>Welcome Back!</span>
                            
                            <span><?php echo $this->sess->userdata('clientName');?></span>
                            
                            <span><a href="javascript:void(0);" class="upload"><i class="fa fa-upload"></i> Upload Videos</a></span>
                            
                            <span class="margin-10">|</span>
                            
                            <span><a href="<?php echo $url;?>logout"><i class="fa fa-sign-out"></i> Logout</a></span>
                            
                            <span class="margin-10">|</span>

                            <?php if(($this->sess->userdata('isClientLogin') != '')){?>
                                <div class="acc-block">
                                    <span><a href="javascript:void(0);" class="my-accnt"><i class="fa fa-user"></i>My account</a></span>
                                    <ul class="my-acc-area">
                                        <li <?php if($title == 'Dashboard'){ echo 'class="active"';}?>><a href="<?php echo $url;?>dashboard">Dashboard</a></li>
                                        <li <?php if($title == 'Profile'){ echo 'class="active"';}?>><a href="<?php echo $url;?>profile">Profile</a></li>
                                        <li <?php if($title == 'Change Password'){ echo 'class="active"';}?>><a href="<?php echo $url;?>change-password">Change Password</a></li>
                                        <!--<li <?php /*if($title == 'Upload Video'){ echo 'class="active"';}*/?>><a href="<?php /*echo $url;*/?>upload-video">Upload Video</a></li>-->
                                    </ul>
                                </div>



                            <?php } ?>
                        <?php } ?>

                    </div><!-- /.top-sitemap -->
                </div>

                <div class="col-sm-4">
                    <!-- /.top-sitemap -->
                </div>

            </div><!-- /.row -->
        </div><!-- /.container -->
    </div><!-- /.header-top -->

    <div class="header-middle" style="display: none;">
        <div class="container">
            <div class="row">
                <div class="col-sm-2">
                    <!-- <div class="navbar-brand hidden-xs"><a href="<?php echo $url;?>"><img src="<?php echo $image;?>logo.png" alt="Site Logo" class="site-logo"></a></div> -->
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
                <div class="col-sm-2">
                    <div class="navbar-brand hidden-xs"><a href="<?php echo $url;?>"><img src="<?php echo $image;?>logo.png" alt="Site Logo" class="site-logo"></a></div>
                </div>
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

                                <li class="menu-item <?php if($active == 'cate_buy'){ echo 'active';}?>">
                                    <a href="<?php echo $url;?>partner/categories">Buy Video</a>
                                </li>

                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="upload">Submit Video</a>
                                </li>
                                <!-- <li class="menu-item <?php if($active == 'cate'){ echo 'active';}?>">
                                    <a href="<?php echo $url;?>categories">Video Library</a>
                                </li> -->
                                

                                <!-- <li class="menu-item abt <?php if($active == 'about-us'){ echo 'active';}?>">
                                    <a href="<?php echo $url;?>about-us">About Us</a>
                                </li> -->

                                <li class="menu-item abt <?php if($active == 'faq'){ echo 'active';}?>">
                                    <a href="<?php echo $url;?>faq">Faq<span style="text-transform: lowercase;">s</span></a>
                                </li>

                                <li class="menu-item <?php if($active == 'contact-us'){ echo 'active';}?>">
                                    <a href="<?php echo $url;?>contact-us">Contact Us</a>
                                </li>
                                
                                <!-- <?php if(($this->sess->userdata('isClientLogin') != '')){?>
                                    <li class="menu-item menu-item-has-children <?php if($active == 'profile'){ echo 'active';}?>">
                                    <a href="javascript:void(0);">My account</a>
                                    <ul class="sub-menu children">
                                            <li <?php if($title == 'Dashboard'){ echo 'class="active"';}?>><a href="<?php echo $url;?>dashboard">Dashboard</a></li>
                                            <li <?php if($title == 'Profile'){ echo 'class="active"';}?>><a href="<?php echo $url;?>profile">Profile</a></li>
                                            <li <?php if($title == 'Change Password'){ echo 'class="active"';}?>><a href="<?php echo $url;?>change-password">Change Password</a></li>
                                            <li <?php /*if($title == 'Upload Video'){ echo 'class="active"';}*/?>><a href="<?php /*echo $url;*/?>upload-video">Upload Video</a></li>
                                    </ul>
                                </li> 
                                <?php } ?> -->
                            </ul>
                        </div><!-- /.navbar-collapse -->
                    </nav><!-- /.navbar -->
                </div>
                <div class="col-sm-2" style="position: relative;">
                    
                	<div class="signing-sec" style="display: none">
                        <?php if($this->sess->userdata('isClientLogin') == ''){?>
                		<a class="lock" href="<?php echo $url;?>login">
                            <span><i class="fa fa-lock" aria-hidden="true"></i></span>
                            Log In
                        </a>
                        <?php } ?>
                        <?php if(($this->sess->userdata('isClientLogin') != '')){?>
                        <div class="success-login">
                            <a href="javascript:void(0)" class="drop-ico arrow">
                               <!-- <span><i class="fa fa-unlock-alt" aria-hidden="true"></i></span> -->
                               <!-- <?php echo $this->sess->userdata('clientName');?> -->
                               <span class="last"><i class="fa fa-caret-down" aria-hidden="true"></i></span>
                            </a>
                            <div class="drop-circle">
                               <!-- <span><i class="fa fa-unlock-alt" aria-hidden="true"></i></span> -->
                               <?php echo substr($this->sess->userdata('clientName'),0,1);?>
                               <!-- <span class="last"><i class="fa fa-caret-down" aria-hidden="true"></i></span> -->
                            </div>
                            
                            <div class="login-menu">
                                <div class="triangle-up"></div>
                                <a href="<?php echo $url;?>dashboard" <?php if($title == 'Dashboard'){ echo 'class="active"';}?>>Dashboard</a>
                                <a href="<?php echo $url;?>profile" <?php if($title == 'Profile'){ echo 'class="active"';}?>>Profile</a>
                                <a href="<?php echo $url;?>change-password" <?php if($title == 'Change Password'){ echo 'class="active"';}?>>Change Password</a>
                                <a href="<?php echo $url;?>logout" class="signout"><span><i class="fa fa-sign-out" aria-hidden="true"></i></span>Logout</a>
                            </div>
                            <div class="beta_version">Beta Version</div>
                            <?php } ?>



                        </div>
                		
                	</div>
                    <div style="display: none;">
                        <!-- <form class="search-form" method="get" style="z-index: 999;" action="<?php echo $url."search"?>">
                            <div class="field ui-widget">
                                <?php   $search = "";
                                    if(isset($_GET['search']) && !empty($_GET['search'])) {
                                        $search = $_GET['search'];
                                    }
                                ?>
                                <input type="text" name="search" id="search_input" placeholder="Search Here ...." maxlength="40" value="<?php echo $search;?>" />
                               
                            </div>
                        </form> -->
                    </div>
                       
              
                    <!-- <form class="search-form" method="get" action="<?php echo $url."search"?>">
                        
                        <div class="ui-widget">
                            <?php   $search = "";
                            if(isset($_GET['search']) && !empty($_GET['search'])) {
                                $search = $_GET['search'];
                            }
                            ?>
                            <input name="search" type="text" class="search_input" id="search_input" size="20" maxlength="20" placeholder="Search Here ...." value="<?php $search;?>">

                        </div>
                        
                    </form>-->
                </div>


            </div>
        </div><!-- /.container -->
    </div><!-- /.header-bottom -->
    
</header><!-- /.header -->
<div class="hd-back"></div>






