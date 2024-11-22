<?php
/**
 * Created by PhpStorm.
 * User: Usman Ali Sarwar
 * Date: 16/01/2018
 * Time: 2:54 PM
 */
?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="zxx"> <!--<![endif]-->
<head>
	<meta property="og:title" content="<?php echo $share_title;?>">
	<meta property="og:description" content="<?php echo $share_description;?>">
	<meta property="og:image" content="<?php echo str_replace('https','http',$share_image);?>">
	<meta property="og:url" content="<?php echo $share_url;?>">
	<meta name="twitter:title" content="<?php echo $share_title;?>">
	<meta name="twitter:description" content="<?php echo $share_description;?>">
	<meta name="twitter:image" content="<?php echo str_replace('https','http',$share_image);?>">
	<meta name="twitter:card" content="summary_large_image">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $title;?> | <?php echo $site_title;?></title>
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $image;?>favi/favicon-16x16.png">
    <meta charset="UTF-8">
    <meta name="description" content="<?php echo $description;?>">
    <meta name="keywords" content="<?php echo $keywords;?>">
    <meta name="author" content="wooglobe.com">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link rel="apple-touch-icon" href="apple-touch-icon.png">

    <link rel="stylesheet" type="text/css" href="<?php echo $assets;?>css/slick.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $assets;?>css/slick-theme.css">

    <!-- ========= FontAwesome Icon Css File ========= -->
    <link rel="stylesheet" href="<?php echo $assets;?>css/themify-icons.css">

    <!-- ========= Themify Icon Css File ========= -->
    <link rel="stylesheet" href="<?php echo $assets;?>css/font-awesome.min.css">

    <!-- ========= Bootstrap Css File ========= -->
    <link rel="stylesheet" href="<?php echo $assets;?>css/bootstrap.min.css">

    <!-- ========= Magnific PopUp Css File ========= -->
    <link rel="stylesheet" href="<?php echo $assets;?>css/magnific-popup.css">

    <!-- ========= Owl Carousel Css File ========= -->
    <link rel="stylesheet" href="<?php echo $assets;?>css/owl.carousel.css">

    <!-- ========= Animate Css File ========= -->
    <link rel="stylesheet" href="<?php echo $assets;?>css/animate.min.css">

    <!-- ========= Template Default Css File ========= -->
    <link rel="stylesheet" href="<?php echo $assets;?>css/style.css">

    <!-- ========= Template Menu Css File ========= -->
    <link rel="stylesheet" href="<?php echo $assets;?>css/header.css">

    <!-- ========= Template Main Css File ========= -->
    <link rel="stylesheet" href="<?php echo $assets;?>css/themes.css">

    <!-- ========= Template Responsive Style Css File ========= -->
    <link rel="stylesheet" href="<?php echo $assets;?>css/responsive.css">

    <link rel="stylesheet" href="<?php echo $assets;?>jquery-ui/css/ui-lightness/jquery-ui.custom.css" type="text/css">

    <link rel="stylesheet" href="<?php echo $assets;?>css/custom.css">

    <link rel="stylesheet" href="<?php echo $assets;?>toastr/toastr.min.css">

    <link rel="stylesheet" type="text/css" href="<?php echo $assets ?>datatables/css/jquery.dataTables.css">

    <script src="<?php echo $assets;?>js/modernizr.custom.js"></script>

    <link rel="stylesheet" href="<?php echo $assets;?>zebra/dist/css/bootstrap/zebra_datepicker.min.css" type="text/css">

    <link rel="stylesheet" href="<?php echo $assets;?>dropify/dist/css/dropify.css" type="text/css">
    <link rel="stylesheet" href="<?php echo $assets;?>zone/dist/min/dropzone.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo $assets;?>css/style1.css">
    <link rel="stylesheet" href="<?php echo $assets;?>css/accordian.css">
    <link rel="stylesheet" href="<?php echo $assets;?>css/multiform.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $assets?>tooltipster/dist/css/tooltipster.bundle.min.css">
    <!--smart search starts-->
    <link href="<?php echo $assets;?>css/jquery.autocompleter.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?php echo $assets;?>css/export.css" type="text/css" media="all" />
    <!--<link href="<?php /*echo $assets;*/?>plyr/dist/plyr.css" rel="stylesheet" type="text/css">-->
    <!--smart search ends-->

    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->
    <style>
        .error p{

            color: #ff000d !important;
        }
    </style>


