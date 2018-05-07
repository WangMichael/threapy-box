
define(function () {
    "use strict";

    return {

        drawPie: function ($) {


            var config = $('#clothes_id');


            if(!(config.length))
                return;


            $.getScript('https://www.gstatic.com/charts/loader.js', function(){

                google.charts.load("current", {packages:["corechart"]});
                google.charts.setOnLoadCallback(function () {

                    var $config = $('#clothes_id').attr('data-config'),
                        config  = JSON.parse($config);

                    var config_array = [];
                    config_array.push(['Clothes ratio', '']);
                    for (var category in config) {
                        config_array.push([category, config[category]]);
                    }

                    var data = google.visualization.arrayToDataTable(config_array);

                    var options = {
                        legend: 'none',
                        pieStartAngle: 100,
                        backgroundColor: 'transparent',
                        width: 180,
                        height: 160
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('clothes_id'));
                    chart.draw(data, options);

                });

            });

        },

        setup: function($){

            var obj = this;

            $(function(){

                obj.drawPie($);
            });
        }
    };
});