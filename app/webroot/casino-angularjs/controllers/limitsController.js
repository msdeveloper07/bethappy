'use strict';
ctrls.controller('limitsController', ['$rootScope', '$scope', '$routeParams', '$route', '$location', '$window', 'SweetAlert', 'usersService', function ($rootScope, $scope, $routeParams, $route, $location, $window, SweetAlert, usersService) {

        console.log($rootScope.isAuthenticated);

        $scope.getPlayerLimits = function () {
            usersService.getPlayerLimits().then(function (response) {
                //console.log(response.data);
                if (response.data.status === 'success') {
                    $scope.Limits = response.data.data.limits;
                    $scope.LimitTypes = response.data.data.limit_types;
                    $scope.SessionLimitTypes = response.data.data.session_limit_types;
                } else {
                    console.log(response);
                }
            }, function (error) {
                console.log(error);
            });
        }


        $scope.saveDepositLimit = function () {
            var data = {limitCategory: "deposit", limitType: $scope.UserLimits.depositLimitType, limitAmount: $scope.UserLimits.depositLimitAmount};

            usersService.setPlayerLimits(data).then(function (response) {
                //console.log(response);
                if (response.data.status === 'success') {
                    SweetAlert.swal({
                        title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
                        text: response.data.message,
                        type: response.data.status,
                        confirmButtonColor: "#A5DC86",
                        confirmButtonText: "OK",
                        closeOnConfirm: true,
                    }, function () {
                        $scope.getPlayerLimits();
                        //$window.location.reload();
                    });

                } else {
                    SweetAlert.swal({
                        title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
                        text: response.data.message,
                        type: response.data.status,
                        confirmButtonColor: "#F27474",
                        confirmButtonText: "Close",
                        closeOnConfirm: true,
                    });
                }
            }, function (error) {
                console.log(error);
            });
        };



        $scope.saveSessionLimit = function () {
            var data = {limitCategory: "session", limitType: $scope.UserLimits.sessionLimitType, limitAmount: $scope.UserLimits.sessionLimitAmount};
            usersService.setPlayerLimits(data).then(function (response) {
                //console.log(response);
                if (response.data.status === 'success') {
                    SweetAlert.swal({
                        title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
                        text: response.data.message,
                        type: response.data.status,
                        confirmButtonColor: "#A5DC86",
                        confirmButtonText: "OK",
                        closeOnConfirm: true,
                    }, function () {
                        $scope.getPlayerLimits();
                        //$window.location.reload();
                    });

                } else {
                    SweetAlert.swal({
                        title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
                        text: response.data.message,
                        type: response.data.status,
                        confirmButtonColor: "#F27474",
                        confirmButtonText: "Close",
                        closeOnConfirm: true,
                    });
                }
            }, function (error) {
                console.log(error);
            });
        };

        $scope.saveWagerLimit = function () {
            var data = {limitCategory: "wager", limitType: $scope.UserLimits.wagerLimitType, limitAmount: $scope.UserLimits.wagerLimitAmount};

            usersService.setPlayerLimits(data).then(function (response) {
                //console.log(response);
                if (response.data.status === 'success') {
                    SweetAlert.swal({
                        title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
                        text: response.data.message,
                        type: response.data.status,
                        confirmButtonColor: "#A5DC86",
                        confirmButtonText: "OK",
                        closeOnConfirm: true,
                    }, function () {
                        $scope.getPlayerLimits();
                        //$window.location.reload();
                    });

                } else {
                    SweetAlert.swal({
                        title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
                        text: response.data.message,
                        type: response.data.status,
                        confirmButtonColor: "#F27474",
                        confirmButtonText: "Close",
                        closeOnConfirm: true,
                    });
                }
            }, function (error) {
                console.log(error);
            });
        };

        $scope.saveLossLimit = function () {
            var data = {limitCategory: "loss", limitType: $scope.UserLimits.lossLimitType, limitAmount: $scope.UserLimits.lossLimitAmount};
            usersService.setPlayerLimits(data).then(function (response) {
                //console.log(response);
                if (response.data.status === 'success') {
                    SweetAlert.swal({
                        title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
                        text: response.data.message,
                        type: response.data.status,
                        confirmButtonColor: "#A5DC86",
                        confirmButtonText: "OK",
                        closeOnConfirm: true,
                    }, function () {
                        $scope.getPlayerLimits();
                        //$window.location.reload();
                    });

                } else {
                    SweetAlert.swal({
                        title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
                        text: response.data.message,
                        type: response.data.status,
                        confirmButtonColor: "#F27474",
                        confirmButtonText: "Close",
                        closeOnConfirm: true,
                    });
                }
            }, function (error) {
                console.log(error);
            });
        };

        $scope.saveSelfExclutionLimit = function () {
            var data = {limitCategory: "selfexclusion", limitType: "selfexclusion", limitAmount: $scope.UserLimits.selfExclutionLimitPeriod};
            usersService.setPlayerLimits(data).then(function (response) {
                //console.log(response);
                if (response.data.status === 'success') {
                    SweetAlert.swal({
                        title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
                        text: response.data.message,
                        type: response.data.status,
                        confirmButtonColor: "#A5DC86",
                        confirmButtonText: "OK",
                        closeOnConfirm: true,
                    }, function () {
                        $window.location.reload();
                    });

                } else {
                    SweetAlert.swal({
                        title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
                        text: response.data.message,
                        type: response.data.status,
                        confirmButtonColor: "#F27474",
                        confirmButtonText: "Close",
                        closeOnConfirm: true,
                    });
                }
            }, function (error) {
                console.log(error);
            });
        };

        $scope.deleteAccount = function () {
            var data = {limitCategory: "deleteaccount", limitType: "deleteaccount", limitAmount: 1};
            usersService.setPlayerLimits(data).then(function (response) {
                console.log(response);
                if (response.data.status === 'success') {
                    SweetAlert.swal({
                        title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
                        text: response.data.message,
                        type: response.data.status,
                        confirmButtonColor: "#A5DC86",
                        confirmButtonText: "OK",
                        closeOnConfirm: true,
                    }, function () {
                        $window.location.reload();
                    });

                } else {
                    SweetAlert.swal({
                        title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
                        text: response.data.message,
                        type: response.data.status,
                        confirmButtonColor: "#F27474",
                        confirmButtonText: "Close",
                        closeOnConfirm: true,
                    });
                }
            }, function (error) {
                console.log(error);
            });
        };



        $scope.cancelPlayerLimit = function (limit_id) {
            usersService.cancelPlayerLimit(limit_id).then(function (response) {
                //console.log(response);
                if (response.data.status === 'success') {
                    SweetAlert.swal({
                        title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
                        text: response.data.message,
                        type: response.data.status,
                        confirmButtonColor: "#A5DC86",
                        confirmButtonText: "OK",
                        closeOnConfirm: true,
                    }, function () {
                        $scope.getPlayerLimits();
                        //$window.location.reload();
                    });

                } else {
                    SweetAlert.swal({
                        title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
                        text: response.data.message,
                        type: response.data.status,
                        confirmButtonColor: "#F27474",
                        confirmButtonText: "Close",
                        closeOnConfirm: true,
                    });
                }
            }, function (error) {
                console.log(error);
            });
        };

    }]);
