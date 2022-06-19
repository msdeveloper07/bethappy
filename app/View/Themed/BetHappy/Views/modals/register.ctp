<?php if (!$this->Session->check('Auth.User')): ?>
    <!--    <div class="modal-dialog register" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="panel-heading modal-heading clearfix">
                        <div class="panel-title pull-left clearfix"><h2 class="modal-title text-uppercase"><?= __('Register'); ?></h2></div>
                        <div class="pull-right clearfix">
                            <button type="button" class="close" aria-label="Close" ng-click="$root.cancel()"><span aria-hidden="true">&times;</span></button>
                        </div>
                    </div> -->

    <div class="modal-dialog modal-xl w-100 register" role="document">
        <div class="modal-content bg-primary">
            <div class="modal-header text-white">
                <h5 class="modal-title text-uppercase"><?= __('Register'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="$root.cancel()">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body">

                <ng-include src="'/Views/view/register'"></ng-include>


            </div>
        </div>
    </div>
    </div>
<?php else: ?>
    <div class="modal-dialog register" role="document">
        <div class="modal-content">
            <div class="modal-body text-center"><?= __('You are already logged in'); ?></div>
        </div>
    </div>

<?php endif; ?>