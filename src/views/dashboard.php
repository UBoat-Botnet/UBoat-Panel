<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 2/27/2016
 * Time: 4:11 PM.
 */
?>
<script>
    $(function() {

        var visitorsData = <?php echo strtoupper($countries); ?>;
        $('#world-map').vectorMap({
            map: 'world_mill_en',
            backgroundColor: "transparent",
            regionStyle: {
                initial: {
                    fill: '#e4e4e4',
                    "fill-opacity": 1,
                    stroke: 'none',
                    "stroke-width": 0,
                    "stroke-opacity": 1
                }
            },
            series: {
                regions: [{
                    values: visitorsData,
                    scale: ["#92c1dc", "#ebf4f9"],
                    normalizeFunction: 'polynomial'
                }]
            },
            onRegionLabelShow: function (e, el, code) {
                if (typeof visitorsData[code] != "undefined")
                    el.html(el.html() + ': ' + visitorsData[code] + ' new visitors');
            }
        });

        var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas);
        var PieData = [
            {
                value: <?php echo $os_data['Windows Vista']; ?>,
                color: "#f56954",
                highlight: "#f56954",
                label: "Windows Vista"
            },
            {
                value: <?php echo $os_data['Windows XP']; ?>,
                color: "#00a65a",
                highlight: "#00a65a",
                label: "Windows XP"
            },
            {
                value: <?php echo $os_data['Windows 7']; ?>,
                color: "#f39c12",
                highlight: "#f39c12",
                label: "Windows 7"
            },
            {
                value: <?php echo $os_data['Windows 8']; ?>,
                color: "#00c0ef",
                highlight: "#00c0ef",
                label: "Windows 8"
            },
            {
                value: <?php echo $os_data['Windows 8.1']; ?>,
                color: "#3c8dbc",
                highlight: "#3c8dbc",
                label: "Windows 8.1"
            },
            {
                value: <?php echo $os_data['Windows 10']; ?>,
                color: "#ff8000",
                highlight: "#ffbf80",
                label: "Windows 10"
            },
            {
                value: <?php echo $os_data['Other']; ?>,
                color: "#d2d6de",
                highlight: "#d2d6de",
                label: "Other"
            }
        ];
        var pieOptions = {
            //Boolean - Whether we should show a stroke on each segment
            segmentShowStroke: true,
            //String - The colour of each segment stroke
            segmentStrokeColor: "#fff",
            //Number - The width of each segment stroke
            segmentStrokeWidth: 1,
            //Number - The percentage of the chart that we cut out of the middle
            percentageInnerCutout: 50, // This is 0 for Pie charts
            //Number - Amount of animation steps
            animationSteps: 100,
            //String - Animation easing effect
            animationEasing: "easeOutBounce",
            //Boolean - Whether we animate the rotation of the Doughnut
            animateRotate: true,
            //Boolean - Whether we animate scaling the Doughnut from the centre
            animateScale: false,
            //Boolean - whether to make the chart responsive to window resizing
            responsive: true,
            // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio: false,
            //String - A legend template
            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
            //String - A tooltip template
            tooltipTemplate: "<%=value %> <%=label%> users"
        };
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        pieChart.Doughnut(PieData, pieOptions);
    });
</script>
<div class="alpha-container container-fluid">
    <?php echo $header; ?>
    <div class="container-fluid col-lg-10" style="margin-right: 0; background-color: white; margin-top: 1em;">
        <br>
        <div class="box-body">
            <div id="world-map" style="height: 450px; width: 100%;"></div>
        </div>
        <div class="box-footer">
            <div class="row">
                <div class="col-sm-3 col-xs-6">
                    <div class="description-block border-right">
                        <?php $percent_on_off = (0 != $on_off['total'][0]) ? (int) ($on_off['online'][0] * 100) / $on_off['total'][0] : 0; ?>
                        <?php echo ($on_off['online'][0] > $on_off['offline'][0]) ? '<span class="description-percentage text-green"><i class="fa fa-caret-up"></i> '.$percent_on_off.' %</span>' : (($on_off['online'][0] === $on_off['offline'][0]) ? '<span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> '.$percent_on_off.' %</span>' : '<span class="description-percentage text-red"><i class="fa fa-caret-down"></i> '.$percent_on_off.' %</span>'); ?>

                        <h5 class="description-header"><?php echo $on_off['online'][0]; ?></h5>
                        <span class="description-text">TOTAL ONLINE</span>
                    </div><!-- /.description-block -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.box-footer -->
        <div class="box box-default" style="padding-bottom: 2em;">
            <div class="box-header with-border">
                <h3 class="box-title">Windows Version</h3>
            </div><!-- /.box-header -->
            <br>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-2">
                        <ul class="chart-legend clearfix" style="list-style-type: none;">
                            <li><i class="fa fa-circle-o" style="color: #f56954;"></i> Windows Vista</li>
                            <li><i class="fa fa-circle-o" style="color: #00a65a;"></i> Windows XP</li>
                            <li><i class="fa fa-circle-o" style="color: #f39c12;"></i> Windows 7</li>
                            <li><i class="fa fa-circle-o" style="color: #00c0ef;"></i> Windows 8</li>
                            <li><i class="fa fa-circle-o" style="color: #3c8dbc;"></i> Windows 8.1</li>
                            <li><i class="fa fa-circle-o" style="color: #ffbf80;"></i> Windows 10</li>
                            <li><i class="fa fa-circle-o" style="color: #d2d6de;"></i> Other</li>
                        </ul>
                    </div><!-- /.col -->
                    <div class="col-md-4">
                        <div class="chart-responsive">
                            <canvas id="pieChart" height="200"></canvas>
                        </div><!-- ./chart-responsive -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.box-body -->
        </div>
    </div>
</div>

