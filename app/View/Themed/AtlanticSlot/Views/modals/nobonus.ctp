<div class="modal-dialog nobonus" role="document">
    <div class="modal-content bg-primary">
        <div class="modal-header text-white">
            <h5 class="modal-title text-uppercase"><?= __('Remove bonus balance'); ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="$root.cancel()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body text-white">

            <div class="row">
                <div class="col-md-12">
                    <p class="p-modal"><?= __("Your bonus balance is low. Do you want to continue playing with your real money?"); ?></p>
                    <p ng-if="showload"><img src="/Layout/images/loader.gif"/></p>
<!--                    <p class="p-modal" style="color: #D0011B">{{error}}</p>
                    <p class="p-modal" style="color: #fdf4a5">{{msg}}</p>-->
                </div>
                <div class="col-md-12">
                    <a class="btn btn-danger" ng-click="releaseBonus()"><?= __('Yes, remove my bonus balance'); ?></a>
                </div>
            </div>

        </div>

    </div>
</div>
<!--
<div class="modal-dialog nomoney" role="document" style="background:#F5F5F5">
    <div class="modal-content">
        <div class="modal-body">
            <div class="panel-heading modal-heading clearfix">
                <div class="panel-title pull-left clearfix"><h2 class="modal-title"><?= __('Bonus'); ?></h2></div>
                <div class="pull-right clearfix">
                    <button type="button" class="close" aria-label="Close" ng-click="$root.cancel()"><span aria-hidden="true">&times;</span></button>
                </div>
            </div>
            <div class="panel-modal-content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-left p-0">
                            <p class="p-modal"><?= __("Your bonus balance is low. Do you want to continue playing with your real money?"); ?></p>
                            <p ng-if="showload"><img src="/Layout/images/loader.gif"/></p>
                            <p class="p-modal" style="color: #D0011B">{{error}}</p>
                            <p class="p-modal" style="color: #fdf4a5">{{msg}}</p>
                        </div>
                    </div>
                    <div class="col-md-12 text-right">
                        <a class="btn btn-main text-brown font-weight-bold" ng-click="releaseBonus()"><?= __('Yes remove my bonus balance'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>-->