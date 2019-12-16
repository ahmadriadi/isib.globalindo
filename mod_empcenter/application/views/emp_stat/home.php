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

        <style type="text/css">a.ui-dialog-titlebar-close { display:none }</style>
        
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
                    // init pie chart
                    this.chart_pie.init();
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
                    chartColors: [ themerPrimaryColor, "#444", "#777", "#999", "#DDD", "#EEE" ],
                    chartBackgroundColors: ["transparent", "transparent"],

                    applyStyle: function(that)
                    {
                            that.options.colors = charts.utility.chartColors;
                            that.options.grid.backgroundColor = { colors: charts.utility.chartBackgroundColors };
                            that.options.grid.borderColor = charts.utility.chartColors[0];
                            that.options.grid.color = charts.utility.chartColors[0];
                    },

                    // generate random number for charts
                    randNum: function()
                    {
                            return (Math.floor( Math.random()* (1+40-20) ) ) + 20;
                    }
                },

                // pie chart
                chart_pie:
                {
                    // chart data
                    data: [
                        <?php echo $data;?>
                    ],

                    // will hold the chart object
                    plot: null,

                    // chart options
                    options: 
                    {
                        series: {
                            pie: { 
                                show: true,
                                highlight: {
                                        opacity: 0.1
                                },
                                radius: 1,
                                stroke: {
                                        color: '#fff',
                                        width: 2
                                },
                                startAngle: 2,
                                combine: {
                                    color: '#353535',
                                    threshold: 0.05
                                },
                                label: {
                                    show: true,
                                    radius: 1,
                                    formatter: function(label, series){
                                        return '<div class="label label-inverse" >'+label+'&nbsp;'+Math.round(series.percent)+'%</div>';
                                    }
                                }
                            },
                            grow: {	active: false}
                        },
                        colors: [],
                        legend:{show:true},
                        grid: {
                            hoverable: true,
                            clickable: true,
                            backgroundColor : { }
                        },
                        tooltip: true,
                        tooltipOpts: {
                            content: "%s : %y.1"+"%",
                            shifts: {
                                    x: -30,
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
                            //load plot
                            this.plot = $.plot($("#chart_pie"), this.data, this.options);
                    }
                }

            };
            $(function()
            {
                // initialize charts
                if (typeof charts != 'undefined') 
                    charts.initCharts();
            });
        
            
            
        </script>   
    </head>
    <body>	
        <div class="widget">
		<!-- Widget heading -->
		<div class="widget-head">
			<h4 class="heading">Gender chart</h4>
		</div>
		<!-- // Widget heading END -->
		
		<div class="widget-body">
		
			<!-- Pie Chart -->
			<div id="chart_pie" style="height: 250px;"></div>
		</div>
	</div>
	<!-- Easy-pie Plugin -->
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/charts/easy-pie/jquery.easy-pie-chart.js"></script>
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/charts/flot/jquery.flot.js"></script>
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/charts/flot/jquery.flot.pie.js"></script>

	

    </body>
</html>
