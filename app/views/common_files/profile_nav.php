<?php
/**
 * Created by PhpStorm.
 * User: T3500
 * Date: 1/29/2018
 * Time: 12:42 PM
 */

?>
<nav class="author-page-links">
    <?php foreach($profile_menu as $i=>$v){?>
        <a <?php if($profile_nav == $i){ echo 'class="active"';} ?> href="<?php echo $url.$i;?>"><?php echo $v;?></a>
    <?php } ?>

</nav><!-- /.author-page-links -->