</head>



<body class="<?php echo $body_class;?>">





<?php $this->load->view('common_files/header');
if($banner === true) {
    //$this->load->view('common_files/banner');
}
print("<div class='content'>");
 echo $content;
print("</div>");
 $this->load->view('common_files/footer');?>
<script>
    var base_url = "<?php echo $url;?>";
    var tags = '<?php echo $autocomplete;?>';
    console.log(tags);
    //tags = JSON.parse(tags);
    

</script>
<!-- START: Mobile Autoplay Video -->

<!-- END: Mobile Autoplay Video -->

<script src="<?php echo $assets;?>js/plugins.js"></script> 
<script src="<?php echo $assets;?>parsley/dist/parsley.min.js"></script>
<script src="<?php echo $assets;?>toastr/toastr.min.js"></script>
<script src="<?php echo $assets;?>zebra/dist/zebra_datepicker.min.js"></script>
<script src="<?php echo $assets;?>dropify/dist/js/dropify.min.js"></script>
<script src="<?php echo $assets;?>zone/dist/dropzone.js"></script>
<script src="<?php echo $assets;?>jquery-ui/js/jquery-ui.custom.js"></script>
<script src="<?php echo $assets;?>datatables/js/jquery.dataTables.js"></script>
<script src="<?php echo $assets;?>js/jSignature-master/libs/flashcanvas.js"></script>
<script src="<?php echo $assets;?>js/jSignature-master/libs/jSignature.min.js"></script>
<script type="text/javascript" src="<?php echo $assets?>tooltipster/dist/js/tooltipster.bundle.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js"></script>
<!--<script src="<?php /*echo $assets;*/?>plyr/dist/plyr.min.js"></script>-->
<script src="<?php echo $assets;?>js/main.js"></script>
<script>

</script>

<!--country flag starts here-->
<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
<script src="<?php /*echo $assets;*/?>flagDrop/bootstrap-select.min.js"></script>
<script src="<?php /*echo $assets;*/?>flagDrop/countrypicker.js"></script>-->
<!--country flag ends here-->

<!--chosen.js single and multiselect-->
<link rel="stylesheet" href="<?php echo $assets;?>chosen_selector/chosen.css">
<script src="<?php echo $assets;?>chosen_selector/chosen.jquery.js" type="text/javascript"></script>
<script>
    $("#country_id").chosen({no_results_text: "Oops, nothing found!"});
</script>
<script>
    $("#country_code").chosen({no_results_text: "Oops, nothing found!"});
</script>

<!--<script>
    $("#state_id").chosen({no_results_text: "Oops, nothing found!"});
</script>
<script>
    $("#city_id").chosen({no_results_text: "Oops, nothing found!"});
</script>-->
<!--chosen.js single and multiselect-->


<?php foreach ($js as $script){?>
    <script src="<?php echo $assets;?>js/<?php echo $script;?>.js"></script>
<?php } ?>
<?php
if(isset($upload_js)){
    foreach ($upload_js as $script){?>
        <script src="<?php echo $assets;?>vid_up/<?php echo $script;?>.js"></script>
    <?php }
} ?>


<script>
    <?php if($this->sess->flashdata('msg') != ''){?>
    toastr.success('<?php echo $this->sess->flashdata('msg');?>');
    <?php } ?>
    <?php if($this->sess->flashdata('err') != ''){?>
    toastr.error('<?php echo $this->sess->flashdata('err');?>');
    <?php } ?>
