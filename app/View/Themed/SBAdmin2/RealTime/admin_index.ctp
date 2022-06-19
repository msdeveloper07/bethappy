<div class="breadcrumb span12"><b><?=__('Timer Clock:');?> </b><span id="timer"></span></div>
<?php echo $this->element('flash_message'); ?>

<div class="sortable container-fluid">
    
    <!-- Table for real time last 10 alerts per 10 minutes -->
    <div class="box span6" style="margin-left:1px">
        <div class="box-header well">
            <h2><i class="icon-list-alt"></i> <?=__('Last Alerts');?></h2>
            <div class="box-icon">
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-down"></i></a>
                <a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
            </div>
        </div>
        <div class="box-content" style="display:none; overflow:auto">
            <div class="lastalerts"></div>
        </div>
    </div>

    <!-- Table for real time last 10 withdraws per 10 minutes -->
    <div class="box span6" style="margin-left:1px">
        <div class="box-header well">
            <h2><i class="icon-list-alt"></i> <?=__('Last Withdraws');?></h2>
            <div class="box-icon">
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-down"></i></a>
                <a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
            </div>
        </div>
        <div class="box-content" style="display:none; overflow:auto">
            <div class="lastwithdraws"></div>
        </div>
    </div>

    <!-- Table for real time last 10 deposits per 10 minutes -->
    <div class="box span6" style="margin-left:1px">
        <div class="box-header well">
            <h2><i class="icon-list-alt"></i> <?=__('Last Deposits');?></h2>
            <div class="box-icon">
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-down"></i></a>
                <a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
            </div>
        </div>
        <div class="box-content" style="display:none; overflow:auto">
            <div class="lastdeposits"></div>
        </div>
    </div>

    <!-- Table for real time last 10 KYC per 10 minutes -->
    <div class="box span6" style="margin-left:1px">
        <div class="box-header well">
            <h2><i class="icon-list-alt"></i> <?=__('Recent KYC');?></h2>
            <div class="box-icon">
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                <a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
            </div>
        </div>
        <div class="box-content" style="display:none; overflow:auto">
            <div class="lastkyc"></div>
        </div>
    </div>

    <!-- Table for real time active users per 10 minutes -->
    <div class="box span6" style="margin-left:1px">
        <div class="box-header well">
            <h2><i class="icon-list-alt"></i> <?=__('# of Active Users');?></h2>
            <div class="box-icon">
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-down"></i></a>
                <a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
            </div>
        </div>
        <div class="box-content" style="display:none; overflow:auto">
            <div class="lastactive"></div>
        </div>
    </div>

    <!-- Graph for real time server load -->
    <div class="box span12" style="overflow:auto;margin-left: 0px;">
        <div class="box-header well">
            <h2><i class="icon-list-alt"></i> <?=__('Server Load');?></h2>
            <div class="box-icon">
                <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                <a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
            </div>
        </div>
        <div class="box-content">
            <div id="realtimechart" style="height:190px;"></div>
            <p class="clearfix"><?=__('You can update a chart periodically to get a real-time effect by using a timer to insert the new data in the plot and redraw it.');?></p>
            <p><?=__('Time between updates:');?> <input id="updateInterval" type="text" value="" style="text-align: right; width:5em"> <?=__('milliseconds');?></p>
        </div>
    </div>
</div>

<script>
$( document ).ready(function() {
    $(".msg").hide(); 
        //timer on top of page index - local time
        setInterval(function(){myTimer()},10);
       
        function myTimer() {
            var d = new Date();
            document.getElementById("timer").innerHTML = d.toLocaleTimeString();
        }
        
        //last 10 deposits
        function RealTimeDeposits() {
            $.ajax({
                url: "/admin/deposits/real_time_ajax/",
                success: function(data){ $(".lastdeposits").html(data); }
            });
            setTimeout(RealTimeDeposits, 600000);
        }
        RealTimeDeposits();
        
        //last 10 withdraws
        function RealTimeWithdraws() {
            $.ajax({
                url: "/admin/withdraws/real_time_ajax/",
                success: function(data){ $(".lastwithdraws").html(data); }
            });
            setTimeout(RealTimeWithdraws, 600000);
        }
        RealTimeWithdraws();
        
        //last 10 withdraws
        function RealTimeKYC() {
            $.ajax({
                url: "/admin/KYC/real_time_ajax/",
                success: function(data){ $(".lastkyc").html(data); }
            });
            setTimeout(RealTimeKYC, 600000);
        }
        RealTimeKYC(); 
        
        //last 10 alerts
        function RealTimeAlerts() {
            $.ajax({
                url: "/admin/Alert/real_time_ajax/",
                success: function(data){ $(".lastalerts").html(data); }
            });
            setTimeout(RealTimeAlerts, 600000);
        }
        RealTimeAlerts(); 
        
        //active users
        function RealTimeActiveUsers() {
            $.ajax({
                url: "/admin/users/real_time_ajax/",
                success: function(data){ $(".lastactive").html(data); }
            });
            setTimeout(RealTimeActiveUsers, 600000);
        }
        RealTimeActiveUsers();
});
</script>
