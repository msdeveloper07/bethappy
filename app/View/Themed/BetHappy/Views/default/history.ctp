<main class="main">
    <div class="container">

        <div class="row">
            <div class="col-md-12"><h1 class="title mb-5 ng-binding"><?= __('History'); ?></h1></div>


            <div class="col-md-12">
                <uib-tabset active="activeJustified" justified="true">
                    <uib-tab index="0" heading="<?= __('Payments'); ?>" class="account-nav-item">

                        <div class="row mt-5">
                            <div class="col-md-12">
                                <div class="card history-card mb-3" ng-if="payments.length > 0">
                                    <div class="card-body">
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <h4><?= __('Deposits chart'); ?></h4>
                                                <canvas id="deposits-doughnut" class="chart chart-doughnut" chart-options="doughnut_chart_options"
                                                        chart-colors="paymentChartColors" chart-data="deposits_data" chart-labels="deposits_labels">
                                                </canvas> 
                                            </div>
                                            <div class="col-md-6">
                                                <h4><?= __('Withdraws chart'); ?></h4>
                                                <canvas id="withdraws-doughnut" class="chart chart-doughnut" chart-options="doughnut_chart_options"
                                                        chart-colors="paymentChartColors" chart-data="withdraws_data" chart-labels="withdraws_labels">
                                                </canvas> 
                                            </div>
                                        </div>

                                        <ag-grid-angular ag-grid="paymentsGridOptions" class="ag-theme-balham-dark" style="width: 100%; height: 500px"></ag-grid-angular>

                                    </div>

                                </div>

                                <div ng-if="payments.length <= 0">
                                    <?= __('No data to show.'); ?>
                                </div>
                            </div>
                        </div>
                    </uib-tab>

                    <uib-tab index="1" heading="Real bets/wins">

                        <div class="row mt-5">
                            <div class="col-md-12">
                                <div class="card history-card mb-3" ng-if="real_gameplay.length > 0">
                                    <div class="card-body">
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <h4><?= __('Real bets/wins chart'); ?></h4>
                                                <canvas id="real-doughnut" class="chart chart-doughnut" chart-options="doughnut_chart_options"
                                                        chart-colors="gameplayChartColors" chart-data="real_data" chart-labels="real_labels">
                                                </canvas> 
                                            </div>
                                        </div>

                                        <ag-grid-angular ag-grid="realGridOptions" class="ag-theme-balham-dark" style="width: 100%; height: 500px"></ag-grid-angular>
                                    </div>
                                </div>

                                <div ng-if="real_gameplay.length <= 0">
                                    <?= __('No data to show.'); ?>
                                </div>
                            </div>
                        </div>


                    </uib-tab>
                    <uib-tab index="2" heading="Bonus bets/wins">

                        <div class="row mt-5">
                            <div class="col-md-12">
                                <div class="card history-card mb-3" ng-if="bonus_gameplay.length > 0">
                                    <div class="card-body">
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <h4><?= __('Bonus bets/wins chart'); ?></h4>
                                                <canvas id="bonus-doughnut" class="chart chart-doughnut" chart-options="doughnut_chart_options"
                                                        chart-colors="gameplayChartColors" chart-data="bonus_data" chart-labels="bonus_labels">
                                                </canvas> 
                                            </div>
                                        </div>

                                        <ag-grid-angular ag-grid="bonusGridOptions" class="ag-theme-balham-dark" style="width: 100%; height: 500px"></ag-grid-angular>

                                    </div>

                                </div>
                            </div>
                        </div>

                    </uib-tab>

                    <uib-tab index="3" heading="<?= __('Game Logs'); ?>">
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <div class="card history-card mb-3"  ng-if="games.length > 0">
                                    <div class="card-body">
                                        <div class="row mb-4">
                                            <div class="col-md-12">
                                                <h4><?= __('Most played games chart'); ?></h4>
                                                <canvas id="games-bar" class="chart chart-bar" chart-options="bar_chart_options"
                                                        chart-data="games_data" chart-labels="games_labels">
                                                </canvas>
                                            </div>
                                        </div>

                                        <ag-grid-angular ag-grid="gamesGridOptions" class="ag-theme-balham-dark" style="width: 100%; height: 1020px"></ag-grid-angular>
                                    </div>
                                </div>
                                <div ng-if="games.length <= 0">
                                    <?= __('No data to show.'); ?>
                                </div>
                            </div>
                        </div>

                    </uib-tab>
                </uib-tabset>

            </div>
        </div>
    </div>
</main>
