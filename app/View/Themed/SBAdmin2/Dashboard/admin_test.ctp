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
<?php
if (!$model)
    $model = 'Report';
?>
<h1 class="h3 mb-2 text-gray-800">Dashboard</h1>
<div class="row">

    <div class="col-xl-12 col-md-12 mb-4">
        <div id="carouselToday" class="carousel slide bg-default rounded text-white carousel-dash-default" data-ride="carousel" data-interval="false">
            <ol class="carousel-indicators">
                <li data-target="#carouselToday" data-slide-to="0" class="active"></li>
                <!--<li data-target="#carouselToday" data-slide-to="1" class=""></li>-->
            </ol>
            <div class="carousel-inner pb-3" role="listbox">
                <div class="carousel-item text-center p-4 active">
                    <h6 class="font-weight-bold text-white">Deposits</h6>
                    <div class="row no-gutters">

                        <div class="col-md-3">
                            <div class="text-xs mb-1"><span class=" text-uppercase">Today</span></div>
                            <?php
                            if (!empty($today_deposits)):
                                foreach ($today_deposits as $key => $value):
                                    ?> 
                                    <div class="h5 mb-0 font-weight-bold"><?= $value['Currency']['currency_code'] . $value[0]['amount']; ?></div>
                                    <?php
                                endforeach;
                            else:
                                ?>
                                <?= __('No deposits today.'); ?>
                            <?php endif; ?>
                            <hr width="90%">
                            <!--<div class="col-10 offset-1 small d-none d-md-block">Today's cumulative deposits by currency.</div>-->
                        </div>
                        <div class="col-md-3">
                            <div class="text-xs mb-1"><span class=" text-uppercase">Yesterday</span></div>
                            <?php
                            if (!empty($yesterday_deposits)):
                                foreach ($yesterday_deposits as $key => $value):
                                    ?> 
                                    <div class="h5 mb-0 font-weight-bold"><?= $value['Currency']['currency_code'] . $value[0]['amount']; ?></div>
                                    <?php
                                endforeach;
                            else:
                                ?>
                                <?= __('No deposits yestarday.'); ?>
                            <?php endif; ?>
                            <hr width="90%">
                            <!--<div class="col-10 offset-1 small d-none d-md-block"></div>-->
                        </div>
                        <div class="col-md-3">
                            <div class="text-xs mb-1"><span class=" text-uppercase">This week</span></div>
                            <?php
                            if (!empty($weekly_deposits)):
                                foreach ($weekly_deposits as $key => $value) :
                                    ?> 
                                    <div class="h5 mb-0 font-weight-bold"><?= $value['Currency']['currency_code'] . $value[0]['amount']; ?></div>
                                    <?php
                                endforeach;
                            else:
                                ?>
                                <?= __('No deposits this week.'); ?>
                            <?php endif; ?>
                            <hr>
                            <!--<div class="col-10 offset-1 small d-none d-md-block"></div>-->
                        </div>

                        <div class="col-md-3">
                            <div class="text-xs mb-1"><span class=" text-uppercase">This month</span></div>
                            <?php
                            if (!empty($monthly_deposits)):
                                foreach ($monthly_deposits as $key => $value):
                                    ?> 
                                    <div class="h5 mb-0 font-weight-bold"><?= $value['Currency']['currency_code'] . $value[0]['amount']; ?></div>
                                    <?php
                                endforeach;
                            else:
                                ?>
                                <?= __('No deposits this month.'); ?>
                            <?php endif; ?>
                            <hr>
                            <!--<div class="col-10 offset-1 small d-none d-md-block"></div>-->
                        </div>
                    </div>
                </div>
                <!--                <div class="carousel-item text-center p-4">
                                    <div class="row no-gutters">
                                        <div class="col-md-4">
                                            <div class="text-xs mb-1"><span class=" text-uppercase">Bets</span> (today)</div>
                                            <div class="h5 mb-0 font-weight-bold">€40,000.12</div>
                                            <hr width="90%">
                                            <div class="col-10 offset-1 small d-none d-md-block">Cumulative bets across all providers and currencies converted to euros.</div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-xs mb-1"><span class=" text-uppercase">Wins</span> (today)</div>
                                            <div class="h5 mb-0 font-weight-bold">€40,000.12</div>
                                            <hr width="90%">
                                            <div class="col-10 offset-1 small d-none d-md-block">Cumulative wins across all providers and currencies converted to euros.</div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-xs mb-1"><span class=" text-uppercase">GGR</span> (today)</div>
                                            <div class="h5 mb-0 font-weight-bold">€40,000.12</div>
                                            <hr>
                                            <div class="col-10 offset-1 small d-none d-md-block">Cumulative GGR across all providers and currencies converted to euros.</div>
                                        </div>
                                    </div>
                                </div>-->
            </div>

            <a class="carousel-control-prev" href="#carouselToday" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselToday" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</div>

