$(function() {

    var modal_y1 = null;
    var modal_label1 = null;
    var modal_y2 = null;
    var modal_label2 = null;

    chart1 = new Chart($("#vl_chart1"), {
        type: 'line',
        data: {}
    });
    chart2 = new Chart($("#vl_chart2"), {
        type: 'bar',
        data: {}
    });
    modal_chart1 = new Chart($("#chart1-enlarged"), {
        type: 'line',
        data: {}
    });
    modal_chart2 = new Chart($("#chart2-enlarged"), {
        type: 'line',
        data: {}
    });

    $('#date_to').focus();
    $('#date_from').focus();
    // $('section').focus();
    
    load_charts();

    $('#search').click(function() {
        load_charts();
    });

    $("select#publish_status").change(function() {
        load_charts();
    });

    function load_charts() {
        $.ajax({
            type : "POST",
            url  : base_url+'dashboard_vl',
            data : {
                date_from : $('#date_from').val(),
                date_to : $('#date_to').val(),
                status : $('#publish_status').val(),
            },
            success : function (res) {
                res = JSON.parse(res);
                if(res["code"] != 200) {
                    return;
                }
                if (res.vl_chart1 != null) {
                    var label1 = res.vl_chart1.map(function(index) {
                        return index.label1;
                    });
                    var data1 = res.vl_chart1.map(function(index) {
                        return index.y1;
                    });
                    chart1.destroy();
                    chart1 = new Chart($("#vl_chart1"), {
                        type: 'line',
                        data: {
                        labels: label1,
                        datasets: [{
                            label: 'by Time',
                            data: data1,
                            borderWidth: 1
                        }]
                        },
                        options: {
                            scales: {
                                y: {
                                beginAtZero: true
                                }
                            }
                        }
                    });
                    modal_label1 = label1;
                    modal_y1 = data1;
                }
                if (res.vl_chart2 != null) {
                    var label2 = res.vl_chart2.map(function(index) {
                        return index.label2;
                    });
                    var data2 = res.vl_chart2.map(function(index) {
                        return index.y2;
                    });
                    chart2.destroy();
                    chart2 = new Chart($("#vl_chart2"), {
                        type: 'bar',
                        data: {
                        labels: label2,
                        datasets: [{
                            label: 'by People',
                            data: data2,
                        }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                }
                            }
                        }
                    });
                    modal_label2 = label2;
                    modal_y2 = data2;
                }
            }
        });
    }

    $(document).on('click', '#chart1-enlarge', function(e){
        e.preventDefault();
        var modal = UIkit.modal("#chart1-modal");
        modal.show();
        modal_chart1.destroy();
        modal_chart1 = new Chart($("#chart1-enlarged"), {
            type: 'line',
            data: {
            labels: modal_label1,
            datasets: [{
                label: 'by Time',
                data: modal_y1,
                borderWidth: 1
            }]
            },
            options: {
                scales: {
                    y: {
                    beginAtZero: true
                    }
                }
            }
        });
    });
    
    $(document).on('click', '#chart2-enlarge', function(e){
        e.preventDefault();
        var modal = UIkit.modal("#chart2-modal");
        modal.show();
        modal_chart2.destroy();
        modal_chart2 = new Chart($("#chart2-enlarged"), {
            type: 'bar',
            data: {
            labels: modal_label2,
            datasets: [{
                label: 'by Time',
                data: modal_y2,
                borderWidth: 1
            }]
            },
            options: {
                scales: {
                    y: {
                    beginAtZero: true
                    }
                }
            }
        });
    });
});