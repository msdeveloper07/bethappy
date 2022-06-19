<script type="text/javascript">
    'use strict';
    var app = angular.module('CasinoApp', ['app.directives', 'app.controllers',
        'ngRoute',
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
        'ngFileUpload'
    ]);

    var ctrls = angular.module('app.controllers', []);
    var drtvs = angular.module('app.directives', ['ngAnimate']);
    var fltrs = angular.module('app.filters', []);
    var fctry = angular.module('app.factories', []);
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

    app.config(['$routeProvider', '$locationProvider', function ($routeProvider, $locationProvider) {
            $routeProvider
                    .when('/', {templateUrl: '/Views/view/home', controller: 'gamesController'})
                    .when('/home', {templateUrl: '/Views/view/home', controller: 'gamesController'})
                    .when('/games', {templateUrl: '/Views/view/games', controller: 'gamesController'})
                    .when('/games/categories/:category', {templateUrl: '/Views/view/games', controller: 'gamesController'})
                    .when('/games/providers/:brand', {templateUrl: '/Views/view/games', controller: 'gamesController'})
                    .when('/game/:game_id/:fun_play', {templateUrl: '/Views/view/game', controller: 'gamesController'})
                    //account links
                    .when('/account/reset-password/:reset_token', {templateUrl: '/Views/view/', controller: 'Controller'})
                    .when('/account/verify-email/:verification_token', {templateUrl: '/Views/view/', controller: 'usersController'})
                    .when('/account/profile', {templateUrl: '/Views/view/profile', controller: 'usersController'})
                    .when('/account/bonuses', {templateUrl: '/Views/view/bonuses', controller: 'usersController'})
                    .when('/account/limits', {templateUrl: '/Views/view/limits', controller: 'usersController'})
                    .when('/account/kyc', {templateUrl: '/Views/view/kyc', controller: 'usersController'})
                    .when('/account/deposits', {templateUrl: '/Views/view/deposits', controller: 'usersController'})
                    .when('/account/withdraws', {templateUrl: '/Views/view/withdraws', controller: 'usersController'})
                    .when('/account/gameplay', {templateUrl: '/Views/view/gameplay', controller: 'usersController'})
                    .when('/account/favorites', {templateUrl: '/Views/view/favorites', controller: 'usersController'})
                    //pages links
                    .when('/contact-us', {templateUrl: '/Views/view/', controller: 'pagesController'})
                    .when('/bet-happy-affiliates', {templateUrl: '/Views/view/', controller: 'pagesController'})
                    .when('/terms-of-use', {templateUrl: '/Views/view/', controller: 'pagesController'})
                    .when('/privacy-policy', {templateUrl: '/Views/view/', controller: 'pagesController'})
                    .when('/refunds-policy', {templateUrl: '/Views/view/', controller: 'pagesController'})
                    .when('/withdrawals-policy', {templateUrl: '/Views/view/', controller: 'pagesController'})
                    .when('/deposits-policy', {templateUrl: '/Views/view/', controller: 'pagesController'})
                    .when('/responsible-gaming', {templateUrl: '/Views/view/', controller: 'pagesController'})
                    .when('/anti-money-laundering-policy', {templateUrl: '/Views/view/', controller: 'pagesController'})
                    .when('/gdpr-policy', {templateUrl: '/Views/view/', controller: 'pagesController'})
                    .when('/faq', {templateUrl: '/Views/view/', controller: 'pagesController'})
                    .when('/payment-methods', {templateUrl: '/Views/view/', controller: 'pagesController'})
                    .when('/sitemap', {templateUrl: '/Views/view/', controller: 'gamesController'})


//                    .when('/game/:gameid', {templateUrl: '/Views/view/game', controller: 'HomeController'})
//                    .when('/game/:gameid/:fun', {templateUrl: '/Views/view/game', controller: 'HomeController'})
//                    .when('/page/:pageurl', {templateUrl: '/Views/view/page', controller: 'PageController'})
//                    .when('/register', {templateUrl: '/Views/view/register', controller: 'RegistrationController'})
//                    .when('/confirm/:code', {controller: 'PassRecoveryController'})
//                    .when('/recovery/:type/', {template: " ", controller: 'PassRecoveryController'})
//                    .when('/recovery/:type/:confirmcode', {template: " ", controller: 'PassRecoveryController'})
//
//
//                    .when('/tc', {templateUrl: '/Views/view/home', controller: 'HomeController',
//                        resolve: {
//                            data1: function ($rootScope) {
//                                return $rootScope.showAdvanced('click', 'page', $rootScope.controllers.Page, 'general-terms-conditions');
//                            }
//                        }
//                    })
//                    .when('/contact-us', {templateUrl: '/Views/view/home', controller: 'HomeController',
//                        resolve: {
//                            data1: function ($rootScope) {
//                                return $rootScope.showAdvanced('click', 'page', $rootScope.controllers.Page, 'contact-us');
//                            }
//                        }
//                    })
//                    .when('/nobonus', {templateUrl: '/Views/view/home', controller: 'HomeController',
//                        resolve: {
//                            data1: function ($rootScope) {
//                                return $rootScope.showAdvanced('click', 'nobonus', $rootScope.controllers.Bonus, 'nobonus');
//                            }
//                        }
//                    })
//                    .when('/deposit', {templateUrl: '/Views/view/home', controller: 'HomeController',
//                        resolve: {
//                            data1: function ($rootScope) {
//                                return $rootScope.showAdvanced('click', 'account', $rootScope.controllers.Top, 'deposits');
//                            }
//                        }
//                    })
                    .otherwise({redirectTo: '/'});

            /* clear hashtag '#' from urls - doesnt work well */
//            $locationProvider.html5Mode(true);
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

    app.filter('toDate', function () {
        return function (items) {
            return new Date(items);
        };
    });
    app.filter('limitFrom', function () {
        return function (input, start) {
            if (input) {
                start = +start;
                return input.slice(start);
            }
            return [];
        };
    });
    app.filter('replace', [function () {
            return function (input, from, to) {
                if (input === undefined) {
                    return;
                }
                var regex = new RegExp(from, 'g');
                return input.replace(regex, to);
            };
        }]);
    app.filter('trustAsHtml', ['$sce', function ($sce) {
            return function (text) {
                return $sce.trustAsHtml(text);
            };
        }]);
    app.filter('ellipsis', function () {
        return function (text, length) {
            if (text.length > length) {
                return text.substr(0, length) + "...";
            }
            return text;
        };
    });
    app.filter('passwordCharacterCount', [function () {
            return function (value, peak) {
                value = angular.isString(value) ? value : 0;
                peak = isFinite(peak) ? peak : 7;
                return (value === '' ? 0 : value) && value.length;
            };
        }]);
    app.filter('range', function () {
        return function (input, min, max) {
            min = parseInt(min);
            max = parseInt(max);
            for (var i = min; i <= max; i++)
                input.push(i);
            return input;
        };
    });
    app.filter('orderObjectBy', function () {
        return function (input, attribute) {
            if (!angular.isObject(input))
                return input;

            var array = [];
            for (var objectKey in input) {
                array.push(input[objectKey]);
            }

            array.sort(function (a, b) {
                a = parseInt(a[attribute]);
                b = parseInt(b[attribute]);
                return a < b ? -1 : a > b ? 1 : 0;
            });
            return array;
        };
    });
    app.filter('currencyFilter', ['$filter', '$sce',
        function ($filter, $sce) {
            return function (input, curr) {

                var formattedValue = $filter('currency')(input, curr);
                return $sce.trustAsHtml(formattedValue);
            }
        }]);

    drtvs.directive('errSrc', function () {
        return {
            link: function (scope, element, attrs) {
                var defaultSrc = attrs.src;
                element.bind('error', function () {
                    if (attrs.errSrc) {
                        element.attr('src', attrs.errSrc);
                    } else if (attrs.src) {
                        element.attr('src', defaultSrc);
                    }
                });
            }
        };
    });
    drtvs.directive('fullscreenButton', function ($document) {
        return {
            restrict: 'EA',
            scope: {
                target: '@fullscreenButton',
                isFullscreen: '=?',
                onChange: '&'
            },
            link: function (scope, element, attrs) {
                var doc = $document[0];
                var onChange = function () {
                    scope.$apply(function () {
                        scope.isFullscreen = !!(document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement);
                        scope.onChange({fullscreen: scope.isFullscreen});
                    });
                };

                angular.forEach(['', 'moz', 'webkit'], function (prefix) {
                    $document.bind(prefix + 'fullscreenchange', onChange);
                });

                element.bind('click', function (event) {
                    event && event.preventDefault();

                    var el;
                    if (scope.target) {
                        if (angular.isString(scope.target)) {
                            el = doc.querySelector(scope.target);
                        }
                    } else {
                        el = doc.documentElement;
                    }

                    if (!el)
                        return;

                    if (el.requestFullscreen) {
                        el.requestFullscreen();
                    } else if (el.mozRequestFullScreen) {
                        el.mozRequestFullScreen();
                    } else if (el.webkitRequestFullscreen) {
                        el.webkitRequestFullscreen();
                    } else {
                        window.open(doc.location.href, '_blank');
                    }
                });
            }
        };
    });
    drtvs.directive('uniqueField', function ($http) {
        var toId;
        return {
            restrict: 'A',
            require: 'ngModel',
            link: function (scope, elem, attr, ctrl) {
                //when the scope changes, check the field.
                scope.$watch(attr.ngModel, function (value) {
                    // if there was a previous attempt, stop it.
                    if (toId)
                        clearTimeout(toId);
                    // start a new attempt with a delay to keep it from
                    toId = setTimeout(function () {
                        // call to some API that echo "1" or echo "0"

                        $http({
                            url: 'users/isFieldUnique/' + attr.name + '/' + value,
                            method: "GET",
                        }).then(function (response) {
                            console.log(response);
                            if (response.data.status == 'success') {
                                ctrl.$setValidity('uniqueField', true);
                            } else {
                                ctrl.$setValidity('uniqueField', false);
                            }
                        }, function (response) {
                            console.log(response);
                        });
                    }, 100);
                });
            }
        };
    });
    drtvs.directive('passwordStrengthMeter', ['zxcvbn', function (zxcvbn) {
            return {
                // restrict to only attribute and class
                restrict: 'AC',
                // use the NgModelController
                require: 'ngModel',
                // add the NgModelController as a dependency to your link function
                link: function ($rootScope, $element, $attrs, ngModelCtrl) {
                    $element.on('blur change keydown', function (evt) {
                        $rootScope.$evalAsync(function ($rootScope) {
                            // update the $rootScope.password with the element's value
                            var pwd = $rootScope.password = $element.val();
                            // resolve password strength score using zxcvbn service
                            $rootScope.passwordStrength = pwd ? (pwd.length > 7 && zxcvbn.score(pwd) || 0) : null;
                            // define the validity criterion for password-strength-meter constraint
                            ngModelCtrl.$setValidity('passwordStrengthMeter', $rootScope.passwordStrength >= 2);
                        });
                    });
                }
            };
        }]);
    app.factory('zxcvbn', [function () {
            return {
                score: function () {
                    var compute = zxcvbn.apply(null, arguments);
                    return compute && compute.score;
                }
            };
        }]);



</script>

<?php
//services
echo $this->Html->script('/casino-angularjs/services/headerService.js');
echo $this->Html->script('/casino-angularjs/services/footerService.js');
echo $this->Html->script('/casino-angularjs/services/usersService.js');
echo $this->Html->script('/casino-angularjs/services/countriesService.js');
echo $this->Html->script('/casino-angularjs/services/currenciesService.js');
echo $this->Html->script('/casino-angularjs/services/languagesService.js');
echo $this->Html->script('/casino-angularjs/services/gamesService.js');
echo $this->Html->script('/casino-angularjs/services/sliderService.js');
echo $this->Html->script('/casino-angularjs/services/pagesService.js');
//controllers

echo $this->Html->script('/casino-angularjs/controllers/headerController.js');
echo $this->Html->script('/casino-angularjs/controllers/footerController.js');
echo $this->Html->script('/casino-angularjs/controllers/usersController.js');
echo $this->Html->script('/casino-angularjs/controllers/pagesController.js');
echo $this->Html->script('/casino-angularjs/controllers/gamesController.js');
echo $this->Html->script('/casino-angularjs/controllers/limitsController.js');
echo $this->Html->script('/casino-angularjs/controllers/kycController.js');
echo $this->Html->script('/casino-angularjs/controllers/gameplayController.js');
echo $this->Html->script('/casino-angularjs/controllers/depositsController.js');
echo $this->Html->script('/casino-angularjs/controllers/withdrawsController.js');
echo $this->Html->script('/casino-angularjs/controllers/base.js');
?>
<body>

    <?php $this->element('casino_header'); ?>

    <main class="app-content">
        <?php $this->element('casino_slider'); ?>           
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

    <?php $this->element('casino_footer'); ?>
</body>