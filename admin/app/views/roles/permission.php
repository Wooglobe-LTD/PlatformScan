<?php
/**
 * Created by PhpStorm.
 * User: T3500
 * Date: 2/13/2018
 * Time: 3:32 PM
 */
?>

<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><a href="<?php echo $url;?>roles">Staff Members Roles Management</a></li>
            <li><span><?php echo $title.' ('.$data->title.')';?></span></li>
        </ul>
    </div>
    <div id="page_content_inner">
        <form id="permission" method="post" action="<?php echo $url;?>save_permission/<?php echo $data->id;?>">
            <h3><?php echo $title.' ('.$data->title.')';?></h3>
            <div class="uk-grid" data-uk-grid-margin>
                <?php foreach($menus->result() as $menu){?>
                <div class="uk-width-medium-1-1">
                    <div class="md-card">
                        <div class="md-card-content">
                            <h3 class="heading_a">
                                <p>
                                    <input type="checkbox" name="<?php echo $menu->id;?>[]" value="0" id="<?php echo $menu->id;?>_list" <?php if(isset($permissionts[$menu->id])){ echo 'checked'; }?>  data-md-icheck class="list-checkbox" />
                                    <label for="<?php echo $menu->id;?>_list" class="inline-label"><b><?php echo $menu->menu_name;?></b></label>
                                </p>
                                </h3>
                            <div class="uk-grid" data-uk-grid-margin>

                                <div class="uk-width-medium-1-1">
                                    <?php $action = getActionsByMenuId($menu->id);?>
                                    <?php foreach($action->result() as $action){?>
                                        <span class="icheck-inline">
                                            <input type="checkbox" value="<?php echo $action->id;?>" name="<?php echo $menu->id;?>[]" id="action_<?php echo $menu->id.'_'.$action->id;?>" <?php if(isset($permissionts[$menu->id])){ if(isset($permissionts[$menu->id][$action->id])){ echo 'checked';} }else{ echo 'disabled';}?>  data-md-icheck class="child-checkbox" />
                                            <label for="action_<?php echo $menu->id.'_'.$action->id;?>" class="inline-label"><?php echo $action->action_name;?></label>
                                        </span>
                                    <?php } ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="uk-grid">
                <div class="uk-width-1-1">
                    <button type="submit" class="md-btn md-btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
