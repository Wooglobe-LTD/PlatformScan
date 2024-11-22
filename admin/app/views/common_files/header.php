<style>

    .search_hdr {
        width: 100%;
        position: relative
    }

    .searchTerm {
        float: left;
        width: 100%;
        border: 0px solid #cccccc;
        padding: 5px;
        height: 25px;
        border-radius: 3px;
        outline: none;
        color: #444;
    }

    .searchTerm:focus{
        color: #444;
    }
    .searchButton i{
        color: #1976d2;
        font-size: 25px;
    }
    .searchButton {
        position: absolute;
        right: -46px;
        width: 40px;
        height: 35px;
        border: 0px solid #cccccc;
        background: #ffffff;
        text-align: center;
        color: #fff;
        border-radius: 3px;
        cursor: pointer;
        font-size: 20px;
    }

    /*Resize the wrap to see the search bar change!*/
    .wrap_hdr{
        width: 30%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
</style>

<!-- main header -->
    <header id="header_main">
        <div class="header_main_content">
            <nav class="uk-navbar">
                                
                <!-- main sidebar switch -->
                <a href="#" id="sidebar_main_toggle" class="sSwitch sSwitch_left">
                    <span class="sSwitchIcon"></span>
                </a>
                
                <!-- secondary sidebar switch -->
                <a href="#" id="sidebar_secondary_toggle" class="sSwitch sSwitch_right sidebar_secondary_check">
                    <span class="sSwitchIcon"></span>
                </a>
                <a href="javascript:window.history.go(-1);" id="" class="sSwitch sSwitch_right" style="float: left; color: white; padding: 2px 0px;margin-left: 5px; font-size: 16px; font-weight: bold;">
                    Back
                </a>

                <div class="wrap_hdr">
                    <div class="search_hdr">
                        <form class="uk-search uk-search-default" action="<?php echo $url."search"?>" method="get">
                            <?php   $search = "";
                            if(isset($_GET['search']) && !empty($_GET['search'])) {
                                $search = $_GET['search'];
                            }
                            ?>
                        <input name="search" type="text" class="searchTerm" placeholder="What are you looking for?" value="<?php echo $search;?>">
                        <button type="submit" class="searchButton">
                            <i class="material-icons">search</i>
                        </button>
                        </form>
                    </div>
                </div>

                
                <div class="uk-navbar-flip">
                    <ul class="uk-navbar-nav user_actions">
                       
                        
                        <li data-uk-dropdown="{mode:'click',pos:'bottom-right'}">
                            <a href="#" class="user_action_image"><img class="md-user-image" src="<?php echo $asset;?>assets/img/avatars/avatar_11_tn.png" alt=""/></a>
                            <div class="uk-dropdown uk-dropdown-small">
                                <ul class="uk-nav js-uk-prevent">
                                    <!--<li><a href="javascript:void(0);" data-uk-modal="{target:'#profile'}">My profile</a></li>-->
                                    <li><a href="javascript:void(0);" id="change_password">Change Password</a></li>
                                    <li><a href="javascript:void(0);" id="site_settings">Settings</a></li>
                                    <li><a href="<?php echo $url;?>logout">Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        
    </header><!-- main header end -->