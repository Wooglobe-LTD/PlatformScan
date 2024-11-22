var table_export;
var soryFlast = parseInt(parseInt($('thead').find('th').length)-1);
var statuss;
var statusAdd;
var pubdate;
var pubdateAdd;
var pubdelay;
var pubdelayAdd;

var typep;
var typepAdd;

$(function() {

    altair_datatables.dt_tableExport1("#rss_article_feeds");
    altair_datatables.dt_tableExport2("#rss_articles");

    var languages;
    $.ajax({
        type: "GET",
        cache: true,
        url: base_url + "get_languages_list",
        success: function (res) {
            res = JSON.parse(res);
            if (res.code == 200) {
                languages = res.data.languages;
            }
        },
        error: function () {
            $("#err-msg").attr("data-message", "Languages list not loaded!");
            $("#err-msg").click();
        }
    });



    $("#selected_language").on("input", function () {
        const inputText = $(this).val();
        updateDropdown(inputText);
    });
    function updateDropdown(filterText) {
        const filteredLanguages = languages.filter(language =>
            language.toLowerCase().startsWith(filterText.toLowerCase())
        );
        const dropdown = $("#language_dropdown");
        dropdown.empty();
        filteredLanguages.forEach(language => {
            const listItem = $("<li>").text(language);
            dropdown.append(listItem);
        });
    }
    $("#language_dropdown").on("click", "li", function () {
        const selectedLanguage = $(this).text();
        $("#selected_language").val(selectedLanguage);
        $("#language_dropdown").empty();
    });
    $("#translate_send_button").on("click", function(e) {
        e.preventDefault();
    
        $(".error").empty();
        $("#slide_ids").empty();
        var slideOrder = [];
        $("div[class^='article_slide_num']").each(function () {
            slideOrder.push($(this).data("id"));
        });
        $.each(slideOrder, function (index, value) {
            $("#slide_ids").append('<option value="' + value + '">' + value + '</option>');
        });
        $("#slide_ids option").attr("selected", true);
        var form = $("#rss_article_form")[0];

        $.ajax({
            type: "POST",
            url: base_url + "translate_article_form",
            data: new FormData(form),
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {
                res = JSON.parse(res);
                if (res.code == 200) {
                    $.each(res.data, function (i, v) {
                        $("#" + i).val(v);
                    });
                }
                else {
                    $("#err-msg").attr("data-message", data.message);
                    $("#err-msg").click();
                }
            },
            error: function () {
                $("#err-msg").attr("data-message", "Something is going wrong!");
                $("#err-msg").click();
            },
        });
    });



    $("#add_article").on('click', function() {
        $("#slides").empty();
        $(".error").empty();
        $("#slide_ids").empty();
        $("div[class^='article_slide_num']").remove();
        $("#article_slide_num").val(0);
        $("#article_id").val(0);
        $("#article_edit").val(0);
        $("#article_feeds option").attr("selected", false);
        $("#article_category option").attr("selected", false);
        $("#rss_article_form").trigger("reset");
        var modal = UIkit.modal("#rss_article_modal");
        modal.show();
    });
    $("#rss_article_feeds").on('click', 'a[id^="add_article_tbl_btn_"]', function() {
        $("#slides").empty();
        $(".error").empty();
        $("#slide_ids").empty();
        $("div[class^='article_slide_num']").remove();
        $("#article_slide_num").val(0);
        $("#article_id").val(0);
        $("#article_edit").val(0);
        $("#rss_article_form").trigger("reset");
        var rss_id = $(this).data('id');
        $("#article_feeds option").attr("selected", false);
        $("#article_category option").attr("selected", false);
        $("#article_feeds option[value="+rss_id+"]").attr("selected", true);
        var modal = UIkit.modal("#rss_article_modal");
        modal.show();
    });
    $("#rss_article_form_save").on('click', function() {
        $("#rss_article_form").submit();
    });
    $(document).on("submit", "#rss_article_form", function (e) {
        e.preventDefault();
        $(".error").empty();
        $("#slide_ids").empty();
        var slideOrder = [];
        $("div[class^='article_slide_num']").each(function () {
            slideOrder.push($(this).data("id"));
        });
        $.each(slideOrder, function (index, value) {
            $("#slide_ids").append('<option value="' + value + '">' + value + '</option>');
        });
        $("#slide_ids option").attr("selected", true);
        $.ajax({
            type: "POST",
            url: base_url + "save_article",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                data = JSON.parse(data);
                if (data.code == 200) {
                    var modal = UIkit.modal("#rss_article_modal");
                    modal.hide();
                    $("#rss_article_form").trigger("reset");
                    $("#suc-msg").attr("data-message", " Article Saved Successfully!");
                    $("#suc-msg").click();
                    altair_datatables.dt_reload2();
                    
                }
                else if (data.code == 201) {
                    $.each(data.error, function (i, v) {
                        $("#" + i)
                        .parent()
                        .parent()
                        .find(".error")
                        .html(v);
                    });
                    if(data.data.length > 0) {
                        $("#add_article_slide").parent().parent().find(".error").html("Validation Error In Slide " + data.data);
                    }
                }
                else if (data.code == 202) {
                    $("#add_article_slide").parent().parent().find(".error").html(data.error);
                }
                else {
                    $("#err-msg").attr("data-message", data.message);
                    $("#err-msg").click();
                }
            },
            error: function () {
                $("#err-msg").attr("data-message", "Something is going wrong!");
                $("#err-msg").click();
            },
        });
    });
    $("#rss_articles").on('click', 'a[id^="edit_article_"]', function() {
        var article_id = $(this).data("id");
        $("#slides").empty();
        $("#slide_ids").empty();
        $(".error").empty();
        $("div[class^='article_slide_num']").remove();
        $("#article_slide_num").val(0);
        $("#rss_article_form").trigger("reset");
        $.ajax({
            type: "POST",
            cache: false,
            data: {id: article_id},
            url: base_url + "get_article_data",
            success: function (data) {
                data = JSON.parse(data);
                if (data.code == 200) {
                    $("#article_id").val(article_id);
                    $("#article_edit").val(1);
                    $("#article_title").val(data.data.title);
                    $("#article_description").val(data.data.description);
                    $("#article_feeds option").attr("selected", false);
                    $("#article_feeds option[value="+data.data.feed_id+"]").attr("selected", true);
                    $("#article_category option").attr("selected", false);
                    $("#article_category option[value="+data.data.category+"]").attr("selected", true);
                    $("#article_keywords").val(data.data.keywords);
                    $("#article_credit").val(data.data.credit);

                    var slides = data.data.slides;
                    for(let d in slides) {
                        slide_num = parseInt(d) + 1;
                        $("#add_article_slide").click();
                        $("#slide_db_id_"+slide_num).val(slides[d].id);
                        $("#slide_title_"+slide_num).val(slides[d].file_title);
                        $("#slide_headline_"+slide_num).val(slides[d].headline)
                        $("#slide_description_"+slide_num).val(slides[d].description);;
                        $("#slide_credit_"+slide_num).val(slides[d].credit);

                        var image = '';
                        if (slides[d].image_s3_url) {
                            image = slides[d].image_s3_url;
                        }
                        else {
                            image = slides[d].image_url;
                        }
                        $('label[for="image_upload_'+slide_num+'"').empty();
                        var html = '<img src="'+image+'" alt="Slide Image" />';
                        $('label[for="image_upload_'+slide_num+'"').html(html);
                    }

                    $("#rss_article_form input, #rss_article_form textarea").parent().addClass("md-input-focus uk-dropdown-shown");
                    var modal = UIkit.modal("#rss_article_modal");
                    modal.show();
                }
                else {
                    $("#err-msg").attr("data-message", data.message);
                    $("#err-msg").click();
                }
            },
            error: function () {
                $("#err-msg").attr("data-message", "Something is going wrong!");
                $("#err-msg").click();
            },
        });
    });
    $("#rss_articles").on('click', 'a[id^="delete_article_"]', function() {
        var article_id = $(this).data("id");
        $.ajax({
            type: "POST",
            cache: false,
            data: {id: article_id},
            url: base_url + "delete_article",
            success: function (data) {
                data = JSON.parse(data);
                if (data.code == 200) {
                    altair_datatables.dt_reload2();
                    $("#suc-msg").attr("data-message", "Article Deleted!");
                    $("#suc-msg").click();
                }
                else {
                    $("#err-msg").attr("data-message", data.message);
                    $("#err-msg").click();
                }
            },
            error: function () {
                $("#err-msg").attr("data-message", "Something is going wrong!");
                $("#err-msg").click();
            },
        });
    });



	$('#add_article_feed_btn').on('click',function(){
		var modal = UIkit.modal("#add_article_feed_modal");
    	modal.show();
    });
    $("#add_article_feed").on('click', function() {
        $("#add_article_feed_form").submit();
    });
    $(document).on("submit", "#add_article_feed_form", function (e) {
        e.preventDefault();
    
        $(".error").empty();
        $.ajax({
            type: "POST",
            url: base_url + "add_article_feed",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                data = JSON.parse(data);
                if (data.code == 200) {
                    var modal = UIkit.modal("#add_article_feed_modal");
                    modal.hide();
                    $("#add_article_feed_form").trigger("reset");
                    $("#suc-msg").attr("data-message", " Article Feed Saved Successfully!");
                    $("#suc-msg").click();
                    altair_datatables.dt_reload1();
                }
                else if (data.code == 201) {
                    $.each(data.error, function (i, v) {
                        $("#" + i)
                        .parent()
                        .parent()
                        .find(".error")
                        .html(v);
                    });
                }
                else {
                    $("#err-msg").attr("data-message", data.message);
                    $("#err-msg").click();
                }
            },
            error: function () {
                $("#err-msg").attr("data-message", "Something is going wrong!");
                $("#err-msg").click();
            },
        });
    });
    $("#rss_article_feeds").on('click', 'a[id^="delete_feed_"]', function() {
        var article_id = $(this).data("id");
        $.ajax({
            type: "POST",
            cache: false,
            data: {id: article_id},
            url: base_url + "delete_article_feed",
            success: function (data) {
                data = JSON.parse(data);
                if (data.code == 200) {
                    altair_datatables.dt_reload1();
                    $("#suc-msg").attr("data-message", "Feed Deleted!");
                    $("#suc-msg").click();
                }
                else {
                    $("#err-msg").attr("data-message", data.message);
                    $("#err-msg").click();
                }
            },
            error: function () {
                $("#err-msg").attr("data-message", "Something is going wrong!");
                $("#err-msg").click();
            },
        });
    });



    $(document).on('click', '.article_slide_num', function(e) {
        e.preventDefault();
        var slide_num = $(this).data("id");
        $(".article_slide_num_active").attr("class", "article_slide_num");
        $("#article_slide_"+slide_num).attr("class", "article_slide_num_active");
        $(".slide_preview").css('display', 'none');
        $("#article_slide_preview_"+slide_num).css('display', 'block');
    });
    $("#add_article_slide").on('click', function(e) {
        e.preventDefault();
        var slide_num = $("#article_slide_num").val();
        slide_num++;
        $("#article_slide_num").val(slide_num);
        var html = 
        '<div class="article_slide_num ui-sortable-handle" id="article_slide_'+slide_num+'" data-id="'+slide_num+'">'+
            '<div type="button" class="md-btn-info">'+
                '<i class="material-icons" title="Remove Slide">remove_circle</i>'+
                '<div class="slide_number">'+slide_num+'</div>'+
            '</div>'+
        '</div>';
        $(html).insertBefore("#add_article_slide");
        $("#rss_article_slides").sortable({
            items: "[class^='article_slide_num']",
            axis: "x",
            delay: 200,
            distance: 10,
            helper: "clone"
        });
        $(".slide_preview").css('display', 'none');
        html = 
        '<div id="article_slide_preview_'+slide_num+'" class="slide_preview">'+

            '<input type="hidden" id="slide_db_id_'+slide_num+'" name="slide_db_id_'+slide_num+'" />'+

            '<div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">'+
                '<div class="uk-width-medium-1-1">'+
                    '<div class="parsley-row">'+
                        '<div class="md-input-wrapper">'+
                            '<label for="slide_title_'+slide_num+'" class=" uk-form-label uk-text-primary">Image Title</label>'+
                            '<input type="text" id="slide_title_'+slide_num+'" name="slide_title_'+slide_num+'" class="md-input" data-parsley-required-message="This field is required." />'+
                            '<span class="md-input-bar "></span>'+
                        '</div>'+
                        '<div class="error"></div>'+
                    '</div>'+
                '</div>'+
            '</div>'+

            '<div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">'+
                '<div class="uk-width-medium-1-1">'+
                    '<div class="parsley-row">'+
                        '<div class="md-input-wrapper">'+
                            '<label for="slide_headline_'+slide_num+'" class=" uk-form-label uk-text-primary">Image Headline</label>'+
                            '<input type="text" id="slide_headline_'+slide_num+'" name="slide_headline_'+slide_num+'" class="md-input" data-parsley-required-message="This field is required." />'+
                            '<span class="md-input-bar "></span>'+
                        '</div>'+
                        '<div class="error"></div>'+
                    '</div>'+
                '</div>'+
            '</div>'+

            '<div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">'+
                '<div class="uk-width-medium-1-1">'+
                    '<div class="parsley-row">'+
                        '<div class="md-input-wrapper">'+
                            '<label for="slide_description_'+slide_num+'" class="uk-form-label uk-text-primary">Image Decription</label>'+
                            '<textarea id="slide_description_'+slide_num+'" name="slide_description_'+slide_num+'" class="md-input" data-parsley-required-message="This field is required." style="max-height:150px; margin-top:10px;"></textarea>'+
                            '<span class="md-input-bar "></span>'+
                        '</div>'+
                        '<div class="error"></div>'+
                    '</div>'+
                '</div>'+
            '</div>'+

            '<div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">'+
                '<div class="uk-width-medium-1-1">'+
                    '<div class="parsley-row">'+
                        '<div class="md-input-wrapper">'+
                            '<label for="slide_credit_'+slide_num+'" class=" uk-form-label uk-text-primary">Image Credit</label>'+
                            '<input type="text" id="slide_credit_'+slide_num+'" name="slide_credit_'+slide_num+'" class="md-input" data-parsley-required-message="This field is required." />'+
                            '<span class="md-input-bar "></span>'+
                        '</div>'+
                        '<div class="error"></div>'+
                    '</div>'+
                '</div>'+
            '</div>'+

            '<div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">'+
                '<div class="uk-width-medium-1-1">'+
                    '<div class="parsley-row">'+
                        '<div class="md-input-wrapper">'+
                            '<label for="image_upload_'+slide_num+'" class="drop-container" id="dropcontainer">'+
                                '<span class="drop-title">Drop File Here</span>'+
                                'or'+
                                '<input type="file" id="image_upload_'+slide_num+'" name="image_upload_'+slide_num+'" accept="image/*">'+
                            '</label>'+
                            '<div class="error"></div>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
            '</div>'+

        '</div>'
        $("#slides").append(html);
        $("#article_slide_"+slide_num).click();
    });
    $(document).on("click", "div[class^='article_slide_num'] div i", function () {
        var slide_id = $(this).parent().parent().data('id');
        $("#article_slide_preview_"+slide_id).remove();
        $("#article_slide_"+slide_id).remove();
    });



    $("#generate_article").on("click", function() {
        $(".error").empty();
        var modal = UIkit.modal("#generate_article_modal");
        modal.show();
    });
    $("#generate_article_form_save").on("click", function() {
        $("#generate_article_form").submit();
    });
    $(document).on("submit", "#generate_article_form", function (e) {
        e.preventDefault();
    
        $(".error").empty();
        $.ajax({
            type: "POST",
            url: base_url + "generate_article",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {
                res = JSON.parse(res);
                if (res.code == 200) {
                    var modal = UIkit.modal("#generate_article_modal");
                    modal.hide();
                    if(res.data != null) {
                        $("#generate_article_form").trigger("reset");
                        $("#article_id").val(0);
                        $("#article_edit").val(0);
                        $("#slides").empty();
                        $("#slide_ids").empty();
                        $("div[class^='article_slide_num']").remove();
                        $("#article_slide_num").val(0);
                        $("#rss_article_form").trigger("reset");
                        for(let i = 0; i < parseInt(res.slides); i++) {
                            $("#add_article_slide").click();
                        }
                        $('#rss_article_form input[id^="slide_credit_"], #article_credit').val("Wooglobe Ltd.");
                        $.each(res.data, function (i, v) {
                            $("#" + i).val(v);
                        });
                        $("#rss_article_form input, #rss_article_form textarea").parent().addClass("md-input-focus uk-dropdown-shown");
                        var modal = UIkit.modal("#rss_article_modal");
                        modal.show();
                    }
                    else {
                        $("#err-msg").attr("data-message", "Query was unsuccessfull");
                        $("#err-msg").click();
                    }
                }
                else if (res.code == 201) {
                    $.each(res.error, function (i, v) {
                        $("#" + i)
                        .parent()
                        .parent()
                        .find(".error")
                        .html(v);
                    });
                }
                else {
                    $("#err-msg").attr("data-message", data.message);
                    $("#err-msg").click();
                }
            },
            error: function () {
                $("#err-msg").attr("data-message", "Something is going wrong!");
                $("#err-msg").click();
            },
        });
    });

});

altair_datatables = {
    dt_tableExport1: function(selector) {
        var $dt_tableExport = $(selector),
            $dt_buttons = $dt_tableExport.prev('.dt_colVis_buttons');

        if($dt_tableExport.length) {
            table_export = $dt_tableExport.DataTable({
                "processing": true,
                "serverSide": true,
                "columnDefs": [ { orderable: false, targets: [soryFlast, 0] }],
                "ajax": base_url+'get_rss_article_feeds',
            });
        }
    },

    dt_tableExport2: function(selector) {
        var $dt_tableExport = $(selector),
            $dt_buttons = $dt_tableExport.prev('.dt_colVis_buttons');

        if($dt_tableExport.length) {
            table_export = $dt_tableExport.DataTable({
                "processing": true,
                "serverSide": true,
                "columnDefs": [ { orderable: false, targets: [soryFlast, 0] }],
                "ajax": base_url+'get_rss_articles',
            });
        }
    },
    
    dt_reload1: function() {
        var table = $('#rss_article_feeds').DataTable();
        table.ajax.reload(null, false);
    },

    dt_reload2: function() {
        var table = $('#rss_articles').DataTable();
        table.ajax.reload(null, false);
    }
};