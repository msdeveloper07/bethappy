<script type="text/javascript">
    'use strict'
    var app = angular.module('CasinoApp', ['app.directives', 'app.controllers', 'ngRoute']);
    var ctrls = angular.module('app.controllers', []);
    var drtvs = angular.module('app.directives');
//    app.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
//        $routeProvider
////            .when('/dashboard', {templateUrl: '/Dashboard/admin_index', controller: 'DashboardController'})
//            .otherwise({ redirectTo: '/admin' });
//    }]);
</script>

<script type="text/javascript" src="/js/controllers/dashboard.js"></script>
<script type="text/javascript" src="/js/controllers/header.js"></script>
<script type="text/javascript" src="/js/controllers/footer.js"></script>

<!--<body ng-mousemove="mouseTimeout()">
    <div ng-controller="HeaderController"><ng-include src="'/Views/view/header'"></ng-include></div>
    <div class="content-wrap"><div ng-view></div></div>
    <div ng-controller="FooterController"><ng-include src="'/Views/view/footer'"></ng-include></div>
</body>-->