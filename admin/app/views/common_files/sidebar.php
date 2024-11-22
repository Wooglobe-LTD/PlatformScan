 <!-- main sidebar -->
    <aside id="sidebar_main">
        
        <div class="sidebar_main_header">
            <div class="sidebar_logo" style="height: 80px; line-height: 85px;">
                <a href="index.html" class="sSidebar_hide sidebar_logo_large">
                    <img class="logo_regular" src="<?php echo $asset;?>assets/img/logo.png" alt="" height="15" width="71"/>
                    <img class="logo_light" src="<?php echo $asset;?>assets/img/logo.png" alt="" height="15" width="71"/>
                </a>
                <a href="index.html" class="sSidebar_show sidebar_logo_small">
                    <img class="logo_regular" src="<?php echo $asset;?>assets/img/logo.png" alt="" height="32" width="32"/>
                    <img class="logo_light" src="<?php echo $asset;?>assets/img/logo.png" alt="" height="32" width="32"/>
                </a>
            </div>

        </div>
        
        <div class="menu_section">
            <ul>
                <?php //if(role_permitted_html(false)){?>
<!--     <li title="Staff Management">-->
<!--         <a href="javascript:void(0);">-->
<!--             <span class="menu_icon"><i class="material-icons">&#xE85E;</i></span>-->
<!--             <span class="menu_title">Staff Management</span>-->
<!--         </a>-->
<!--         <ul>-->
<!--             <li --><?php //if($active == 'roles'){ echo 'class="act_item"';}?><!-- title="Staff Member Roles">-->
<!--                 <a href="--><?php //echo $url;?><!--roles">-->
<!--                     <!--<span class="menu_icon"><i class="material-icons">&#xE897;</i></span>-->
<!--                     <span class="menu_title">Staff Member Roles</span>-->
<!--                 </a>-->
<!---->
<!--             </li>-->
<!--             <li --><?php //if($active == 'groups'){ echo 'class="act_item"';}?><!-- title="Staff Member Groups">-->
<!--                 <a href="--><?php //echo $url;?><!--groups">-->
<!--                     <!--<span class="menu_icon"><i class="material-icons">&#xE897;</i></span>-->
<!--                     <span class="menu_title">Staff Member Groups</span>-->
<!--                 </a>-->
<!---->
<!--             </li>-->
<!--             <li --><?php //if($active == 'add_staff_group'){ echo 'class="act_item"';}?><!-- title="Add Staff Member Groups">-->
<!--                 <a href="--><?php //echo $url;?><!--add_staff_group">-->
<!--                     <!--<span class="menu_icon"><i class="material-icons">&#xE897;</i></span>-->
<!--                     <span class="menu_title">Add Staff Member Groups</span>-->
<!--                 </a>-->
<!---->
<!--             </li>-->
<!--             <li --><?php //if($active == 'staff'){ echo 'class="act_item"';}?><!-- title="Staff Members">-->
<!--                 <a href="--><?php //echo $url;?><!--members">-->
<!--                     <!--<span class="menu_icon"><i class="material-icons">&#xE897;</i></span>-->
<!--                     <span class="menu_title">Staff Members</span>-->
<!--                 </a>-->
<!---->
<!--             </li>-->
<!--             <li --><?php //if($active == 'menus'){ echo 'class="act_item"';}?><!-- title="Menu Management" >-->
<!--                 <a href="--><?php //echo $url;?><!--menus">-->
<!--                     <!--<span class="menu_icon"><i class="material-icons">&#xE897;</i></span>-->
<!--                     <span class="menu_title">Menu Management</span>-->
<!--                 </a>-->
<!--             </li>-->
<!--             <li --><?php //if($active == 'actions'){ echo 'class="act_item"';}?><!-- title="Menu Actions">-->
<!--                 <a href="--><?php //echo $url;?><!--menu_actions">-->
<!--                     <!--<span class="menu_icon"><i class="material-icons">&#xE897;</i></span>-->
<!--                     <span class="menu_title">Menu Actions</span>-->
<!--                 </a>-->
<!---->
<!--             </li>-->
<!---->
<!--         </ul>-->
<!--     </li>-->
<!---->
<!-- --><?php //} ?>
                <!-- --><?php //if(role_permitted_html(false)){?>
