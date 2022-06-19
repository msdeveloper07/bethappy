'use strict';
ctrls.controller('footerController', ['$translate', '$rootScope', '$scope', '$routeParams', '$route', '$location', '$window', '$interval', '__', 'footerService', 'usersService', function ($translate, $rootScope, $scope, $routeParams, $route, $location, $window, $interval, __, footerService, usersService) {

        /**** Load Main Menu ****/
        ($scope.getFooterMenus = function () {
            footerService.getFooterMenus().then(function (response) {
                console.log(response);
                if (response.status === 200) {
                    $scope.footer_menus = response.data;
                } else {
                    console.log(response);
                }
            }, function (error) {
                console.log(error);
            });
        })();

    }]);