</script>

<!--dashboard starts here-->

<script>

</script>
<script>
    /*$('.term-list').each(function(){
        var lis = $(this).find('.dash_vid_listing:gt(9)');
        if(!$(this).hasClass('expanded')) {
            lis.hide(0);
        } else {
            lis.show(0);
        }

        if(lis.length>0){
            $(this).append($('<div class="expand"><span>More</span></div>').click(function(event){
                var $expandible = $(this).parents('.expandible');
                $expandible.toggleClass('expanded');
                if ( !$expandible.hasClass('expanded')) {
                    $(this).text('More');
                } else {
                    $(this).text('Less');
                };
                lis.toggle();
                event.preventDefault();
            }));
        }
    });*/

</script>

    <!--video uploader starts here-->
<link rel="shortcut icon" href="https://innostudio.de/fileuploader/images/favicon.ico">

    <!-- fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
    <link href="<?php echo $assets;?>vid_up/font/font-fileuploader.css" rel="stylesheet">
        
    
    <!-- styles -->
    <link href="<?php echo $assets;?>vid_up/jquery.fileuploader.min.css" media="all" rel="stylesheet">
    <link href="<?php echo $assets;?>vid_up/jquery.fileuploader-theme-dragdrop.css" media="all" rel="stylesheet">
    
    <!-- js -->

<!--video upploader ends here-->


<!--<script type="text/javascript">
function sliceSize(dataNum, dataTotal) {
  return (dataNum / dataTotal) * 360;
}
function addSlice(sliceSize, pieElement, offset, sliceID, color) {
  $(pieElement).append("<div class='slice "+sliceID+"'><span></span></div>");
  var offset = offset - 1;
  var sizeRotation = -179 + sliceSize;
  $("."+sliceID).css({
    "transform": "rotate("+offset+"deg) translate3d(0,0,0)"
  });
  $("."+sliceID+" span").css({
    "transform"       : "rotate("+sizeRotation+"deg) translate3d(0,0,0)",
    "background-color": color
  });
}
function iterateSlices(sliceSize, pieElement, offset, dataCount, sliceCount, color) {
  var sliceID = "s"+dataCount+"-"+sliceCount;
  var maxSize = 179;
  if(sliceSize<=maxSize) {
    addSlice(sliceSize, pieElement, offset, sliceID, color);
  } else {
    addSlice(maxSize, pieElement, offset, sliceID, color);
    iterateSlices(sliceSize-maxSize, pieElement, offset+maxSize, dataCount, sliceCount+1, color);
  }
}
function createPie(dataElement, pieElement) {
  var listData = [];
  $(dataElement+" span").each(function() {
    listData.push(Number($(this).html()));
  });
  var listTotal = 0;
  for(var i=0; i<listData.length; i++) {
    listTotal += listData[i];
  }
  var offset = 0;
  var color = [
    "cornflowerblue", 
    "#73b52d", 
    "orange", 
    "tomato", 
    "crimson", 
    "purple", 
    "turquoise", 
    "forestgreen", 
    "navy", 
    "gray"
  ];
  for(var i=0; i<listData.length; i++) {
    var size = sliceSize(listData[i], listTotal);
    iterateSlices(size, pieElement, offset, i, 0, color[i]);
    $(dataElement+" li:nth-child("+(i+1)+")").css("border-color", color[i]);
    offset += size;
  }
}
createPie(".pieID.legend", ".pieID.pie");
</script>
dashboard ends here-->


<!--donut pie chart starts here-->


<!--donut pie chart ends here-->

