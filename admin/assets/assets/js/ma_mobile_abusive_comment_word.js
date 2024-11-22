var table_export;
var soryFlast = parseInt(parseInt($("thead").find("th").length) - 1);
var statuss;
var statusAdd;

$(function() {
  // datatables
  altair_datatables.dt_tableExport();

  statuss = $("#status_e").selectize();

  if (statuss.length > 0) {
    statuss = statuss[0].selectize;
  }

  statusAdd = $("#status").selectize();

  if (statusAdd.length > 0) {
    statusAdd = statusAdd[0].selectize;
  }

  $("#add").on("click", function() {
    $("#ma_form_validation2")
      .parsley()
      .reset();
    $("#ma_form_validation2")[0].reset();
    statusAdd.setValue("");

    var modal = UIkit.modal("#add_model");
    modal.show();
  });
  $(".single")
    .eq(4)
    .css("z-index", 9999);
  $(".single")
    .eq(5)
    .css("z-index", 9999);
  $(".single")
    .eq(6)
    .css("z-index", 9999);
  $(".single")
    .eq(7)
    .css("z-index", 9999);
  $("#add_from").on("click", function(e) {
    e.preventDefault();

    $("#ma_form_validation2").submit();
  });

  $("#edit_from").on("click", function(e) {
    e.preventDefault();

    $("#ma_form_validation3").submit();
  });

  $("#ma_form_validation2").on("submit", function(e) {
    e.preventDefault();
    var formData = new FormData($("#ma_form_validation2")[0]);

    $.ajax({
      type: "POST",
      url: base_url + "mobile-app-add_abusive_comment_word",
      processData: false,
      contentType: false,
      data: formData,

      success: function(data) {
        data = JSON.parse(data);
        if (data.code == 204) {
          $("#err-msg").attr("data-message", data.message);
          $("#err-msg").click();
          setTimeout(function() {
            window.location = data.url;
          }, 1000);
        } else if (data.code == 201) {
          $("#err-msg").attr("data-message", data.message);
          $("#err-msg").click();
          $("#ma_form_validation2")
            .parsley()
            .reset();
          $.each(data.error, function(i, v) {
            $("#" + i)
              .parent()
              .parent()
              .find(".error")
              .html(v);
          });
        } else if (data.code == 200) {
          $("#suc-msg").attr("data-message", data.message);
          $("#suc-msg").click();

          var modal = UIkit.modal("#add_model");
          modal.hide();
          $("#ma_form_validation2")
            .parsley()
            .reset();
          $("#ma_form_validation2")[0].reset();
          table_export.ajax.reload();
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

  $(document).on("click", ".ma-edit-acw", function() {
    var id = $(this).data("id");

    $("#ma_form_validation3")
      .parsley()
      .reset();
    $("#ma_form_validation3")[0].reset();
    $.ajax({
      type: "POST",
      url: base_url + "mobile-app-get_abusive_comment_word",
      data: { id: id },
      success: function(data) {
        data = JSON.parse(data);

        if (data.code == 204) {
          $("#err-msg").attr("data-message", data.message);
          $("#err-msg").click();
          setTimeout(function() {
            window.location = data.url;
          }, 1000);
        } else if (data.code == 201) {
          $("#err-msg").attr("data-message", data.message);
          $("#err-msg").click();
          $("#ma_form_validation3")
            .parsley()
            .reset();
        } else if (data.code == 200) {
          var modal = UIkit.modal("#edit_model");
          modal.show();
          $.each(data.data, function(i, v) {
            $("#" + i + "_e").val(v);

            if ($("#" + i + "_e").hasAttr("select-s")) {
              statuss.setValue(v);
            }

            $("#" + i + "_e").focus();
          });
        } else {
          $("#err-msg").attr("data-message", "Something is going wrong!");
          $("#err-msg").click();
        }
      }
    });
  });

  $("#ma_form_validation3").on("submit", function(e) {
    e.preventDefault();

    $.ajax({
      type: "POST",
      url: base_url + "mobile-app-update_abusive_comment_word",
      data: $("#ma_form_validation3").serialize(),
      success: function(data) {
        data = JSON.parse(data);
        if (data.code == 204) {
          $("#err-msg").attr("data-message", data.message);
          $("#err-msg").click();
          setTimeout(function() {
            window.location = data.url;
          }, 1000);
        } else if (data.code == 201) {
          $("#err-msg").attr("data-message", data.message);
          $("#err-msg").click();
          $("#ma_form_validation3")
            .parsley()
            .reset();
          $.each(data.error, function(i, v) {
            $("#" + i + "_e")
              .parent()
              .parent()
              .find(".error")
              .html(v);
          });
        } else if (data.code == 200) {
          $("#suc-msg").attr("data-message", data.message);
          $("#suc-msg").click();

          var modal = UIkit.modal("#edit_model");
          modal.hide();
          $("#ma_form_validation3")
            .parsley()
            .reset();
          $("#ma_form_validation3")[0].reset();
          table_export.ajax.reload();
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

  $(document).on("click", ".ma-delete-acw", function() {
    var id = $(this).data("id");
    UIkit.modal.confirm("Are you sure to want delete this?", function() {
      $.ajax({
        type: "POST",
        url: base_url + "mobile-app-delete_abusive_comment_word",
        data: { id: id },
        success: function(data) {
          data = JSON.parse(data);

          if (data.code == 204) {
            $("#err-msg").attr("data-message", data.message);
            $("#err-msg").click();
            setTimeout(function() {
              window.location = data.url;
            }, 1000);
          } else if (data.code == 201) {
            $("#err-msg").attr("data-message", data.message);
            $("#err-msg").click();
          } else if (data.code == 200) {
            $("#suc-msg").attr("data-message", data.message);
            $("#suc-msg").click();
            table_export.ajax.reload();
          } else {
            $("#err-msg").attr("data-message", "Something is going wrong!");
            $("#err-msg").click();
          }
        }
      });
    });
  });
});

altair_datatables = {
  dt_tableExport: function() {
    var $dt_tableExport = $("#ma_dt_tableExport"),
      $dt_buttons = $dt_tableExport.prev(".dt_colVis_buttons");

    if ($dt_tableExport.length) {
      table_export = $dt_tableExport.DataTable({
        processing: true,
        serverSide: true,
        ajax: base_url + "mobile-app-get_abusive_comment_words",
        columnDefs: [{ orderable: false, targets: [soryFlast, 0] }]
      });
    }
  }
};
