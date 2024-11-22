$(function() {
  $("#promote_video").on("change", function() {
    if ($(this).is(":checked")) {
      $("#position").removeAttr("readonly");
    } else {
      $("#position").attr("readonly", "readonly");
    }
  });

  // file upload
  altair_form_file_upload.init();

  $(".dropify_my").dropify({
    messages: {
      default: "Drag and drop a CSV file here or click",
      replace: "Drag and drop or click to replace",
      remove: "Remove",
      error: "Ooops, something wrong happended."
    }
  });

  /*for mobile application*/
  var Youtube = (function() {
    "use strict";

    var video, results;

    var getThumb = function(url, size) {
      if (url === null) {
        return "";
      }
      size = size === null ? "big" : size;
      results = url.match("[\\?&]v=([^&#]*)");
      video = results === null ? url : results[1];

      if (size === "small") {
        return "http://img.youtube.com/vi/" + video + "/2.jpg";
      }
      return "http://img.youtube.com/vi/" + video + "/0.jpg";
    };

    return {
      thumb: getThumb
    };
  })();

  function ytVidId(url) {
    var p = /^(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?(?=.*v=((\w|-){11}))(?:\S+)?$/;
    return url.match(p) ? RegExp.$1 : false;
  }

  $("#url").bind("change keyup input", function() {
    var url = $(this).val();
    if (ytVidId(url) !== false) {
      var thumb = Youtube.thumb(url, "big");

      $("#thumbnail").val(thumb);
    } else {
      $("#thumbnail").val("");
    }
  });

  $("#import_single_videos").on("submit", function(e) {
    e.preventDefault();
    var form = $("#import_single_videos")[0];

    var data = new FormData(form);

    $.ajax({
      type: "POST",
      url: base_url + "save-video",
      cache: false,
      contentType: false,
      processData: false,
      data: data,
      success: function(data) {
        data = JSON.parse(data);
        $(".error").html("");
        altair_helpers.custom_preloader_hide();
        if (data.code == 204) {
          $("#err-msg").attr("data-message", data.message);
          $("#err-msg").click();
          setTimeout(function() {
            window.location = data.url;
          }, 1000);
        } else if (data.code == 201) {
          $("#err-msg").attr("data-message", data.message);
          $("#err-msg").click();
          $("#import_videos")
            .parsley()
            .reset();
          $.each(data.error, function(i, v) {
            $("#" + i)
              .parent()
              .parent()
              .parent()
              .find(".error")
              .html(v);
          });
        } else if (data.code == 200) {
          if (data.problems) {
            //sendBulkUploadDetails(data.problems);
          }
          $("#suc-msg").attr("data-message", data.message);

          //sendBulkUploadDetails(data.import_status_message);
          $("#suc-msg").click();
          setTimeout(function() {
            //window.location = data.url;
          }, 1000);
        } else {
          $("#err-msg").attr("data-message", "Something is going wrong!");
          $("#err-msg").click();
        }
      },
      error: function() {
        $("#err-msg").attr("data-message", "Something is going wrong!");
        $("#err-msg").click();
      }
    });
  });

  $("#import_videos").on("submit", function(e) {
    e.preventDefault();
    var form = $("#import_videos")[0];

    var data = new FormData(form);
    console.log("form data " + data);
    $.ajax({
      type: "POST",
      enctype: "multipart/form-data",
      url: base_url + "import-videos",
      cache: false,
      contentType: false,
      processData: false,
      data: data,
      success: function(data) {
        data = JSON.parse(data);
        $(".error").html("");
        altair_helpers.custom_preloader_hide();
        if (data.code == 204) {
          $("#err-msg").attr("data-message", data.message);
          $("#err-msg").click();
          setTimeout(function() {
            window.location = data.url;
          }, 1000);
        } else if (data.code == 201) {
          $("#err-msg").attr("data-message", data.message);
          $("#err-msg").click();
          $("#import_videos")
            .parsley()
            .reset();
          $.each(data.error, function(i, v) {
            $("#" + i)
              .parent()
              .parent()
              .parent()
              .find(".error")
              .html(v);
          });
        } else if (data.code == 200) {
          if (data.problems) {
            sendBulkUploadDetails(data.problems);
          }
          $("#suc-msg").attr("data-message", data.message);

          sendBulkUploadDetails(data.import_status_message);
          $("#suc-msg").click();
          setTimeout(function() {
            //window.location = data.url;
          }, 1000);
        } else {
          $("#err-msg").attr("data-message", "Something is going wrong!");
          $("#err-msg").click();
        }
      },
      error: function() {
        $("#err-msg").attr("data-message", "Something is going wrong!");
        $("#err-msg").click();
      }
    });
  });
});

function sendBulkUploadDetails(message, adminEmail) {
  $.ajax({
    type: "POST",
    url: base_url + "send_mail",
    data: {
      to: adminEmail,
      cc: "",
      bcc: "",
      subject: "Bulk Upload Complete",
      message: message
    },
    success: function(data) {
      data = JSON.parse(data);
      if (data.code == 204) {
        $("#err-msg").attr("data-message", data.message);
        $("#err-msg").click();
      } else if (data.code == 201) {
        alert(data.error);
      } else if (data.code == 200) {
        window.close();
      } else {
        $("#err-msg").attr("data-message", "Something is going wrong!");
        $("#err-msg").click();
      }
    },
    error: function() {
      $("#err-msg").attr("data-message", "Something is going wrong!");
      $("#err-msg").click();
    }
  });
}
