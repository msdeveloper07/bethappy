
<header ng-controller="headerController" class="header">
    <div class="container-xl">
        <div class="top-header py-1">

            <div class="row align-items-center top-header-row">
                <div class="col-sm-12 col-md-6 col-xl-3 col-12">
                    <div class="top-header-row-wrapper">
                        <button class="navbar-toggler float-left collapsed" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fas fa-bars"></i>
                        </button>

                        <div class="top-bar-left d-sm-flex flex-sm-column">
                            <div class="logo">
                                <a class="navbar-brand" href="/">
                                    <?= $this->Html->image('casino/bet-happy-logo-lg.png', array('alt' => Configure::read('Settings.websiteName'), 'height' => '80px')); ?>   
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (Configure::read('Settings.login') == 1): ?>
                    <div class="col-sm-12 col-md-6 col-xl-9 col-12">
                        <?php if (!$this->Session->check('Auth.User.id')): ?>
                            <div class="top-bar-right">        
                                <button type="button" class="btn btn-default btn-md text-uppercase font-weight-bold mr-2" ng-click="openSignInModal()"><?= __('Login'); ?></button>
                                <button type="button" class="btn btn-highlight btn-md text-uppercase font-weight-bold mr-2" ng-click="openSignUpModal()"><?= __('Register'); ?></button>

                                <ul class="navbar-nav">
                                    <li class="nav-item" id="translations" ng-cloak>{{$select.selected}}
                                    <ui-select ng-model="language.selected" theme="selectize" search-enabled="false" ng-change="setLanguage(language.selected.id, language.selected.locale_code)">

                                        <ui-select-match>
                                            <span>
                                                <!--<img ng-src="https://flagcdn.com/{{$select.selected.ISO6391_code == 'en' ? 'gb' : ($select.selected.ISO6391_code == 'hi' ? 'in' : $select.selected.ISO6391_code)}}.svg" width="30"/>-->
                                                <img ng-src="https://flagcdn.com/{{$select.selected.ISO6391_code == 'en' ? 'gb' : ($select.selected.ISO6391_code == 'hi' ? 'in' : ($select.selected.ISO6391_code == 'ar' ? 'sa' : $select.selected.ISO6391_code))}}.svg" width="30"/>

                                            </span>
                                        </ui-select-match>
                                        <ui-select-choices repeat="language in Languages">
                                            <span>
                                                <img ng-src="https://flagcdn.com/{{language.ISO6391_code == 'en' ? 'gb' : (language.ISO6391_code == 'hi' ? 'in' :(language.ISO6391_code == 'ar' ? 'sa' : language.ISO6391_code))}}.svg" width="30"/>
                                            </span>    
                                            <!--{{language.name | translate}-->               
                                        </ui-select-choices>
                                    </ui-select>        
                                    </li>
                                </ul>
                            </div>
                        <?php else: //var_dump(CakeSession::read('Auth.User')); ?>
                            <div class="top-bar-right account-bar">
                                <div class="account-bar-column"><span class="small"><?= __('Balance'); ?></span> 
                                    <span class="header-balance"><?= CakeSession::read('Auth.User.Currency.code'); ?> <?= CakeSession::read('Auth.User.balance'); ?></span>
                                    <!--<span class="header-balance"><span ng-bind-html="balance ? balance  : '' | currencyFilter:User.Currency.code"></span></span>-->
                                </div>
                                <div class="account-bar-column"><span class="small"><?= __('Bonus Balance'); ?></span>
                                    <span class="header-balance"><?= CakeSession::read('Auth.User.Currency.code'); ?> <?= CakeSession::read('Auth.User.ActiveBonus.balance') ? CakeSession::read('Auth.User.ActiveBonus.balance') : '0.00'; ?></span>
                                    <!--<span class="header-balance"><span ng-bind-html="bonus_balance ? bonus_balance  : '' | currencyFilter:User.Currency.code"></span></span>-->
                                </div>
                                <div class="account-bar-column">
                                    <div class="btn-group order-md-last order-lg-last show">
                                        <div class="dropdown-toggle" id="accountDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            <i class="far fa-user"></i>
                                        </div>

                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-default" aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-item">

                                                <div class="d-flex justify-content-center align-items-center">
                                                    <div class="profile-image mr-2">
                                                        <?= substr(CakeSession::read('Auth.User.first_name'), 0, 1); ?><?= substr(CakeSession::read('Auth.User.last_name'), 0, 1); ?> </div>
                                                    <div class="profile-info d-flex flex-column justify-content-center align-items-start">
                                                        <a href="javascript:;" class="dropdown-item"><?= CakeSession::read('Auth.User.username'); ?></a>
                                                        <a href="javascript:;" class="dropdown-item small"><?= CakeSession::read('Auth.User.first_name'); ?>  <?= CakeSession::read('Auth.User.last_name'); ?></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item small cursor-default" href="javascript:;">
                                                <?= __('Balance'); ?>: <?= CakeSession::read('Auth.User.Currency.code'); ?> <?= CakeSession::read('Auth.User.balance'); ?>
                                                <!--<span ng-bind-html="balance ? balance  : '' | currencyFilter:User.Currency.code"></span>-->
                                            </a>
                                            <a class="dropdown-item small cursor-default" href="javascript:;">
                                                <?= __('Bonus Balance'); ?>: 
                                                <?= CakeSession::read('Auth.User.Currency.code'); ?> <?= CakeSession::read('Auth.User.ActiveBonus.balance') ? CakeSession::read('Auth.User.ActiveBonus.balance') : '0.00'; ?>
                                                <!--<span ng-bind-html="bonus_balance ? bonus_balance  : '' | currencyFilter:User.Currency.code"></span>-->
                                            </a>

                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="/#!/account/profile"><i class="fas fa-user mr-3" aria-hidden="true"></i><?= __('Profile'); ?></a>
                                            <a class="dropdown-item" href="/#!/account/bonuses"><i class="fas fa-gift mr-3" aria-hidden="true"></i><?= __('Bonuses'); ?></a>
                                            <a class="dropdown-item" href="/#!/account/deposits"><i class="fas fa-plus-circle mr-3" aria-hidden="true"></i><?= __('Deposits'); ?></a>
                                            <a class="dropdown-item" href="/#!/account/withdraws"><i class="fas fa-minus-circle mr-3" aria-hidden="true"></i><?= __('Withdraws'); ?></a>
                                            <a class="dropdown-item" href="/#!/account/kyc"><i class="fas fa-id-card mr-3" aria-hidden="true"></i><?= __('KYC'); ?></a>
                                            <a class="dropdown-item" href="/#!/account/limits"><i class="fas fa-sliders-h mr-3" aria-hidden="true"></i><?= __('Limits'); ?></a>
                                            <a class="dropdown-item" href="/#!/account/gameplay"><i class="fas fa-gamepad mr-3" aria-hidden="true"></i><?= __('Gameplay'); ?></a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="javascript:;" ng-click="signOut()"><i class="fas fa-power-off mr-3" aria-hidden="true"></i><?= __('Log out'); ?></a>
                                        </div>

                                    </div>
                                </div>
                                <div class="account-bar-column">
                                    <ul class="navbar-nav">
                                        <li class="nav-item" id="translations" ng-cloak>{{$select.selected}}
                                        <ui-select ng-model="language.selected" theme="selectize" search-enabled="false" ng-change="setLanguage(language.selected.id, language.selected.locale_code)">

                                            <ui-select-match>
                                                <span>
                                                    <!--<img ng-src="https://flagcdn.com/{{$select.selected.ISO6391_code == 'en' ? 'gb' : ($select.selected.ISO6391_code == 'hi' ? 'in' : $select.selected.ISO6391_code)}}.svg" width="30"/>-->
                                                    <img ng-src="https://flagcdn.com/{{$select.selected.ISO6391_code == 'en' ? 'gb' : ($select.selected.ISO6391_code == 'hi' ? 'in' : ($select.selected.ISO6391_code == 'ar' ? 'sa' : $select.selected.ISO6391_code))}}.svg" width="30"/>

                                                </span>
                                            </ui-select-match>
                                            <ui-select-choices repeat="language in Languages">
                                                <span>
                                                    <img ng-src="https://flagcdn.com/{{language.ISO6391_code == 'en' ? 'gb' : (language.ISO6391_code == 'hi' ? 'in' :(language.ISO6391_code == 'ar' ? 'sa' : language.ISO6391_code))}}.svg" width="30"/>
                                                </span>    
                                                <!--{{language.name | translate}-->               
                                            </ui-select-choices>
                                        </ui-select>        
                                        </li>
                                    </ul>
                                    <!--                                    <ul class="navbar-nav">
                                                                            <li class="nav-item" id="translations" ng-cloak>{{$select.selected}}
                                                                            <ui-select ng-model="language.selected" theme="selectize" search-enabled="false" ng-change="setLanguage(language.selected.id, language.selected.locale_code)">
                                    
                                                                                <ui-select-match>
                                                                                    <span><img ng-src="https://flagcdn.com/{{$select.selected.ISO6391_code == 'en' ? 'gb' : ($select.selected.ISO6391_code == 'hi' ? 'in' : $select.selected.ISO6391_code)}}.svg" width="30"/></span>
                                                                                </ui-select-match>
                                                                                <ui-select-choices repeat="language in Languages">
                                                                                    <span><img ng-src="https://flagcdn.com/{{language.ISO6391_code == 'en' ? 'gb' : (language.ISO6391_code == 'hi' ? 'in' : language.ISO6391_code)}}.svg" width="30"/></span>    {{language.name | translate}               
                                                                                </ui-select-choices>
                                                                            </ui-select>        
                                                                            </li>
                                                                        </ul>-->
                                </div>
                            </div>
                        <?php endif; ?>


                    </div>
                <?php endif; ?>


            </div>
        </div>
        <!--        <div class="main-menu">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <nav class="navbar navbar-expand-lg mr-auto">
                                <div class="navbar-collapse collapse" id="main-menu">
                                    <ul class="navbar-nav mr-auto">
                                        <li class="nav-item" ng-repeat="item in main_menu" ng-if="!item.sub && item.active == 1" ng-click="activateMenuItem(item.id)" ng-class="{'active':item.id == activeMenuItem}">
                                            <a ng-click="reloadRoute(item.url)" class="nav-link" href="{{item.url}}">
                                                <span ng-if="item.id === '2'"><?= __('Casino'); ?>  <span class="sr-only" ng-cloak>(current)</span></span>
                                                <span ng-if="item.id !== '2'" ng-cloak>{{item.title | translate}}</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="faq"><?= __('FAQ'); ?></a>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>-->
    </div>
</header>

<script>
    $(function () {
        $(window).on("scroll", function () {
            if ($(window).scrollTop() > 100) {
                $(".header").addClass("scrolled");
            } else {
                //remove the background property so it comes transparent again (defined in your css)
                $(".header").removeClass("scrolled");
            }
        });
    });
</script>


<!--<header ng-controller="headerController" class="header">
    <div class="container-xl">
        <div class="top-header py-1">
            <div class="row align-items-center top-header-row">
                <div class="col-sm-12 col-md-3 col-xl-3 col-12">
                    <div class="top-header-row-wrapper">
                        <button class="navbar-toggler float-left collapsed" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fas fa-bars"></i>
                        </button>

                        <div class="top-bar-left d-sm-flex flex-sm-column">
                            <div class="logo">
                                <a class="navbar-brand" href="/">
<?= $this->Html->image('casino/bet-happy-logo-lg.png', array('alt' => Configure::read('Settings.websiteName'), 'height' => '80px', 'class' => 'logoHeader')); ?>   
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
<?php if (Configure::read('Settings.login') == 1): ?>
                        <div class="col-sm-12 col-md-9 col-xl-9 col-12">
    <?php if (!$this->Session->check('Auth.User.id')): ?>
                                    <div class="top-bar-right">        
                                        <button type="button" class="btn btn-default btn-md text-uppercase font-weight-bold mr-2" ng-click="openSignInModal()"><?= __('Login'); ?></button>
                                        <button type="button" class="btn btn-highlight btn-md text-uppercase font-weight-bold mr-2" ng-click="openSignUpModal()"><?= __('Register'); ?></button>

                                        <ul class="navbar-nav">
                                            <li class="nav-item" id="translations" ng-cloak>{{$select.selected}}
                                            <ui-select ng-model="language.selected" theme="selectize" search-enabled="false" ng-change="setLanguage(language.selected.id, language.selected.locale_code)">

                                                <ui-select-match>
                                                    <span>
                                                        <img ng-src="https://flagcdn.com/{{$select.selected.ISO6391_code == 'en' ? 'gb' : ($select.selected.ISO6391_code == 'hi' ? 'in' : $select.selected.ISO6391_code)}}.svg" width="30"/>
                                                        <img ng-src="https://flagcdn.com/{{$select.selected.ISO6391_code == 'en' ? 'gb' : ($select.selected.ISO6391_code == 'hi' ? 'in' : ($select.selected.ISO6391_code == 'ar' ? 'sa' : $select.selected.ISO6391_code))}}.svg" width="30"/>

                                                    </span>
                                                </ui-select-match>
                                                <ui-select-choices repeat="language in Languages">
                                                    <span>
                                                        <img ng-src="https://flagcdn.com/{{language.ISO6391_code == 'en' ? 'gb' : (language.ISO6391_code == 'hi' ? 'in' :(language.ISO6391_code == 'ar' ? 'sa' : language.ISO6391_code))}}.svg" width="30"/>
                                                    </span>    
                                                    {{language.name | translate}               
                                                </ui-select-choices>
                                            </ui-select>        
                                            </li>
                                        </ul>
                                    </div>
    <?php else: //var_dump(CakeSession::read('Auth.User')); ?>
                                    <div class="top-bar-right account-bar">
                                        <div class="account-bar-column"><span class="small"><?= __('Balance'); ?></span> 
                                            <span class="header-balance"><?= CakeSession::read('Auth.User.balance'); ?> <?= CakeSession::read('Auth.User.Currency.code'); ?></span>
                                            <span class="header-balance"><span ng-bind-html="balance ? balance  : '' | currencyFilter:User.Currency.code"></span></span>
                                        </div>
                                        <div class="account-bar-column"><span class="small"><?= __('Bonus Balance'); ?></span>
                                            <span class="header-balance"><?= CakeSession::read('Auth.User.ActiveBonus.balance'); ?> <?= CakeSession::read('Auth.User.Currency.code'); ?></span>
                                            <span class="header-balance"><span ng-bind-html="bonus_balance ? bonus_balance  : '' | currencyFilter:User.Currency.code"></span></span>
                                        </div>
                                        <div class="account-bar-column">
                                            <div class="btn-group order-md-last order-lg-last show">
                                                <div class="dropdown-toggle" id="accountDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    <i class="far fa-user"></i>
                                                </div>

                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-default" aria-labelledby="dropdownMenuLink">
                                                    <div class="dropdown-item">

                                                        <div class="d-flex justify-content-center align-items-center">
                                                            <div class="profile-image mr-2">
        <?= substr(CakeSession::read('Auth.User.first_name'), 0, 1); ?><?= substr(CakeSession::read('Auth.User.last_name'), 0, 1); ?> </div>
                                                            <div class="profile-info d-flex flex-column justify-content-center align-items-start">
                                                                <a href="javascript:;" class="dropdown-item"><?= CakeSession::read('Auth.User.username'); ?></a>
                                                                <a href="javascript:;" class="dropdown-item small"><?= CakeSession::read('Auth.User.first_name'); ?>  <?= CakeSession::read('Auth.User.last_name'); ?></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item small cursor-default" href="javascript:;">
        <?= __('Balance'); ?>:  <?= CakeSession::read('Auth.User.balance'); ?> <?= CakeSession::read('Auth.User.Currency.code'); ?>
                                                        <span ng-bind-html="balance ? balance  : '' | currencyFilter:User.Currency.code"></span>
                                                    </a>
                                                    <a class="dropdown-item small cursor-default" href="javascript:;">
        <?= __('Bonus Balance'); ?>: 
        <?= CakeSession::read('Auth.User.ActiveBonus.balance'); ?> <?= CakeSession::read('Auth.User.Currency.code'); ?>
                                                        <span ng-bind-html="bonus_balance ? bonus_balance  : '' | currencyFilter:User.Currency.code"></span>
                                                    </a>

                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="/#!/account/profile"><i class="fas fa-user mr-3" aria-hidden="true"></i><?= __('Profile'); ?></a>
                                                    <a class="dropdown-item" href="/#!/account/bonuses"><i class="fas fa-gift mr-3" aria-hidden="true"></i><?= __('Bonuses'); ?></a>
                                                    <a class="dropdown-item" href="/#!/account/deposits"><i class="fas fa-plus-circle mr-3" aria-hidden="true"></i><?= __('Deposits'); ?></a>
                                                    <a class="dropdown-item" href="/#!/account/withdraws"><i class="fas fa-minus-circle mr-3" aria-hidden="true"></i><?= __('Withdraws'); ?></a>
                                                    <a class="dropdown-item" href="/#!/account/kyc"><i class="fas fa-id-card mr-3" aria-hidden="true"></i><?= __('KYC'); ?></a>
                                                    <a class="dropdown-item" href="/#!/account/limits"><i class="fas fa-sliders-h mr-3" aria-hidden="true"></i><?= __('Limits'); ?></a>
                                                    <a class="dropdown-item" href="/#!/account/gameplay"><i class="fas fa-gamepad mr-3" aria-hidden="true"></i><?= __('Gameplay'); ?></a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="javascript:;" ng-click="signOut()"><i class="fas fa-power-off mr-3" aria-hidden="true"></i><?= __('Log out'); ?></a>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="account-bar-column">
                                                                  <ul class="navbar-nav">
                                            <li class="nav-item" id="translations" ng-cloak>{{$select.selected}}
                                            <ui-select ng-model="language.selected" theme="selectize" search-enabled="false" ng-change="setLanguage(language.selected.id, language.selected.locale_code)">

                                                <ui-select-match>
                                                    <span>
                                                        <img ng-src="https://flagcdn.com/{{$select.selected.ISO6391_code == 'en' ? 'gb' : ($select.selected.ISO6391_code == 'hi' ? 'in' : $select.selected.ISO6391_code)}}.svg" width="30"/>
                                                        <img ng-src="https://flagcdn.com/{{$select.selected.ISO6391_code == 'en' ? 'gb' : ($select.selected.ISO6391_code == 'hi' ? 'in' : ($select.selected.ISO6391_code == 'ar' ? 'sa' : $select.selected.ISO6391_code))}}.svg" width="30"/>

                                                    </span>
                                                </ui-select-match>
                                                <ui-select-choices repeat="language in Languages">
                                                    <span>
                                                        <img ng-src="https://flagcdn.com/{{language.ISO6391_code == 'en' ? 'gb' : (language.ISO6391_code == 'hi' ? 'in' :(language.ISO6391_code == 'ar' ? 'sa' : language.ISO6391_code))}}.svg" width="30"/>
                                                    </span>    
                                                    {{language.name | translate}               
                                                </ui-select-choices>
                                            </ui-select>        
                                            </li>
                                        </ul>
                                            <ul class="navbar-nav">
                                                <li class="nav-item" id="translations" ng-cloak>{{$select.selected}}
                                                <ui-select ng-model="language.selected" theme="selectize" search-enabled="false" ng-change="setLanguage(language.selected.id, language.selected.locale_code)">

                                                    <ui-select-match>
                                                        <span><img ng-src="https://flagcdn.com/{{$select.selected.ISO6391_code == 'en' ? 'gb' : ($select.selected.ISO6391_code == 'hi' ? 'in' : $select.selected.ISO6391_code)}}.svg" width="30"/></span>
                                                    </ui-select-match>
                                                    <ui-select-choices repeat="language in Languages">
                                                        <span><img ng-src="https://flagcdn.com/{{language.ISO6391_code == 'en' ? 'gb' : (language.ISO6391_code == 'hi' ? 'in' : language.ISO6391_code)}}.svg" width="30"/></span>    {{language.name | translate}               
                                                    </ui-select-choices>
                                                </ui-select>        
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
    <?php endif; ?>


                        </div>
<?php endif; ?>


            </div>
        </div>
                <div class="main-menu">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <nav class="navbar navbar-expand-lg mr-auto">
                                <div class="navbar-collapse collapse" id="main-menu">
                                    <ul class="navbar-nav mr-auto">
                                        <li class="nav-item" ng-repeat="item in main_menu" ng-if="!item.sub && item.active == 1" ng-click="activateMenuItem(item.id)" ng-class="{'active':item.id == activeMenuItem}">
                                            <a ng-click="reloadRoute(item.url)" class="nav-link" href="{{item.url}}">
                                                <span ng-if="item.id === '2'"><?= __('Casino'); ?>  <span class="sr-only" ng-cloak>(current)</span></span>
                                                <span ng-if="item.id !== '2'" ng-cloak>{{item.title | translate}}</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="faq"><?= __('FAQ'); ?></a>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
    </div>
</header>-->

<script>
//    $(function () {
//        $(window).on("scroll", function () {
//            if ($(window).scrollTop() > 100) {
//                $(".header").addClass("scrolled");
//            } else {
//                //remove the background property so it comes transparent again (defined in your css)
//                $(".header").removeClass("scrolled");
//            }
//        });
//    });
</script>