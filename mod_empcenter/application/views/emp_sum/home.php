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
            a.ui-dialog-titlebar-close { display:none }</style>
        
	<!-- Easy-pie Plugin -->
	<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/charts/easy-pie/jquery.easy-pie-chart.css" rel="stylesheet" />
        
        <!-- JQuery -->
        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-latest.js"></script>

        <!-- JQueryUI -->
        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
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
                    chartColors: [ "#0080ff", "#ff0080", "#80ff00", "#999", "#DDD", "#EEE" ],
                    chartBackgroundColors: ["transparent", "transparent"],

                    applyStyle: function(that)
                    {
                        that.options.colors = charts.utility.chartColors;
                        that.options.grid.backgroundColor = { colors: charts.utility.chartBackgroundColors };
                        that.options.grid.borderColor = charts.utility.chartColors[0];
//                        that.options.grid.color = charts.utility.chartColors[0];
                        that.options.grid.color = "#fff";// set text color of chart
                    },

                    // generate random number for charts
                    randNum: function()
                    {
                        return (Math.floor( Math.random()* (1+40-20) ) ) + 20;
                    }
                },

                // chart stacked
                chart_stacked_bars:
                {
                    // chart data
                    data: <?php echo $chart_data;?>,
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
                            color: "#afafaf" ,
                            labelMargin: 5,
                            axisMargin: 0, 
                            borderWidth: 0,
                            borderColor:null,
                            minBorderMargin: 5 ,
                            clickable: true, 
                            hoverable: true,
                            autoHighlight: true,
                            mouseActiveRadius: 20,
                            backgroundColor : { }
                        },
                        series: {
                            grow: {active:false},
                            stack: 0,
                            lines: { show: false, fill: true, steps: false },
                            bars: { show: true, barWidth: 0.5, fill:1}
                        },
                        xaxis: { ticks:[[0, "Jan"], [1, "Feb"], [2, "Mar"], [3, "Apr"], [4, "May"], [5, "Jun"], [6, "Jul"], [7, "Aug"], [8, "Sep"], [9, "Oct"], [10, "Nov"], [11, "Dec"]], color:"#fff",tickDecimals: 0},
                        yaxis: { ticks: 10,color:"#fff"},
                        legend: { position: "ne", backgroundColor: "#000", backgroundOpacity: 0, container : $("#legendchart")},
                        colors: [],
                        shadowSize:1,
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
            $(function()
            {
                // initialize charts
                if (typeof charts != 'undefined') 
                    charts.initCharts();
            });
            $(document).ready(function(){
                var this_tahun = $("#thnhide").val();
                if (this_tahun == <?php echo date("Y"); ?>){
                    $("a.chevron-right").addClass("disabled");
                    $("a.chevron-right").prop("onclick","");
                }
            });
            function togbtn(){
                var this_tahun = $("#thnhide").val();
                var last_tahun = 2008;//set last tahun here
                if (this_tahun == <?php echo date("Y"); ?>){
                    $("a.chevron-right").addClass("disabled");
                    $("a.chevron-right").prop("onclick","");
                } if (this_tahun < <?php echo date("Y");?>){
                    $("a.chevron-right").remove();
                    $("div.nextbtn").append('<a class="btn btn-small btn-default glyphicons standard chevron-right" onclick="get_data(\'chev_next\')"><i></i>Next</a>');
                }
                if (this_tahun == last_tahun){
                    $("a.chevron-left").addClass("disabled");
                    $("a.chevron-left").prop("onclick","");
                } if (this_tahun > last_tahun){
                    $("a.chevron-left").remove();
                    $("div.prevbtn").append('<a class="btn btn-small btn-default glyphicons standard chevron-left" onclick="get_data(\'chev_prev\')"><i></i>Prev</a>');
                }
            }
            function get_data(btn){
                var this_tahun = $("#thnhide").val();
                if (btn == "drop"){
                    var tahun = $("#tahun").val();
                    $("#thnhide").val(tahun);
                    togbtn();
                }
                if (btn == "chev_prev"){
                    var tahun = this_tahun-1;
//                    alert(tahun);
                    $("#thnhide").val(tahun);
                    $("select#tahun").find("option[value='"+tahun+"']").prop("selected",true);
                    togbtn();
                }
                if (btn == "chev_next"){
                    var tahun = this_tahun*(-1)*(-1)+1;
//                    alert(tahun);
                    $("#thnhide").val(tahun);
                    $("select#tahun").find("option[value='"+tahun+"']").prop("selected",true);
                    togbtn();
                }
                $.ajax({
                    url : "<?php echo site_url();?>/emp_sum/home/get_data",
                    data: "tahun="+tahun,
                    type: "post",
                    dataType:"json",
                    cache : false,
                    success : 
                    function (data_tabel){
                        $(".head_tahun").text(tahun);
//                        alert(data_tabel.toSource());
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
                                chartColors: [  "#0080ff", "#ff0080", "#80ff00", "#999", "#DDD", "#EEE" ],
                                chartBackgroundColors: ["transparent", "transparent"],

                                applyStyle: function(that)
                                {
                                    that.options.colors = charts.utility.chartColors;
                                    that.options.grid.backgroundColor = { colors: charts.utility.chartBackgroundColors };
                                    that.options.grid.borderColor = charts.utility.chartColors[0];
//                                    that.options.grid.color = charts.utility.chartColors[0];
                                    that.options.grid.color = "#fff";// set text color of chart
                                },

                                // generate random number for charts
//                                randNum: function()
//                                {
//                                    return (Math.floor( Math.random()* (1+40-20) ) ) + 20;
//                                }
                            },

                            // chart stacked
                            chart_stacked_bars:
                            {
                                // chart data
//                                data_tabel,
                                data: data_tabel,
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
                                        color: "#afafaf" ,
                                        labelMargin: 5,
                                        axisMargin: 0, 
                                        borderWidth: 0,
                                        borderColor:null,
                                        minBorderMargin: 5 ,
                                        clickable: true, 
                                        hoverable: true,
                                        autoHighlight: true,
                                        mouseActiveRadius: 20,
                                        backgroundColor : { }
                                    },
                                    series: {
                                        grow: {active:false},
                                        stack: 0,
                                        lines: { show: false, fill: true, steps: false },
                                        bars: { show: true, barWidth: 0.5, fill:1}
                                    },
                                    xaxis: { ticks:[[0, "Jan"], [1, "Feb"], [2, "Mar"], [3, "Apr"], [4, "May"], [5, "Jun"], [6, "Jul"], [7, "Aug"], [8, "Sep"], [9, "Oct"], [10, "Nov"], [11, "Dec"]], color:"#fff",tickDecimals: 0},
                                    yaxis: { ticks: 10,color:"#fff"},
                                    legend: { position: "ne", backgroundColor: "#000", backgroundOpacity: 0, container : $("#legendchart")},
                                    colors: [],
                                    shadowSize:1,
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
                    },
                    error   : function (a){
                        alert(a.responseText);
                    }
                });                
            };
        </script>
    </head>
    <body>
	<div class="widget">
            <input type="hidden" id="thnhide" value="<?php echo date("Y"); ?>">
		<!-- Widget heading -->
		<div class="widget-head">
			<h4 class="heading">Employee Summary</h4>
		</div>
		<!-- // Widget heading END -->
		
		<div class="widget-body">
		
                    <!-- Stacked bars Chart --><center><h3 class="head_tahun"><?php echo date("Y");?></h3></center>
			<div id="chart_stacked_bars" style="height: 250px;"></div>
                        <div class="row-fluid">
                            <div id="legendchart" class="span3"></div>
                            <div class="span8 row-fluid">
                                <div class="control-group span7">
                                    <label class="control-label span2" for="tahun"><h4>Year</h4></label>
                                    <div class="controls">
                                        <select name="tahun" class="span3"id="tahun" onchange="get_data('drop')">
                                            <?php 
                                            $now = date("Y");
                                            for($i=2008;$i<=$now;$i++){
                                                $i == $now ? $selected = "selected" : $selected = "";
                                                echo "<option value='$i' $selected >$i</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="span1 center hidden-phone prevbtn">
                                    <a class="btn btn-small btn-default glyphicons standard chevron-left" onclick="get_data('chev_prev')"><i></i>Prev</a>
                                </div>
                                <div class="span3 center hidden-phone"><h2 class="head_tahun"><?php echo date("Y");?></h2></div>
                                <div class="span1 center hidden-phone nextbtn">
                                    <a class="btn btn-small btn-default glyphicons standard chevron-right" onclick="get_data('chev_next')"><i></i>Next</a>
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
