<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 4/23/2018
 * Time: 10:49 AM
 */?>
<section class="video-contents">
    <div class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h2 class="section-title">
                        <?php if(isset($page_content)){
                            echo $page_content->title;
                        }else{?>
                            Link Expired
                        <?php } ?>
                        </h2><!-- /.section-title -->
                    <?php if(isset($page_content)){
                        echo $page_content->description;
                    }else{?>
                    <p>Your Link has been expired because a contract already been sent to you. </p>
                    <?php } ?>
                </div>

            </div><!-- /.row -->
        </div><!-- /.container -->
    </div><!-- /.section-padding -->
</section><!-- /.video-content -->