<!-- <script type="text/javascript">
var chart = AmCharts.makeChart("chartdiv", {
  "type": "pie",
  "startDuration": 0,
   "theme": "light",
  "addClassNames": true,
  "legend":{
    "position":"right",
    "marginRight":100,
    "autoMargins":false
  },
  "radius": "40%",
  "innerRadius": "60%",
  "defs": {
    "filter": [{
      "id": "shadow",
      "width": "200%",
      "height": "200%",
      "feOffset": {
        "result": "offOut",
        "in": "SourceAlpha",
        "dx": 0,
        "dy": 0
      },
      "feGaussianBlur": {
        "result": "blurOut",
        "in": "offOut",
        "stdDeviation": 5
      },
      "feBlend": {
        "in": "SourceGraphic",
        "in2": "blurOut",
        "mode": "normal"
      }
    }]
  },
  "dataProvider": [{
    "country": "Add Revenue",
    "litres": 50
  }, {
    "country": "Licensing",
    "litres": 60
  }],
  "valueField": "litres",
  "titleField": "country",
  
});

chart.addListener("rollOverSlice", function(e) {
  handleRollOver(e);
});

function handleInit(){
  chart.legend.addListener("rollOverItem", handleRollOver);
}

function handleRollOver(e){
  var wedge = e.dataItem.wedge.node;
  wedge.parentNode.appendChild(wedge);
}
</script> -->
<!--video background starts here-->
<!-- <script type="text/javascript">
  var vid = document.getElementById("bgvid");
var pauseButton = document.querySelector("#polina button");

if (window.matchMedia('(prefers-reduced-motion)').matches) {
    vid.removeAttribute("autoplay");
    vid.pause();
    pauseButton.innerHTML = "Paused";
}

function vidFade() {
  vid.classList.add("stopfade");
}

vid.addEventListener('ended', function()
{
vid.pause();
vidFade();
}); 


pauseButton.addEventListener("click", function() {
  vid.classList.toggle("stopfade");
  if (vid.paused) {
    vid.play();
    pauseButton.innerHTML = "Pause";
  } else {
    vid.pause();
    pauseButton.innerHTML = "Paused";
  }
})
</script> -->

<!-- <script>
fullscreen();
$(window).resize(fullscreen);
$(window).scroll(headerParallax);

function fullscreen() {
  var masthead = $('.masthead');
  var windowH = $(window).height();
  var windowW = $(window).width();

  masthead.width(windowW);
  masthead.height(windowH);
}

function headerParallax() {
  var st = $(window).scrollTop();
  var headerScroll = $('.masthead h1');

  if (st < 500) {
    headerScroll.css('opacity', 1-st/1000);
    $('.masthead-arrow ').css('opacity', 0.5-st/250);
    headerScroll.css({
      '-webkit-transform' : 'translateY(' + st/7 + '%)',
      '-ms-transform' : 'translateY(' + st/7 + '%)',
      transform : 'translateY(' + st/7 + '%)'
    });
  }
}
</script> -->
<!--video background ends here-->

<!--buy video license popup check boxes jquery starts here-->
<script src="<?php echo $assets;?>js/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script>
(function($) {
    'use strict';

    if(typeof jQuery === "undefined") {
        /*console.log('jquery.checkboxall plugin needs the jquery plugin');*/
        return false;
    }

    $.fn.checkboxall = function(allSelector) {

        if (allSelector === undefined) {
            allSelector =   'all';
        }

        var parent  =   this;

        if ($('.' + allSelector, parent).length) {
            var all             =   $('.' + allSelector, parent),
                checkbox        =   parent.find('input[type="checkbox"]'),
                childCheckbox   =   checkbox.not('.' + allSelector, parent);

            return checkbox
                    .unbind('click')
                    .click(function(event) {
                        event.stopPropagation();

                        var th  =   $(this);

                        if (th.hasClass(allSelector)) {
                            checkbox.prop('checked', th.prop('checked'));
                        }
                        else {
                            if (childCheckbox.length !== childCheckbox.filter(':checked').length) {
                                all.prop('checked', false);
                            }
                            else {
                                all.prop('checked', true);
                            }
                        }
                    });
        }
        else {
            console.log('jquery.checkboxall error: main selector is not exists.');
            console.log('Please add \'all\' class to first checkbox or give the first checkbox a class name and enter the checkboxall() functions for the class name!');
            console.log('Example: $(selector).checkboxall(\'your-checkbox-class-name\');');
        }
    };
}(jQuery));
</script>


