<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 3/14/2018
 * Time: 4:07 PM
 */?>
<div class="content-wrapper acq-videos">
    <div class="container">
		<div class="row">
			<div class="col-md-3 col-xs-12">
                <?php include("common_files/dashboard-sidenav.php");?>
            </div>
            <div class="col-md-9 col-xs-12">
				<h2 class="section-title"><?php echo $profile_nav;?></h2><!-- /.section-title -->
		    	<?php foreach ($videos as $video){?>
		    	<div class="col-md-6">
		            <div>
		              	
		                <div class="item active">
		                    <?php if($video['embed'] == 0){?>
		                        <video poster="<?php echo $url.$video['thumbnail'];?>" width="100%" height="400px" controls controlsList="nodownload" autoplay><source src="<?php echo $url.$video['url'];?>">Your browser does not support HTML5 video.</video>
		                    <?php } else {?>

		                        <?php echo $video['url'];?>
		                    <?php }?>
		                </div>
		                                    
		                <h4> <?php echo $video['title'];?></h4>

		                <div class="vid-ttl-date">
		                	<?php echo $video['title'];?> <span style="float: right"><?php $date = $video['created_at'];
		                    $date1=date_create($date);
		                    echo date_format($date1,"Y-m-d");
		                    ?></span>
		                </div>
		                
		            </div>

		            <!-- <div class="col-md-12">
			            <div class="seprator"></div>
			        </div> -->
		        </div>
		    	<?php } ?>
	    	</div>
        </div>
    </div>
</div>
