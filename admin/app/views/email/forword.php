<?php
/**
 * Created by PhpStorm.
 * User: T3500
 * Date: 6/7/2018
 * Time: 12:15 PM
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>FW : <?php echo $email['subject'];?> </title>
</head>
<body>

<!--input tags starts here-->
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="<?php echo $asset;?>email/jquery.emailinput.min.js"></script>
<!--input tags ends here-->

<!--editor starts here-->
<!-- include libraries(jQuery, bootstrap) -->
<link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
<link rel="icon" type="image/png" sizes="16x16" href="https://www.wooglobe.com/images/favi/favicon-16x16.png">

<!-- include summernote css/js -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>

<!--editor ends here-->

<style type="text/css">
    *{
        padding: 0px;
        margin: 0px;
    }
    * {
        -webkit-box-sizing: unset;
        -moz-box-sizing: unset;
        box-sizing: unset;
    }
    *:before, *:after {
        -webkit-box-sizing: unset;
        -moz-box-sizing: unset;
        box-sizing: unset;
    }
    /*summer note starts here*/
    .note-toolbar {
        z-index: 0;
        box-sizing: border-box;

    }
    .note-toolbar{
        position: static !important;
    }
    .note-editor.note-frame {
        border: 1px solid #ddd;
    }
    .note-toolbar-wrapper{
        position: absolute;
        bottom: 0px;
    }
    .panel-default {
        border-color: #ddd;
        float: left;
        width: 100%;
        padding-top: 0px;
        margin-bottom: 5px;
    }
    .note-editor {
        position: relative;
        float: left;
        width: 100%;
        margin: 45px 0px 60px;
        border-radius: 0px;

    }
    .note-editor.note-frame .note-editing-area {
        overflow: unset;
    }
    .note-editor .note-editing-area {
        position: static;
        width: 100%;
        float: left;
    }
    .note-editable{
        height: 500px !important;
        max-height: 1000px;
        overflow: auto;
    }

    /*summer notes ends here*/

    .m-con{
        width: 100%;
        height: auto;
        font-family: arial;
    }
    .mWrap{
        width: 90%;
        margin: auto;
    }
    .mCover {
        width: 100%;
        height: auto;
        margin: 25px 0px;
    }
    .m-head{
        border: 1px solid #ccc;
        height: auto;
        padding: 10px;
    }
    .m-head:after {
        content: '';
        display: table;
        clear: both;
    }
    
    .m-email-add {
        width: 100%;
        float: left;
        height: auto;
        border-bottom: 1px solid #ddd;
        position: relative;
    }
    .avatar img {
        max-width: 60px;
        border-radius: 100%;
        float: left;
    }
    .avatar{
        float: left;
        height: auto;
    }
    .avatar-name{
        width: auto;
        float: left;
        height: auto
    }
    .avatar-link {
        width: auto;
        float: left;
        height: auto;
        padding: 21px;
    }
    .avatar-link a {
        text-decoration: none;
        height: auto;
        color: #1e88e5;
        margin-right: 15px;
        font-size: 14px;
    }
    .avatar-name {
        width: auto;
        float: left;
        height: auto;
        padding: 21px;
        font-size: 16px;
        font-family: arial;
        color: #444;
        margin-right: 40px;
    }
    .m-email-add span{
        border: 0px;
        padding: 50px 10px 150px 10px;
    }
    .emailinput span {
        background-image: url(<?php echo $asset;?>/email/cross.png);
        background-size: 20px;
        float: left;
        padding-left: 28px;
        background-repeat: no-repeat;
        font-size: 14px;
        color: #444;
    }
    div.ei, input.ei {
        height: 28px;
        color: #444;
        background-color: #fff;
        padding: 5px;
        cursor: text;
        overflow: auto
    }

    .ei_box {
        display: block;
        margin: 1px;
        padding: 1px 3px;
        width: auto;
        float: left;
        outline: none;
    }

    span.ei_valid {
        border: 1px solid #bbd8fb;
        background-color: #f3f7fd;
        cursor: pointer
    }

    span.ei_invalid {
        border: 1px solid #b55e5e;
        background-color: #fdf5f5;
        cursor: pointer
    }
    .input-mail-box{
        height: auto;
        border: 1px solid #ccc;
        border-top: none;
        border-bottom: none;
    }
    .input-mail-row label{
        position: absolute;
        left: 10px;
        top: 7px;
        font-size: 14px;
        font-style: italic;
        color: #444;
        font-weight: 400;
        margin-bottom: 0px;
    }
    .input-mail-row {
        position: relative;
        padding: 0px 10px 0px 33px;
        box-sizing: border-box;
        margin-bottom: 8px;
        border-bottom: 1px solid #ddd;
    }
    .input-mail-subject {
        position: relative;
        padding: 6px 10px 8px 10px;
        box-sizing: border-box;
        margin-top: 20px;
    }
    .input-mail-subject input{
        width: 100%;
        border: 0px;
        outline: none;
        color: #8c8c8c;
        margin-bottom: 0px;
        text-transform: capitalize;
    }
    .bcc_cc{
        position: absolute;
        top: 7px;
        right: 10px;
        font-size: 14px;
        font-style: italic;
    }
    .bcc_cc a{
        text-decoration: none;
        color: #444;
        margin-left: 5px;
    }
    .fix-btm{
        position: fixed;
        bottom: 0px;
        width: 100%;
        padding: 5px 0px;
        background: #fff;
        -webkit-box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.15);
        -moz-box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.15);
        box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.15);
    }
    .popup .send-btn {
        padding: 9px 15px;
        background-color: #1e88e5;
        border: 1px solid #1e88e5;
        color: #fff;
        box-shadow: none;
        border-radius: 2px;
        cursor: pointer;
        font-size: 14px;
        float: left;
        margin-right: 20px;

    }
    .popup .send-btn-close {
        padding: 9px 15px;
        background-color: #fff;
        border: 1px solid #444;
        color: #444;
        box-shadow: none;
        border-radius: 2px;
        cursor: pointer;
        font-size: 14px;
        float: left;
    }
    .send-btn {
        padding: 9px 15px;
        background-color: #2dbf67;
        border: 1px solid #2dbf67;
        color: #fff;
        box-shadow: none;
        border-radius: 2px;
        cursor: pointer;
        font-size: 14px;
        float: left;
        margin-right: 40px;
    }
    .attachment, .schedule {
        width: auto;
        float: left;
        padding: 5px 0px 0px;
        position: relative;
    }
    span.lal{
        border: 1px solid #484848;
        border-radius: 2px;
        float: left;
        width: 25px;
        height: 25px;
        line-height: 25px;
        text-align: center;
        cursor: pointer;
        margin-right: 10px;
        color: #484848;
    }
    .att-box {
        position: absolute;
        bottom: 33px;
        background: #fff;
        width: 110px;
        padding: 20px 20px;
        border: 1px solid #484848;
        border-radius: 2px;
        display: none;
    }
    .att-box ul{
        list-style-type: none;
        bottom: 0px;
        background:#fff;

    }
    .att-box ul li{
        padding: 0px 0px 20px;
    }
    .att-box ul li a{
        text-decoration: none;
    }
    .att-box ul li.last{
        padding: 0px 0px 0px;
    }


    /*attachment file starts here*/
    .copyright {
        display:block;
        margin-top: 100px;
        text-align: center;
        font-family: Helvetica, Arial, sans-serif;
        font-weight: bold;
        text-transform: uppercase;
    }
    .copyright a{
        text-decoration: none;
        color: #EE4E44;
    }
    .file-upload{
        display:block;
        cursor: pointer;
        text-align:center;
        font-family: Arial;
        display: inline-block;
    }
    .file-upload .file-select {
        cursor: pointer;
        text-align: left;
        background: #FFFFFF;
        overflow: hidden;
        position: relative;
    }
    .file-upload .file-select .file-select-button {
        background: #fff;
        display: inline-block;
        height: 25px;
        line-height: 25px;
        width: 25px;
        text-align: center;
        border: 1px solid #484848;
        border-radius: 2px;
        float: left;
    }
    .file-upload .file-select .file-select-name {
        font-size: 13px;
        line-height: 25px;
        display: inline-block;
        padding: 1px 8px;
        float: left;
    }
    .file-upload.active .file-select{
        border-color:#3fa46a;
        transition:all .2s ease-in-out;
        -moz-transition:all .2s ease-in-out;
        -webkit-transition:all .2s ease-in-out;
        -o-transition:all .2s ease-in-out;
    }
    .file-upload.active .file-select .file-select-button{
        transition:all .2s ease-in-out;
        -moz-transition:all .2s ease-in-out;
        -webkit-transition:all .2s ease-in-out;
        -o-transition:all .2s ease-in-out;
    }
    .file-upload .file-select input[type=file]{
        z-index:100;
        cursor:pointer;
        position:absolute;
        height:100%;
        width:100%;
        top:0;
        left:0;
        opacity:0;
        filter:alpha(opacity=0);
    }
    .file-upload .file-select.file-select-disabled{opacity:0.65;}

    /*attachment file ends here*/

    /*radio box starts here*/
    .checkbox-custom, .radio-custom {
        opacity: 0;
        position: absolute;
    }
    .radio-custom-label:visited {
        outline: -webkit-focus-ring-color auto 0px;
        border: 0px;
    }
    .checkbox-custom, .checkbox-custom-label, .radio-custom, .radio-custom-label {
        display: inline-block;
        vertical-align: middle;
        cursor: pointer;
    }
    .checkbox-custom-label, .radio-custom-label {
        position: absolute;
        top: 0px;
        left: 0px;
    }
    .checkbox-custom + .checkbox-custom-label:before, .radio-custom + .radio-custom-label:before {
        content: '';
        background: #fff;
        border: 1px solid #929292;
        display: inline-block;
        vertical-align: middle;
        width: 8px;
        height: 8px;
        padding: 2px;
        margin-right: 10px;
    }
    .checkbox-custom:checked + .checkbox-custom-label:before {
        background: rebeccapurple;
        box-shadow: inset 0px 0px 0px 4px #fff;
    }
    .radio-custom + .radio-custom-label:before {
        border-radius: 50%;
    }
    .radio-custom:checked + .radio-custom-label:before {
        background: #1f88e5;
        border: 1px solid #1f88e5;
        box-shadow: inset 0px 0px 0px 3px #fff;
    }
    .radio-bx {
        position: relative;
        padding: 0px 20px 15px 25px;
        margin-bottom: 15px;
        border-bottom: 1px solid #f1f1f1;
    }

    /*radio box ends here */
    .mail-pop-blk {
        font-size: 14px;
        color: #444;
        margin-top: 20px;
    }
    .mail-pop-blk select {
        font-size: 14px;
        color: #444;
        border: 0px;
        padding: 0px 10px 0px 0px;
    }
    #dt{text-indent: -500px;height:25px; width:200px;}
    #date,#time{
        font-size: 14px;
        color: #444;
    }
    .datetimepick {
        width: 100%;
        float: left;
        margin-bottom: 15px;
    }
    .datetimepick label{
        float: left;
        margin-right: 10px;
    }
    .temp-drop {
        position: relative;
        float: right;

    }
    .temp-drop-down {
        display: none;
        position: absolute;
        min-width: 120px;
        top: 30px;
        right: 0px;
        z-index: 501;
        background: #fff;
        padding: 10px;
        border: 1px solid #eee;
        -webkit-box-shadow: 0px 0px 20px -9px rgba(0,0,0,0.3);
        -moz-box-shadow: 0px 0px 20px -9px rgba(0,0,0,0.3);
        box-shadow: 0px 0px 20px -9px rgba(0,0,0,0.3);
    }
    .temp-drop-down a {
        display: block;
        padding: 7px;
        margin: 0px;
        color: #444;
    }
    .ql-container {
        margin-top: 0 !important;
    }
