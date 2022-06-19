'use strict';
ctrls.controller('gameplayController', ['__', '$rootScope', '$scope', '$routeParams', '$route', '$location', '$window', 'usersService', function (__, $rootScope, $scope, $routeParams, $route, $location, $window, usersService) {
        $scope.gamesPlayed = {};
        $scope.paginateGameLogs = {};
        $scope.casinoTransactions = {};
        $scope.paginateCasinoTransactions = {};

        var items_per_page = __.ItemsPerPage;

        ($scope.getGameLogs = function (page) {
            usersService.getGameLogs(page).then(function (response) {
                //console.log(response.data);
                if (response.data.status === 'success') {
                    $scope.gamesLogs = response.data.data;
                    $scope.gamesPlayed = response.data.games_played;
                    $scope.paginateGameLogs = __.paginate(response.data.total, response.data.page, response.data.items_per_page);
                } else {
                    console.log(response);
                }
            }, function (error) {
                console.log(error);
            });

        })(1);

        $scope.pageGameLogsChanged = function () {
            $scope.getGameLogs($scope.paginateGameLogs.currentPage);
        };


        ($scope.getPlayerFavoriteGames = function (page) {
            usersService.getPlayerFavoriteGames(page).then(function (response) {
                //console.log(response.data);
                if (response.data.status === 'success') {
                    $scope.Favorites = response.data.data;
                    $scope.totalFavorites = response.data.total;
                    $scope.paginateFavorites = __.paginate(response.data.total, response.data.page, response.data.items_per_page);
                } else {
                    console.log(response);
                }
            }, function (error) {
                console.log(error);
            });

        })(1);

        $scope.pageFavoritesChanged = function () {
            $scope.getPlayerFavoriteGames($scope.paginateFavorites.currentPage);
        };




        ($scope.sumByTransactionType = function () {
            usersService.sumByTransactionType().then(function (response) {
                //console.log(response.data);
                if (response.data.status === 'success') {
                    $scope.casinoSummary = response.data.data;
                } else {
                    console.log(response);
                }
            }, function (error) {
                console.log(error);
            });

        })();



        ($scope.getCasinoTransactions = function (type = 'real', items_per_page, page) {
            usersService.getCasinoTransactions(type, items_per_page, page).then(function (response) {
                //console.log(response.data);
                if (response.data.status === 'success') {
                    $scope.casinoTransactions = response.data.data;
                    $scope.paginateCasinoTransactions = __.paginate(response.data.total, response.data.page);
                    //console.log($scope.paginateCasinoTransactions);
                } else {
                    console.log(response);
                }
            }, function (error) {
                console.log(error);
            });

        })('real', items_per_page, 1);

        $scope.pageCasinoTransactionsChanged = function () {
            //console.log('change');
            //console.log($scope.paginateCasinoTransactions);
            $scope.getCasinoTransactions('real', items_per_page, $scope.paginateCasinoTransactions.currentPage);
        };



        ($scope.getBonusTransactions = function (type = 'bonus', items_per_page, page) {
            usersService.getCasinoTransactions(type, items_per_page, page).then(function (response) {
                //console.log(response.data);
                if (response.data.status === 'success') {
                    $scope.bonusTransactions = response.data.data;
                    $scope.paginateBonusTransactions = __.paginate(response.data.total, response.data.page);
                } else {
                    console.log(response);
                }
            }, function (error) {
                console.log(error);
            });

        })('bonus', items_per_page, 1);

        $scope.pageBonusTransactionsChanged = function () {
            $scope.getBonusTransactions('bonus', items_per_page, $scope.paginateBonusTransactions.currentPage);
        };

        $scope.setStatus = function (status) {
            return __.setStatus(status);
        };
    }]);
