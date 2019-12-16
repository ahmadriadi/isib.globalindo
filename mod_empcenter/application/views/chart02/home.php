<!DOCTYPE html PUBLIC"-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <!-- Meta -->
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta content="utf-8" http-equiv="encoding" />

        <?php $base_url = $this->session->userdata('sess_base_url'); ?>
        <!-- JQueryUI -->

        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" />
        <!-- hide the close link in the toolbar -->

        <style type="text/css">
            #legendchart{ color: #fff;}
            a.ui-dialog-titlebar-close { display:none }
            .ui-datepicker-calendar {
                display: none;
            }

        </style>

        <!-- Easy-pie Plugin -->
        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/charts/easy-pie/jquery.easy-pie-chart.css" rel="stylesheet" />

        <!-- JQuery -->
        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-latest.js"></script>


        <!-- JQueryUI -->
        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/monthpicker.js"></script>
        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

        <script type="text/javascript" charset="utf-8">
            var ROOT = {
                'site_url': '<?php echo $base_url . '/index.php'; ?>',
                'base_url': '<?php echo $base_url; ?>'
            };
            
         
            

            var charts =
                    {
                        // init charts on finances page
                        initFinances: function()
                        {
                            // init simple chart
                            this.chart_simple.init();
                        },
                        // init charts on Charts page
                        initCharts: function()
                        {
                            // init stacked bars chart
                            this.chart_stacked_bars.init();
                        },
                        // init charts on dashboard
                        initIndex: function()
                        {
                            // chart_ordered_bars
                            //this.chart_ordered_bars.init();

                            // init lines chart with fill & without points
                            this.chart_lines_fill_nopoints.init();

                            // init traffic sources pie
                            this.traffic_sources_pie.init();
                        },
                        // utility class
                        utility:
                                {
                                    chartColors: ["#2bed3b", "#FF9C2A", "#f52c5b", "#999", "#DDD", "#EEE"],
                                    chartBackgroundColors: ["transparent", "transparent"],
                                    applyStyle: function(that)
                                    {
                                        that.options.colors = charts.utility.chartColors;
                                        that.options.grid.backgroundColor = {colors: charts.utility.chartBackgroundColors};
                                        that.options.grid.borderColor = charts.utility.chartColors[0];
//                        that.options.grid.color = charts.utility.chartColors[0];
                                        that.options.grid.color = "#fff";// set text color of chart
                                    },
                                    // generate random number for charts
                                    randNum: function()
                                    {
                                        return (Math.floor(Math.random() * (1 + 40 - 20))) + 20;
                                    }
                                },
                        // chart stacked
                        chart_stacked_bars:
                                {
                                    // chart data
                                    data: <?php echo $chart_data; ?>,
//                        {label:"Data Two", data:[[0, 5], [1, 1], [2, 3], [3, 15]]},
//                        {label:"Data Three", data:[[0, 8], [1, 10], [2, 4], [3, 2]]}
//                    ],



                                    // will hold the chart object
                                    plot: null,
                                    // chart options
                                    options:
                                            {
                                                grid: {
                                                    show: true,
                                                    aboveData: true,
                                                    color: "#afafaf",
                                                    labelMargin: 5,
                                                    axisMargin: 0,
                                                    borderWidth: 0,
                                                    borderColor: null,
                                                    minBorderMargin: 5,
                                                    clickable: true,
                                                    hoverable: true,
                                                    autoHighlight: true,
                                                    mouseActiveRadius: 20,
                                                    backgroundColor: {}
                                                },
                                                series: {
                                                    grow: {active: false},
                                                    stack: 0,
                                                    lines: {show: false, fill: true, steps: false},
                                                    bars: {show: true, barWidth: 0.5, fill: 1}
                                                },
                                                xaxis: {ticks: [[0, "1"], [1, "2"], [2, "3"], [3, "4"], [4, "5"], [5, "6"],
                                                                [6, "7"], [7, "8"], [8, "9"], [9, "10"], [10, "11"], [11, "12"],
                                                                [12, "13"], [13, "14"], [14, "15"], [15, "16"], [16, "17"], [17, "18"]
                                                                [18, "19"], [19, "20"], [20, "21"], [21, "22"], [22, "23"], [23, "24"]
                                                                [24, "25"], [25, "26"], [26, "27"], [27, "28"], [28, "29"], [29, "30"]
                                                                [30, "31"]
                                                                                ], color: "#fff", tickDecimals: 0},
                                                yaxis: {ticks: 10, color: "#fff"},
                                                legend: {position: "ne", backgroundColor: "#000", backgroundOpacity: 0, container: $("#legendchart")},
                                                colors: [],
                                                shadowSize: 1,
                                                tooltip: true,
                                                tooltipOpts: {
                                                    content: "%s : %y.0", // 0 setelah %y. adalah desimal, jml 0 di belakang koma
                                                    shifts: {
                                                        x: -70, //atur posisi tooltip garis x
                                                        y: -50  //atur posisi tooltip garis y
                                                    },
                                                    defaultTheme: false
                                                }
                                            },
                                    // initialize
                                    init: function()
                                    {
                                        // apply styling
                                        charts.utility.applyStyle(this);
//                        alert(arraynya.toSource()); alert data berbentuk array
                                        this.plot = $.plot($("#chart_stacked_bars"), this.data, this.options);
                                    }
                                },
                    };


            /* ============END CHART FROM INDEX=============  */

            
            function toObject(arr) {
                var rv = {};
                for (var i = 0; i < arr.length; ++i)
                  if (arr[i] !== undefined) rv[arr[i].id] = arr[i].name;
                return rv;
              }
            
            

            $(function()
            {
                // initialize charts
                if (typeof charts != 'undefined')
                    charts.initCharts();
            });

           /* $(document).ready(function() { */

                options = {
                    pattern: 'mm, yyyy', // Default is 'mm/yyyy' and separator char is not mandatory
                    selectedYear: '<?php echo $years2 ?>',
                    startYear: '<?php echo $years1 ?>',
                    finalYear: '<?php echo $years2 ?>',
                    //monthNames: ['', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez']
                };



                $('#monthdata').monthpicker(options);

                $('#monthdata').monthpicker().bind('monthpicker-hide', function(event) {
                    var dept = $("#dept").val();
                    var loc = $('input:radio[name=location]:checked').val();
                    var monthdata = $("#monthdata").val();
                    var url_post = '<?php echo site_url(); ?>/chart02/home/getchart_param';

                    $.ajax({
                        url: url_post,
                        data: "dept=" + dept + "&loc=" + loc + "&valmonth=" + encodeURIComponent(monthdata),
                        type: "post",
                        dataType: "json",
                        cache: false,
                        success:
                                function(dataajax,text) {
                                
                                   $(".head_tahun").text(monthdata);
                                    /*============================START CHART AJAX ==================*/

                                    var charts =
                                            {
                                                // init charts on finances page
                                                initFinances: function()
                                                {
                                                    // init simple chart
                                                    this.chart_simple.init();
                                                },
                                                // init charts on Charts page
                                                initCharts: function()
                                                {
                                                    // init stacked bars chart
                                                    this.chart_stacked_bars.init();
                                                },
                                                // init charts on dashboard
                                                initIndex: function()
                                                {
                                                    // chart_ordered_bars
                                                    //this.chart_ordered_bars.init();

                                                    // init lines chart with fill & without points
                                                    this.chart_lines_fill_nopoints.init();

                                                    // init traffic sources pie
                                                    this.traffic_sources_pie.init();
                                                },
                                                // utility class
                                                utility:
                                                        {
                                                            chartColors: ["#2bed3b", "#FF9C2A", "#f52c5b", "#999", "#DDD", "#EEE"],
                                                            chartBackgroundColors: ["transparent", "transparent"],
                                                            applyStyle: function(that)
                                                            {
                                                                that.options.colors = charts.utility.chartColors;
                                                                that.options.grid.backgroundColor = {colors: charts.utility.chartBackgroundColors};
                                                                that.options.grid.borderColor = charts.utility.chartColors[0];
//                                    that.options.grid.color = charts.utility.chartColors[0];
                                                                that.options.grid.color = "#fff";// set text color of chart
                                                            },
                                                            // generate random number for charts
                                                            randNum: function()
                                                            {
                                                                return (Math.floor(Math.random() * (1 + 40 - 20))) + 20;
                                                            }
                                                        },
                                                // chart stacked
                                                chart_stacked_bars:
                                                        {
                                                            // chart data
//                                data_tabel,               
                                                            data: dataajax,
                                                                        
//                                [                         
//                                    data_tabel        
//                                      {"label":"Data Two", "data":[[0, "5"], [1, "1"], [2, "3"], [3, "15"]]}
//                                      {label:"Data Three", data:[[0, 8], [1, 10], [2, 4], [3, 2]]}
//                                ],

                                                            // will hold the chart object
                                                            plot: null,
                                                            // chart options
                                                            options:
                                                                    {
                                                                        grid: {
                                                                            show: true,
                                                                            aboveData: true,
                                                                            color: "#afafaf",
                                                                            labelMargin: 5,
                                                                            axisMargin: 0,
                                                                            borderWidth: 0,
                                                                            borderColor: null,
                                                                            minBorderMargin: 5,
                                                                            clickable: true,
                                                                            hoverable: true,
                                                                            autoHighlight: true,
                                                                            mouseActiveRadius: 20,
                                                                            backgroundColor: {}
                                                                        },
                                                                        series: {
                                                                            grow: {active: false},
                                                                            stack: 0,
                                                                            lines: {show: false, fill: true, steps: false},
                                                                            bars: {show: true, barWidth: 0.5, fill: 1}
                                                                        },
                                                                       xaxis: {ticks: [[0, "1"], [1, "2"], [2, "3"], [3, "4"], [4, "5"], [5, "6"],
                                                                                [6, "7"], [7, "8"], [8, "9"], [9, "10"], [10, "11"], [11, "12"],
                                                                                [12, "13"], [13, "14"], [14, "15"], [15, "16"], [16, "17"], [17, "18"]
                                                                                        [18, "19"], [19, "20"], [20, "21"], [21, "22"], [22, "23"], [23, "24"]
                                                                                        [24, "25"], [25, "26"], [26, "27"], [27, "28"], [28, "29"], [29, "30"]
                                                                                        [30, "31"]
                                                                                ], color: "#fff", tickDecimals: 0},
                                                                        yaxis: {ticks: 10, color: "#fff"},
                                                                        legend: {position: "ne", backgroundColor: "#000", backgroundOpacity: 0, container: $("#legendchart")},
                                                                        colors: [],
                                                                        shadowSize: 1,
                                                                        tooltip: true,
                                                                        tooltipOpts: {
                                                                            content: "%s : %y.0",
                                                                            shifts: {
                                                                                x: -70,
                                                                                y: -50
                                                                            },
                                                                            defaultTheme: false
                                                                        }
                                                                    },
                                                            // initialize
                                                            init: function()
                                                            {
                                                                // apply styling
                                                                charts.utility.applyStyle(this);
//                                    alert(data_chart.toSource()); //alert data berbentuk array
//                                    alert(this.data.toSource()); //alert data berbentuk array
                                                                this.plot = $.plot($("#chart_stacked_bars"), this.data, this.options);
                                                            }
                                                        },
                                            };
                                    $(function()
                                    {
                                        // initialize charts
                                        if (typeof charts != 'undefined')
                                            charts.initCharts();
                                    });


                                    /*==========================END CHART AJAX =====================*/



                                }
                    });

                    return false;
                });

         /*   }); */








        </script>
    </head>
    <body>
        <div class="widget">
            <input type="hidden" id="thnhide" value="<?php echo date("Y"); ?>">
            <!-- Widget heading -->
            <div class="widget-head">
                <h4 class="heading">Daily Man Hour Chart</h4>
            </div>
            <!-- // Widget heading END -->

            <div class="widget-body">

                <!-- Stacked bars Chart --><center><h3 class="head_tahun"><?php echo $default['monthdata']; ?></h3></center>
                <div id="chart_stacked_bars" style="height: 250px;"></div>
                <div class="row-fluid">
                    <div id="legendchart" class="span3"></div>
                    <div class="row-fluid">
                        <div class="span3">
                            <select class="span6" id="dept" name="dept" >