</style>




<div class="m-con">
    <div class="mWrap">
        <div class="mCover">

            <form id="send_mail">
                <div class="m-head">
                    <div class="avatar">
                        <img src="<?php echo $asset;?>email/default-avatar.png" alt="Avatar">
                    </div>
                    <div class="avatar-name">
                        <span>viral@wooglobe.com</span>
                    </div>
                    <div class="avatar-link">
                        <?php if($templates->num_rows() > 0){?>
                            <div class="temp-drop">
                                <a href="javascript:void(0)">Choose Template</a>
                                <div class="temp-drop-down">
                                    <?php foreach ($templates->result() as $tem){?>
                                        <a href="javascript:void(0);" class="choose_tem" data-id="<?php echo $tem->short_code;?>"><?php echo $tem->title;?></a>
                                    <?php } ?>

                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php $to = '';
                $bcc = '';
                $cc = '';
                $email['cc'] = array();
                $email['bcc'] = array();
                $to = $email['to_email'];
                /*//foreach($email['from'] as $toe){
                    $to = $email['from']['email'];

                //}*/

                /*foreach($email['cc'] as $cce){
                    $cc .= $cce['email'].' ';

                }

                foreach($email['bcc'] as $bce){
                    $bcc .= $bce['email'].' ';

                }*/

                ?>
                <div class="input-mail-box">
                    <div class="input-mail-row"><label>To:</label><input type="email" id='to' class="emailinput ei" value='' />

                        <div class="bcc_cc">
                            <?php if(count($email['bcc']) == 0){?>
                                <a href="javascript:void(0)" class="bcc">Bcc</a>
                            <?php } ?>
                            <?php if(count($email['cc']) == 0){?>
                                <a href="javascript:void(0)" class="cc">Cc</a>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="input-mail-row cc-open" style="display: <?php if(count($email['bcc']) > 0){ echo 'block';}else{ echo 'none';}?>"><label>Cc:</label> <input type="text" id='cc' class="emailinput ei" /></div>
                    <div class="input-mail-row bcc-open" style="display: <?php if(count($email['cc']) > 0){ echo 'block';}else{ echo 'none';}?>;"><label>Bcc:</label> <input type="text" id='bcc' class="emailinput ei" /></div>
                    <div class="input-mail-subject"><input type="text" class="" id="subject" placeholder="Subject" value="FW : Re : <?php echo $email['subject'];?>" /></div>
                </div>

                <div id='output'></div>

                <script language="javascript" type="text/javascript">
                    $('.emailinput').emailinput({ onlyValidValue: true, delim: ',' }); // initialize
                    $('#getAddress').bind('click', function() {
                        var output = $('#output').empty();
                        output.append('From: ' + $('#from').val() + '<br />');
                        output.append('To: ' + $('#to').val() + '<br />');
                        output.append('CC: ' + $('#cc').val() + '<br />');
                        output.append('BCC: ' + $('#bcc').val() + '<br />');
                    });
                    $( ".cc" ).on( "click", function() {
                        $( ".cc-open" ).show();
                        $( ".cc" ).hide();
                    });
                    $( ".bcc" ).on( "click", function() {
                        $( ".bcc-open" ).show();
                        $( ".bcc" ).hide();
                    });

                    $( ".temp-drop" ).on( "click", function() {
                        $( ".temp-drop-down" ).slideToggle();

                    });
                </script>


                <div id="new_msg" style="min-height: 100px; margin-top: 10px; margin-bottom: 10px;" class="new_msg">
                    <p><div id="info" style="margin-bottom: 30px; margin-top: 15px;">
                        On <?php echo date('D, M d, Y',strtotime($email['date'])).' at '.date('h:i A',strtotime($email['date'])).', '.$email['from']['name'].' <<a href="mailto:'.$email['from']['email'].'">'.$email['from']['email'].'</a>> wrote :';?>
                    </div>
                    <div id="last" style="background: #CCCCCC; padding: 20px; margin: 5px;">
                        <?php  $foo=$email['body']['html'];
                        $cleanStr = trim(preg_replace('/\s\s+/', '', str_replace("\n", " ", $email['body']['html'])));
                        $cleanStr = str_replace("&nbsp;"," ",$cleanStr);
                        echo $cleanStr;?>
                    </div></p><br><br>

                </div>
                <script>
                    /* var quill = new Quill('#new_msg', {
                         modules: {
                             syntax: true,
                             toolbar: '#toolbar-container'
                         },
                         placeholder: 'Compose an epic...',
                         theme: 'snow'
                     });*/
                </script>
                <script>
                    var toolbarOptions = [
                        ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                        ['blockquote', 'code-block'],

                        [{ 'header': 1 }, { 'header': 2 }],               // custom button values
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
                        [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
                        [{ 'direction': 'rtl' }],                         // text direction

                        [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
                        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                        [ 'link', 'video'],          // add's image support
                        [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
                        [{ 'font': [] }],
                        [{ 'align': [] }],

                        ['clean']                                         // remove formatting button
                    ];
                    var quill = new Quill('#new_msg', {
                        modules: {
                            toolbar: toolbarOptions
                        },
                        placeholder: 'Please enter the image caption',
                        theme: 'snow'  // or 'bubble'
                    });
                </script>



        </div>
    </div>


    <div class="fix-btm">
        <div class="mWrap">
            <button type="button" class="send-btn">Send</button>
        </div>
    </div>

    </form>


</div>



<!--popup starts here-->
<link rel="stylesheet" href="<?php echo $asset;?>email/popup/css/jquery.popup.css" type="text/css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="<?php echo $asset;?>email/popup/js/jquery.popup.js"></script>

<script type="text/javascript">
    var base_url = '<?php echo $url;?>';
    $(function() {

        $(".js__p_start").simplePopup();
        $('#new_msg').trigger('focus');
        $(document).on('click','.choose_tem',function () {
            var short_code = $(this).data('id');
            $.ajax({
                type: "POST",
                url: base_url + "get_template_html",
                data: {code: short_code, id: '<?php echo $email['message_id'] ?>'},
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.code == 204) {
                        $('#err-msg').attr('data-message', data.message);
                        $('#err-msg').click();

                    } else if (data.code == 201) {
                        alert(data.error);

                    } else if (data.code == 200) {
                        $('.new_msg').html('');
                        $('.new_msg').html(data.html);
                    } else {
                        $('#err-msg').attr('data-message', 'Something is going wrong!');
                        $('#err-msg').click();
                    }

                },
                error: function () {
                    $('#err-msg').attr('data-message', 'Something is going wrong!');
                    $('#err-msg').click();
                }
            });
        });
        var message_content=jQuery('#new_msg .ql-editor').html();
        jQuery('#new_msg').on('focusout', function () {
            message_content = jQuery('#new_msg .ql-editor').html();
            if(message_content == "<p><br></p>"){
                message_content = "";
            }
        });
        $(document).on('click','.send-btn',function () {
            var to = $('#to').val();
            var cc = $('#cc').val();
            var bcc = $('#bcc').val();
            var subject = $('#subject').val();
            var message = message_content;
            if(to.length == 0){
                alert('To email is required.');
            }else if(subject.length == 0) {
                alert('Subject is required.');
            }else {
                $.ajax({
                    type: "POST",
                    url: base_url + "send_mail",
                    data: {to:to,cc:cc,bcc:bcc,subject:subject,message:message},
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.code == 204) {
                            $('#err-msg').attr('data-message', data.message);
                            $('#err-msg').click();

                        } else if (data.code == 201) {
                            alert(data.error);

                        } else if (data.code == 200) {
                            window.close();
                        } else {
                            $('#err-msg').attr('data-message', 'Something is going wrong!');
                            $('#err-msg').click();
                        }

                    },
                    error: function () {
                        $('#err-msg').attr('data-message', 'Something is going wrong!');
                        $('#err-msg').click();
                    }
                });
            }

        });
    });
</script>
<!--popup ends here-->
<script type="text/javascript">
    $( ".att-click" ).on( "click", function() {
        $( ".att-box" ).slideToggle();
    });
    $(document).on('click', function (e) {
        if ($(e.target).closest(".att-click").length === 0) {
            $(".att-box").slideUp();
        }
    });

    $('#chooseFile').bind('change', function () {
        var filename = $("#chooseFile").val();
        if (/^\s*$/.test(filename)) {
            $(".file-upload").removeClass('active');
            $("#noFile").text("No file chosen...");
        }
        else {
            $(".file-upload").addClass('active');
            $("#noFile").text(filename.replace("C:\\fakepath\\", ""));
        }
    });


</script>

<!--date time picker -->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.0/moment-with-locales.min.js"></script>
<script type="text/javascript" src="<?php echo $asset;?>email/datetimepicker/js/datetimepicker.js"></script>
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link href="<?php echo $asset;?>email/datetimepicker/css/datetimepicker.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
    $(document).ready( function () {
        $('#picker').dateTimePicker();
    });
</script>
<!--date time picker end here -->

</body>
</html>
