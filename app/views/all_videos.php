<?php
/**
 * Created by PhpStorm.
 * Date: 3/14/2018
 * Time: 5:34 PM
 */?>
<div class="content-wrapper">
    <div class="container">
        <h2 class="section-title"><?php echo $profile_nav;?></h2><!-- /.section-title -->
        <div id="accordion">
            <?php foreach ($videos as $video){?>
                <h3><?php echo $video['video_title'];?> <span style="float: right"><?php $date = $video['created_at'];
                        $date1=date_create($date);
                        echo date_format($date1,"Y-m-d");
                        if($video['status'] == 0){?><span style="margin-left: 10px;color: #0a0a0a">Rejected</span><?php }?>
                        <?php if($video['status'] == 1){?><span style="margin-left: 10px;">Pending</span><?php }?>
                        <?php  if($video['status'] == 2){?><span style="margin-left: 10px;color: orange;">Approved</span><?php }?>
                    </span></h3>
                <div>
                    <p>
                        <b style="color: #0a001f">Name: </b><span style="color: #0a0a0a;margin-left: 10px"><?php echo $video['first_name'];?> <?php echo $video['last_name'];?></span><br>
                        <b style="color: #0a001f">Email:  </b><span style="color: #0a0a0a;margin-left: 10px"> <?php echo $video['email'];?></span><br>
                        <b style="color: #0a001f">Video Title:  </b><span style="color: #0a0a0a;margin-left: 10px"> <?php echo $video['video_title'];?></span><br>

                        <?php
                        //This is a general function for generating an embed link of an FB/Vimeo/Youtube Video.
                        $finalUrl = '';
                        $iframe = '';
                        if(strpos($video['video_url'], 'facebook.com/') !== false) {
                            //it is FB video
                            $finalUrl = 'https://www.facebook.com/plugins/video.php?href='.rawurlencode($video['video_url']).'&show_text=1&width=200';
                            $iframe = '<iframe  src = "'.$finalUrl.'"  frameborder="0" allowfullscreen style="height: 60vh; width:60%;"></iframe>';
                        }else if(strpos($video['video_url'], 'vimeo.com/') !== false) {
                            //it is Vimeo video
                            $videoId = explode("vimeo.com/",$video['video_url'])[1];
                            if(strpos($videoId, '&') !== false){
                                $videoId = explode("&",$videoId)[0];
                            }
                            $finalUrl = 'https://player.vimeo.com/video/'.$videoId;
                            $iframe = '<iframe  src = "'.$finalUrl.'"  frameborder="0" allowfullscreen style="height: 60vh;width:60%;"></iframe>';

                        }else if(strpos($video['video_url'], 'youtube.com/') !== false) {
                            //it is Youtube video
                            $videoId = explode("v=",$video['video_url'])[1];
                            if(strpos($videoId, '&') !== false){
                                $videoId = explode("&",$videoId)[0];
                            }
                            $finalUrl = 'https://www.youtube.com/embed/'.$videoId;
                            $iframe = '<iframe src = "'.$finalUrl.'"  frameborder="0" allowfullscreen style="height: 60vh;width:60%;"></iframe>';



                        }else if(strpos($video['video_url'], 'youtu.be/') !== false){
                            //it is Youtube video
                            $videoId = explode("youtu.be/",$video['video_url'])[1];
                            if(strpos($videoId, '&') !== false){
                                $videoId = explode("&",$videoId)[0];
                            }
                            $finalUrl ='https://www.youtube.com/embed/'.$videoId;
                            $iframe = '<iframe  src = "'.$finalUrl.'"  frameborder="0" allowfullscreen style="height: 60vh;width:60%;"></iframe>';



                        }else{
                            //echo $finalUrl;
                        }

                        ?>


                    <div >
                        <?php echo $iframe;?>
                    </div>


                    </p>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

