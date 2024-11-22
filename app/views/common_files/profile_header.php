<?php
/**
 * Created by PhpStorm.
 * User: T3500
 * Date: 1/29/2018
 * Time: 12:38 PM
 */?>

<section class="author-heading">
    
    <div class="heading-top">
        <div class="author-cover-image background-bg" data-image-src="images/profile-banner.png">
            <div class="overlay"></div><!-- /.overlay -->
        </div><!-- /.author-cover-image -->
    </div><!-- /.heading-top -->
    
    <!--<div class="section-padding"></div> /.section-padding -->
    
    <div class="container">
        <div class="heading-bottom">
            <div class="bottom-left col-xs-6">
                <?php $pic_url = $userData->picture;
                if(!empty($pic_url)){
                    if (strpos($pic_url, 'http') !== false) {
                        $pic_url = $pic_url;
                    }else{
                        $pic_url = $url.$pic_url;
                    }
                }else{
                    $pic_url = $assets.'img/profile-icon.png';
                }
                ?>
                <div class="author-image"><img src="<?php echo $pic_url;?>" alt="Profile Picture"></div>
                <h3 class="author-name"><a href="javascript:void(0);"><?php echo $userData->full_name;?></a></h3>
            </div><!-- /.bottom-left -->

        </div><!-- /.header-bottom -->
    </div><!-- /.container -->
    

</section><!-- /.author-heading -->
