<style type="text/css">
    .chart-title {
        position: absolute;
        font-weight: bold;
    }
    
</style>

<?php
    $placeholderClass = isset($placeholderClass) ? $placeholderClass : null;
    $chartsData = isset($chartsData) ? $chartsData : array();
    $chartsCount = count($chartsData);
    foreach($chartsData as $key=>$ticketscount):
        $data.="[\"TicketID:(".$key.")\", ".(($ticketscount/$maxbetparts)*100)."], ";
    endforeach;
    $data = substr($data, 0, -2); 						
?>
    <span class="chart-title">Fraud Check</span>
    <div class="<?php echo $placeholderClass; ?>" style="width:700px;">
        <div id="chartbars" class="chart" style="width:700px;height: 400px;"></div>
    </div>
    <br><br>
    <?php
        foreach($chartsData as $key=>$ticketscount):
            echo '<a href="'.$this->HTML->url(array(controller=>'tickets',action=>'view',$key)).'">Ticket ID:'.$key.'=>'.(($ticketscount/$maxbetparts)*100).'%</a><br>';
        endforeach;
    ?>


<script type="text/javascript">
$(function() {
    var data = [ <?php echo $data;?> ];

    $.plot("#chartbars", [ data ], {
            series: {
                    bars: {
                        show: true,
                        barWidth: 0.6,
                        align: "center"
                    }
            },
            xaxis: {
                mode: "categories",
                tickLength: 0
            }
    });

    // Add the Flot version string to the footer


});
</script>