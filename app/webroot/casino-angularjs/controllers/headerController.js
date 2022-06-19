'use strict';
ctrls.controller('headerController', ['__', '$rootScope', '$scope', '$routeParams', '$route', '$location', '$window', '$timeout', '$interval', '$filter', '$translate', '$uibModal', '$http', '$sce', 'SweetAlert', 'headerService', 'usersService', 'sliderService', 'countriesService', 'currenciesService', function (__, $rootScope, $scope, $routeParams, $route, $location, $window, $timeout, $interval, $filter, $translate, $uibModal, $http, $sce, SweetAlert, headerService, usersService, sliderService, countriesService, currenciesService) {

        $scope.websiteEmail = __.websiteEmail;
        $scope.websitePhone = __.websitePhone;
        $scope.Loader = false;

        $scope.signOut = function () {
            usersService.signOut().then(function (response) {
                //console.log(response);
                if (response.status === 200) {
                    $window.localStorage.clear('isAuth');
                    $window.localStorage.clear('user');
                }
                $window.location.href = 'https://www.bethappy.com/';
                $window.location.reload();
            });
        };

        ($scope.pingUser = function () {

            usersService.pingPlayer().then(function (response) {
                console.log(response);
                if (response.data.status == 'success') {
                    userBalance.balance = response.data.data.Balance;
                    $scope.balance = response.data.data.Balance;
                    if (response.data.Bonus) {
                        $scope.bonus_balance = response.data.data.Bonus.balance;
                    } else {
                        $scope.bonus_balance = null;
                    }

                    if ($scope.bonus_balance) {
//                        if ($scope.bonus_balance <= 0.50 && $scope.noBonusDialogShown == false) {
//                            $rootScope.ShownoBonusDialog();
//                        }
                        // Very dirty code to override player balance check 
                        userBalance.balance = response.data.data.Bonus.balance;
                    }

                    __.loadUserData(response.data.data);


                    if (response.data.data == null && $scope.balance != null) {
                        console.log('Your session has ended. Please refresh the page.');
                        $scope.signOut();
                        window.location = "/";

                    } else {
                        if (response.data.data.User != null) {
                            console.log('Your session has ended. Please refresh the page.');
                            setTimeout(function () {
                                $scope.pingUser();
                            }, 10000);
                        }
                    }
                } else {
                    if ($scope.balance != null) {
                        console.log('Your session has ended. Please refresh the page.');
                        $scope.signOut();
                        window.location = "/";
                    }
                }
            }, function (error) {
                console.log(error);
            });
        })();

        $scope.$on('loadUserData', function () {
            $scope.pingUser(true);
        });

        /**** Load Main Menu ****/
        ($scope.getMainMenu = function () {
            headerService.getMainMenu().then(function (response) {
                //console.log(response);
                if (response.status === 200) {
                    $scope.main_menu = response.data;
                } else {
                    console.log(response);
                }
            }, function (error) {
                console.log(error);
            });
        })();

        $scope.activeMenuItem = 1;
        $scope.activateMenuItem = function (id) {
            $scope.activeMenuItem = id;
        }


        $scope.getCurrencies = function () {
            currenciesService.getCurrencies().then(function (response) {
                //console.log(response);
                if (response.status === 200) {
                    $scope.Currencies = response.data;
                } else {
                    console.log(response);
                }
            });
        }();

        $scope.getCountries = function () {
            countriesService.getCountries().then(function (response) {
                //console.log(response);
                if (response.status === 200) {
                    $scope.Countries = response.data;
                } else {
                    console.log(response);
                }
            });
        }();

        $scope.language = {};
        $scope.getLanguages = function () {
            headerService.getLanguages().then(function (response) {
                //console.log(response);
                if (response.status === 200) {
                    angular.forEach(response.data, function (value, key) {
                        if (value.selected === true)
                            $scope.language.selected = value;
                    });
                    $scope.Languages = response.data;
                } else {
                    console.log(response);
                }
            }, function (error) {
                console.log(error);
            });
        };
        $scope.getLanguages();


        $scope.setLanguage = function (language_id, language_code) {
            console.log(language_id, language_code);
            headerService.setLanguage(language_id).then(function (response) {
                console.log(response);
                if (response.status === 200) {
                    $translate.use(language_code);

                    //$translate.use(language_code).then(() => $translate.refresh());
                    //$scope.getLanguages();
                    $window.location.reload();
                } else {
                    console.log(response);
                }
            }, function (error) {
                console.log(error);
            });

        };


        $scope.Slides = [];
        $scope.sliderInterval = 5000;
        $scope.activeSlide = 0;

        $scope.getSlides = function () {
            sliderService.getSlides().then(function (response) {
                //console.log(response);
                if (response.status === 200) {
                    angular.forEach(response.data, function (value, key) {
                        //console.log(value);
                        var slide = {};
                        slide.id = value.Slide.id;
                        slide.title = value.Slide.title;
                        slide.url = value.Slide.url;
                        slide.image = value.Slide.image;
                        slide.image_mobile = value.Slide.image_mobile;
                        slide.order = value.Slide.order;
                        slide.start_date = value.Slide.start_date;
                        slide.end_date = value.Slide.end_date;
                        slide.active = value.Slide.active;
                        slide.description = value.Slide.description;
//                        slide.description = $sce.trustAsHtml(value.Slide.description);

                        $scope.Slides.push(slide);

                    });
                    //console.log($scope.Slides);
                }
            }, function (error) {
                console.log(error);
            });
        };
        $scope.getSlides();




        //Sign Up date picker options
        var currentTime = new Date().getTime();
        var minAge = 1000 * 60 * 60 * 24 * 365 * 18;
        var maxAge = 1000 * 60 * 60 * 24 * 365 * 100;
        $scope.maxDate = $filter('date')(new Date(currentTime - minAge), 'yyyy-MM-dd');
        $scope.minDate = $filter('date')(new Date(currentTime - maxAge), 'yyyy-MM-dd');

        $scope.defaultDatePickerOptions = {
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            maxDate: $scope.maxDate,
            minDate: $scope.minDate
        };

        $scope.openDateOFBirthPopup = function () {
            $scope.dateOfBirthPopup.opened = true;
        };

        $scope.dateOfBirthPopup = {
            opened: false
        };


        //Sign Up telephone options
        //https://extreme-ip-lookup.com/json/
        //https://freegeoip.app/json/
        $scope.intlTelInputOptions = {
            initialCountry: "auto",
//        nationalMode: true,
//        separateDialCode: true,
            geoIpLookup: function (success, failure) {
                $http.get("https://extreme-ip-lookup.com/json/", ).then(function (response) {
                    var countryCode = (response.data && response.data.countryCode) ? response.data.countryCode : "";
                    success(countryCode);
                });
            }
        };

        $scope.showSignUpPassword = false;
        $scope.showSignInPassword = false;
        $scope.showPasswordConfirm = false;
        //if no element, element is password, else element is confirm password
        $scope.toggleShowPassword = function (element) {
            //console.log(element);
            switch (element) {
                case 'confirm':
                    $scope.showPasswordConfirm = !$scope.showPasswordConfirm;
                    break;
                case 'sign-in':
                    $scope.showSignInPassword = !$scope.showSignInPassword;
                    break;
                case 'sign-up':
                    $scope.showSignUpPassword = !$scope.showSignUpPassword;
                    break;
                default:
                    $scope.showPassword = !$scope.showPassword;
                    break;
            }

        };

//email format pattern to match against user input
        $scope.emailFormat = /^[a-z]+[a-z0-9._]+@[a-z]+\.[a-z.]{2,5}$/;
        var modalSignIn = '';
        var modalSignUp = '';
        var modalContact = '';
        var modalForgotPassword = '';
        var modalResetPassword = '';
        var modalGame = '';
        var modalGDPR = '';

        $scope.openGDPRModal = function (task) {
            modalGame = $uibModal.open({
                animation: false,
                templateUrl: 'casino-angularjs/views/partials/gdpr.ctp',
                controller: 'modalController',
                scope: $scope,
//            size: 'xl',
                backdrop: 'static'
            });
        };

        $scope.cancelGDPRModal = function () {
            modalGame.dismiss('cancel');
        };

        $scope.openGameModal = function (task) {
            modalGame = $uibModal.open({
                animation: false,
                templateUrl: 'casino-angularjs/views/partials/game.ctp',
                controller: 'modalController',
                scope: $scope,
//            size: 'xl',
                backdrop: 'static',
                windowClass: 'game-modal',
            });
        };

        $scope.cancelGameModal = function () {
            modalGame.dismiss('cancel');
        };
        $scope.openSignUpModal = function (task) {
            if (modalSignIn)
                modalSignIn.dismiss('cancel');

            modalSignUp = $uibModal.open({
                animation: false,
                templateUrl: 'casino-angularjs/views/partials/sign-up.ctp',
                controller: 'modalController',
                scope: $scope,
                size: 'md',
                backdrop: 'static'
            });
        };

        $scope.cancelSignUpModal = function () {
            modalSignUp.dismiss('cancel');
        };

        $scope.openSignInModal = function (task) {
            if (modalSignUp)
                modalSignUp.dismiss('cancel');

            modalSignIn = $uibModal.open({
                animation: false,
                templateUrl: 'casino-angularjs/views/partials/sign-in.ctp',
                controller: 'modalController',
                scope: $scope,
                size: 'md',
                backdrop: 'static'
            });
        };

        $scope.cancelSignInModal = function () {
            modalSignIn.dismiss('cancel');
        };


//        $scope.openContactModal = function (task) {
//            modalContact = $uibModal.open({
//                animation: false,
//                templateUrl: 'casino-angularjs/views/partials/contact.ctp',
//                controller: 'modalController',
//                scope: $scope,
//                size: 'md',
//                backdrop: 'static'
//            });
//        };
//
//        $scope.cancelContactModal = function () {
//            modalContact.dismiss('cancel');
//        };
        $scope.openForgotPasswordModal = function (task) {
            $scope.closeSignInModal();
            modalForgotPassword = $uibModal.open({
                animation: false,
                templateUrl: 'casino-angularjs/views/partials/forgot-password.ctp',
                controller: 'modalController',
                scope: $scope,
                size: 'md',
                backdrop: 'static'
            });
        };

        $scope.cancelForgotPasswordModal = function () {
            modalForgotPassword.dismiss('cancel');
        };

        $scope.openResetPasswordModal = function (task) {
            modalResetPassword = $uibModal.open({
                animation: false,
                templateUrl: 'casino-angularjs/views/partials/reset-password.ctp',
                controller: 'modalController',
                scope: $scope,
                size: 'md',
                backdrop: 'static'
            });
        };

        $scope.cancelResetPasswordModal = function () {
            modalResetPassword.dismiss('cancel');

        };






//        $scope.getLanguages = function () {
//            languagesService.getLanguages().then(function (response) {
//                console.log(response);
//                if (response.status === 200) {
//                    $scope.Languages = response.data;
//                } else {
//                    console.log(response);
//                }
//            });
//        }();




        $scope.Contact = {};
        $scope.ForgotPassword = {};
        $scope.ResetPassword = {};
        $scope.User = {};
        $scope.userSignUp = {};
        $scope.userSignUpCurrency = {};
        $scope.userSignUpCountry = {};
        $scope.userSignUpLanguage = {};
        $scope.userSignUpGender = 'male';
        $scope.userSignUp.terms = 1;
//    $scope.disableSignUpStep1 = true;

        $scope.setGender = function (gender) {
            $scope.userSignUpGender = gender;
        };
        $scope.validateSignUpStep1 = function (form) {

            if ($scope.userSignUp.username === 'undefuned' ||
                    $scope.userSignUp.email === 'undefuned' ||
                    $scope.userSignUp.mobile_number === 'undefuned' ||
                    $scope.userSignUpCurrency === 'undefuned' ||
                    $scope.userSignUp.password === 'undefuned' ||
                    $scope.userSignUp.confirm_password === 'undefuned') {

                return false;
            } else {
                return true;
            }
        };


        $scope.SignUp = function () {
            $scope.Loader = true;
            //console.log($scope.userSignUp);
            $scope.userSignUp.language_id = $scope.userSignUpLanguage.selected.id;
            $scope.userSignUp.language = $scope.userSignUpLanguage.selected.name;
            $scope.userSignUp.currency_id = $scope.userSignUpCurrency.selected.id;
            $scope.userSignUp.currency = $scope.userSignUpCurrency.selected.name;
            $scope.userSignUp.country_id = $scope.userSignUpCountry.selected.id;
            $scope.userSignUp.country = $scope.userSignUpCountry.selected.alpha2_code;
            $scope.userSignUp.gender = $scope.userSignUpGender;
            $scope.userSignUp.date_of_birth = $filter('date')($scope.userSignUp.date_of_birth, "yyyy-MM-dd");

            if ($location.search().btag !== null)
                $scope.userSignUp.query_params = $location.search();

            //console.log($scope.userSignUp.newsletter);

            if ($scope.userSignUp.newsletter === 'undefined')
                $scope.userSignUp.newsletter = 0;

            usersService.signUp($scope.userSignUp).then(function (response) {
                //console.log(response);

                if (response.data.status === 'success') {
                    $scope.cancelSignUpModal();

                    $scope.userSignUp = {};
                    $scope.userSignUpCurrency = {};
                    $scope.userSignUpCountry = {};
                    SweetAlert.swal({
                        title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
                        text: $sce.trustAsHtml(response.data.message),
                        type: response.data.status,
                        confirmButtonColor: "#A5DC86",
                        confirmButtonText: "OK",
                        closeOnConfirm: true,
                    }, function () {
                        //$location.path("/"); //redirect to home page     
                        $location.url("/");
                    });
                    //$location.url("/");
                } else {
                    console.log(response);
                    $scope.cancelSignUpModal();
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
            $scope.Loader = false;
        };
        $scope.userSignIn = {};
        $scope.signIn = function () {
            $scope.Loader = true;
            usersService.signIn($scope.userSignIn).then(function (response) {
                console.log(response);
                if (response.data.status === 'success') {
                    $scope.cancelSignInModal();
                    $scope.userSignIn = {};
                    $window.localStorage['isAuth'] = true;
                    $window.localStorage['user'] = JSON.stringify(response.data.data);
                    $rootScope.User = response.data.data;
                    $translate.use(response.data.data.Language.locale_code);
                    $window.location.reload();
                } else {
                    $scope.cancelSignInModal();
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

            $scope.Loader = false;
        };

//        $scope.userSignInInline = {};
//        $scope.signInInline = function () {
//            usersService.signIn($scope.userSignInInline).then(function (response) {
//                console.log(response);
//                if (response.data.status === 'success') {
//                    $scope.userSignInInline = {};
//                    $window.localStorage['isAuth'] = true;
//                    $window.localStorage['user'] = JSON.stringify(response.data.data);
//                    $window.location.reload();
//                } else {
//                    SweetAlert.swal({
//                        title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
//                        text: response.data.message,
//                        type: response.data.status,
//                        confirmButtonColor: "#F27474",
//                        confirmButtonText: "Close",
//                        closeOnConfirm: true,
//                    });
//                }
//            }, function (error) {
//                console.log(error);
//            });
//        };


//        $scope.isAuthenticated = function () {
//            usersService.isAuthenticated().then(function (response) {
//                //console.log(response);
//                if (response.data.status === 'success') {
//                    $window.localStorage['isAuth'] = true;
//                    $window.localStorage['user'] = JSON.stringify(response.data.data);
//                    $scope.User = JSON.parse($window.localStorage['user']);
//                }
//
//                if (response.data.status === 'error') {
//                    $window.localStorage['isAuth'] = false;
//                    $window.localStorage.clear();
//                    $scope.User = {};
//                }
//
//            });
//        }();
//
//
//        $scope.isAuthenticated = $window.localStorage['isAuth'] ? true : false;


        //console.log($window.localStorage);
        $scope.forgotPassword = function (email) {
            $scope.Loader = true;
            usersService.forgotPassword(email).then(function (response) {
                //console.log(response.data);
                if (response.data.status === 'success') {
                    $scope.ForgotPassword = {};
                    $scope.cancelForgotPasswordModal();
                    SweetAlert.swal({
                        title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
                        text: response.data.message,
                        type: response.data.status,
                        confirmButtonColor: "#F27474",
                        confirmButtonText: "Close",
                        closeOnConfirm: true,
                    });


                } else {
                    console.log(response);
                }
            }, function (error) {
                console.log(error);
            });
            $scope.Loader = false;
        }

        if ($routeParams.reset_token) {
            //check if token expired
            usersService.checkTokenExpiration($routeParams.reset_token).then(function (response) {
                console.log(response.data);
                if (response.data.status === 'error') {
                    SweetAlert.swal({
                        title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
                        text: response.data.message,
                        type: response.data.status,
                        confirmButtonColor: "#F27474",
                        confirmButtonText: "Close",
                        closeOnConfirm: true,
                    });
                } else {
                    $scope.openResetPasswordModal();
                    console.log(response);
                }
            }, function (error) {
                console.log(error);
            });
        }

        $scope.resetPassword = function () {
            $scope.Loader = true;
            if ($routeParams.reset_token)
                $scope.ResetPassword.confirmation_code = $routeParams.reset_token;

            usersService.resetPassword($scope.ResetPassword).then(function (response) {
                //console.log(response.data);
                if (response.data.status === 'success') {
                    $scope.ResetPassword = {};
                    $scope.cancelResetPasswordModal();
                    SweetAlert.swal({
                        title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
                        text: response.data.message,
                        type: response.data.status,
                        confirmButtonColor: "#F27474",
                        confirmButtonText: "Close",
                        closeOnConfirm: true,
                    });
                    $timeout(function () {
                        $location.path("/");
                    }, 2000);
                    $scope.Loader = false;
                } else {
                    console.log(response);
                }
            }, function (error) {
                console.log(error);
            });
        };



        $scope.verifyEmail = function (code) {
            console.log(code);
            usersService.verifyEmail(code).then(function (response) {
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
                        $location.path("/"); //redirect to home page     
                    });
                    $scope.userSignUp = {};
                } else {
                    console.log(response);

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

        console.log($routeParams);

        if ($routeParams.verification_token) {
            var token = $routeParams.verification_token;
            //check if token expired
            usersService.checkTokenExpiration(token).then(function (response) {
                console.log(response.data);
                if (response.data.status === 'error') {
                    SweetAlert.swal({
                        title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
                        text: response.data.message,
                        type: response.data.status,
                        confirmButtonColor: "#F27474",
                        confirmButtonText: "Close",
                        closeOnConfirm: true,
                    });
                } else {
                    $scope.verifyEmail(token);
                    console.log(response);
                }
            }, function (error) {
                console.log(error);
            });


        }



//        $scope.$on('loadUserBalance', function () {
//            console.log('on loadUserBalance');
//            $scope.pingPlayer(true);
//        });


    }]);

ctrls.controller('modalController', function ($rootScope, $uibModalInstance) {
    $rootScope.closeSignUpModal = function () {
        $uibModalInstance.close();
        //$uibModalInstance.dismiss('cancel');
    };
    $rootScope.closeSignInModal = function () {
        $uibModalInstance.close();
        //$uibModalInstance.dismiss('cancel');
    };
    $rootScope.closeContactModal = function () {
        $uibModalInstance.dismiss('cancel');
    };
    $rootScope.closeForgotPasswordModal = function () {
        $uibModalInstance.close();
        //$uibModalInstance.dismiss('cancel');
    };
    $rootScope.closeResetPasswordModal = function () {
        $uibModalInstance.close();
        //$uibModalInstance.dismiss('cancel');
    };
    $rootScope.closeGDPRModal = function () {
        $uibModalInstance.dismiss('cancel');
    };
});