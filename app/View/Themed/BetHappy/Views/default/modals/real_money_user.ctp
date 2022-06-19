<div class="modal-dialog nomoney" role="document">
    <div class="modal-content">
        <div class="modal-body">
            <div class="panel-heading modal-heading clearfix">
                <div class="panel-title pull-left clearfix"><h2 class="modal-title"><?= __('Real money player'); ?></h2></div>
                <div class="pull-right clearfix">
                    <button type="button" class="close" aria-label="Close" ng-click="$root.cancel()"><span aria-hidden="true">&times;</span></button>
                </div>
            </div>
            <div class="panel-modal-content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-left p-0">
                            <p class="p-modal"><?= __('%s, you have no money in your balance', CakeSession::read('Auth.User.username')) . '!'; ?></p>
                            <p class="p-modal"><?= __('Go to the CASHIER to make a deposit'); ?> .</p>
                            <p class="p-modal"><?= __('Enjoy Your Luck'); ?>,<br> <?= __('WinnerMillion Casino'); ?></p>
                        </div>
                    </div>
                    <div class="col-md-12 text-right">
                        <a class="btn btn-main text-brown font-weight-bold" ng-click="$root.switchAdvanced($event, 'account', $root.controllers.Header, 'deposits')"><?=__('OK');?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>