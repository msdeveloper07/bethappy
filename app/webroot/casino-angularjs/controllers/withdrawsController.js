'use strict';
ctrls.controller('withdrawsController', ['__', '$rootScope', '$scope', '$routeParams', '$route', '$location', '$window', '$sce', 'appSettings', 'usersService', function (__, $rootScope, $scope, $routeParams, $route, $location, $window, $sce, appSettings, usersService) {
        $scope.Withdraws = {};
        $scope.withdrawsURL = $sce.trustAsResourceUrl(appSettings.serviceBaseUri + 'payments/withdraws/index');

        $scope.getPlayerWithdraws = function (page) {
            usersService.getPlayerWithdraws(page).then(function (response) {
                //console.log(response.data);
                if (response.data.status === 'success') {
                    $scope.Withdraws = response.data.data;
                    $scope.WithdrawsSum = response.data.sum;
                    $scope.TotalWithdraws = response.data.total;
                    $scope.TotalsByStatus = response.data.totals_by_status;
                    $scope.paginateWithdraws = __.paginate(response.data.total, response.data.page, response.data.items_per_page);
                } else {
                    console.log(response);
                }
            }, function (error) {
                console.log(error);
            });

        };

        $scope.getPlayerWithdraws(1);

        $scope.pageWithdrawsChanged = function () {
            $scope.getPlayerWithdraws($scope.paginateWithdraws.currentPage);
        };


        ($scope.getPlayerWithdrawsStatistics = function () {
            usersService.getPlayerWithdrawsStatistics().then(function (response) {
                console.log(response);
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

        $scope.setStatus = function (status) {
            return __.setStatus(status);
        };

    }]);