<!--     <li title="Staff Management">-->
<!--         <a href="javascript:void(0);">-->
<!--             <span class="menu_icon"><i class="material-icons">payments</i></span>-->
<!--             <span class="menu_title">Payments</span>-->
<!--         </a>-->
<!--         <ul>-->
<!--             <li --><?php //if($active == 'payments'){ echo 'class="act_item"';}?><!-- title="Payments">-->
<!--                 <a href="--><?php //echo $url;?><!--payments">-->
<!--                     <!--<span class="menu_icon"><i class="material-icons">&#xE897;</i></span>-->
<!--                     <span class="menu_title">Payments</span>-->
<!--                 </a>-->
<!---->
<!--             </li>-->
<!--             <li --><?php //if($active == 'earning_requests'){ echo 'class="act_item"';}?><!-- title="Earning Requests">-->
<!--                 <a href="--><?php //echo $url;?><!--earning_requests">-->
<!--                     <!--<span class="menu_icon"><i class="material-icons">&#xE897;</i></span>-->
<!--                     <span class="menu_title">Earning Requests</span>-->
<!--                 </a>-->
<!---->
<!--             </li>-->
<!--             <li --><?php //if($active == 'earnings'){ echo 'class="act_item"';}?><!-- title="Payment History" >-->
<!--                 <a href="--><?php //echo $url;?><!--earnings">-->
<!--                     <!--<span class="menu_icon"><i class="material-icons">&#xE897;</i></span>-->
<!--                     <span class="menu_title">Payment History</span>-->
<!--                 </a>-->
<!--             </li>-->
<!--             <li --><?php //if($active == 'bulk_payment_upload'){ echo 'class="act_item"';}?><!-- title="Bulk Payment" >-->
<!--                 <a href="--><?php //echo $url;?><!--bulk_payment_upload">-->
<!--                     <!--<span class="menu_icon"><i class="material-icons">&#xE897;</i></span>-->
<!--                     <span class="menu_title">Bulk Payment</span>-->
<!--                 </a>-->
<!--             </li>-->
<!---->
<!---->
<!--         </ul>-->
<!--     </li>-->
<!---->
<!-- --><?php //} ?>
                <?php if($menu_list){
                    foreach($menu_list as $nav){ ?>
                        <li <?php if($active == $nav->active_class){ echo 'class="current_section"';}?> title="<?php echo $nav->menu_name;?>">
                            <?php if(isset($this->data[$nav->menu_name])){ ?>
                                <a href="javascript:void(0);">
                                    <span class="menu_icon"><i class="material-icons"><?php echo $nav->icon_code; ?></i></span>
                                    <span class="menu_title"><?php echo $nav->menu_name; ?></span>
                                </a>
                                <ul>
                                <?php foreach($this->data[$nav->menu_name] as $sub_menu){ ?>
                                    <li <?php if($active == $sub_menu->active_class){ echo 'class="act_item"';}?> title="<?php echo $sub_menu->menu_name; ?>" >
                                    <a href="<?php echo $url.$sub_menu->controller_uri;?>">
                                        <!--<span class="menu_icon"><i class="material-icons">&#xE897;</i></span>-->
                                        <span class="menu_title"><?php echo $sub_menu->menu_name; ?></span>
                                    </a>
                                    </li>
                                <?php } ?>
                                </ul>
                            <?php } 
                            else { ?>
                                <a href="<?php echo $url.$nav->controller_uri;?>">
                                    <span class="menu_icon"><i class="material-icons"><?php echo $nav->icon_code;?></i></span>
                                    <span class="menu_title"><?php echo $nav->menu_name;?></span>
                                </a>
                            <?php } ?>
                        </li>
                    <?php }
                } ?>
                <!--<li <?php /*if($active == 'users'){ echo 'class="current_section"';}*/?> title="Users">
                    <a href="<?php /*echo $url;*/?>users">
                        <span class="menu_icon"><i class="material-icons">&#xE87C;</i></span>
                        <span class="menu_title">Users</span>
                    </a>
                    
                </li>

                <li <?php /*if($active == 'categories'){ echo 'class="current_section"';}*/?> title="Categories">
                    <a href="<?php /*echo $url;*/?>categories">
                        <span class="menu_icon"><i class="material-icons">&#xE6DD;</i></span>
                        <span class="menu_title">Categories</span>
                    </a>
                    
                </li>
                <li <?php /*if($active == 'videos'){ echo 'class="current_section"';}*/?> title="Videos">
                    <a href="<?php /*echo $url;*/?>videos">
                        <span class="menu_icon"><i class="material-icons">&#xE04A;</i></span>
                        <span class="menu_title">Videos</span>
                    </a>
                    
                </li>
                <li <?php /*if($active == 'content'){ echo 'class="current_section"';}*/?> title="Content">
                    <a href="<?php /*echo $url;*/?>content">
                        <span class="menu_icon"><i class="material-icons">&#xE02F;</i></span>
                        <span class="menu_title">Content</span>
                    </a>

                </li>
                
                <li <?php /*if($active == 'channel'){ echo 'class="current_section"';}*/?> title="Channels">
                    <a href="<?php /*echo $url;*/?>channel">
                        <span class="menu_icon"><i class="material-icons">&#xE6DD;</i></span>
                        <span class="menu_title">Channels</span>
                    </a>

                </li>-->
                <?php //if(role_permitted_html(false)){?>
