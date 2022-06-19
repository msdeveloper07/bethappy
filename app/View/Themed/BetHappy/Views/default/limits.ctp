<main class="main">
    <div class="container">

        <div class="row">
            <div class="col-md-12"><h1 class="title mb-5 ng-binding"><?= __('Limits'); ?></h1></div>

            <div class="col-md-12 mb-5">
                <h2 class="mt-3"><?= __("Session limits"); ?></h2>
                <table class="table table-borderless text-white" align="center" ng-if="limits.session.data.length">
                    <thead class="thead-default">
                        <tr>
                            <th><?= __('Limits'); ?></th>
                            <th><?= __('Amount'); ?></th>
                            <th><?= __('Until'); ?></th>
                            <th><?= __('Actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="session in limits.session.data">
                            <td>{{session.UsersLimits.limit_type}}</td>
                            <td>{{currency}}{{session.UsersLimits.amount}}</td>
                            <td>{{session.UsersLimits.until_date}}</td>
                            <!--there is until date only for exclusion-->
                            <td><a class="btn btn-outline-light btn-sm" href="javascript://" ng-if="!session.UsersLimits.until_date" ng-click="unsetLimits(session.UsersLimits.id)"><?= __('Cancel limit'); ?></a></td>
                        </tr>
                    </tbody>
                </table>
                <p ng-if="!limits.session.data.length"><?= __('No limits assigned yet.'); ?></p>
                <h5><?= __('Add session limit'); ?></h5>

                <!--tables structure is needed to align labels, forms, and errors-->
                <form name="sessionLimitForm" class="needs-validation" novalidate>
                    <table>
                        <tr>
                            <td><label for="limits_type"><?= __('Limit Type'); ?></label></td>
                            <td><label for="limits_type"><?= __('Amount'); ?></label></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>   
                                <select class="custom-select form-control" name="limit_type" id="limit_type" ng-model="UserLimits.sessionLimitPeriod"
                                        ng-class="{'is-invalid': sessionLimitForm.limit_type.$error.required && !sessionLimitForm.limit_type.$pristine}" required>
                                    <option value="" ng-selected="true"><?= __('Select limit type'); ?></option>
                                    <option ng-repeat="(limit_value, limit_desc) in session_limits_types" value="{{limit_value}}">{{limit_desc}}</option>
                                </select>
                            </td>
                            <td>
                                <input class="form-control" ng-class="{'is-invalid': sessionLimitForm.limit_value.$error.required && !sessionLimitForm.limit_value.$pristine}" name="limit_value" type="number" id="limit_value" ng-model="UserLimits.sessionLimitAmount" required/>
                            </td>
                            <td>
                                <button class="btn btn-primary px-4" ng-click="setSessionLimit()" ng-disabled="sessionLimitForm.$invalid"><?= __('Set limit', true); ?></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span ng-show="sessionLimitForm.limit_type.$error.required && !sessionLimitForm.limit_type.$pristine" class="text-danger"><?= __('Limit type is required.', true); ?></span>
                            </td>
                            <td>
                                <span ng-show="sessionLimitForm.limit_value.$error.required && !sessionLimitForm.limit_value.$pristine" class="text-danger"><?= __('Amount is required.', true); ?></span>

                            </td>
                            <td></td>
                        </tr>
                    </table>
                </form>
            </div>

            <div class="col-md-12 mb-5">
                <h2><?= __("Deposit limits"); ?></h2>
                <table class="table table-borderless text-white" align="bottom" ng-if="limits.deposit.data.length">
                    <thead>
                        <tr>
                            <th><?= __('Limit type'); ?></th>
                            <th><?= __('Amount'); ?></th>
                            <th><?= __('Until'); ?></th>
                            <th><?= __('Actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="deposit in limits.deposit.data">
                            <td>{{deposit.UsersLimits.limit_type}}</td>
                            <td>{{currency}}{{deposit.UsersLimits.amount}}</td>
                            <td>{{deposit.UsersLimits.until_date}}</td>
                            <td><a class="btn btn-outline-light btn-sm" href="javascript://" ng-if="!deposit.UsersLimits.until_date" ng-click="unsetLimits(deposit.UsersLimits.id)"><?= __('Cancel limit'); ?></a></td>
                        </tr>
                    </tbody>
                </table>
                <p ng-if="!limits.deposit.data.length" class="mt-3"><?= __('No limits assigned yet.'); ?></p>

                <h5><?= __('Add deposit limit'); ?></h5>
                <form name="depositLimitForm" class="needs-validation" novalidate>

                    <table>
                        <tr>
                            <td><label for="limits_type"><?= __('Limit Type'); ?></label></td>
                            <td><label for="limits_type"><?= __('Amount'); ?></label></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>    
                                <select class="custom-select form-control" name="limit_type" id="deposit_limit_type" ng-model="UserLimits.depositLimitPeriod"
                                        ng-class="{'is-invalid': depositLimitForm.limit_type.$error.required && !depositLimitForm.limit_type.$pristine}" required>

                                    <option value="" ng-selected="true"><?= __('Select limit type'); ?></option>
                                    <option ng-repeat="(limit_value, limit_desc) in limits_types" value="{{limit_value}}">{{limit_desc}}</option>

                                </select>
                            </td>
                            <td>                            
                                <input class="form-control" ng-class="{'is-invalid': depositLimitForm.limit_value.$error.required && !depositLimitForm.limit_value.$pristine}"  name="limit_value" type="number" id="deposit_limit_value" ng-model="UserLimits.depositLimitAmount" required/>
                            </td>
                            <td>                            
                                <button class="btn btn-primary px-4" ng-click="setDepositLimit()" ng-disabled="depositLimitForm.$invalid"><?= __('Set limit', true); ?></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span ng-show="depositLimitForm.limit_type.$error.required && !depositLimitForm.limit_type.$pristine" class="text-danger"><?= __('Limit type is required.', true); ?></span>
                            </td>
                            <td>
                                <span ng-show="depositLimitForm.limit_value.$error.required && !depositLimitForm.limit_value.$pristine" class="text-danger"><?= __('Amount is required.', true); ?></span>
                            </td>
                            <td></td>
                        </tr>
                    </table>


                </form>
            </div>

            <div class="col-md-12 mb-5">
                <h2 class="mt-3"><?= __("Wager limits"); ?></h2>
                <table class="table table-borderless text-white" align="center" ng-if="limits.wager.data.length">
                    <thead class="thead-default">
                        <tr>
                            <th><?= __('Limits'); ?></th>
                            <th><?= __('Amount'); ?></th>
                            <th><?= __('Until'); ?></th>
                            <th><?= __('Actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="wager in limits.wager.data">
                            <td>{{wager.UsersLimits.limit_type}}</td>
                            <td>{{currency}}{{wager.UsersLimits.amount}}</td>
                            <td>{{wager.UsersLimits.until_date}}</td>
                            <td><a class="btn btn-outline-light btn-sm" href="javascript://" ng-if="!wager.UsersLimits.until_date" ng-click="unsetLimits(wager.UsersLimits.id)"><?= __('Cancel limit'); ?></a></td>
                        </tr>
                    </tbody>
                </table>
                <p ng-if="!limits.wager.data.length"><?= __('No limits assigned yet.'); ?></p>

                <h5><?= __('Add wager limit'); ?></h5>
                <form name="wagerLimitForm" class="needs-validation" novalidate>
                    <table>
                        <tr>
                            <td><label for="limits_type"><?= __('Limit Type'); ?></label></td>
                            <td><label for="limits_type"><?= __('Amount'); ?></label></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>    
                                <select class="custom-select form-control" name="limit_type" id="wager_limit_type" ng-model="UserLimits.wagerLimitPeriod"
                                        ng-class="{'is-invalid': wagerLimitForm.limit_type.$error.required && !wagerLimitForm.limit_type.$pristine}" required>

                                    <option value="" ng-selected="true"><?= __('Select limit type'); ?></option>
                                    <option ng-repeat="(limit_value, limit_desc) in limits_types" value="{{limit_value}}">{{limit_desc}}</option>

                                </select>
                            </td>
                            <td>                            
                                <input class="form-control" ng-class="{'is-invalid': wagerLimitForm.limit_value.$error.required && !wagerLimitForm.limit_value.$pristine}"  name="limit_value" type="number" id="wager_limit_value" ng-model="UserLimits.wagerLimitAmount" required/>
                            </td>
                            <td>                            
                                <button class="btn btn-primary px-4" ng-click="setWagerLimit()" ng-disabled="wagerLimitForm.$invalid"><?= __('Set limit', true); ?></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span ng-show="wagerLimitForm.limit_type.$error.required && !wagerLimitForm.limit_type.$pristine" class="text-danger"><?= __('Limit type is required.', true); ?></span>
                            </td>
                            <td>
                                <span ng-show="wagerLimitForm.limit_value.$error.required && !wagerLimitForm.limit_value.$pristine" class="text-danger"><?= __('Amount is required.', true); ?></span>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </form>
            </div>

            <div class="col-md-12">
                <h2 class="mt-3"><?= __("Loss limits"); ?></h2>
                <table class="table table-borderless text-white" align="center" ng-if="limits.loss.data.length">
                    <thead>
                        <tr>
                            <th><?= __('Limits'); ?></th>
                            <th><?= __('Amount'); ?></th>
                            <th><?= __('Until'); ?></th>
                            <th><?= __('Actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="loss in limits.loss.data">
                            <td>{{loss.UsersLimits.limit_type}}</td>
                            <td>{{currency}}{{loss.UsersLimits.amount}}</td>
                            <td>{{loss.UsersLimits.until_date}}</td>
                            <td><a class="btn btn-outline-light btn-sm" href="javascript://" ng-if="!loss.UsersLimits.until_date" ng-click="unsetLimits(loss.UsersLimits.id)"><?= __('Cancel limit'); ?></a></td>
                        </tr>
                    </tbody>
                </table>	

                <p ng-if="!limits.loss.data.length"><?= __('No limits assigned yet.'); ?></p>

                <h5><?= __('Add loss limit'); ?></h5>
                <form name="lossLimitForm" class="needs-validation" novalidate>
                    <table>
                        <tr>
                            <td><label for="limits_type"><?= __('Limit Type'); ?></label></td>
                            <td><label for="limits_type"><?= __('Amount'); ?></label></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>    
                                <select class="custom-select form-control" name="limit_type" id="loss_limit_type" ng-model="UserLimits.lossLimitPeriod"
                                        ng-class="{'is-invalid': lossLimitForm.limit_type.$error.required && !lossLimitForm.limit_type.$pristine}" required>

                                    <option value="" ng-selected="true"><?= __('Select limit type'); ?></option>
                                    <option ng-repeat="(limit_value, limit_desc) in limits_types" value="{{limit_value}}">{{limit_desc}}</option>

                                </select>
                            </td>
                            <td>                            
                                <input class="form-control" ng-class="{'is-invalid': lossLimitForm.limit_value.$error.required && !lossLimitForm.limit_value.$pristine}"  name="limit_value" type="number" id="loss_limit_value" ng-model="UserLimits.lossLimitAmount" required/>
                            </td>
                            <td>                            
                                <button class="btn btn-primary px-4" ng-click="setLossLimit()" ng-disabled="lossLimitForm.$invalid"><?= __('Set limit', true); ?></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span ng-show="lossLimitForm.limit_type.$error.required && !lossLimitForm.limit_type.$pristine" class="text-danger"><?= __('Limit type is required.', true); ?></span>
                            </td>
                            <td>
                                <span ng-show="lossLimitForm.limit_value.$error.required && !lossLimitForm.limit_value.$pristine" class="text-danger"><?= __('Amount is required.', true); ?></span>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </form>
            </div>

            <div class="col-md-12">
                <h2 class="mt-3"><?= __("Self-exclusion"); ?></h2>

                <p class="small">
                    <?= __('Self-exclusion means temporary or permanent freezing of your account.'); ?>
                    <?= __('This option still allows Art of Slots casino to contact you with marketing material such as bonus and promotional offerings.'); ?>
                    <?= __('In case you want to re-open your account before the self-exclusion time has elapsed, please contact our Customer Support on'); ?> <a href="mailto:<?= Configure::read('Settings.websiteSupportEmail'); ?>"><?= Configure::read('Settings.websiteSupportEmail'); ?></a>.
                </p>

                <form name="selfExclusionLimitForm" class="needs-validation" novalidate>
                    <div class="custom-control custom-switch" >
                        <input type="checkbox" class="custom-control-input" id="exclusion-7" ng-model="UserLimits.selfExclusionLimitPeriod7" ng-change="setExclusionLimitPeriod('7_days')"/>
                        <label class="custom-control-label" for="exclusion-7"><?= __('Do not allow me to access my account for the next 7 days'); ?></label>
                    </div>

                    <div class="custom-control custom-switch" >
                        <input type="checkbox" class="custom-control-input" id="exclusion-30" ng-model="UserLimits.selfExclusionLimitPeriod30" ng-change="setExclusionLimitPeriod('30_days')"/>
                        <label class="custom-control-label" for="exclusion-30"><?= __('Do not allow me to access my account for the next 30 days'); ?></label>
                    </div>

                    <div class="custom-control custom-switch" >
                        <input type="checkbox" class="custom-control-input" id="exclusion-90" ng-model="UserLimits.selfExclusionLimitPeriod90" ng-change="setExclusionLimitPeriod('90_days')"/>
                        <label class="custom-control-label" for="exclusion-90"><?= __('Do not allow me to access my account for the next 90 days'); ?></label>
                    </div>


                    <div class="form-group col-sm-12 col-md-3 offset-md-9">
                        <button class="btn btn-primary" ng-click="setSelfExclusionLimit()" ng-disabled="!UserLimits.selfExclusionLimitPeriod"><?= __('Set exclusion', true); ?>
                        </button>
                    </div>
                </form>
            </div>


            <div class="col-md-12">
                <h2 class="mt-3"><?= __("Delete Account"); ?></h2>
                <p class="small text-danger"><?= __('Please note that by clickin on the "Delete my account" button, you will permanently delete your account and you will not be able to login anymore.'); ?></p>
                <button class="btn btn-danger" ng-click="deleteAccount()"><?= __('Delete my account', true); ?></button>
            </div>

        </div>
    </div>
</main>
