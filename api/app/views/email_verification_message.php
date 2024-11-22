<!DOCTYPE html>
<html class=" js csstransitions" lang="zxx">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style type="text/css">
.gm-style .gm-style-mtc label, .gm-style .gm-style-mtc div {
    font-weight: 400
}
.gm-control-active>img {
    box-sizing: content-box;
    display: none;
    left: 50%;
    pointer-events: none;
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%)
}
.gm-control-active>img:nth-child(1) {
    display: block
}
.gm-control-active:hover>img:nth-child(1), .gm-control-active:active>img:nth-child(1) {
    display: none
}
.gm-control-active:hover>img:nth-child(2), .gm-control-active:active>img:nth-child(3) {
    display: block
}
.gm-ui-hover-effect {
    opacity: .6
}
.gm-ui-hover-effect:hover {
    opacity: 1
}
.gm-style .gm-style-cc span, .gm-style .gm-style-cc a, .gm-style .gm-style-mtc div {
    font-size: 10px;
    box-sizing: border-box
}
@media print {
.gm-style .gmnoprint, .gmnoprint {
    display: none
}
}

@media screen {
.gm-style .gmnoscreen, .gmnoscreen {
    display: none
}
}
.gm-style-pbc {
    transition: opacity ease-in-out;
    background-color: rgba(0,0,0,0.45);
    text-align: center
}
.gm-style-pbt {
    font-size: 22px;
    color: white;
    font-family: Roboto, Arial, sans-serif;
    position: relative;
    margin: 0;
    top: 50%;
    -webkit-transform: translateY(-50%);
    -ms-transform: translateY(-50%);
    transform: translateY(-50%)
}
.gm-style img {
    max-width: none;
}
.gm-style {
    font: 400 11px Roboto, Arial, sans-serif;
    text-decoration: none;
}
.contact-details .wpcf7-form input[type="text"]{
         width: 100%;
         margin-bottom: 15px;
     }
    .contact-details .wpcf7-form input[type="email"]{
        width: 100%;
        margin-bottom: 15px;
    }
    .contact-details .wpcf7-form textarea{
        width: 100%;
        margin-bottom: 15px;
    }
    .parsley-errors-list li{
        margin-bottom: 15px;
    }
    .error p {
    color: #ff000d !important;
}
.pixelParallel-panel[data-v-54cb82b4] {
    position: fixed;
    right: 10px;
    bottom: 10px;
    z-index: 2147483647;
    width: 375px;
    height: 265px;
    transform: translate(0);
    background: #fff;
    transition: width .2s ease-out, height .2s ease-out;
    will-change: top, left, width, height, transform;
    opacity: 0
}
.pixelParallel-panel-inner[data-v-54cb82b4] {
    position: relative;
    overflow: hidden;
    height: 100%;
    border: 1px solid #dddedf;
    box-shadow: 0 2px 10px rgba(0,0,0,.1);
    box-sizing: border-box
}
.pixelParallel-panel-handle[data-v-54cb82b4] {
    position: absolute;
    top: -10px;
    right: -10px;
    z-index: 3;
    width: 20px;
    height: 20px;
    background: red;
    transform: rotate(45deg);
    background: linear-gradient(0deg, transparent, transparent 50%, #ccc 0, #ccc);
    background-size: 100% 2px;
    cursor: move
}
.pixelParallel-panel-isolator[data-v-54cb82b4] {
    border: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    position: relative
}
.pixelParallel-panel-dragging .pixelParallel-panel-inner[data-v-54cb82b4] {
    pointer-events: none
}
.pixelParallel-panel-minimized[data-v-54cb82b4] {
    width: 112px;
    height: 50px;
    transition: width .2s ease-out .15s, height .2s ease-out .15s
}

@media (max-width:395px) {
.pixelParallel-panel[data-v-54cb82b4] {
    right: 0;
    bottom: 0;
    width: 320px
}
.pixelParallel-panel-minimized[data-v-54cb82b4] {
    width: 112px
}
}
   .pixelParallel-overlay {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    text-align: center;
    white-space: nowrap;
    display: none
}
.pixelParallel-overlay-enabled {
    display: block
}
 .pixelParallel-image-outer {
    visibility: hidden;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    overflow: hidden;
    max-width: 100vw;
    min-height: 100vh
}
.pixelParallel-image-inner {
    position: absolute;
    z-index: 2147483646;
    left: 50%;
    top: 0;
    cursor: move;
    transform: translateX(-50%);
    transition: opacity .15s
}
.pixelParallel-image-inner img {
    width: auto;
    height: auto;
    max-width: none;
    max-height: none;
    vertical-align: top;
    margin: 0;
    padding: 0;
    position: relative;
    transform-origin: 50% 0;
    top: -1px;
    border: 1px dashed #333
}
.pixelParallel-image-inner img:not([src]), .pixelParallel-image-inner img[src=""] {
    visibility: hidden
}
.pixelParallel-image-enabled {
    visibility: visible
}
.pixelParallel-image-difference {
    mix-blend-mode: difference
}
.pixelParallel-image-difference img {
    opacity: 1!important
}
.pixelParallel-image-locked, .pixelParallel-image-no-image {
    pointer-events: none
}
.pixelParallel-image-locked .pixelParallel-image-inner img {
    top: 0;
    border: 0 none
}
.pixelParallel-grids {
    position: relative;
    z-index: 2147483646;
    pointer-events: none
}
.pixelParallel-grid-horizontal, .pixelParallel-grid-vertical {
    position: fixed;
    z-index: 1;
    pointer-events: none;
    visibility: hidden
}
.pixelParallel-grid-horizontal {
    top: 50%;
    left: 50%;
    display: table;
    width: 100vw;
    height: 200vh;
    opacity: .5;
    table-layout: fixed;
    border-spacing: 30px;
    transform: translate(-50%, -50%)
}
.pixelParallel-grid-horizontal span {
    display: table-cell;
    background: red;
    height: 200vh
}
.pixelParallel-grid-vertical {
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(180deg, transparent, transparent 90%, blue 0, blue);
    background-size: 100% 10px;
    opacity: .5
}
.pixelParallel-grid-horizontal-enabled, .pixelParallel-grid-vertical-enabled {
    visibility: visible
}
.pixelParallel-rulers {
    position: relative;
    z-index: 2147483646
}
.pixelParallel-ruler-x, .pixelParallel-ruler-y {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 2;
    background: cyan
}
.pixelParallel-ruler-x:after, .pixelParallel-ruler-y:after {
    content: "";
    position: absolute;
    top: -8px;
    left: -8px;
    right: 0;
    bottom: 0;
    padding: 10px
}
.pixelParallel-ruler-x {
    right: 0;
    height: 1px;
    cursor: row-resize
}
.pixelParallel-ruler-y {
    bottom: 0;
    width: 1px;
    cursor: col-resize
}
.pixelParallel-rulers-enabled .pixelParallel-ruler-x, .pixelParallel-rulers-enabled .pixelParallel-ruler-y {
    display: block
}
.fb_hidden {
    position: absolute;
    top: -10000px;
    z-index: 10001
}
.fb_reposition {
    overflow: hidden;
    position: relative
}
.fb_invisible {
    display: none
}
.fb_reset {
    background: none;
    border: 0;
    border-spacing: 0;
    color: #000;
    cursor: auto;
    direction: ltr;
    font-family: "lucida grande", tahoma, verdana, arial, sans-serif;
    font-size: 11px;
    font-style: normal;
    font-variant: normal;
    font-weight: normal;
    letter-spacing: normal;
    line-height: 1;
    margin: 0;
    overflow: visible;
    padding: 0;
    text-align: left;
    text-decoration: none;
    text-indent: 0;
    text-shadow: none;
    text-transform: none;
    visibility: visible;
    white-space: normal;
    word-spacing: normal
}
.fb_reset>div {
    overflow: hidden
}
@keyframes fb_transform {
from {
opacity:0;
transform:scale(.95)
}
to {
opacity:1;
transform:scale(1)
}
}
.fb_animate {
    animation: fb_transform .3s forwards
}
.fb_dialog {
    background: rgba(82, 82, 82, .7);
    position: absolute;
    top: -10000px;
    z-index: 10001
}
.fb_dialog_advanced {
    border-radius: 8px;
    padding: 10px
}
.fb_dialog_content {
    background: #fff;
    color: #373737
}
.fb_dialog_close_icon {
    background: url(https://static.xx.fbcdn.net/rsrc.php/v3/yq/r/IE9JII6Z1Ys.png) no-repeat scroll 0 0 transparent;
    cursor: pointer;
    display: block;
    height: 15px;
    position: absolute;
    right: 18px;
    top: 17px;
    width: 15px
}
.fb_dialog_mobile .fb_dialog_close_icon {
    left: 5px;
    right: auto;
    top: 5px
}
.fb_dialog_padding {
    background-color: transparent;
    position: absolute;
    width: 1px;
    z-index: -1
}
.fb_dialog_close_icon:hover {
    background: url(https://static.xx.fbcdn.net/rsrc.php/v3/yq/r/IE9JII6Z1Ys.png) no-repeat scroll 0 -15px transparent
}
.fb_dialog_close_icon:active {
    background: url(https://static.xx.fbcdn.net/rsrc.php/v3/yq/r/IE9JII6Z1Ys.png) no-repeat scroll 0 -30px transparent
}
.fb_dialog_iframe {
    line-height: 0
}
.fb_dialog_content .dialog_title {
    background: #6d84b4;
    border: 1px solid #365899;
    color: #fff;
    font-size: 14px;
    font-weight: bold;
    margin: 0
}
.fb_dialog_content .dialog_title>span {
    background: url(https://static.xx.fbcdn.net/rsrc.php/v3/yd/r/Cou7n-nqK52.gif) no-repeat 5px 50%;
    float: left;
    padding: 5px 0 7px 26px
}
body.fb_hidden {
    height: 100%;
    left: 0;
    margin: 0;
    overflow: visible;
    position: absolute;
    top: -10000px;
    transform: none;
    width: 100%
}
.fb_dialog.fb_dialog_mobile.loading {
    background: url(https://static.xx.fbcdn.net/rsrc.php/v3/ya/r/3rhSv5V8j3o.gif) white no-repeat 50% 50%;
    min-height: 100%;
    min-width: 100%;
    overflow: hidden;
    position: absolute;
    top: 0;
    z-index: 10001
}
.fb_dialog.fb_dialog_mobile.loading.centered {
    background: none;
    height: auto;
    min-height: initial;
    min-width: initial;
    width: auto
}
.fb_dialog.fb_dialog_mobile.loading.centered #fb_dialog_loader_spinner {
    width: 100%
}
.fb_dialog.fb_dialog_mobile.loading.centered .fb_dialog_content {
    background: none
}
.loading.centered #fb_dialog_loader_close {
    clear: both;
    color: #fff;
    display: block;
    font-size: 18px;
    padding-top: 20px
}
#fb-root #fb_dialog_ipad_overlay {
    background: rgba(0, 0, 0, .4);
    bottom: 0;
    left: 0;
    min-height: 100%;
    position: absolute;
    right: 0;
    top: 0;
    width: 100%;
    z-index: 10000
}
#fb-root #fb_dialog_ipad_overlay.hidden {
    display: none
}
.fb_dialog.fb_dialog_mobile.loading iframe {
    visibility: hidden
}
.fb_dialog_mobile .fb_dialog_iframe {
    position: sticky;
    top: 0
}
.fb_dialog_content .dialog_header {
    background: linear-gradient(from(#738aba), to(#2c4987));
    border-bottom: 1px solid;
    border-color: #1d3c78;
    box-shadow: white 0 1px 1px -1px inset;
    color: #fff;
    font: bold 14px Helvetica, sans-serif;
    text-overflow: ellipsis;
    text-shadow: rgba(0, 30, 84, .296875) 0 -1px 0;
    vertical-align: middle;
    white-space: nowrap
}
.fb_dialog_content .dialog_header table {
    height: 43px;
    width: 100%
}
.fb_dialog_content .dialog_header td.header_left {
    font-size: 12px;
    padding-left: 5px;
    vertical-align: middle;
    width: 60px
}
.fb_dialog_content .dialog_header td.header_right {
    font-size: 12px;
    padding-right: 5px;
    vertical-align: middle;
    width: 60px
}
.fb_dialog_content .touchable_button {
    background: linear-gradient(from(#4267B2), to(#2a4887));
    background-clip: padding-box;
    border: 1px solid #29487d;
    border-radius: 3px;
    display: inline-block;
    line-height: 18px;
    margin-top: 3px;
    max-width: 85px;
    padding: 4px 12px;
    position: relative
}
.fb_dialog_content .dialog_header .touchable_button input {
    background: none;
    border: none;
    color: #fff;
    font: bold 12px Helvetica, sans-serif;
    margin: 2px -12px;
    padding: 2px 6px 3px 6px;
    text-shadow: rgba(0, 30, 84, .296875) 0 -1px 0
}
.fb_dialog_content .dialog_header .header_center {
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    line-height: 18px;
    text-align: center;
    vertical-align: middle
}
.fb_dialog_content .dialog_content {
    background: url(https://static.xx.fbcdn.net/rsrc.php/v3/y9/r/jKEcVPZFk-2.gif) no-repeat 50% 50%;
    border: 1px solid #4a4a4a;
    border-bottom: 0;
    border-top: 0;
    height: 150px
}
.fb_dialog_content .dialog_footer {
    background: #f5f6f7;
    border: 1px solid #4a4a4a;
    border-top-color: #ccc;
    height: 40px
}
#fb_dialog_loader_close {
    float: left
}
.fb_dialog.fb_dialog_mobile .fb_dialog_close_button {
    text-shadow: rgba(0, 30, 84, .296875) 0 -1px 0
}
.fb_dialog.fb_dialog_mobile .fb_dialog_close_icon {
    visibility: hidden
}
#fb_dialog_loader_spinner {
    animation: rotateSpinner 1.2s linear infinite;
    background-color: transparent;
    background-image: url(https://static.xx.fbcdn.net/rsrc.php/v3/yD/r/t-wz8gw1xG1.png);
    background-position: 50% 50%;
    background-repeat: no-repeat;
    height: 24px;
    width: 24px
}
@keyframes rotateSpinner {
0% {
transform:rotate(0deg)
}
100% {
transform:rotate(360deg)
}
}
.fb_iframe_widget {
    display: inline-block;
    position: relative
}
.fb_iframe_widget span {
    display: inline-block;
    position: relative;
    text-align: justify
}
.fb_iframe_widget iframe {
    position: absolute
}
.fb_iframe_widget_fluid_desktop, .fb_iframe_widget_fluid_desktop span, .fb_iframe_widget_fluid_desktop iframe {
    max-width: 100%
}
.fb_iframe_widget_fluid_desktop iframe {
    min-width: 220px;
    position: relative
}
.fb_iframe_widget_lift {
    z-index: 1
}
.fb_iframe_widget_fluid {
    display: inline
}
.fb_iframe_widget_fluid span {
    width: 100%
}
.fb_customer_chat_bounce_in_v2 {
    animation-duration: 300ms;
    animation-name: fb_bounce_in_v2;
    transition-timing-function: ease-in
}
.fb_customer_chat_bounce_out_v2 {
    animation-duration: 300ms;
    animation-name: fb_bounce_out_v2;
    transition-timing-function: ease-in
}
.fb_customer_chat_bounce_in_v2_mobile_chat_started {
    animation-duration: 300ms;
    animation-name: fb_bounce_in_v2_mobile_chat_started;
    transition-timing-function: ease-in
}
.fb_customer_chat_bounce_out_v2_mobile_chat_started {
    animation-duration: 300ms;
    animation-name: fb_bounce_out_v2_mobile_chat_started;
    transition-timing-function: ease-in
}
.fb_customer_chat_bubble_pop_in {
    animation-duration: 250ms;
    animation-name: fb_customer_chat_bubble_bounce_in_animation
}
.fb_customer_chat_bubble_animated_no_badge {
    box-shadow: 0 3px 12px rgba(0, 0, 0, .15);
    transition: box-shadow 150ms linear
}
.fb_customer_chat_bubble_animated_no_badge:hover {
    box-shadow: 0 5px 24px rgba(0, 0, 0, .3)
}
.fb_customer_chat_bubble_animated_with_badge {
    box-shadow: -5px 4px 14px rgba(0, 0, 0, .15);
    transition: box-shadow 150ms linear
}
.fb_customer_chat_bubble_animated_with_badge:hover {
    box-shadow: -5px 8px 24px rgba(0, 0, 0, .2)
}
.fb_invisible_flow {
    display: inherit;
    height: 0;
    overflow-x: hidden;
    width: 0
}
.fb_mobile_overlay_active {
    background-color: #fff;
    height: 100%;
    overflow: hidden;
    position: fixed;
    visibility: hidden;
    width: 100%
}
@keyframes fb_bounce_in_v2 {
0% {
opacity:0;
transform:scale(0, 0);
transform-origin:bottom right
}
50% {
transform:scale(1.03, 1.03);
transform-origin:bottom right
}
100% {
opacity:1;
transform:scale(1, 1);
transform-origin:bottom right
}
}
@keyframes fb_bounce_in_v2_mobile_chat_started {
0% {
opacity:0;
top:20px
}
100% {
opacity:1;
top:0
}
}
@keyframes fb_bounce_out_v2 {
0% {
opacity:1;
transform:scale(1, 1);
transform-origin:bottom right
}
100% {
opacity:0;
transform:scale(0, 0);
transform-origin:bottom right
}
}
@keyframes fb_bounce_out_v2_mobile_chat_started {
0% {
opacity:1;
top:0
}
100% {
opacity:0;
top:20px
}
}
@keyframes fb_customer_chat_bubble_bounce_in_animation {

0% {
bottom:6pt;
opacity:0;
transform:scale(0, 0);
transform-origin:center
}
70% {
bottom:18pt;
opacity:1;
transform:scale(1.2, 1.2)
}
100% {
transform:scale(1, 1)
}
}   
</style>
<meta property="og:title" content="WooGlobe">
<meta property="og:description" content="WooGlobe is a leader in user-generated content connecting creators and distributors around the world.">
<meta property="og:image" content="http://wooglobe.com/images/meta-logo.png">
<meta property="og:url" content="https://wooglobe.com/contact-us">
<meta name="twitter:title" content="WooGlobe">
<meta name="twitter:description" content="WooGlobe is a leader in user-generated content connecting creators and distributors around the world.">
<meta name="twitter:image" content="http://wooglobe.com/images/meta-logo.png">
<meta name="twitter:card" content="summary_large_image">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Contact Us | WooGlobe</title>
<link rel="icon" type="image/png" sizes="16x16" href="https://wooglobe.com/images/favi/favicon-16x16.png">
<meta name="description" content="WooGlobe is a leader in user-generated content connecting creators and distributors around the world.">
<meta name="keywords" content="Trending Videos, Viral Videos, Best Funny Videos, Epic Fail,  Dash Cam Videos, Stunts Completion and Sports Videos, Crashed Videos, Natural Disasters, Fighting Videos, Animal Videos, Emotional Videos">
<meta name="author" content="wooglobe.com">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="apple-touch-icon" href="https://wooglobe.com/apple-touch-icon.png">
<link rel="stylesheet" href="<?php echo base_url();?>assets/email_verification/all.css">
<script defer src="<?php echo base_url();?>assets/email_verification/all.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>assets/email_verification/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/email_verification/style.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/email_verification/header.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/email_verification/themes.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/email_verification/responsive.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/email_verification/style1.css">
</head>
<body class="">
<header class="header">
  <div class="header-top" style="display: none;">
    <div class="container">
      <div class="row">
        <div class="col-sm-8">
          <div class="top-sitemap text-left"> <span> <a href="https://wooglobe.com/login"><i class="fa fa-sign-in"></i> Login In</a></span> <span><a href="https://wooglobe.com/partner/categories"><i class="fa fa-users"></i> Partner Portal</a></span> <span><a href="javascript:void(0);" class="upload"><i class="fa fa-upload"></i> Upload Videos</a></span> </div>
        </div>
        <div class="col-sm-4"> </div>
      </div>
    </div>
  </div>
  <div class="header-middle" style="display: none;">
    <div class="container">
      <div class="row">
        <div class="col-sm-2"> </div>
        <div class="col-sm-10" style="height: 95px"> </div>
      </div>
    </div>
  </div>
  <div class="header-bottom">
    <div class="container">
      <div class="row">
        <div class="col-sm-2">
          <div class="navbar-brand hidden-xs"><a href="https://wooglobe.com/"><img src="<?php echo base_url();?>assets/email_verification/logo.png" alt="Site Logo" class="site-logo"></a></div>
        </div>
        <div class="col-sm-8">
          <nav class="navbar navbar-default">
            <div class="navbar-header visible-xs">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu" aria-expanded="false"> <i class="fa fa-bars"></i> </button>
              <a class="navbar-brand" href="https://wooglobe.com/"><img src="Contact Us _ WooGlobe_files/logo.png" alt="Logo"></a> </div>
            <div id="menu" class="main-menu collapse navbar-collapse pull-left">
              <ul class="nav navbar-nav">
                <li class="menu-item "> <a href="https://wooglobe.com/">Home</a> </li>
                <li class="menu-item "> <a href="https://wooglobe.com/partner/categories">Buy Video</a> </li>
                <li class="menu-item"> <a href="javascript:void(0)" class="upload">Submit Video</a> </li>
                <li class="menu-item abt "> <a href="https://wooglobe.com/faq">Faq<span style="text-transform: lowercase;">s</span></a> </li>
                <li class="menu-item "> <a href="https://wooglobe.com/contact-us">Contact Us</a> </li>
              </ul>
            </div>
          </nav>
        </div>
        <div class="col-sm-2" style="position: relative;">
          <div class="signing-sec" style="display: none"> <a class="lock" href="https://wooglobe.com/login"> <span><i class="fa fa-lock" aria-hidden="true"></i></span> Log In </a> </div>
        </div>
        <div style="display: none;"> </div>
      </div>
    </div>
  </div>
</header>
<div class="content">
  <section class="contact-details">
    <div class="section-padding">
      <div class="container">
        <div class="row">
          <div>
            <h2 class="section-title"><span>STATUS</span></h2>
            <div> <?php echo $message ?> </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<div class="preloadr-div"></div>
<footer class="site-footer">
  <div class="footer-bottom">
    <div class="padding">
      <div class="container">
        <div class="row">
          <div class="col-md-8">
            <nav>
              <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Buy Videos</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Faqs</a></li>
                <li><a href="#">Contact Us</a></li>
              </ul>
            </nav>
            <div class="copyright"> © <a href="#">WooGlobe</a> 2019 </div>
          </div>
          <div class="col-md-4">
            <h4 class="sub-txt">Subscribe To Our Email And Get Updated</h4>
            <div class="ftr-newsletter">
              <input type="email" placeholder="email@example.com" name="mail_newsletter" id="mail_newsletter">
              <button type="button" id="button_newsletter" class="ftr-newsletter-btn"><i class="fa fa-paper-plane"></i> </button>
            </div>
            <div class="error" id="err_newsletter" style="text-align: center;"></div>
            <div class="top-sitemap right"> <a class="youtube" href="https://www.youtube.com/channel/UCbbtHuBeqqlRB9yNr_Hpc7w" target="_blank"> <i class="fab fa-youtube"></i> </a> <a class="facebook" href="https://www.facebook.com/WooGlobe/" target="_blank"> <i class="fab fa-facebook-f"></i> </a> <a class="instagram" href="https://www.instagram.com/wooglobe/?hl=en" target="_blank"> <i class="fab fa-instagram"></i> </a> <a class="twitter" href="https://twitter.com/wooglobe?lang=en" target="_blank"> <i class="fab fa-twitter"></i> </a> </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="beta_version" style="display: none">Beta Version</div>
</footer>
</body>
</html>