<!--     <li title="App Management">-->
<!--         <a href="javascript:void(0);">-->
<!--             <span class="menu_icon"><i class="material-icons">&#xE85E;</i></span>-->
<!--             <span class="menu_title">App Management</span>-->
<!--         </a>-->
<!--         <ul>-->
<!--             <li --><?php //if($active == 'App Import Videos'){ echo 'class="act_item"';}?><!-- title="App Import Videos">-->
<!--                 <a href="--><?php //echo $url;?><!--import-videos-view">-->
<!--                     <!--<span class="menu_icon"><i class="material-icons">&#xE897;</i></span>-->
<!--                     <span class="menu_title">Import Videos</span>-->
<!--                 </a>-->
<!---->
<!--             </li>-->
<!--             <li --><?php //if($active == 'Mobile Categories Management'){ echo 'class="act_item"';}?><!-- title="Mobile Categories Management">-->
<!--                 <a href="--><?php //echo $url;?><!--mobile-app-categories">-->
<!--                     <!--<span class="menu_icon"><i class="material-icons">&#xE897;</i></span>-->
<!--                     <span class="menu_title">Mobile Categories Management</span>-->
<!--                 </a>-->
<!---->
<!--             </li>-->
<!--             <li --><?php //if($active == 'Mobile App Videos'){ echo 'class="act_item"';}?><!-- title="Mobile App Videos" >-->
<!--                 <a href="--><?php //echo $url;?><!--mobile-app-videos">-->
<!--                     <!--<span class="menu_icon"><i class="material-icons">&#xE897;</i></span>-->
<!--                     <span class="menu_title">Videos Management</span>-->
<!--                 </a>-->
<!--             </li>-->
<!--             <li --><?php //if($active == 'Mobile App Users'){ echo 'class="act_item"';}?><!-- title="Mobile App Users">-->
<!--                 <a href="--><?php //echo $url;?><!--mobile-app-users">-->
<!--                     <!--<span class="menu_icon"><i class="material-icons">&#xE897;</i></span>-->
<!--                     <span class="menu_title">Mobile App Users</span>-->
<!--                 </a>-->
<!---->
<!--             </li>-->
<!--             <li --><?php //if($active == 'Mobile App Comments'){ echo 'class="act_item"';}?><!-- title="Mobile App Comments">-->
<!--                 <a href="--><?php //echo $url;?><!--mobile-app-comments">-->
<!--                     <!--<span class="menu_icon"><i class="material-icons">&#xE897;</i></span>-->
<!--                     <span class="menu_title">Mobile App Comments</span>-->
<!--                 </a>-->
<!---->
<!--             </li>-->
<!---->
<!--         </ul>-->
<!--     </li>-->
<!---->
<!-- --><?php //} ?>
               
            </ul>
        </div>
    </aside><!-- main sidebar end -->
