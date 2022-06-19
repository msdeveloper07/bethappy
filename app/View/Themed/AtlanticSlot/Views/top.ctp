<header class="header" ng-if="isLanding">
    <div class="inner_center">
        <a class="navbar-brand" href="/" onClick="window.location = '/'">
            <div class="logo">
                Art of Slots
            </div>
        </a>
        <div class="nav">
            <ul>
                <li ng-repeat="item in menu" ng-if="!item.sub">
                    <a ng-click="reloadRoute(item.url)" href ng-if="item.url !== '/page/bonuses'"><?= __('{{item.title}}'); ?></a>
                    <a role="button" ng-click="showAdvanced($event, 'page', controllers.Page, 'bonuses')" ng-if="item.url === '/page/bonuses'"><?= __('{{item.title}}'); ?></a>
                </li>
            </ul>
        </div>
    </div>
</header>

<header ng-if="!isLanding">

    <nav class="navbar navbar-expand-lg main-navbar">
        <div class="container flex-md-row flex-lg-column justify-content-lg-center align-items-md-end align-items-lg-end">
            <a class="navbar-brand main-brand order-sm-1 order-md-1 order-lg-1" href="/" onClick="window.location = '/'">Art of Slots</a>
            <span class="d-flex order-sm-1 order-md-2 order-lg-3 ">
                <span class="navbar-buttons order-sm-1 order-md-2 order-lg-3 mr-1">
                    <?php if (Configure::read('Settings.login') == 1): ?>
                        <?php
                        if (!$this->Session->check('Auth.User')):
                            ?> 
                            <a class="btn btn-lg btn-primary text-uppercase" href="javascript://" ng-click="showAdvanced($event, 'sign-in', controllers.Top)"><?= __('Sign in'); ?></a>
                            <a class="btn btn-lg btn-secondary text-uppercase" href="/#/sign-up"><?= __('Sign up'); ?></a>
                        <?php else: ?>
                            <div class="btn-group">
                                <button class="dropdown-toggle btn btn-lg btn-primary small ml-auto order-lg-last" id="accountDropdown"  type="button" data-toggle="dropdown" aria-haspopup="true" aria-haspopup="true" aria-expanded="false">
                                    <?= CakeSession::read('Auth.User.username'); ?>
                                </button>


                                <div class="dropdown-menu dropdown-menu-right text-muted-default" aria-labelledby="dropdownMenuLink">
                                    <div class="dropdown-item">

                                        <div class="d-flex justify-content-center align-items-center">
                                            <div class="profile-image text-uppercase mr-2">
                                                <?= substr(CakeSession::read('Auth.User.first_name'), 0, 1); ?><?= substr(CakeSession::read('Auth.User.last_name'), 0, 1); ?>
                                            </div>
                                            <div class="profile-info d-flex flex-column justify-content-center align-items-start">
                                                <span class="text-white"> <?= CakeSession::read('Auth.User.first_name'); ?>  <?= CakeSession::read('Auth.User.last_name'); ?></span>
                                                <a href="/#/account/profile" class="small text-underline"><?= __('Profile'); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item small cursor-default" href="#"><?= __('Balance'); ?>: {{userbalance ? userbalance : '0.00'}}<?= Configure::read('Settings.currency'); ?></a>
                                    <a class="dropdown-item small cursor-default" href="#"> <?= __('Bonus'); ?> <?= __('Balance'); ?>: {{userbonusbalance ? userbonusbalance : '0.00'}}<?= Configure::read('Settings.currency'); ?></a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/#/account/deposits"><i class="fas fa-plus-circle text-muted-default mr-3" aria-hidden="true"></i><?= __('Deposits'); ?></a>
                                    <a class="dropdown-item" href="/#/account/withdraws"><i class="fas fa-minus-circle text-muted-default mr-3" aria-hidden="true"></i><?= __('Withdraws'); ?></a>
                                    <a class="dropdown-item" href="/#/account/kyc"><i class="fas fa-cloud-upload-alt text-muted-default mr-3" aria-hidden="true"></i><?= __('KYC'); ?></a>
                                    <a class="dropdown-item" href="/#/account/limits"><i class="fas fa-user-shield text-muted-default mr-3" aria-hidden="true"></i><?= __('Limits'); ?></a>
                                    <a class="dropdown-item" href="/#/account/bonuses"><i class="fas fa-gifts text-muted-default mr-3" aria-hidden="true"></i><?= __('Bonuses'); ?></a>
                                    <a class="dropdown-item" href="/#/account/settings"><i class="fas fa-tools text-muted-default mr-3" aria-hidden="true"></i><?= __('Settings'); ?></a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/users/logout?<?= rand(0, 9999999); ?>"><i class="fas fa-power-off text-muted-default mr-3" aria-hidden="true"></i><?= __('Sign out'); ?></a>
                                </div>


                                <!--                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="accountDropdown">
                                                                    <a class="dropdown-item text-center small" href="#">
                                <?= __('Balance'); ?>: {{userbalance}}<?= Configure::read('Settings.currency'); ?>
                                                                    </a>
                                                                    <a class="dropdown-item text-center small" href="#" ng-if="userbonusbalance">
                                <?= __('Bonus'); ?> <?= __('Balance'); ?>:{{userbonusbalance}}<?= Configure::read('Settings.currency'); ?>
                                                                    </a>
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item" href="javascript://" role="button" ng-click="showAdvanced($event, 'account', controllers.Top, 'profile')">
                                                                        <i class="far fa-user-circle mr-2"></i> <?= __('Profile'); ?>
                                                                    </a>
                                
                                                                    <a class="dropdown-item" href="javascript://" role="button" ng-click="showAdvanced($event, 'account', controllers.Top, 'deposits')">
                                                                        <i class="fas fa-plus-circle mr-2"></i> <?= __('Deposit'); ?>
                                                                    </a>
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item" href="/users/logout?<?= rand(0, 9999999); ?>">
                                                                        <i class="fas fa-sign-out-alt mr-2"></i> <?= __('Logout'); ?>
                                                                    </a>
                                                                </div>-->
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </span>

                <button class="navbar-toggler order-sm-3 order-md-3 order-lg-4" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon d-flex justify-content-center align-items-center">
                        <i class="fas fa-bars text-white"></i>
                    </span>
                </button>
            </span>
            <div class="collapse navbar-collapse order-sm-4 order-md-4 order-lg-2" id="navbarSupportedContent">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ng-include src="'/Views/view/mainmenu'" class="w-100"></ng-include>
                </div>
            </div>
        </div>
    </nav>
</header>
<!-- end  header -->
