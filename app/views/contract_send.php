<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 4/23/2018
 * Time: 10:55 AM
 */
?>
<section class="video-contents">
    <div class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h2 class="section-title">
                        <?php if(isset($page_content)){
                            echo $page_content->title;
                        }else{?>
                        Contract Sent
                        <?php } ?>
                    </h2><!-- /.section-title -->
                    <p style="font-weight: 600; font-size: 20px;">Hi <span><?php echo $lead['first_name'];?></span>!</p>
                    <?php if(isset($page_content)){
                        echo $page_content->description;
                    }else{?>
                    <p>Thanks for your interest.</p>



                    <p>We have just sent you our online contract in a separate email. You should have it in your inbox in a few minutes if its not already there.</p>



                    <p>Please review and sign as soon as possible. If you have any questions, please feel free to ask.</p>



                    <p> Thank you so much again for the interest and I look forward to hearing from you soon.</p>
                    <?php } ?>
                </div>

                <div class="col-sm-4"></div>
            </div><!-- /.row -->
        </div><!-- /.container -->
    </div><!-- /.section-padding -->
</section><!-- /.video-content -->


