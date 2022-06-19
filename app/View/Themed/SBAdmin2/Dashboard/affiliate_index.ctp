<style>
    .control-group,
    .control-label,
    .controls {
        display: inline-block;
        vertical-align: middle;
        margin-right: 5px;
    }

    .submit {
        display: inline-block;
        vertical-align: top;
        margin-right: 5px;
    }

    .control-label {
        margin-bottom: 10px;
    }
</style>
<h1 class="h3 mb-2 text-gray-800">Dashboard</h1>

<div class="row">

    <div class="col-xl-3 col-md-6">
        <div class="card border-left-danger shadow mb-3 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs text-uppercase mb-1">Bets (Monthly)</div>
                        <div class="text-md font-weight-bold text-primary text-uppercase mb-1"></div>
                        <?php foreach ($data as $key => $value): ?>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $value['CurrencyCode']; ?><?= number_format($value['RealTotals']['real_bets'] + $value['BonusTotals']['bonus_bets'], 2, '.', ','); ?></div>
                        <?php endforeach; ?>
                    </div>
                    <div class="col-auto">
                        <!--<i class="fas fa-chart-line fa-2x text-gray-300"></i>-->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-left-success shadow mb-3 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs text-uppercase mb-1">Wins (Monthly)</div>
                        <div class="text-md font-weight-bold text-success text-uppercase mb-1"></div>
                        <?php foreach ($data as $key => $value): ?>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $value['CurrencyCode']; ?><?= number_format($value['RealTotals']['real_wins'] + $value['BonusTotals']['bonus_wins'], 2, '.', ','); ?></div>
                        <?php endforeach; ?>
                    </div>
                    <div class="col-auto">
                        <!--<i class="fas fa-euro-sign fa-2x text-gray-300"></i>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-left-info shadow mb-3 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs text-uppercase mb-1">Refunds (Monthly)</div>
                        <div class="text-md font-weight-bold text-info text-uppercase mb-1"></div>
                        <?php foreach ($data as $key => $value): ?>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $value['CurrencyCode']; ?><?= number_format($value['RealTotals']['real_refunds'] + $value['BonusTotals']['bonus_refunds'], 2, '.', ','); ?></div>
                        <?php endforeach; ?>
                    </div>
                    <div class="col-auto">
                        <!--<i class="fas fa-euro-sign fa-2x text-gray-300"></i>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-left-warning shadow mb-3 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs text-uppercase mb-1">Rollbacks (Monthly)</div>
                        <div class="text-md font-weight-bold text-warning text-uppercase mb-1"></div>
                        <?php foreach ($data as $key => $value): ?>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $value['CurrencyCode']; ?><?= number_format($value['RealTotals']['real_rollbacks'] + $value['BonusTotals']['bonus_rollbacks'], 2, '.', ','); ?></div>
                        <?php endforeach; ?>
                    </div>
                    <div class="col-auto">
                        <!--<i class="fas fa-euro-sign fa-2x text-gray-300"></i>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-6">
        <div class="card border-left-default shadow mb-3 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs text-uppercase mb-1">GGR (Monthly)</div>
                        <div class="text-md font-weight-bold text-secondary text-uppercase mb-1"></div>
                         <?php foreach ($data as $key => $value): ?>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $value['CurrencyCode']; ?><?= number_format($value['RealGGR']+ $value['BonusGGR'], 2, '.', ','); ?></div>
                        <?php endforeach; ?>
                    </div>
                    <div class="col-auto">
                        <!--<i class="fas fa-euro-sign fa-2x text-gray-300"></i>-->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-md-6">
        <div class="card border-left-primary shadow mb-3 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs text-uppercase mb-1">Deposits (Monthly)</div>
                        <div class="text-md font-weight-bold text-danger text-uppercase mb-1"></div>
                        <?php foreach ($data as $key => $value): ?>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $value['CurrencyCode']; ?><?= number_format($value['DepositsTotals'], 2, '.', ','); ?></div>
                        <?php endforeach; ?>
                    </div>
                    <div class="col-auto">
                        <!--<i class="fas fa-euro-sign fa-2x text-gray-300"></i>-->
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>




