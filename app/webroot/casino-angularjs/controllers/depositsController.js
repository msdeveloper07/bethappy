'use strict';
ctrls.controller('depositsController', ['__', '$rootScope', '$scope', '$routeParams', '$route', '$location', '$window', '$sce', 'appSettings', 'usersService', function (__, $rootScope, $scope, $routeParams, $route, $location, $window, $sce, appSettings, usersService) {
        $scope.Deposits = {};
        $scope.paginateDeposits = {};
        $scope.depositsURL = $sce.trustAsResourceUrl(appSettings.serviceBaseUri + 'payments/deposits/index');


        $scope.getPlayerDeposits = function (page) {
            usersService.getPlayerDeposits(page).then(function (response) {
                console.log(response.data);
                if (response.data.status === 'success') {
                    $scope.Deposits = response.data.data;
                    $scope.DepositsSum = response.data.sum;
                    $scope.TotalDeposits = response.data.total;
                    $scope.TotalsByStatus = response.data.totals_by_status;
                    $scope.paginateDeposits = __.paginate(response.data.total, response.data.page, response.data.items_per_page);
                    console.log($scope.paginateDeposits);
                } else {
                    console.log(response);
                }
            }, function (error) {
                console.log(error);
            });

        };
        $scope.getPlayerDeposits(1);

        $scope.pageDepositsChanged = function () {
            $scope.getPlayerDeposits($scope.paginateDeposits.currentPage);
        };

        ($scope.getPlayerDepositsStatistics = function () {
            usersService.getPlayerDepositsStatistics().then(function (response) {
                //console.log(response);
                if (response.data.status === 'success') {
                    $scope.TotalCount = response.data.total_count;
                    $scope.TotalSum = response.data.total_sum;
                    $scope.SumsByStatus = response.data.sums_by_status;
                    $scope.CountsByStatus = response.data.counts_by_status;
                    $scope.Percentages = response.data.percentages;
                } else {
                    console.log(response);
                }
            }, function (error) {
                console.log(error);
            });

        })();
        
        
        $scope.setStatus = function(status){
            return __.setStatus(status);
        };

    }]);