<!--MOST PLAYED GAMES START-->
<div class="row">
    <div class="col-md-12">
        <div class="card shadow mb-4">
            <div class="card-header bg-default py-3">
                <h6 class="m-0 font-weight-bold text-white"><?= __('Most played games'); ?></h6>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="false"><?= __('All'); ?></a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="video-slots-tab" data-toggle="tab" href="#video-slots" role="tab" aria-controls="video-slots" aria-selected="true"><?= __('Video Slots'); ?></a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="table-games-tab" data-toggle="tab" href="#table-games" role="tab" aria-controls="profile" aria-selected="false"><?= __('Table Games'); ?></a>
                    </li>

                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                        <div class="list-group">
                            <?php foreach ($most_played_games as $key => $value) { ?>
                                <div class="list-group-item text-ellipsis d-flex justify-content-between align-items-center">
                                    <span class="w-50 text-ellipsis"><img src="<?= $value['IntGames']['image']; ?>" width="80"> <?= $value['IntGames']['name']; ?></span>
                                    <span class=""><?= $value['IntBrands']['name']; ?></span>
                                    <span class=""><?= $value['IntCategories']['name']; ?></span>
                                    <span class="badge badge-danger"><?= __('%s times', $value[0]['times_played']); ?></span>
                                </div> 
                            <?php } ?>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="video-slots" role="tabpanel" aria-labelledby="video-slots-tab">TO DO</div>
                    <div class="tab-pane fade" id="table-games" role="tabpanel" aria-labelledby="table-games-tab">TO DO</div>

                </div>

                <hr>
                <small><?= __('Most played games.'); ?></small>
            </div>
        </div>
    </div>
</div>
<!--MOST PLAYED BY PROVIDER GAMES END-->