<script>
$(function() {
  var $select = $('.territory'),
      $images =  $('#country-div');

  $select.on('change', function() {
    var value = $(this).val();
	  if(value == 'National'){
		  $images.show();
		  $('#buy_country').prop('required',true);
	  }else{
		  $images.hide();
		  $('#buy_country').prop('required',false);
	  }
  });
});
</script>


<script>
    $('.buy_checkbox').checkboxall();

    $('input[name="selectall1"]').on('click', function(){
      if ( $(this).is(':checked') ) {
          /*$('.buy_checkbox2 .buy_check').hide();*/
          $('input[name="check-other"]').attr('checked', false);
          $('.other-field').hide();
      }
      else {
          $('input[name="check-other"]').attr('checked', true);

      }

    });
    $('input[name="check[]"]').on('click', function(){
      if ( $(this).is(':checked') ) {
          /*$('.buy_checkbox2 .buy_check').hide();*/
          $('input[name="check-other"]').attr('checked', false);
          $('.other-field').hide();
      }
      else {
          $('input[name="check-other"]').attr('checked', true);
      }
    });
    $('input[name="check-other"]').on('click', function(){
		  
		  if ( $(this).is(':checked') ) {
			  /*$('.buy_checkbox2 .buy_check').hide();*/
			  $('input[name="selectall1"]').attr('checked', false);
			  $('input[name="check[]"]').attr('checked', false);
			  $('input[name="chec"]').attr('checked', false);
			  $('.other-field').show();

			  $('#other').prop('required',true);

		  }
		  else {
			  $('input[name="selectall1"]').attr('checked', true);
			  $('input[name="check[]"]').attr('checked', true);
			  $('input[name="chec"]').attr('checked', true);
			  $('.other-field').hide();
			  $('#other').prop('required',false);
		  }

    });

</script>
<!--buy video license popup check boxes jquery ends here-->




<!--smart search starts-->
<script src="<?php echo $assets;?>js/jquery.autocompleter.js"></script>

<!--smart search ends-->

<!-- <script src="<?php echo $assets;?>js/jquery.js"></script> -->




<script>

$(".clickable").click(function(){

    $(this).next().css("opacity","1");
    /*$(this).next().css("display","block");*/
});
$(".my-accnt").click(function(){

    $(".my-acc-area").slideToggle();
    /*$(this).next().css("display","block");*/
});



/*jQuery(document).ready(function() {

    $('#next').click(function() {
        $('.current').removeClass('current').hide()
            .next().show().addClass('current');

        if ($('.current').hasClass('last')) {
            $('#next').css('display', 'none');
        }
        $('#prev').css('display', 'block');
    });

    $('#prev').click(function() {
        $('.current').removeClass('current').hide()
            .prev().show().addClass('current');

        if ($('.current').hasClass('first')) {
            $('#prev').css('display', 'none');
        }
        $('#next').css('display', 'block');
    });

});*/


jQuery(document).ready(function() {
    $(".btn").click(function(){
        $(".review_submission").removeClass('not-active');
    });
    $(".btn2").click(function(){
        $(".review_submission").css('display', 'none');
    });

$("#back1").click(function(){

    $("#fm-block1").css('display', 'block');
    $("#fm-block2").css('display', 'none');
    $("#fm-block3").css('display', 'none');
    $("#fm-block4").css('display', 'none');
    $("#fm-block5").css('display', 'none');

});

$("#back2").click(function(){

    $("#fm-block1").css('display', 'none');
    $("#fm-block2").css('display', 'block');
    $("#fm-block3").css('display', 'none');
    $("#fm-block4").css('display', 'none');
    $("#fm-block5").css('display', 'none');
    $(".review_submission").addClass('not-active');


});

$("#back3").click(function(){

    $("#fm-block1").css('display', 'none');
    $("#fm-block2").css('display', 'none');
    $("#fm-block3").css('display', 'block');
    $("#fm-block4").css('display', 'none');
    $("#fm-block5").css('display', 'none');
});

$(".final_sub").click(function(){

    $("#fm-block1").css('display', 'none');
    $("#fm-block2").css('display', 'none');
    $("#fm-block3").css('display', 'none');
    $("#fm-block4").css('display', 'none');
    $("#fm-block5").css('display', 'block');
});


});



