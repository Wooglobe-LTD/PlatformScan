<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 3/14/2018
 * Time: 4:07 PM
 */?>
<div class="content-wrapper">
    <div class="container">
        <h2 class="section-title"><?php echo $profile_nav;?></h2><!-- /.section-title -->
        <div> <!-- id="accordion" class="accordion" -->
            <?php foreach ($videos as $video){?>
                <h3><?php echo $video['title'];?> <span style="float: right"><?php $date = $video['created_at'];
                        $date1=date_create($date);
                        echo date_format($date1,"Y-m-d");
                        ?></span></h3>
                <div>
                    <p>

                        <b style="color: #0a001f">Video Title:  </b><span style="color: #0a0a0a;margin-left: 10px"> <?php echo $video['title'];?></span><br>
                        <div class="item active">

                            <?php if($video['embed'] == 0){?>
                                <video poster="<?php echo $url.$video['thumbnail'];?>" width="100%" height="400px" controls controlsList="nodownload" autoplay><source src="<?php echo $url.$video['url'];?>">Your browser does not support HTML5 video.</video>
                            <?php } else {?>

                                <?php echo $video['url'];?>
                            <?php }?>

                        </div>


                    </p>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
