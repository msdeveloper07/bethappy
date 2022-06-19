<div class="container-fluid conatiner-custom-padding">
    <div class="row">
        <div class="col-sm-4 col-md-3">
            <?= $this->element('casino_sidebar'); ?>
        </div>
        <div class="col-sm-8 col-md-9">
            <div class="row">
                <div class="col-md-12 p-0">
                    <!--<h1 class="mb-4">Withdraws</h1>-->
                    <div class="row">
                        <div class="col-md-12 col-lg-12">

                            <div class="card card-gradient mb-4">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><i class="icon ion-ios-swap mr-2"></i><?= __('Make a withdraw'); ?></span>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <iframe src="{{withdrawsURL}}" class="iframe-default" id="withdraw-iframe"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="card card-gradient">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><i class="icon ion-ios-swap mr-2"></i><?= __('Withdraws history'); ?></span>

                                    </div>
                                </div>
                                <div class="card-body">

                                    <div class="row mb-4">
                                        <div class="col-sm-6 col-md-6 col-lg-4">
                                            <div class="card card-outline px-4 py-2">
                                                <span><?= __('Pending'); ?> ({{Percentages.pending_percentage}}%)</span>
                                                <h4>
                                                    <span ng-bind-html="SumsByStatus.pending_sum | currencyFilter:User.Currency.code"></span>
                                                    ({{CountsByStatus.pending_count}})</h4>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-lg-4">
                                            <div class="card card-outline px-4 py-2">
                                                <span><?= __('Completed'); ?> ({{Percentages.completed_percentage}}%)</span>
                                                <h4><span ng-bind-html="SumsByStatus.completed_sum | currencyFilter:User.Currency.code"></span> ({{CountsByStatus.completed_count}})</h4>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-lg-4">
                                            <div class="card card-outline px-4 py-2">
                                                <span><?= __('Declined'); ?> ({{Percentages.declined_percentage}}%)</span>
                                                <h4><span ng-bind-html="SumsByStatus.declined_sum | currencyFilter:User.Currency.code"></span> ({{CountsByStatus.declined_count}})</h4>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-lg-4">
                                            <div class="card card-outline px-4 py-2">
                                                <span><?= __('Failed'); ?> ({{Percentages.failed_percentage}}%)</span>
                                                <h4><span ng-bind-html="SumsByStatus.failed_sum | currencyFilter:User.Currency.code"></span> ({{CountsByStatus.failed_count}})</h4>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-lg-4">
                                            <div class="card card-outline px-4 py-2">
                                                <span><?= __('Cancelled'); ?> ({{Percentages.cancelled_percentage}}%)</span>
                                                <h4><span ng-bind-html="SumsByStatus.cancelled_sum | currencyFilter:User.Currency.code"></span> ({{CountsByStatus.cancelled_count}})</h4>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-lg-4">
                                            <div class="card card-outline px-4 py-2">
                                                <span><?= __('Total'); ?></span>
                                                <h4>({{TotalCount}})</h4>
                                            </div>
                                        </div>
                                    </div>

                                    <ul class="account-list" >
                                        <li ng-repeat="transaction in Withdraws" ng-if="Withdraws.length > 0">
                                            <div>
                                                <h5>{{transaction.Payment.provider}}</h5>
                                                <h6><small>{{transaction.Payment.created}}</small></h6>
                                            </div>
                                            <div class="mx-4">
                                                <h5 class="small">
                                                    <span ng-bind-html="transaction.Payment.amount ? transaction.Payment.amount : '0.00' | currencyFilter:User.Currency.code"></span>
                                                </h5>
                                            </div>
                                            <small class="badge badge-pill font-weight-light py-2 px-4"  ng-class="setStatus(transaction.Payment.status)">{{transaction.Payment.status}}</small>
                                        </li>
                                        <li ng-if="Withdraws.length == 0"><?= __('No withdraws yet.'); ?></li>
                                    </ul>


                                </div>
                                <div class="card-footer">
                                    <ul uib-pagination total-items="paginateWithdraws.totalRecords" ng-model="paginateWithdraws.currentPage" ng-change="pageWithdrawsChanged()" items-per-page="paginateWithdraws.itemsPerPage" max-size="paginateWithdraws.maxSize" class="pagination-sm" boundary-links="true" rotate="true" force-ellipses="true" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;"></ul>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>