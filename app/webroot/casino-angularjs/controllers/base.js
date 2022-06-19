userBalance = (function () {
    var that = {};
    that.balance = null;
    return that;
})();
app.factory('__', ['$rootScope', '$timeout', '$window', '$location', '$sce', '$http', '$uibModal', 'WizardHandler', 'SweetAlert', 'appSettings', 'usersService', 'countriesService', 'currenciesService', 'languagesService', 'gamesService', 'sliderService', function ($rootScope, $timeout, $window, $location, $sce, $http, $uibModal, WizardHandler, SweetAlert, appSettings, usersService, countriesService, currenciesService, languagesService, gamesService, sliderService) {
        "use strict";
        var that = {};
        console.log(appSettings);
        that.serviceBase = appSettings.serviceBaseUri;
        that.websiteEmail = appSettings.websiteEmail;
        that.websitePhone = appSettings.websitePhone;
        that.location = $location;

        that.currentDate = new Date();


      
        that.SortBy = [
            {id: 0, numeric_value: '0', name: 'Most popular', sort_type: '-open_stats'},
            {id: 1, numeric_value: '1', name: 'A-Z', sort_type: 'name'},
            {id: 2, numeric_value: '2', name: 'Z-A', sort_type: '-name'}
        ];

        that.setStatus = function (status) {
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
        that.ItemsPerPage = 10;
        that.paginate = function (TotalRecords, CurrentPage) {
            var paginate = {};
            paginate.totalRecords = TotalRecords;
            paginate.currentPage = CurrentPage;
            paginate.maxSize = 5;
            paginate.itemsPerPage = that.ItemsPerPage;
            return paginate;
        };

        that.isMobile = function () {
            var is_mobile = false;
            (function (a) {
                if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4)))
                    is_mobile = true;
            })(navigator.userAgent || navigator.vendor || window.opera);
            return is_mobile;
        };

        $rootScope.verifyEmail = function (token) {
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



        //$rootScope.user_balance = 0;
        $rootScope.pingPlayer = function () {
            usersService.pingPlayer().then(function (response) {
                //console.log(response);
                if (response.data.status == 'success') {
                    //console.log('PING');
                    $rootScope.user_balance = response.data.data.Balance;
                    $rootScope.user_bonus_balance = response.data.data.Bonus.balance;
                    //console.log($rootScope.user_balance);
//                __.loadUserData(response.data);
//
//                $rootScope.loginfor = response.data.data.Loginfor;
//                $rootScope.terms = response.data.data.Terms;
//                $rootScope.mga = response.data.data.MGA;
//                $rootScope.Currency = response.data.data.Currency;
                }
            });


        };
//        $interval(function () {
//            $rootScope.pingPlayer();
//        }, 3000);

        $rootScope.$watch("user_balance", function (newValue, oldValue) {
            $rootScope.user_balance = newValue;
        });

//CURRENTLY NOT USED 
//    $rootScope.currentYear = new Date().getFullYear();
//    $rootScope.Years = [];
//    for (var i = $rootScope.currentYear - 100; i < $rootScope.currentYear - 18; i++) {
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

        //games route has route parameters, set games menu item active when paramaters are present
//        $rootScope.$on('$locationChangeStart', function (event, next, current) {
////            console.log($location.$$path);
//            if ($location.$$path.includes('contact-us')) {
//                $scope.activateMenuItem(50);
//            }
//            if ($location.$$path.includes('jackpots')) {
//                $scope.activateMenuItem(52);
//            }
//            if ($location.$$path.includes('games')) {
//                $scope.activateMenuItem(2);
//            }
//            if ($location.$$path.includes('categories')) {
//                $scope.activateMenuItem(2);
//            }
//            if ($location.$$path.includes('providers')) {
//                $scope.activateMenuItem(2);
//            }
//
//            if ($location.$$path.includes('games') && $location.$$path.includes('categories') && $location.$$path.includes('live-casino')) {
//                $scope.activateMenuItem(3);
//            }
//
//            if (!$location.$$path.includes('contact-us') && !$location.$$path.includes('jackpots') && !$location.$$path.includes('games') && !$location.$$path.includes('providers') && !$location.$$path.includes('categories') && !$location.$$path.includes('live-casino')) {
//                $scope.activateMenuItem(1);
//            }
//        });

        return that;
    }]);





