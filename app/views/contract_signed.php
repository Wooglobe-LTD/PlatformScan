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
                        <?php if(isset($page_contact)){
                            echo $page_contact->title;
                        }else{?>
                        Contract Signed
                    <?php } ?>
                    </h2><!-- /.section-title -->
                    <p style="font-weight: 600; font-size: 20px;">Hi <span><?php echo $lead['first_name'];?></span>!</p>
                    <?php if(isset($page_contact)){
                        echo $page_contact->description;
                    }else{?>
                    <p>Thank you for signing the agreement.</p>



                    <p>Our team will get in touch with you shortly for the final step of creating your account. Stay tuned !</p>
                    <?php } ?>
                </div>

                <div class="col-sm-4"></div>
            </div><!-- /.row -->
        </div><!-- /.container -->
    </div><!-- /.section-padding -->
</section><!-- /.video-content -->


