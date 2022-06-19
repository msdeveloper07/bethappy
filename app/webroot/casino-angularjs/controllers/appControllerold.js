userBalance = (function () {
    var that = {};
    that.balance = null;
    return that;
})();
app.controller('appController', function ($rootScope, $scope, $sce, $window, $location, $routeParams, $filter, $timeout, $interval, $http, $uibModal, $translate, SweetAlert, WizardHandler, appSettings, usersService, countriesService, currenciesService, languagesService, gamesService, sliderService) {

//    $scope.currentYear = new Date().getFullYear();
//    $rootScope.Years = [];
//    for (var i = $scope.currentYear - 100; i < $scope.currentYear - 18; i++) {
//        $rootScope.Years.push({id: i, name: i});
//    }
//
//    $rootScope.Months = [
//        {id: 1, numeric_value: '01', name: 'January'},
//        {id: 2, numeric_value: '02', name: 'February'},
//        {id: 3, numeric_value: '03', name: 'March'},
//        {id: 4, numeric_value: '04', name: 'April'},
//        {id: 5, numeric_value: '05', name: 'May'},
//        {id: 6, numeric_value: '06', name: 'June'},
//        {id: 7, numeric_value: '07', name: 'July'},
//        {id: 8, numeric_value: '08', name: 'August'},
//        {id: 9, numeric_value: '09', name: 'September'},
//        {id: 10, numeric_value: '10', name: 'October'},
//        {id: 11, numeric_value: '11', name: 'November'},
//        {id: 12, numeric_value: '12', name: 'December'}
//    ];
    $scope.serviceBase = appSettings.serviceBaseUri;
    $scope.websiteEmail = appSettings.websiteEmail;
    $scope.websitePhone = appSettings.websitePhone;
    $rootScope.location = $location;

    $scope.depositsURL = $sce.trustAsResourceUrl(appSettings.serviceBaseUri + 'payments/deposits/index');
    $scope.withdrawsURL = $sce.trustAsResourceUrl(appSettings.serviceBaseUri + 'payments/withdraws/index');

    $scope.Loader = false;
    $scope.currentDate = new Date();

    $scope.SortBy = [
        {id: 0, numeric_value: '0', name: 'Most popular', sort_type: '-open_stats'},
        {id: 1, numeric_value: '1', name: 'A-Z', sort_type: 'name'},
        {id: 2, numeric_value: '2', name: 'Z-A', sort_type: '-name'},
    ]



    $rootScope.setStatus = function (status) {
        switch (status) {
            case "Pending":
            case "Processing":
            case 0:
            case "0":
                return "badge-warning";
                break;
            case "Completed":
            case "Win":
            case "Refund":
            case 1:
            case "1":
                return "badge-success";
                break;
            case "Declined":
            case "Failed":
            case "Cancelled":
            case "Bet":
            case "Rollback":
            case - 1:
            case "-1":
                return "badge-danger";
                break;

        }
    };
    $rootScope.ItemsPerPage = 10;
    $rootScope.paginate = function (TotalRecords, CurrentPage) {
        var paginate = {};
        paginate.totalRecords = TotalRecords;
        paginate.currentPage = CurrentPage;
        paginate.maxSize = 5;
        paginate.itemsPerPage = $rootScope.ItemsPerPage;
        return paginate;
    };

    $rootScope.isMobile = function () {
        var is_mobile = false;
        (function (a) {
            if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4)))
                is_mobile = true;
        })(navigator.userAgent || navigator.vendor || window.opera);
        return is_mobile;
    }

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
    }

    $rootScope.showSignUpPassword = false;
    $rootScope.showSignInPassword = false;
    $rootScope.showPasswordConfirm = false;
    //if no element, element is password, else element is confirm password
    $rootScope.toggleShowPassword = function (element) {
        //console.log(element);
        switch (element) {
            case 'confirm':
                $rootScope.showPasswordConfirm = !$rootScope.showPasswordConfirm;
                break;
            case 'sign-in':
                $rootScope.showSignInPassword = !$rootScope.showSignInPassword;
                break;
            case 'sign-up':
                $rootScope.showSignUpPassword = !$rootScope.showSignUpPassword;
                break;
            default:
                $rootScope.showPassword = !$rootScope.showPassword;
                break;
        }

    }

