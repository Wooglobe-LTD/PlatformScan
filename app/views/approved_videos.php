<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 3/14/2018
 * Time: 3:17 PM
 */?>
<div class="content-wrapper">
    <div class="container">
        
        <div class="row">
	    	<div class="col-md-3 col-xs-12">
	            <?php include("common_files/dashboard-sidenav.php");?>
	       	</div>
	       	<div class="col-md-9 col-xs-12">
	        <h2 class="section-title"><?php echo $profile_nav;?></h2>
	        <div class="row">
		        <?php foreach ($videos as $video){?>
		        <div class="col-md-6">
			        <div>
			            
                        <span style="float: right;padding: 10px 0px;"><?php $date = $video['created_at'];
                            $date1=date_create($date);
                            echo date_format($date1,"Y-m-d");
                            ?>
                        </span>
	                    	
		                <div>
		                    <p>

		                        <a class="up_org_file" href="<?php echo $url?>submit-video/<?php echo $video['slug']?>"> <b style="float: right">Upload Orignal File</b></a><br/>

		                        <?php
		                        //This is a general function for generating an embed link of an FB/Vimeo/Youtube Video.
		                        $finalUrl = '';
		                        $iframe = '';
		                        if(strpos($video['video_url'], 'facebook.com/') !== false) {
		                            //it is FB video
		                            $finalUrl = 'https://www.facebook.com/plugins/video.php?href='.rawurlencode($video['video_url']).'&show_text=1&width=200';
		                            $iframe = '<iframe  src = "'.$finalUrl.'"  frameborder="0" allowfullscreen style="height: 50vh; width:100%;"></iframe>';
		                        }else if(strpos($video['video_url'], 'vimeo.com/') !== false) {
		                            //it is Vimeo video
		                            $videoId = explode("vimeo.com/",$video['video_url'])[1];
		                            if(strpos($videoId, '&') !== false){
		                                $videoId = explode("&",$videoId)[0];
		                            }
		                            $finalUrl = 'https://player.vimeo.com/video/'.$videoId;
		                            $iframe = '<iframe  src = "'.$finalUrl.'"  frameborder="0" allowfullscreen style="height: 50vh;width:100%;"></iframe>';

		                        }else if(strpos($video['video_url'], 'youtube.com/') !== false) {
		                            //it is Youtube video
		                            $videoId = explode("v=",$video['video_url'])[1];
		                            if(strpos($videoId, '&') !== false){
		                                $videoId = explode("&",$videoId)[0];
		                            }
		                            $finalUrl = 'https://www.youtube.com/embed/'.$videoId;
		                            $iframe = '<iframe src = "'.$finalUrl.'"  frameborder="0" allowfullscreen style="height: 50vh;width:100%;"></iframe>';



		                        }else if(strpos($video['video_url'], 'youtu.be/') !== false){
		                            //it is Youtube video
		                            $videoId = explode("youtu.be/",$video['video_url'])[1];
		                            if(strpos($videoId, '&') !== false){
		                                $videoId = explode("&",$videoId)[0];
		                            }
		                            $finalUrl ='https://www.youtube.com/embed/'.$videoId;
		                            $iframe = '<iframe  src = "'.$finalUrl.'"  frameborder="0" allowfullscreen style="height: 50vh;width:100%;"></iframe>';



		                        }else{
		                            //echo $finalUrl;
		                        }

		                        ?>


		                    <div >
		                        <?php echo $iframe;?>
		                    </div>



		                    </p>
		                    <h3><?php echo $video['video_title'];?></h3>
		                </div>
			            
			        </div>
		        </div>
		        <?php } ?>
	    	</div>
	    	</div>
    	</div>
    
    </div>
</div>
