<script type="text/javascript">
    'use strict'
    var app = angular.module('CasinoApp',
            ['ngRoute',
                'ngTouch',
                'ngSanitize',
                'ngAnimate',
                'ui.bootstrap',
                'ui.directives',
                'ui.select',
                'slickCarousel',
                'oitozero.ngSweetAlert',
                'mgo-angular-wizard',
                'ss.ngIntlTelInput',
                'pascalprecht.translate',
                'ngFileUpload']);

//101bethappy.com
//21bethappy.com
//77bethappy.com
//121bethappy.com
//7bethappy.com


    var protocol = 'https://';
    if (window.location.href.indexOf("www") != -1) {
        protocol = 'https://www.';
    }

    if (window.location.href.indexOf("101bethappy") != -1) {
        var serviceBase = protocol + '101bethappy.com/';
    } else if (window.location.href.indexOf("21bethappy") != -1) {
        var serviceBase = protocol + '21bethappy.com/';
    } else if (window.location.href.indexOf("77bethappy") != -1) {
        var serviceBase = protocol + '77bethappy.com/';
    } else if (window.location.href.indexOf("121bethappy") != -1) {
        var serviceBase = protocol + '121bethappy.com/';
    } else if (window.location.href.indexOf("7bethappy") != -1) {
        var serviceBase = protocol + '7bethappy.com/';
    } else if (window.location.href.indexOf("stackstaging.com") !== -1) {
        var serviceBase = protocol + 'cshelponline-com.stackstaging.com/';
    } else if (window.location.href.indexOf("localhost") !== -1) {
        var serviceBase = 'http://localhost/';
    } else {
        var serviceBase = protocol + 'bethappy.com/';
    }
//

