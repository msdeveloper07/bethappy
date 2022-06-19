
<?php if ($this->Session->check('Auth.User')): ?>
    <div class="modal-dialog account" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <!--                <div class="panel-heading modal-heading pl-0 pr-0 clearfix">
                                    <div class="d-flex align-items-baseline justify-content-between modal-head">
                                        <div class="col-md-3 modal-col">
                                            <img class="modal-logo inline" src="/Layout/Monaco/images/MacaoSpin-logo-sm.png" alt="<?= Configure::read('Settings.defaultTitle'); ?>"> 
                                            <h2 class="modal-title inline"><?= Configure::read('Settings.defaultTitle'); ?></h2>
                                        </div>
                                        <div class="col-md-2 modal-col"><h2 class="modal-title"><?= __('My Account'); ?></h2></div>
                                        <div class="col-md-5 text-center p-0 modal-col">
                                            <ul class="list-inline modal-list-inline">
                                                <li class="list-inline-item"><?= __('Welcome'); ?>: <span class="text-blue"><?= CakeSession::read('Auth.User.username'); ?></span></li>
                                                <li class="list-inline-item"><?= __('Balance'); ?>: <span class="text-blue">{{userbalance}}<?= Configure::read('Settings.currency'); ?></span></li>
                                            </ul>
                                        </div>
                                        <div class="col-md-2 modal-col">
                                            <button type="button" class="close" aria-label="Close" ng-click="$root.cancel()"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                    </div>
                                </div>-->

                <div class="panel-heading modal-heading account-heading">
                    <div class="panel-title">
                        <h2 class="modal-title text-uppercase"><?= __('My Account'); ?></h2>
                    </div>
                    <div class="panel-user-details">
                        <div style="margin-right: 20px;"><?= __('Welcome'); ?>: <span class="text-orange"><?= CakeSession::read('Auth.User.username'); ?></span></div>
                        <div style="margin-right: 20px;"><?= __('Balance'); ?>: <span class="text-orange">{{userbalance}}<?= Configure::read('Settings.currency'); ?></span></div>
                        <div ng-if="userbonusbalance"><?= __('Bonus Balance'); ?>: <span class="text-orange">{{userbonusbalance}}<?= Configure::read('Settings.currency'); ?></span></div>

                    </div>
                    <div class="panel-close">
                        <button type="button" class="close" aria-label="Close" ng-click="$root.cancel()"><span aria-hidden="true">&times;</span></button>
                    </div>
                </div>
                <div class="panel-modal-content">
                    <ng-include src="'/Views/view/accounting'" ng-init="tab = dataItem"></ng-include>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="modal-dialog account" role="document">
        <div class="modal-content">
            <div class="modal-body text-center"><?= __('Please login first'); ?></div>
        </div>
    </div>
<?php endif; ?>
