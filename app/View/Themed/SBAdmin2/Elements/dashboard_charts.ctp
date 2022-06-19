<div class="box span4">
    <div class="box-header well">
        <h2><i class="icon-th"></i> Profit Chart without Bonus</h2>
        <div class="box-icon">
            <!--<a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>-->
            <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
            <!--<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>-->
        </div>
    </div>
    <div class="box-content">
        <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a data-toggle="tab" href="#profit_day">Profit by 14 Days</a></li>
            <li class="tab-pane"><a data-toggle="tab" href="#profit_month">Monthly Profit</a></li>
            <li class="tab-pane"><a data-toggle="tab" href="#profit_year">Profit by Year</a></li>
        </ul>
        <div id="myTabContent" class="tab-content" style="padding: 20px;">
            <div id="profit_day" class="tab-pane fade in active">
                <div id="daysprofitchart14" style="height:190px; width:92.931738%"></div>
            </div>
            <div id="profit_month" class="tab-pane fade">
                <div id="daysprofitchart30" style="height:190px; width:92.931738%"></div>
            </div>
            <div id="profit_year" class="tab-pane fade">
                <div id="daysprofitchart365" style="height:190px; width:92.931738%"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal hide fade" id="myModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>Settings</h3>
    </div>
    <div class="modal-body">
        <p>Here settings can be configured...</p>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Close</a>
        <a href="#" class="btn btn-primary">Save changes</a>
    </div>
</div>