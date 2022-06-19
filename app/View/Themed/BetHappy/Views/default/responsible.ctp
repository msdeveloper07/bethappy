<div class="modal-tab-title mb-3 ng-scope"><h2><?= __('Responsible Gaming', true); ?></h2></div>
<div class="cent-txt txt-pad" ng-init="getseLimits()">
    <div class="section-wrapper">
        <h4 class="modal-tab-subtitle"><i class="fa fa-cog"></i> <?= __("Setup Deposit Limits on your Account"); ?></h4>

        <table class="default-table table table-borderless table-responsive text-dark" align="center" ng-if="dataLimits.deposit.length">
            <thead class="thead-default">
                <tr>
                    <th><?= __('Limits'); ?></th>
                    <th><?= __('Amount'); ?></th>
                    <th><?= __('Until'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="deposit in dataLimits.deposit">
                    <td>{{deposit.UsersLimits.limit_type}}</td>
                    <td>{{deposit.UsersLimits.amount}} <?= Configure::read('Settings.currency'); ?></td>
                    <td>{{formatDateLm(deposit.UsersLimits.until_date)}} <a href="javascript://" ng-if="!deposit.UsersLimits.until_date" ng-click="unsetselimits(deposit.UsersLimits.id)"><?= __('Cancel'); ?></a></td>
                </tr>
            </tbody>
        </table>
        <p class="font-weight-bold" ng-if="!dataLimits.deposit.length" class="mt-3"><?= __('No Limits assigned yet'); ?></p>

        <table class="default-table table borderless table-responsive text-dark" align="center">
            <thead class="thead-default">
                <tr>
                    <td><?= __('Add Limit', true); ?></td>
                    <td ><?= __('Amount:', true); ?></td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select class="custom-select form-control account-input" id="limitstype" ng-model="UserLimits.depositLimitPeriod">
                            <option ng-selected="true" value="" class="text-muted"><?= __('Select limit to add'); ?></option>
                            <option ng-repeat="(limitvalue,limittxt) in generalLimits" value="{{limitvalue}}">{{limittxt}}</option>
                        </select>
                    </td>
                    <td>
                        <input class="form-control account-input-outline" name="limitvalue" type="text" id="limitvalue" ng-model="UserLimits.depositLimitAmount"/>
                    </td>
                    <td>
                        <button class="btn btn-main text-uppercase font-weight-bold" ng-click="SaveDepositLimit()"><?= __('Add Limit', true); ?></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr class="account-hr my-4">

    <div class="section-wrapper">
        <h4 class="modal-tab-subtitle"><i class="fa fa-cog"></i> <?= __("Play Session Limit"); ?></h4>

        <p class="mt-3 font-italic"><?= __('Set the limit you can play for each session'); ?></span>

        <table class="default-table table table-borderless table-responsive text-dark" align="center" ng-if="dataLimits.sessionlimit.length">
            <thead class="thead-default">
                <tr>
                    <th><?= __('Limits'); ?></th>
                    <th><?= __('Amount'); ?></th>
                    <th><?= __('Until'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="sessionlimit in dataLimits.sessionlimit">
                    <td>{{sessionlimit.UsersLimits.limit_type}}</td>
                    <td>{{sessionlimit.UsersLimits.amount}} <?= __('min'); ?></td>
                    <td>{{formatDateLm(sessionlimit.UsersLimits.until_date)}} <a href="javascript://" ng-if="!sessionlimit.UsersLimits.until_date" ng-click="unsetselimits(sessionlimit.UsersLimits.id)"><?= __('Cancel'); ?></a></td>
                </tr>
            </tbody>
        </table>

        <p class="font-weight-bold" ng-if="!dataLimits.sessionlimit.length"><?= __('No Limits assigned yet'); ?></p>

        <table class="default-table table borderless table-responsive text-dark" align="center">
            <thead class="thead-default">
                <tr>
                    <td><?= __('Add Limit', true); ?></td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select class="custom-select form-control account-input" id="limitstype" ng-model="UserLimits.sessionLimitPeriod">
                            <option ng-selected="true" value="" class="text-muted"><?= __('Select limit to add'); ?></option>
                            <option ng-repeat="(i,login) in loginLimits" value="{{i}}">{{login}}</option>
                        </select>
                    </td>
                    <td> 
                        <button class="btn btn-main text-uppercase font-weight-bold" ng-click="SaveSessionLimit()"><?= __('Add Limit', true); ?></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr class="account-hr my-4">

    <div class="section-wrapper">
        <h4 class="modal-tab-subtitle"><i class="fa fa-cog"></i> <?= __("Wager Limit"); ?></h4>

        <table class="default-table table table-borderless table-responsive text-dark" align="center" ng-if="dataLimits.wager.length">
            <thead class="thead-default">
                <tr>
                    <th><?= __('Limits'); ?></th>
                    <th><?= __('Amount'); ?></th>
                    <th><?= __('Until'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="wager in dataLimits.wager">
                    <td>{{wager.UsersLimits.limit_type}}</td>
                    <td>{{wager.UsersLimits.amount}} <?= Configure::read('Settings.currency'); ?></td>
                    <td>{{formatDateLm(wager.UsersLimits.until_date)}} <a href="javascript://" ng-if="!wager.UsersLimits.until_date" ng-click="unsetselimits(wager.UsersLimits.id)"><?= __('Cancel'); ?></a></td>
                </tr>
            </tbody>
        </table>

        <p class="font-weight-bold" ng-if="!dataLimits.wager.length"><?= __('No Limits assigned yet'); ?></p>

        <table class="default-table table borderless table-responsive text-dark" align="center">   
            <thead class="thead-default">
                <tr>
                    <td><?= __('Add Limit', true); ?></td>
                    <td><?= __('Amount', true); ?></td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select class="custom-select form-control account-input" id="limitstype" ng-model="UserLimits.wagerLimitPeriod">
                            <option ng-selected="true" value="" class="text-muted"><?= __('Select limit to add'); ?></option>
                            <option ng-repeat="(limitvalue,limittxt) in generalLimits" value="{{limitvalue}}">{{limittxt}}</option>
                        </select>
                    </td>
                    <td>
                        <input class="form-control account-input-outline" name="limitvalue" type="text" id="limitvalue" ng-model="UserLimits.wagerLimitAmount"/>
                    </td>
                    <td>
                        <button class="btn btn-main text-uppercase font-weight-bold" ng-click="SaveWagerLimit()"><?= __('Add Limit', true); ?></button>
                    </td>
            <tbody>
        </table>
    </div>
    <hr class="account-hr my-4">

    <div class="section-wrapper">                                    
        <h4 class="modal-tab-subtitle"><i class="fa fa-cog"></i> <?= __("Loss Limit"); ?></h4>
        <table class="default-table table borderless table-responsive text-dark" align="center" ng-if="dataLimits.loss.length">
            <thead class="thead-default">
                <tr>
                    <th><?= __('Limits'); ?></th>
                    <th><?= __('Amount'); ?></th>
                    <th><?= __('Until'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="loss in dataLimits.loss">
                    <td>{{loss.UsersLimits.limit_type}}</td>
                    <td>{{loss.UsersLimits.amount}} <?= Configure::read('Settings.currency'); ?></td>
                    <td>{{formatDateLm(loss.UsersLimits.until_date)}} <a href="javascript://" ng-if="!loss.UsersLimits.until_date" ng-click="unsetselimits(loss.UsersLimits.id)"><?= __('Cancel'); ?></a></td>
                </tr>
            </tbody>
        </table>	

        <p class="font-weight-bold" ng-if="!dataLimits.loss.length"><?= __('No Limits assigned yet'); ?></p>
        <table class="default-table table borderless table-responsive text-dark" align="center">
            <thead class="thead-default">
                <tr>
                    <td><?= __('Add Limit', true); ?></td>
                    <td><?= __('Amount', true); ?></td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select class="custom-select form-control account-input" id="limitstype" ng-model="UserLimits.lossLimitPeriod">
                            <option ng-selected="true" value="" class="text-muted"><?= __('Select limit to add'); ?></option>
                            <option ng-repeat="(limitvalue,limittxt) in generalLimits" value="{{limitvalue}}">{{limittxt}}</option>
                        </select>
                    </td>
                    <td>
                        <input class="form-control account-input-outline" name="limitvalue" type="text" id="limitvalue" ng-model="UserLimits.lossLimitAmount"/>
                    </td>
                    <td >
                        <button class="btn btn-main text-uppercase font-weight-bold" ng-click="SaveLossLimit()"><?= __('Add Limit', true); ?></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr class="account-hr my-4">

    <div class="section-wrapper">
        <h4 class="modal-tab-subtitle"><i class="fa fa-cog"></i> <?= __("Self Exclusion"); ?></h4>
        <table class="default-table table borderless table-responsive text-dark" align="center" ng-if="dataLimits.selfexclution.length">
            <thead class="thead-default">
                <tr>
                    <th><?= __("Limits"); ?></th>
                    <th><?= __("Amount"); ?></th>
                    <th><?= __("Until"); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="selfexclution in dataLimits.selfexclution">
                    <td>{{selfexclution.UsersLimits.limit_type}}</td>
                    <td ng-if="selfexclution.UsersLimits.amount == 1"><?= __("7 Days"); ?></td>
                    <td ng-if="selfexclution.UsersLimits.amount != 1"><?= __("6 Months"); ?></td>
                    <td>{{formatDateLm(loss.UsersLimits.until_date)}}</td>
                </tr>
            <tbody>
        </table>

        <table class="default-table table borderless table-responsive text-dark">
            <tr>
                <td>
                    <label class="custom-control custom-radio">
                        <input type="radio" ng-model="UserLimits.selfexclutionLimitPeriod" value="1" id="SelfexclutionAmount1" name="SelfexclutionAmount1" class="custom-control-input">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description"><?= __('Do not allow me to access my account for the next 7 days'); ?></span>
                    </label>

                     <label class="custom-control custom-radio">
                        <input type="radio" ng-model="UserLimits.selfexclutionLimitPeriod" value="2" id="SelfexclutionAmount2" name="SelfexclutionAmount2" class="custom-control-input">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description"><?= __('Do not allow me to access my account for the next 7 days'); ?></span>
                    </label>

                  
                </td>
            </tr>
            <tr>
                <td colspan="2"><button class="btn btn-main text-uppercase font-weight-bold" ng-click="SaveSelfexclutionLimit()"><?= __('Block Account', true); ?></button></td>
            </tr>
        </table>
    </div>
    <hr class="account-hr my-4">

    <div class="text-center">
        <h4 class="modal-tab-subtitle"><?= __("DELETE ACCOUNT"); ?></h4>
        <p class="my-2 font-italic text-danger"><?= __('Permanently delete your account.'); ?></p>
        <button class="btn btn-main text-uppercase font-weight-bold" ng-click="SaveDeleteLimit()"><?= __('Delete My Account', true); ?></button>
    </div>
</div><!-- cent-text-->