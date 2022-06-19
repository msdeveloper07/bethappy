'use strict';
ctrls.controller('usersController', ['__', '$rootScope', '$scope', '$routeParams', '$route', '$location', '$uibModal', '$window', 'SweetAlert', 'usersService', 'gamesService', function (__, $rootScope, $scope, $routeParams, $route, $location, $uibModal, $window, SweetAlert, usersService, gamesService) {

        console.log(__.globalUserData);

//        var modalResetPassword = '';
//
//        $scope.openResetPasswordModal = function (task) {
//            console.log('reset password modal');
//            modalResetPassword = $uibModal.open({
//                animation: false,
//                templateUrl: 'casino-angularjs/views/partials/reset-password.html',
//                controller: 'modalController',
//                scope: $scope,
//                size: 'md',
//                backdrop: 'static'
//            });
//        }
//
//        $scope.cancelResetPassword = function () {
//            modalResetPassword.dismiss('cancel');
//        }
//
//
//        console.log($routeParams.reset_token);
//
//        if ($routeParams.reset_token) {
//            //check if token expired
//            usersService.checkTokenExpiration($routeParams.reset_token).then(function (response) {
//                console.log(response.data);
//                if (response.data.status === 'error') {
//                    SweetAlert.swal({
//                        title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
//                        text: response.data.message,
//                        type: response.data.status,
//                        confirmButtonColor: "#F27474",
//                        confirmButtonText: "Close",
//                        closeOnConfirm: true,
//                    });
//                } else {
//                    $scope.openResetPasswordModal();
//                    console.log(response);
//                }
//            }, function (error) {
//                console.log(error);
//            });
//        }
//  
//


        ($scope.getPlayerLimits = function () {
            usersService.getPlayerLimits().then(function (response) {
                //console.log(response.data);
                if (response.data.status === 'success') {
                    $scope.ActiveLimits = response.data.data.active_limits;
                } else {
                    console.log(response);
                }
            }, function (error) {
                console.log(error);
            });
        })();

        ($scope.getPlayerWithdraws = function (page) {
            usersService.getPlayerWithdraws(page).then(function (response) {
                //console.log(response.data);
                if (response.data.status === 'success') {
                    $scope.WithdrawsSum = response.data.sum;
                    $scope.TotalWithdraws = response.data.total;
                } else {
                    console.log(response);
                }
            }, function (error) {
                console.log(error);
            });
        })(1);

        ($scope.getPlayerDeposits = function (page) {
            usersService.getPlayerDeposits(page).then(function (response) {
                //console.log(response.data);
                if (response.data.status === 'success') {
                    $scope.DepositsSum = response.data.sum;
                    $scope.TotalDeposits = response.data.total;
                } else {
                    console.log(response);
                }
            }, function (error) {
                console.log(error);
            });
        })(1);

        ($scope.getGameLogs = function (page) {
            usersService.getGameLogs(page).then(function (response) {
                if (response.data.status === 'success') {
                    $scope.gamesPlayed = response.data.games_played;
                } else {
                    console.log(response);
                }
            }, function (error) {
                console.log(error);
            });

        })(1);

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


        ($scope.checkPath = function () {
            if ($location.path().indexOf('verify-email') !== -1) {
                $scope.verifyEmail($routeParams.account_code);
            }
        })();




        $scope.isGameFavorite = function () {
            var game_id = $routeParams.game_id;

            gamesService.loadGame(game_id).then(function (response) {
                console.log(response);
                if (response.data.status === 'success') {
                    $scope.Favorite = response.data.favorite;
                }

            }, function (error) {
                console.log(error);
            });
        }

        $scope.$watch('Favorite', function (newVal, oldVal) {
            //console.log(newVal, oldVal);
            if (newVal) {
                //$rootScope.isGameFavorite();
//            $rootScope.Favorite = true;
            }

        });

//        console.log($rootScope.isAuthenticated);
//
//        $scope.getPlayerLimits = function () {
//
//            usersService.getPlayerLimits().then(function (response) {
//                console.log(response.data);
//                if (response.data.status === 'success') {
//                    $scope.Limits = response.data.data.limits;
//                    $scope.LimitTypes = response.data.data.limit_types;
//                    $scope.SessionLimitTypes = response.data.data.session_limit_types;
//                } else {
//
//                }
//            }, function (error) {
//                console.log(error);
//            });
//        }();

//        __.get({
//            url: '/player/getselimits/',
//            success: function (data) {
//                try {
//                    if (data.response === 'success') {
//                        $scope.dataLimits = data.data.datalimits;
//                        $scope.generalLimits = data.data.limits;
//                        $scope.loginLimits = data.data.login_limits;
//                    } else {
//                        __.notify(data.response, data.msg, null, null);
//                    }
//                } catch (ex) {
//                    console.log("data parsing error: ", ex);
//                }
//            },
//            error: function (error) {
//                console.log(error);
//            }
//        });
//        };
//        $scope.getseLimits();
    }]);