<?php foreach ($default['dept'] as $row) { ?>
                                    <option value="<?php echo (isset($row['value'])) ? $row['value'] : ''; ?>" 
    <?php echo (isset($row['selected'])) ? $row['selected'] : ''; ?> >
    <?php echo (isset($row['display'])) ? $row['display'] : ''; ?></option>
<?php } ?>
                            </select>
                        </div>
                        <div class="span3">
                                <?php
                                $no = 'A';
                                foreach ($default['location'] as $row) {
                                    ?>  
                                <input id="location<?php echo $no ?>" name="location" type="radio" 
                                       value="<?php echo (isset($row['value'])) ? $row['value'] : ''; ?>"
    <?php echo (isset($row['checked'])) ? $row['checked'] : ''; ?> >
                                <?php echo (isset($row['display'])) ? $row['display'] : ''; ?>
                                <?php
                                $no++;
                            }
                            ?>
                        </div>
                        <div class="span3">
                            <input class="span5" id="monthdata" name="monthdata" type="text" class="monthdata"  value="<?php echo set_value('monthdata', isset($default['monthdata']) ? $default['monthdata'] : ''); ?>" 
                                   <?php echo (isset($default['readonly_monthdata'])) ? $default['readonly_monthdata'] : ''; ?>
                                   />
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- // Widget END --> 
        <!--  Flot Charts Plugin -->
        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/charts/flot/jquery.flot.js"></script>
        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/charts/flot/jquery.flot.pie.js"></script>
        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/charts/flot/jquery.flot.tooltip.js"></script>
        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/charts/flot/jquery.flot.selection.js"></script>
        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/charts/flot/jquery.flot.resize.js"></script>
        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/charts/flot/jquery.flot.orderBars.js"></script>
        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/charts/flot/jquery.flot.stack.js"></script>

    </body>
</html>
