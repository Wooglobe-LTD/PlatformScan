/**
 * Created by Abdul Rehman Aziz on 4/4/2018.
 */
var chart;
var legend;
var selected;

var types = [{
    type: "Add Revenue",
    percent: ad_revenue,
    color: "#3cabff",
},
    {
        type: "Licensing",
        percent: license_revenue,
        color: "#73b52d",
    }];
function generateChartData() {
    var chartData = [];
    for (var i = 0; i < types.length; i++) {
        if (i == selected) {
            for (var x = 0; x < types[i].subs.length; x++) {
                chartData.push({
                    type: types[i].subs[x].type,
                    percent: types[i].subs[x].percent,
                    color: types[i].color,
                    pulled: true
                });
            }
        } else {
            chartData.push({
                type: types[i].type,
                percent: types[i].percent,
                color: types[i].color,
                id: i
            });
        }
    }
    return chartData;
}
/*AmCharts.makeChart("chartdiv", {
    "type": "pie",
    "theme": "light",
    "radius": "35%",
    "innerRadius": "60%",
    "legend":{
        "position":"bottom",
        "marginRight":100,
        "autoMargins":false
    },

    "dataProvider": generateChartData(),
    "labelText": "[[title]]: [[value]]",
    "balloonText": "[[title]]: [[value]]",
    "titleField": "type",
    "valueField": "percent",
    "outlineColor": "#FFFFFF",
    "outlineAlpha": 0.8,
    "outlineThickness": 2,
    "colorField": "color",
    "pulledField": "pulled",

    "listeners": [{
        "event": "clickSlice",
        "method": function(event) {
            var chart = event.chart;
            if (event.dataItem.dataContext.id != undefined) {
                selected = event.dataItem.dataContext.id;
            } else {
                selected = undefined;
            }
            chart.dataProvider = generateChartData();
            chart.validateData();
        }
    }],
    "export": {
        "enabled": false
    }
});*/
$(function () {

    $('#search').change(function () {

        var val = $('#search').val();
        var href = window.location.href;



        if( href.includes( '?' )) {

            var ori_href = href.split('?');



            window.location = ori_href[0]+"?video="+val;
        }
        else {
            window.location = href+"?video="+val;
        }

    });



});