<div class="row">

    <!--PLAYERS ORIGINS START-->
    <div class="col-lg-6 col-md-12">
        <div class="card shadow mb-4">
            <div class="card-header bg-default py-3">
                <h6 class="m-0 font-weight-bold text-white"><?= __('Players origins'); ?></h6>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <?php foreach ($players_origins['players_origin'] as $key => $value) { ?>
                        <div class="list-group-item text-ellipsis d-flex justify-content-between align-items-center">
                            <span><img src="https://flagcdn.com/<?= strtolower($value['Country']['alpha2_code']); ?>.svg" width="30"> <?= $value['Country']['country_name']; ?> (<?= $value[0]['player_count']; ?>)</span>  <span class="badge badge-danger"><?= round(($value[0]['player_count'] / $players_origins['total_players']) * 100, 2); ?>%</span>
                        </div> 
                    <?php } ?>
                </div>
                <hr>
                <small><?= __('Players origins by country.'); ?>.</small>
            </div>
        </div>
    </div>
    <!--PLAYERS ORIGINS END-->

    <!--PLAYERS KYC START-->
    <div class="col-lg-6 col-md-12">
        <div class="card shadow mb-4">
            <div class="card-header bg-default py-3">
                <h6 class="m-0 font-weight-bold text-white"><?= __('KYC'); ?></h6>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <?php foreach ($players_KYC as $key => $value) { ?>
                        <div  class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between flex-wrap">
                                <h5 class="mb-1"><a href="admin/users/view/<?= $value['User']['id']; ?>"><?= $value['User']['username']; ?></a></h5>
                                <small><?= date('d-m-Y H:i:s', strtotime($value['KYC']['created'])); ?></small>
                            </div>
                            <p class="small mb-1"><?= __('%s uploaded KYC documents.', $value['User']['first_name'] . ' ' . $value['User']['last_name']); ?></p>
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <p class="small mb-1"><?= __('KYC document type'); ?>: 
                                    <?php
                                    switch ($value['KYC']['kyc_type']) {
                                        case 1:
                                            echo '<button class="btn btn-sm btn-outline-primary btn-table">' . __('IDENTIFICATION') . '</button>';
                                            break;

                                        case 2:
                                            echo '<button class="btn btn-sm btn-outline-info btn-table">' . __('ADDRESS') . '</button>';
                                            break;

                                        case 3:
                                            echo '<button class="btn btn-sm btn-outline-dark btn-table">' . __('FUNDING') . '</button>';
                                            break;
                                    }
                                    ?>
                                </p>
                                <a href="admin/KYC/view/<?= $value['KYC']['id']; ?>" class="btn btn-success btn-sm"><?= __('View'); ?></a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <hr>
                <small><?= __('Pending KYC documents.'); ?></small>
            </div>
        </div>
    </div>
    <!--PLAYERS KYC END-->








    <div class="col-xl-4 col-md-6">
        <div class="card border-left-primary shadow mb-3 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs text-uppercase mb-1">GGR (Monthly)</div>
                        <div class="text-md font-weight-bold text-primary text-uppercase mb-1">Platipus</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">€40,000.12</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="card border-left-success shadow mb-3 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs text-uppercase mb-1">GGR (Monthly)</div>
                        <div class="text-md font-weight-bold text-success text-uppercase mb-1">Booongo</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">€215,000.77</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="card border-left-info shadow mb-3 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs text-uppercase mb-1">GGR (Monthly)</div>
                        <div class="text-md font-weight-bold text-info text-uppercase mb-1">Microgaming</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">€25,000.54</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="card border-left-warning shadow mb-3 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs text-uppercase mb-1">GGR (Monthly)</div>
                        <div class="text-md font-weight-bold text-warning text-uppercase mb-1">Betsoft</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">€5,000.78</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="card border-left-danger shadow mb-3 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs text-uppercase mb-1">GGR (Monthly)</div>
                        <div class="text-md font-weight-bold text-danger text-uppercase mb-1">Habanero</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">€256,080.99</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="card border-left-default shadow mb-3 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs text-uppercase mb-1">GGR (Monthly)</div>
                        <div class="text-md font-weight-bold text-secondary text-uppercase mb-1">Vivo</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">€259,040.99</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-7 col-lg-7">
        Area Chart 
        <div class="card shadow mb-4">
            <div class="card-header bg-default py-3">
                <h6 class="m-0 font-weight-bold text-white">Deposits/Withdraws by currency</h6>
            </div>
            <div class="card-body">
                <ul class="nav nav-pills justify-content-end mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-deposits-ac-tab" data-toggle="pill" href="#pills-deposits-ac" role="tab" aria-controls="pills-deposits-ac" aria-selected="true">Deposits</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-withdraws-ac-tab" data-toggle="pill" href="#pills-withdraws-ac" role="tab" aria-controls="pills-withdraws-ac" aria-selected="false">Withdraws</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-deposits-ac" role="tabpanel" aria-labelledby="pills-deposits-tab">
                        <div class="chart-area"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                            <canvas id="myAreaChart" style="display: block; height: 320px; width: 374px;" width="561" height="480" class="chartjs-render-monitor"></canvas>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-withdraws-ac" role="tabpanel" aria-labelledby="pills-withdraws-tab">
                        Another chart
                    </div>
                </div>

                <hr>
                <small>Deposits/Withdraws by currency.</small>
            </div>
        </div>
    </div>
    Donut Chart 
    <div class="col-xl-5 col-lg-5">
        <div class="card shadow mb-4">
            Card Header - Dropdown 
            <div class="card-header bg-default py-3">
                <h6 class="m-0 font-weight-bold text-white">Deposits/Withdraws by provider</h6>
            </div>
            Card Body 
            <div class="card-body">

                <ul class="nav nav-pills justify-content-end mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-deposits-tab" data-toggle="pill" href="#pills-deposits" role="tab" aria-controls="pills-deposits" aria-selected="true">Deposits</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-withdraws-tab" data-toggle="pill" href="#pills-withdraws" role="tab" aria-controls="pills-withdraws" aria-selected="false">Withdraws</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-deposits" role="tabpanel" aria-labelledby="pills-deposits-tab">

                        <div class="chart-pie pt-4"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                            <canvas id="myPieChart" width="373" height="379" class="chartjs-render-monitor" style="display: block; height: 253px; width: 249px;"></canvas>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-withdraws" role="tabpanel" aria-labelledby="pills-withdraws-tab">
                        Another chart
                    </div>
                </div>
                <hr>
                <small>Deposits/Withdraws by provider.</small>
            </div>
        </div>
    </div>
</div>

<div class="row">


</div>
<div class="row">
    <div class="col-xl-12 col-lg-12">
        Area Chart 
        <div class="card shadow mb-4">
            <div class="card-header bg-default py-3">
                <h6 class="m-0 font-weight-bold text-white">Bonuses</h6>
            </div>
            <div class="card-body">
                Area chart
                <hr>
                <small>Bonuses area chart.</small>
            </div>
        </div>
    </div>
</div>



<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <?php //echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $singularName))));         ?>
        </div>
    </div>
    <div id="page" class="dashboard">
        <div>
            <?php //echo $this->element('statistics');        ?>
        </div>
        <div class="row-fluid sortable ui-sortable">
            <?php //echo $this->element('dashboard_charts');         ?>
        </div>
    </div>
</div>


<!--
<script src="https://blackrockdigital.github.io/startbootstrap-sb-admin-2/vendor/chart.js/Chart.min.js"></script>
<script src="https://blackrockdigital.github.io/startbootstrap-sb-admin-2/js/demo/chart-area-demo.js"></script>
<script src="https://blackrockdigital.github.io/startbootstrap-sb-admin-2/js/demo/chart-pie-demo.js"></script>
<script src="https://blackrockdigital.github.io/startbootstrap-sb-admin-2/js/demo/chart-bar-demo.js"></script>-->