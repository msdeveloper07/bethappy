<?php
    if ($this->Session->check('Auth.User')) {
        $loggedin = true;
    } else {
        $loggedin = false;
    }
?>

<!-- Change email Modal from Account -->
<div class="modal-dialog changeEmail" role="document">
    <div class="modal-content">
        <div class="modal-body">
            <div class="panel-heading modal-heading clearfix">
                <div class="panel-title pull-left clearfix"><h2 class="modal-title"><?= __('Change your e-mail address'); ?></h2></div>
                <div class="pull-right clearfix"><button type="button" class="close" aria-label="Close" ng-click="$root.cancel()"><span aria-hidden="true">&times;</span></button></div>
            </div>

            <div class="panel-modal-content">
                <div class="container">
                    <div class="row">
                        <div class="col-12" ng-controller="PassRecoveryController">
                            <ng-include src="'/Views/view/passrecovery'" onload="loggedin = '<?= $loggedin; ?>'; changeemail = 1"></ng-include>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change email Modal After Registration -->
<!--<div class="modal fade" id="newRegisteredEmail" tabindex="-1" role="dialog" aria-labelledby="newRegisteredEmail" aria-hidden="true">
    <div class="modal-dialog changeEmail" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="panel-heading modal-heading clearfix">
                    <div class="panel-title pull-left clearfix"><h2 class="modal-title"><?= __('Change your e-mail address'); ?></h2></div>
                    <div class="pull-right clearfix"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                </div>

                <div class="panel-modal-content">
                    <div class="container">
                        <div class="row">
                            <div ng-controller="PassRecoveryController">
                                <ng-include src="'/Views/view/passrecovery'" onload="loggedin = '<?= $loggedin; ?>'; changeemail = 1; needpass = 1"></ng-include>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>-->