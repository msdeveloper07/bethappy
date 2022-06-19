<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

    <a class="navbar-brand main-brand" href="/#!/">
        <?= $this->Html->image('casino/atlantic_slot_logo.png', array('alt' => Configure::read('Settings.websiteName'))); ?>
    </a>
    <div class="order-lg-last">
        <?php if (Configure::read('Settings.login') == 1): ?>
            <?php
            if (!$this->Session->check('Auth.User.id')):
                ?> 
                <button type="button" class="btn btn-sm btn-light rounded-pill px-4 align-middle ml-auto mr-2 order-md-last order-lg-last" ng-click="openSignInModal()"><?= __('Sign In'); ?></button>
                <button type="button" class="btn btn-sm btn-default rounded-pill text-white px-4 align-middle order-md-last order-lg-last" ng-click="openSignUpModal()"><?= __('Sign Up'); ?></button>


            <?php else: ?>
                <div class="btn-group order-md-last order-lg-last">
                    <button class="dropdown-toggle btn sm btn-default rounded-pill text-white px-4 align-middle" id="accountDropdown"  type="button" data-toggle="dropdown" aria-haspopup="true" aria-haspopup="true" aria-expanded="false">
                        <?= CakeSession::read('Auth.User.username'); ?>
                    </button>

                    <div class="dropdown-menu dropdown-menu-md-right dropdown-menu-default" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-item">

                            <div class="d-flex justify-content-center align-items-center">
                                <div class="profile-image text-uppercase mr-2">
                                    <?= substr(CakeSession::read('Auth.User.first_name'), 0, 1); ?><?= substr(CakeSession::read('Auth.User.last_name'), 0, 1); ?>
                                </div>
                                <div class="profile-info d-flex flex-column justify-content-center align-items-start">
                                    <a href="javascript:;" class="dropdown-item"> <?= CakeSession::read('Auth.User.first_name'); ?>  <?= CakeSession::read('Auth.User.last_name'); ?></a>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item small cursor-default" href="javascript:;">
                            <?= __('Balance'); ?>: 
                            <span ng-bind-html="user_balance ? user_balance  : '' | currencyFilter:User.Currency.code"></span>
                        </a>

                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/#!/account/profile"><i class="fas fa-user mr-3" aria-hidden="true"></i><?= __('Profile'); ?></a>
                        <a class="dropdown-item" href="/#!/account/deposits"><i class="fas fa-plus-circle mr-3" aria-hidden="true"></i><?= __('Deposits'); ?></a>
                        <a class="dropdown-item" href="/#!/account/withdraws"><i class="fas fa-minus-circle mr-3" aria-hidden="true"></i><?= __('Withdraws'); ?></a>
                        <a class="dropdown-item" href="/#!/account/kyc"><i class="fas fa-id-card mr-3" aria-hidden="true"></i><?= __('KYC'); ?></a>
                        <a class="dropdown-item" href="/#!/account/limits"><i class="fas fa-sliders-h mr-3" aria-hidden="true"></i><?= __('Limits'); ?></a>
                        <a class="dropdown-item" href="/#!/account/gameplay"><i class="fas fa-gamepad mr-3" aria-hidden="true"></i><?= __('Gameplay'); ?></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="javascript:;" ng-click="signOut()"><i class="fas fa-power-off mr-3" aria-hidden="true"></i><?= __('Sign out'); ?></a>

                    </div>

                </div>
            <?php endif; ?>
        <?php endif; ?>

        <button class="navbar-toggler ml-3  order-md-last order-lg-last" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="collapse navbar-collapse order-lg-2" id="navbarCollapse" ng-controller="headerController" ng-cloak>

        <ul class="navbar-nav mr-auto">
            <li class="nav-item ml-2" ng-repeat="item in main_menu" ng-if="!item.sub && item.active == 1" ng-click="activateMenuItem(item.id)" ng-class="{'active':item.id == activeMenuItem}">
                <a ng-click="reloadRoute(item.url)" class="nav-link" href="{{item.url}}">
                    <span ng-if="item.id === '1'">Home  <span class="sr-only" ng-cloak>(current)</span></span>
                    <span ng-if="item.id !== '1'" ng-cloak>{{item.title}}</span>
                </a>

                <!--                <a href="javascript:;" class="nav-link" ng-if="item.id == '50'" ng-click="openContactModal()">                                    
                                    <span ng-cloak>{{item.title}}</span>
                                </a>-->
            </li>
<!--            <li class="nav-item ml-2">
                <a ng-click="https://www.livescore.com/en/" class="nav-link" href="https://www.livescore.com/en/">

                    <span ng-cloak>Live Scores</span>
                </a>
            </li>-->

        </ul>

    </div>
</nav>