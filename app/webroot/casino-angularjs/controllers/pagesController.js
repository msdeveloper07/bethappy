'use strict';
ctrls.controller('pagesController', ['__', '$scope', '$routeParams', '$route', '$location', '$window', 'appSettings', 'usersService', 'pagesService', 'SweetAlert', function (__, $scope, $routeParams, $route, $location, $window, appSettings, usersService, pagesService, SweetAlert) {

        $scope.websiteEmail = __.websiteEmail;
        $scope.websitePhone = __.websitePhone;

        $scope.Contact = {};
        $scope.contactUs = function () {
            //console.log($scope.Contact);
            usersService.contactUs($scope.Contact).then(function (response) {
                console.log(response.data);
                if (response.data.status === 'success') {
                    SweetAlert.swal({
                        title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
                        text: response.data.message,
                        type: response.data.status,
                        confirmButtonColor: "#F27474",
                        confirmButtonText: "Close",
                        closeOnConfirm: true,
                    });

                    //$scope.Contact = '';
                } else {
                    console.log(response);
                }
            }, function (error) {
                console.log(error);
            });
        };


        $scope.getPage = function (url) {
            pagesService.getPage(url).then(function (response) {
                console.log(response);
                if (response.data.status === 'success') {
                    $scope.Page = response.data.data;
                } else {
                    console.log(response);
                }
            }, function (error) {
                console.log(error);
            });
        };



        /**** Get Pages ****/
//        $scope.loadPages = function () {
//            __.get({
//                url: '/Views/getPages/',
//                success: function (data) {
//                    try {
//                        if (data.response === 'success') {
//                            $scope.Pages = data.data;
//                        } else {
//                            __.notify(data.response, data.msg, null, null);
//                        }
//                    } catch (ex) {
//                        console.log("data parsing error: ", ex);
//                    }
//                }, error: function (error) {
//                    console.log(error);
//                }
//            });
//        }();

    }]);