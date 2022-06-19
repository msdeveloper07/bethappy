'use strict';
ctrls.controller('kycController', ['__', '$rootScope', '$scope', '$routeParams', '$route', '$location', '$window', '$timeout', 'SweetAlert', 'Upload', 'usersService', function (__, $rootScope, $scope, $routeParams, $route, $location, $window, $timeout, SweetAlert, Upload, usersService) {
        $scope.progress = {};
        $scope.saveKYC = function (kycFiles, type) {
            if (kycFiles && kycFiles.length) {
                Upload.upload({
                    url: 'player/uploadPlayerKYC/' + type,
                    arrayKey: '',
                    data: kycFiles
                }).then(function (response) {
                    console.log(response);
                    if (response.data.status === 'success') {
                        SweetAlert.swal({
                            title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
                            text: response.data.message,
                            type: response.data.status,
                            confirmButtonColor: "#F27474",
                            confirmButtonText: "Close",
                            closeOnConfirm: true,
                        });
                        $scope.progress = {};
                        $scope.kycIdentityFiles = {};
                        $scope.kycAddressFiles = {};
                        $scope.kycFundingFiles = {};

                        $scope.getPlayerKYC();
//                        $timeout(function () {
//                            $window.location.reload();
//                        }, 2000);

                    } else {
                        SweetAlert.swal({
                            title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
                            text: response.data.message,
                            type: response.data.status,
                            confirmButtonColor: "#F27474",
                            confirmButtonText: "Close",
                            closeOnConfirm: true,
                        });
                        console.log(response);
                    }
                    $scope.kycFiles = "";

                    $scope.getPlayerKYC();
                }, function (response) {

                    console.log(response);

                    //if (response.status > 0)
                    //__.notify('error', response.status + ': ' + response.data, null, null);
                    //scope.errorMessage = response.msg;
                }, function (evt) {
                    if (type === 1) {
                        $scope.progress.identity = Math.min(100, parseInt(100.0 * evt.loaded / evt.total));
                    }

                    if (type === 2) {
                        $scope.progress.address = Math.min(100, parseInt(100.0 * evt.loaded / evt.total));
                    }

                    if (type === 3) {
                        $scope.progress.funding = Math.min(100, parseInt(100.0 * evt.loaded / evt.total));
                    }

                });
            }
        };

        $scope.getPlayerKYC = function () {
            usersService.getPlayerKYC().then(function (response) {
                console.log(response.data.data);
                if (response.data.status === 'success') {
                    $scope.Identity = response.data.data.identification;
                    $scope.Address = response.data.data.address;
                    $scope.Funding = response.data.data.funding;
                } else {
                    console.log(response);
                }
            }, function (error) {
                console.log(error);
            });
        };
        $scope.getPlayerKYC();
        
            $scope.setStatus = function(status){
            return __.setStatus(status);
        };

    }]);
