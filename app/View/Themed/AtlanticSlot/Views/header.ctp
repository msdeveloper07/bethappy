<header class="header" ng-if="isLanding">
    <div class="inner_center">
        <a class="navbar-brand" href="/" onClick="window.location = '/'">
            <div class="logo">
                <img src="/Layout/Artofslots/images/the-art-of-slots-logo-lg.png" alt="Art of Slots" height="120"/>
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

    <nav class="navbar main-navbar">
        <div class="container">
            <a class="navbar-brand main-brand" href="/" onClick="window.location = '/'">
                <img src="/Layout/Artofslots/images/the-art-of-slots-logo-lg.png" alt="Art of Slots" height="120"/>
            </a>

            <div class="d-flex flex-column align-items-sm-center align-items-md-end">

                <ng-include src="'/Views/view/main-menu'" class="w-100"></ng-include>

                <span class="d-flex justify-content-center justify-content-md-end">
                    <?php if (Configure::read('Settings.login') == 1): ?>
                        <?php
                        if (!$this->Session->check('Auth.User')):
                            ?> 
                            <a class="btn btn-lg btn-primary text-uppercase mr-2" href="javascript://" ng-click="showAdvanced($event, 'sign-in', controllers.Header)"><?= __('Sign in'); ?></a>
                            <a class="btn btn-lg btn-secondary text-uppercase" href="/#/sign-up"><?= __('Sign up'); ?></a>
                        <?php else: ?>
                            <div class="btn-group">
                                <button class="dropdown-toggle btn btn-lg btn-primary small ml-auto" id="accountDropdown"  type="button" data-toggle="dropdown" aria-haspopup="true" aria-haspopup="true" aria-expanded="false">
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
                                                <!--<a href="/#/account/profile" class="small text-underline"><?= __('Profile'); ?></a>-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item small cursor-default" href="#"><?= __('Balance'); ?>: {{userbalance ? userbalance : '0.00'}}<?= Configure::read('Settings.currency'); ?></a>
                                    <a class="dropdown-item small cursor-default" href="#"> <?= __('Bonus'); ?> <?= __('Balance'); ?>: {{userbonusbalance ? userbonusbalance : '0.00'}}<?= Configure::read('Settings.currency'); ?></a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/#/account/profile"><i class="fas fa-user mr-3" aria-hidden="true"></i><?= __('Profile'); ?></a>

                                    <a class="dropdown-item" href="/#/account/deposit"><i class="fas fa-plus-circle mr-3" aria-hidden="true"></i><?= __('Deposit'); ?></a>
                                    <a class="dropdown-item" href="/#/account/withdraw"><i class="fas fa-minus-circle mr-3" aria-hidden="true"></i><?= __('Withdraw'); ?></a>
                                    <a class="dropdown-item" href="/#/account/kyc"><i class="fas fa-upload mr-3" aria-hidden="true"></i><?= __('KYC'); ?></a>
                                    <a class="dropdown-item" href="/#/account/limits"><i class="fas fa-sliders-h mr-3" aria-hidden="true"></i><?= __('Limits'); ?></a>
                                    <a class="dropdown-item" href="/#/account/bonuses"><i class="fas fa-gift mr-3" aria-hidden="true"></i><?= __('Bonuses'); ?></a>
                                    <a class="dropdown-item" href="/#/account/history"><i class="fas fa-history mr-3" aria-hidden="true"></i><?= __('History'); ?></a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/users/logout?<?= rand(0, 9999999); ?>"><i class="fas fa-power-off mr-3" aria-hidden="true"></i><?= __('Sign out'); ?></a>
                                </div>

                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                </span>
            </div>
        </div>
    </nav>
</header>
<!-- end  header -->
