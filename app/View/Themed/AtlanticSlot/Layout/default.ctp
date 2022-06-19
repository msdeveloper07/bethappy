<script type="text/javascript">
    'use strict'
    var app = angular.module('SportsBook', [
            'app.directives',
            'app.controllers',
            'ngAnimate',
            'ngRoute',
            'ngSanitize',
            'ngDialog',
            'ngFileUpload',
            'infinite-scroll',
            'ngMaterial',
            'ui.bootstrap',
            'oitozero.ngSweetAlert',
            'mgo-angular-wizard',
            'chart.js'
    ]);
    var ctrls = angular.module('app.controllers', []);
    var drtvs = angular.module('app.directives', [
            'ngAnimate'
    ]);
    app.config(['$routeProvider', '$locationProvider', function ($routeProvider, $locationProvider) {
    $routeProvider
            //home
            .when('/', {templateUrl: '/Views/view/home', controller: 'HomeController'})
            .when('/home', {templateUrl: '/Views/view/home', controller: 'HomeController'})
            .when('/games/:category', {templateUrl: '/Views/view/home', controller: 'HomeController'})
            .when('/game/:gameid', {templateUrl: '/Views/view/game', controller: 'HomeController'})
            .when('/game/:gameid/:fun', {templateUrl: '/Views/view/game', controller: 'HomeController'})

            //sign up and validate email
            .when('/sign-up', {templateUrl: '/Views/view/sign-up', controller: 'RegistrationController'})
            .when('/tools/verify-email/:code', {template: " ", controller: 'ToolsController'})
            .when('/tools/:type/:code', {templateUrl: "/Views/view/reset-password", controller: 'ToolsController'})

            //static and dynamic pages
            .when('/page/:pageurl', {templateUrl: '/Views/view/page', controller: 'PagesController'})
            .when('/contact-us', {templateUrl: '/Views/view/contact-us', controller: 'PagesController'})
            .when('/payments', {templateUrl: '/Views/view/payments', controller: 'PagesController'})
            .when('/terms-of-use', {templateUrl: '/Views/view/terms-of-use', controller: 'PagesController'})

            //account pages
            .when('/account/profile', {templateUrl: '/Views/view/profile', controller: 'ProfileController'})
            .when('/account/kyc', {templateUrl: '/Views/view/kyc', controller: 'KYCController'})
            .when('/account/limits', {templateUrl: '/Views/view/limits', controller: 'LimitsController'})
            .when('/account/bonuses', {templateUrl: '/Views/view/bonuses', controller: 'BonusesController'})
            //.when('/account/history', {templateUrl: '/Views/view/history', controller: 'HistoryController'})
            .when('/account/deposit', {templateUrl: '/Views/view/deposit', controller: ''})
            .when('/account/withdraw', {templateUrl: '/Views/view/withdraw', controller: ''})



            .when('/nobonus', {templateUrl: '/Views/view/home', controller: 'HomeController',
                    resolve: {
                    data1: function ($rootScope) {
                    return $rootScope.showAdvanced('click', 'nobonus', $rootScope.controllers.Bonus, 'nobonus');
                    }
                    }
            })

            .otherwise({redirectTo: '/'});
    }]);</script>


<script type="text/javascript" src="/Layout/controllers/base.js?v=0.14"></script>
<script type="text/javascript" src="/Layout/controllers/header.js?v=0.12"></script>
<script type="text/javascript" src="/Layout/controllers/footer.js?v=0.11"></script>

<script type="text/javascript" src="/Layout/controllers/slider.js"></script>
<script type="text/javascript" src="/Layout/controllers/banner.js"></script>

<script type="text/javascript" src="/Layout/directives/dialog.js"></script>
<script type="text/javascript" src="/Layout/js/ng-infinite-scroll.js"></script>
<script type="text/javascript" src="/Layout/js/validator.min.js"></script>

<!--Pages controllers-->
<script type="text/javascript" src="/Layout/controllers/pages.js"></script>
<script type="text/javascript" src="/Layout/controllers/home.js?v=0.12"></script>
<script type="text/javascript" src="/Layout/controllers/register.js"></script>
<script type="text/javascript" src="/Layout/controllers/tools.js"></script>

<!--Account controllers-->
<script type="text/javascript" src="/Layout/controllers/profile.js"></script>
<script type="text/javascript" src="/Layout/controllers/kyc.js"></script>
<script type="text/javascript" src="/Layout/controllers/limits.js"></script>
<script type="text/javascript" src="/Layout/controllers/bonuses.js"></script>
<script type="text/javascript" src="/Layout/controllers/history.js"></script>
<body ng-mousemove="mouseTimeout()">
    <div class="app-header" ng-controller="HeaderController"><ng-include src="'/Views/view/header'"></ng-include></div>
    <div class="app-content" ng-view></div>
    <div class="app-footer" ng-controller="FooterController"><ng-include src="'/Views/view/footer'"></ng-include></div>
</body>