jQuery(document).click(function(e) {
    var target = e.target;
    if (!jQuery(target).is('.my-accnt') ) {
        $(".my-acc-area").slideUp();
    }
})


/*login drop down starts here*/
$(".drop-ico").click(function(){
   $(".login-menu").slideToggle(300); 
})

$(".cate-drop-down .cate-drop-btn").click(function(){
   //$(".cate-drop-content").slideToggle(300); 
   $( ".cate-drop-down .cate-drop-btn" ).next().slideToggle(300);
})


/*login drop down ends here*/

</script>


<!--above the fold banner starts here-->
<!-- <script>
$(document).ready(function() {

    /*Set height of sections to window height*/
    $( ".banner" ).each(function(){
        var $this = $(this);
        $this.css({'height':($(window).height())+'px'});
        /*Recalculate on window resize*/
        $(window).resize(function(){
        $this.css({'height':($(window).height())+'px'});
        });
    });
    $(".banner-content").each(function(){
        var $this = $(this);
        $this.css({'height':($(window).height())+'px'});
        /*Recalculate on window resize*/
        $(window).resize(function(){
        $this.css({'height':($(window).height())+'px'});
        });
    });
});
</script> -->
<!--<script>
    (function() {
        $('#framesid').carousel({
            interval: 3000000   // change the time laps
        });
    }());
    (function() {
        $('.carousel-showmanymoveone .item').each(function() {
            var itemToClone = $(this);
            for (var i = 1; i < 6; i++) {
                itemToClone = itemToClone.next();
                // wrap around if at end of item collection
                if (!itemToClone.length) {
                    itemToClone = $(this).siblings(':first');
                }
                // grab item, clone, add marker class, add to collection
                itemToClone.children(':first-child').clone()
                    .addClass("cloneditem-")
                    .appendTo($(this));
            }
        });
    }());
</script>--> 

<!--slick slider starts here-->
<!-- <script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>-->
<script src="<?php echo $assets;?>js/slick.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
$(document).on('ready', function() {
  

  $(".slider").slick({
    dots: false,
    infinite: true,
    autoplay: true, 
    autoplaySpeed: 2400,
    slidesToShow: 6,
    slidesToScroll: 1,
    responsive: [
    
    {
      breakpoint: 1200,
      settings: {
        slidesToShow: 5,
        slidesToScroll: 1,
        infinite: true
      }
    },
    {
      breakpoint: 992,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 1,
        infinite: true
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
        infinite: true
      }
    },
    {
      breakpoint: 481,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
        infinite: true
      }
    },
    {
      breakpoint: 320,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true
      }
    }
    ]
  
});

});
</script>
<!--slick slider ends here-->


<link href="<?php echo $assets;?>datepicker/dcalendar.picker.css" rel="stylesheet" type="text/css">
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<script src="<?php echo $assets;?>datepicker/dcalendar.picker.js"></script>
<script>
$('#question3').dcalendarpicker({format: 'mm/dd/yyyy',maxdate:'today'});
</script>


<script src="<?php echo $assets;?>js/customs.js" type="text/javascript" charset="utf-8"></script>



<a href="#" onclick="goUp()" id="gotoTopBtn" title="Go to top">
    <i class="fa fa-toggle-up" aria-hidden="true"></i></a>


</body >
</html>