//email format pattern to match against user input
    $rootScope.emailFormat = /^[a-z]+[a-z0-9._]+@[a-z]+\.[a-z.]{2,5}$/;
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
            templateUrl: 'casino-angularjs/views/partials/gdpr.html',
            controller: 'modalController',
            scope: $scope,
//            size: 'xl',
            backdrop: 'static'
        });
    }

    $scope.cancelGDPRModal = function () {
        modalGame.dismiss('cancel');
    }

    $scope.openGameModal = function (task) {
        modalGame = $uibModal.open({
            animation: false,
            templateUrl: 'casino-angularjs/views/partials/game.html',
            controller: 'modalController',
            scope: $scope,
//            size: 'xl',
            backdrop: 'static',
            windowClass: 'game-modal',
        });
    }

    $scope.cancelGameModal = function () {
        modalGame.dismiss('cancel');
    }
    $scope.openSignUpModal = function (task) {
        modalSignIn = $uibModal.open({
            animation: false,
            templateUrl: 'casino-angularjs/views/partials/sign-up.html',
            controller: 'modalController',
            scope: $scope,
            size: 'md',
            backdrop: 'static'
        });
    }

    $scope.cancelSignUpModal = function () {
        modalSignIn.dismiss('cancel');
    }

    $scope.openSignInModal = function (task) {
        modalSignUp = $uibModal.open({
            animation: false,
            templateUrl: 'casino-angularjs/views/partials/sign-in.ctp',
            controller: 'modalController',
            scope: $scope,
            size: 'md',
            backdrop: 'static'
        });
    }

    $scope.cancelSignInModal = function () {
        modalSignUp.dismiss('cancel');
    }


    $scope.openContactModal = function (task) {
        modalContact = $uibModal.open({
            animation: false,
            templateUrl: 'casino-angularjs/views/partials/contact.html',
            controller: 'modalController',
            scope: $scope,
            size: 'md',
            backdrop: 'static'
        });
    }

    $scope.cancelContactModal = function () {
        modalContact.dismiss('cancel');
    }
    $scope.openForgotPasswordModal = function (task) {
        modalForgotPassword = $uibModal.open({
            animation: false,
            templateUrl: 'casino-angularjs/views/partials/forgot-password.html',
            controller: 'modalController',
            scope: $scope,
            size: 'md',
            backdrop: 'static'
        });
    }

    $scope.cancelForgotPassword = function () {
        modalForgotPassword.dismiss('cancel');
    }

    $scope.openResetPasswordModal = function (task) {
        modalResetPassword = $uibModal.open({
            animation: false,
            templateUrl: 'casino-angularjs/views/partials/reset-password.html',
            controller: 'modalController',
            scope: $scope,
            size: 'md',
            backdrop: 'static'
        });
    }

    $scope.cancelResetPassword = function () {
        modalResetPassword.dismiss('cancel');
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
    
    
    
    
    $scope.getLanguages= function () {
        languagesService.getLanguages().then(function (response) {
//console.log(response);
            if (response.status === 200) {
                $scope.Languages = response.data;
            } else {
                console.log(response);
            }
        });
    }();

    $scope.Slides = [];
    $scope.sliderInterval = 5000;
    $scope.activeSlide = 0;

    $scope.getSlides = function () {
        sliderService.getSlides().then(function (response) {
            //console.log(response);
            if (response.status === 200) {
                $scope.Slides = response.data;
            }

        }, function (error) {
            console.log(error);
        });
    }();


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
        //console.log($scope.userSignUp);
        $scope.userSignUp.language_id = $scope.userSignUpLanguage.selected.id;
        $scope.userSignUp.language = $scope.userSignUpLanguage.selected.name;
        $scope.userSignUp.currency_id = $scope.userSignUpCurrency.selected.id;
        $scope.userSignUp.currency = $scope.userSignUpCurrency.selected.name;
        $scope.userSignUp.country_id = $scope.userSignUpCountry.selected.id;
        $scope.userSignUp.country = $scope.userSignUpCountry.selected.alpha2_code;
        $scope.userSignUp.gender = $scope.userSignUpGender;
        $scope.userSignUp.date_of_birth = $filter('date')($scope.userSignUp.date_of_birth, "yyyy-MM-dd");
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
                    $location.path("/"); //redirect to home page     
                });

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
    };
    $scope.userSignIn = {};
    $scope.signIn = function () {
        usersService.signIn($scope.userSignIn).then(function (response) {
            console.log(response);
            if (response.data.status === 'success') {
                $scope.cancelSignInModal();
                $scope.userSignIn = {};
                $window.localStorage['isAuth'] = true;
                $window.localStorage['user'] = JSON.stringify(response.data.data);
                //$translate.use(language_code);
                //$window.location.reload();
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
    };

    $scope.userSignInInline = {};
    $scope.signInInline = function () {
        usersService.signIn($scope.userSignInInline).then(function (response) {
            console.log(response);
            if (response.data.status === 'success') {
                $scope.userSignInInline = {};
                $window.localStorage['isAuth'] = true;
                $window.localStorage['user'] = JSON.stringify(response.data.data);
                $window.location.reload();
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


    $scope.isAuthenticated = function () {
        usersService.isAuthenticated().then(function (response) {
            //console.log(response);
            if (response.data.status === 'success') {
                $window.localStorage['isAuth'] = true;
                $window.localStorage['user'] = JSON.stringify(response.data.data);
                $scope.User = JSON.parse($window.localStorage['user']);
            }

            if (response.data.status === 'error') {
                $window.localStorage['isAuth'] = false;
                $window.localStorage.clear();
                $scope.User = {};
            }

        });
    }();


    $rootScope.isAuthenticated = $window.localStorage['isAuth'] ? true : false;

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
    }
    //console.log($window.localStorage);
    $scope.forgotPassword = function (email) {
        usersService.forgotPassword(email).then(function (response) {
            //console.log(response.data);
            if (response.data.status === 'success') {
                $scope.ForgotPassword = {};
                $scope.cancelForgotPassword();
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

    }

    $scope.resetPassword = function () {
        if ($routeParams.reset_token)
            $scope.ResetPassword.confirmation_code = $routeParams.reset_token;

        usersService.resetPassword($scope.ResetPassword).then(function (response) {
            //console.log(response.data);
            if (response.data.status === 'success') {
                $scope.ResetPassword = {};
                $scope.cancelResetPassword();
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

            } else {
                console.log(response);
            }
        }, function (error) {
            console.log(error);
        });
    };



    $scope.verifyEmail = function (token) {
        usersService.verifyEmail(token).then(function (response) {
            //console.log(response.data);
            if (response.data.status === 'success') {

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


            } else {
                console.log(response);
            }
        }, function (error) {
            console.log(error);
        });
    };



    $scope.loadGame = function (game_id, fun_play) {
        $scope.Loader = true;
        //console.log($rootScope.isAuthenticated);
        if (!fun_play)
            fun_play = 'false';

        if ((fun_play == 'false' || fun_play == false) && $rootScope.isAuthenticated == false) {
            $scope.openSignInModal();
        } else {
            console.log('load game...');
            $location.path('/game/' + game_id + '/' + fun_play);
//            console.log(game_id, fun_play);
            gamesService.loadGame(game_id, fun_play).then(function (response) {
//                console.log(response);
                if (response.data.status === 'success') {
                    $scope.GameiFrame = $sce.trustAsHtml(response.data.content);
                    $scope.Game = response.data.game;
                    $scope.Favorite = response.data.favorite;
                    $scope.Loader = false;
                }
            }, function (error) {
                console.log(error);
            });
        }
    };


//    $scope.$watch('Favorite', function (newValue, oldValue, scope) {
//        console.log(newValue);
//        console.log(oldValue);
//    });
//    var url = $location.path().split('/');
//    $scope.firstParameter = url[2];
//    $scope.secondParameter = url[3];
//    console.log(url);
//
//    console.log($routeParams.game_id);
//    if ($routeParams.game_id && $routeParams.fun_play) {
//        var game_id = $routeParams.game_id;
//        var fun_play = $routeParams.fun_play;
//        console.log(game_id, fun_play);
//        $scope.loadGame(game_id, fun_play);
////        $scope.Loader = true;
//
////        gamesService.loadGame(game_id, fun_play).then(function (response) {
////            console.log(response);
////            if (response.data.status === 'success') {
////                $scope.GameiFrame = $sce.trustAsHtml(response.data.content);
////                $scope.Game = response.data.game;
////                $scope.Favorite = response.data.favorite;
////                $scope.Loader = false;
////            }
////        }, function (error) {
////            console.log(error);
////        });
//    }


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
            //$scope.isGameFavorite();
//            $scope.Favorite = true;
        }

    });


    //$scope.user_balance = 0;
    $scope.pingPlayer = function () {
        usersService.pingPlayer().then(function (response) {
            //console.log(response);
            if (response.data.status == 'success') {
                //console.log('PING');
                $scope.user_balance = response.data.data.Balance;
                $scope.user_bonus_balance = response.data.data.Bonus.balance;
                //console.log($scope.user_balance);
//                __.loadUserData(response.data);
//
//                $scope.loginfor = response.data.data.Loginfor;
//                $scope.terms = response.data.data.Terms;
//                $scope.mga = response.data.data.MGA;
//                $scope.Currency = response.data.data.Currency;
            }
        });


    };
    $interval(function () {
        $scope.pingPlayer();
    }, 3000);

    $scope.$watch("user_balance", function (newValue, oldValue) {
        $scope.user_balance = newValue;
    });


//    console.log($window.localStorage);
//    if ($window.localStorage.getItem('isFirstLoad') || $window.localStorage.length === 0) {
//        $scope.openGDPRModal();
//        $window.localStorage['isFirstLoad'] = true;
//    }
//    console.log($window.localStorage.getItem('isFirstLoad'));
    //console.log($window.localStorage);
});

app.controller('modalController', function ($scope, $uibModalInstance) {
    $scope.closeSignUpModal = function () {
        $uibModalInstance.dismiss('cancel');
    };
    $scope.closeSignInModal = function () {
        $uibModalInstance.dismiss('cancel');
    };
    $scope.closeContactModal = function () {
        $uibModalInstance.dismiss('cancel');
    };
    $scope.closeForgotPasswordModal = function () {
        $uibModalInstance.dismiss('cancel');
    };

    $scope.closeGDPRModal = function () {
        $uibModalInstance.dismiss('cancel');
    };
});
app.filter('toDate', function () {
    return function (items) {
        return new Date(items);
    };
});
app.filter('limitFrom', function () {
    return function (input, start) {
        if (input) {
            start = +start;
            return input.slice(start);
        }
        return [];
    };
});
app.filter('replace', [function () {
        return function (input, from, to) {
            if (input === undefined) {
                return;
            }
            var regex = new RegExp(from, 'g');
            return input.replace(regex, to);
        };
    }]);
app.filter('trustAsHtml', ['$sce', function ($sce) {
        return function (text) {
            return $sce.trustAsHtml(text);
        };
    }]);
app.filter('ellipsis', function () {
    return function (text, length) {
        if (text.length > length) {
            return text.substr(0, length) + "...";
        }
        return text;
    }
});
app.filter('passwordCharacterCount', [function () {
        return function (value, peak) {
            value = angular.isString(value) ? value : 0;
            peak = isFinite(peak) ? peak : 7;
            return (value === '' ? 0 : value) && value.length;
        };
    }]);
app.filter('range', function () {
    return function (input, min, max) {
        min = parseInt(min);
        max = parseInt(max);
        for (var i = min; i <= max; i++)
            input.push(i);
        return input;
    };
});
app.filter('orderObjectBy', function () {
    return function (input, attribute) {
        if (!angular.isObject(input))
            return input;

        var array = [];
        for (var objectKey in input) {
            array.push(input[objectKey]);
        }

        array.sort(function (a, b) {
            a = parseInt(a[attribute]);
            b = parseInt(b[attribute]);
            return a < b ? -1 : a > b ? 1 : 0;
        });
        return array;
    }
});
app.filter('currencyFilter', ['$filter', '$sce',
    function ($filter, $sce) {
        return function (input, curr) {

            var formattedValue = $filter('currency')(input, curr);
            return $sce.trustAsHtml(formattedValue);
        }
    }]);

app.directive('errSrc', function () {
    return {
        link: function (scope, element, attrs) {
            var defaultSrc = attrs.src;
            element.bind('error', function () {
                if (attrs.errSrc) {
                    element.attr('src', attrs.errSrc);
                } else if (attrs.src) {
                    element.attr('src', defaultSrc);
                }
            });
        }
    }
});
app.directive('fullscreenButton', function ($document) {
    return {
        restrict: 'EA',
        scope: {
            target: '@fullscreenButton',
            isFullscreen: '=?',
            onChange: '&'
        },
        link: function (scope, element, attrs) {
            var doc = $document[0];
            var onChange = function () {
                scope.$apply(function () {
                    scope.isFullscreen = !!(document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement);
                    scope.onChange({fullscreen: scope.isFullscreen});
                });
            }

            angular.forEach(['', 'moz', 'webkit'], function (prefix) {
                $document.bind(prefix + 'fullscreenchange', onChange);
            });

            element.bind('click', function (event) {
                event && event.preventDefault();

                var el;
                if (scope.target) {
                    if (angular.isString(scope.target)) {
                        el = doc.querySelector(scope.target);
                    }
                } else {
                    el = doc.documentElement;
                }

                if (!el)
                    return;

                if (el.requestFullscreen) {
                    el.requestFullscreen();
                } else if (el.mozRequestFullScreen) {
                    el.mozRequestFullScreen();
                } else if (el.webkitRequestFullscreen) {
                    el.webkitRequestFullscreen();
                } else {
                    window.open(doc.location.href, '_blank');
                }
            });
        }
    }
});
app.directive('uniqueField', function (__, $http) {
    var toId;
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, elem, attr, ctrl) {
            //when the scope changes, check the field.
            scope.$watch(attr.ngModel, function (value) {
                // if there was a previous attempt, stop it.
                if (toId)
                    clearTimeout(toId);
                // start a new attempt with a delay to keep it from
                toId = setTimeout(function () {
                    // call to some API that echo "1" or echo "0"

                    $http({
                        url: 'users/isFieldUnique/' + attr.name + '/' + value,
                        method: "GET",
                    }).then(function (response) {
                        console.log(response);
                        if (response.data.status == 'success') {
                            ctrl.$setValidity('uniqueField', true);
                        } else {
                            ctrl.$setValidity('uniqueField', false);
                        }
                    }, function (response) {
                        console.log(response);
                    });
                }, 100);
            })
        }
    }
});
app.directive('passwordStrengthMeter', ['zxcvbn', function (zxcvbn) {
        return {
            // restrict to only attribute and class
            restrict: 'AC',
            // use the NgModelController
            require: 'ngModel',
            // add the NgModelController as a dependency to your link function
            link: function ($rootScope, $element, $attrs, ngModelCtrl) {
                $element.on('blur change keydown', function (evt) {
                    $rootScope.$evalAsync(function ($rootScope) {
                        // update the $rootScope.password with the element's value
                        var pwd = $rootScope.password = $element.val();
                        // resolve password strength score using zxcvbn service
                        $rootScope.passwordStrength = pwd ? (pwd.length > 7 && zxcvbn.score(pwd) || 0) : null;
                        // define the validity criterion for password-strength-meter constraint
                        ngModelCtrl.$setValidity('passwordStrengthMeter', $rootScope.passwordStrength >= 2);
                    });
                });
            }
        };
    }]);
app.directive("digitalClock", function ($timeout, dateFilter) {
    return {
        restrict: 'E',
        link: function (scope, iElement) {
            (function updateClock() {
                iElement.text(dateFilter(new Date(), 'HH:mm'));
                $timeout(updateClock, 1000);
            })();
        }
    };
});
app.factory('__', ['$rootScope', '$timeout', '$window', '$location', '$sce', '$http', 'WizardHandler', function ($rootScope, $timeout, $window, $location, $sce, $http, WizardHandler) {
        "use strict";
        var that = {};
        return that;


//        that.globalUserData = null;
//
//        that.loadUserData = function (userData) {
//            that.globalUserData = userData;
//        }
//
//
//        $rootScope.$on('userBalance', function () {
//            console.log('on userBalance');
//            $rootScope.$broadcast('loadUserBalance');
//        });



    }]);
app.factory('zxcvbn', [function () {
        return {
            score: function () {
                var compute = zxcvbn.apply(null, arguments);
                return compute && compute.score;
            }
        };
    }]);
