<?php $parents = parent_categories();
$last = $parents->num_rows();
$cat_url = '';
if(isset($cate_url)){
    $cat_url = $cate_url;
}
if( $last > 0 ){  ?>
    <ul class="cate-drop-content">
        <?php foreach ($parents->result() as $key => $parent) {?>
            <li class="<?php if(($key-1) == $last){ echo 'last';}?>""><a href="<?php echo $url.$cat_url.'categories/'.$parent->slug;?>"><?php echo $parent->title;?></a></li>
       <?php  } ?>
        
    </ul>
<?php } ?>