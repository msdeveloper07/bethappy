<style type="text/css">
    .chart-title { font-weight: bold; }
</style>

<?php
    $placeholderClass = isset($placeholderClass) ? $placeholderClass : null;
    $chartsData = isset($chartsData) ? $chartsData : array();
    $chartsCount = count($chartsData);
?>

<div class="row">
    <?php foreach($chartsData AS $chartIndex => $chartData): ?>
        <!--<span class="chart-title" style="margin-left:14px;"><?php echo $chartIndex ?></span>-->
            <div id="chart<?php echo str_replace(' ', '', $chartIndex) ?>" class="chart col-sm-12 col-md-<?= 12/$chartsCount; ?>" style="height: 300px; display:inline-block; vertical-align: top;min-width: 300px"></div>
    <?php endforeach; ?>
</div>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<input type="hidden" id="chart-data" value='<?php echo json_encode($chartsData); ?>' />
    
<script type="text/javascript">
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawCharts);

function drawCharts() {
    try {
        var data = JSON.parse($('#chart-data').val());
        $('#chart-data').remove();
                
                console.log(data);
                
        $('.chart').width((parseInt($('#page').width()) / Object.keys(data).length) - 10);
        
        // responsive
        /*$(document.body).resize(function() {            
            $('.chart').width(parseInt($('#page').width()) / Object.keys(data).length);
        });*/
        
        for(var name in data) {
            var title = name.replace(' ',''),
                chart = new google.visualization.PieChart(document.getElementById('chart' + title)),
                chartData = [['Item', 'Value']];

            for(var item in data[name]) {
                chartData.push([item, parseInt(data[name][item])]);
            }

            chart.draw(google.visualization.arrayToDataTable(chartData), {title: name, titleTextStyle: {fontSize: '15'}, is3D: true});
        }            
    } catch(ex) {
        console.log('Chart data exception: ', ex);
    }
  }
</script>