//app.config(['$httpProvider', function ($httpProvider) {
//        $httpProvider.defaults.headers.common = {};
//        $httpProvider.defaults.headers.post = {};
//        $httpProvider.defaults.headers.put = {};
//        $httpProvider.defaults.headers.patch = {};
//    }
//]);

    app.config(function ($routeProvider, $locationProvider) {
        $locationProvider.html5Mode(true);
        $routeProvider.when("/", {
            controller: "gamesController",
            templateUrl: "casino-angularjs/views/home.html",
            title: 'Home'
        });

        $routeProvider.when("/casino", {
            controller: "gamesController",
            templateUrl: "casino-angularjs/views/home.html",
            title: 'Home'
        });
        $routeProvider.when("/games", {
            controller: "gamesController",
            templateUrl: "casino-angularjs/views/games.html",
            title: 'Games',
        });
        $routeProvider.when("/games/categories/:category", {
            controller: "gamesController",
            templateUrl: "casino-angularjs/views/games.html",
            title: 'Categories'

        });
        $routeProvider.when("/games/providers/:brand", {
            controller: "gamesController",
            templateUrl: "casino-angularjs/views/games.html",
            title: 'Providers',
        });

        $routeProvider.when("/game/:game_id/:fun_play", {
            controller: 'gamesController',
            templateUrl: "casino-angularjs/views/game.html"
        });
        $routeProvider.when("/contact-us", {
            controller: "pagesController",
            templateUrl: "casino-angularjs/views/contact-us.html",
            title: 'Contact us',
        });
        //pages
        $routeProvider.when("/bet-happy-affiliates", {
            controller: "pagesController",
            templateUrl: "casino-angularjs/views/affiliates-program.html",
            title: 'Bet Happy Affiliates',
        });

        $routeProvider.when("/terms-of-use", {
            controller: "pagesController",
            templateUrl: "casino-angularjs/views/terms-of-use.html",
            title: "Terms of use"
        });

        $routeProvider.when("/privacy-policy", {
            controller: "pagesController",
            templateUrl: "casino-angularjs/views/privacy-policy.html",
            title: "Privacy policy"
        });

        $routeProvider.when("/refunds-policy", {
            controller: "pagesController",
            templateUrl: "casino-angularjs/views/refunds-policy.html",
            title: "Refunds policy"
        });

        $routeProvider.when("/withdrawals-policy", {
            controller: "pagesController",
            templateUrl: "casino-angularjs/views/withdrawals-policy.html",
            title: "Withdrawals policy"
        });

        $routeProvider.when("/deposits-policy", {
            controller: "pagesController",
            templateUrl: "casino-angularjs/views/deposits-policy.html",
            title: "Deposits policy"
        });

        $routeProvider.when("/responsible-gaming", {
            controller: "pagesController",
            templateUrl: "casino-angularjs/views/responsible-gaming.html",
            title: "Responsible gaming"
        });
        $routeProvider.when("/anti-money-laundering-policy", {
            controller: "pagesController",
            templateUrl: "casino-angularjs/views/anti-money-laundering-policy.html",
            title: "Anti-money laundering policy"
        });
        $routeProvider.when("/gdpr-policy", {
            controller: "pagesController",
            templateUrl: "casino-angularjs/views/gdpr-policy.html",
            title: "GDPR policy"
        });

        $routeProvider.when("/faq", {
            controller: "pagesController",
            templateUrl: "casino-angularjs/views/faq.html",
            title: "FAQ"
        });


//    $routeProvider.when("/payment-methods", {
//        controller: "pagesController",
//        templateUrl: "casino-angularjs/views/payments.html",
//        title: "Payment methods"
//    });


//
//    $routeProvider.when("/complaints", {
//        controller: "pagesController",
//        templateUrl: "casino-angularjs/views/complaints.html",
//        title: 'Complaints',
//    });

//    $routeProvider.when("/jackpots", {
//        controller: "pagesController",
//        templateUrl: "casino-angularjs/views/jackpots.html",
//        title: 'Jackpots',
//    });
//    $routeProvider.when("/about-us", {
//        controller: "pagesController",
//        templateUrl: "casino-angularjs/views/about-us.html",
//        title: 'About us',
//    });
//
        //account

        $routeProvider.when("/account/reset-password/:reset_token", {
            controller: "usersController",
            templateUrl: "/casino-angularjs/views/home.html",
            requiresAuth: false
        });
        $routeProvider.when("/account/verify-email/:verification_token", {
            controller: "usersController",
            templateUrl: "/casino-angularjs/views/home.html",
            requiresAuth: false
        });
        $routeProvider.when("/account/profile", {
            controller: "usersController",
            templateUrl: "casino-angularjs/views/account/profile.html",
            requiresAuth: true,
            title: 'Profile'
        });
        $routeProvider.when("/account/profile/edit", {
            controller: "usersController",
            templateUrl: "casino-angularjs/views/account/edit-profile.html",
            requiresAuth: true,
            title: 'Edit Profile'
        });
        $routeProvider.when("/account/bonuses", {
            controller: "usersController",
            templateUrl: "casino-angularjs/views/account/bonuses.html",
            requiresAuth: true,
            title: 'Bonuses'
        });
        $routeProvider.when("/account/limits", {
            controller: "limitsController",
            templateUrl: "casino-angularjs/views/account/limits.html",
            requiresAuth: true,
            title: 'Limits'
        });
        $routeProvider.when("/account/kyc", {
            controller: "kycController",
            templateUrl: "casino-angularjs/views/account/kyc.html",
            requiresAuth: true,
            title: 'KYC'
        });
        $routeProvider.when("/account/deposits", {
            controller: "depositsController",
            templateUrl: "casino-angularjs/views/account/deposits.html",
            requiresAuth: true,
            title: 'Deposits'
        });
        $routeProvider.when("/account/withdraws", {
            controller: "withdrawsController",
            templateUrl: "casino-angularjs/views/account/withdraws.html",
            requiresAuth: true,
            title: 'Withdraws'
        });
        $routeProvider.when("/account/gameplay", {
            controller: "gameplayController",
            templateUrl: "casino-angularjs/views/account/gameplay.html",
            requiresAuth: true,
            title: 'Gameplay'
        });

        $routeProvider.when("/account/favorites", {
            controller: "gameplayController",
            templateUrl: "casino-angularjs/views/account/favorites.html",
            requiresAuth: true,
            title: 'Favorites'
        });
        $routeProvider.when("/sitemap", {
            controller: "gamesController",
            templateUrl: "casino-angularjs/views/sitemap.html",
            title: 'Sitemap'
        });
        $routeProvider.otherwise({redirectTo: "/casino"});
    }
    );
    app.config(['$translateProvider', function ($translateProvider) {
            // add translation tables
            $translateProvider.translations('en_us', translationsEN);
            $translateProvider.translations('de_de', translationsDE);
//        $translateProvider.useStaticFilesLoader({
//            prefix: '/i18n/',
//            suffix: '.js'
//        });
            $translateProvider.fallbackLanguage('en_us');
            $translateProvider.preferredLanguage('en_us');
        }]);
    app.constant('appSettings', {
        serviceBaseUri: serviceBase,
        websiteEmail: 'support@bethappy.com',
        websitePhone: '+44 555 6666'
    });
    app.run(['$rootScope', '$location', '$window', 'usersService', function ($rootScope, $location, $window, usersService) {
            //do not allow access to account pages unless user is logged in
            $rootScope.$on("$routeChangeStart", function (event, next, current) {
                if ($window.localStorage['isAuth'] !== 'true' && next.$$route.requiresAuth) {
                    $location.path("/");
                }
            });
        }]);
    app.run(['$rootScope', function ($rootScope) {
            $rootScope.$on('$routeChangeSuccess', function (event, current, previous) {
                $rootScope.title = current.$$route.title;
//            $rootScope.description = current.$$route.description;
//            $rootScope.keywords = current.$$route.keywords;
            });

            $rootScope.$on('$translatePartialLoaderStructureChanged', function () {
                $translate.refresh();
            });
        }]);
</script>

<?php ?>
<body ng-controller="appController">

    <?= $this->element('casino_header'); ?>

    <main class="app-content">
        <?= $this->element('casino_slider'); ?>           
        <div class="container-xl">
            <?php if ($this->Session->check('Auth.User.id')): ?>
                <div class="row">
                    <div class="col-md-12 col-lg-3">
                        <?= $this->element('casino_sidebar'); ?>
                    </div>
                    <div class="col-md-12 col-lg-9">
                        <div ng-view autoscroll="true"></div>
                    </div>
                </div>
            <?php else: ?>
                <div ng-view autoscroll="true"></div>
            <?php endif; ?>
        </div>
    </main>

    <?= $this->element('casino_footer'); ?>



</body>