'use strict';
app.factory('usersService', ['$http', 'appSettings', function ($http, appSettings) {

        var serviceBase = appSettings.serviceBaseUri;
        var usersServiceFactory = {};


        usersServiceFactory.signUp = function (data) {
            return $http.post(serviceBase + 'users/signUp', data).then(function (results) {
                return results;
            });
        };

        usersServiceFactory.signIn = function (data) {
            return $http.post(serviceBase + 'users/signIn', data).then(function (results) {
                return results;
            });
        };

        usersServiceFactory.signOut = function () {
            return $http.post(serviceBase + 'users/signOut?' + Math.random()).then(function (results) {
                return results;
            });
        };

        usersServiceFactory.isAuthenticated = function () {
            return $http.post(serviceBase + 'users/isAuthenticated').then(function (results) {
                return results;
            });
        };

        usersServiceFactory.checkTokenExpiration = function (token) {
            return $http.post(serviceBase + 'player/checkTokenExpiration/' + token).then(function (results) {
                return results;
            });
        };

        usersServiceFactory.forgotPassword = function (email) {
            return $http.post(serviceBase + 'player/forgotPassword/' + email).then(function (results) {
                return results;
            });
        };

        usersServiceFactory.resetPassword = function (data) {
            return $http.post(serviceBase + 'player/resetPassword', data).then(function (results) {
                return results;
            });
        };

        usersServiceFactory.verifyEmail = function (token) {
            return $http.post(serviceBase + 'player/verifyEmail/' + token).then(function (results) {
                return results;
            });
        };


        usersServiceFactory.pingPlayer = function () {
            return $http.post(serviceBase + 'player/pingPlayer').then(function (results) {
                return results;
            });
        };

        usersServiceFactory.pingPlayer = function (data) {
            return $http.post(serviceBase + 'player/pingPlayer', data).then(function (results) {
                return results;
            });
        };

        usersServiceFactory.getPlayerLimits = function () {
            return $http.get(serviceBase + 'player/getPlayerLimits').then(function (results) {
                return results;
            });
        };

        usersServiceFactory.setPlayerLimits = function (data) {
            return $http.post(serviceBase + 'player/setPlayerLimits', data).then(function (results) {
                return results;
            });
        };

        usersServiceFactory.cancelPlayerLimit = function (limit_id) {
            return $http.get(serviceBase + 'player/cancelPlayerLimit/' + limit_id).then(function (results) {
                return results;
            });
        };


        usersServiceFactory.getPlayerKYC = function () {
            return $http.get(serviceBase + 'player/getPlayerKYC').then(function (results) {
                return results;
            });
        };



        usersServiceFactory.getGameLogs = function (page) {
            return $http.get(serviceBase + 'player/getGameLogs' + '/' + page).then(function (results) {
                return results;
            });
        };

        usersServiceFactory.getPlayerFavoriteGames = function (page) {
            return $http.get(serviceBase + 'player/getPlayerFavoriteGames' + '/' + page).then(function (results) {
                return results;
            });
        };

        usersServiceFactory.sumByTransactionType = function () {
            return $http.get(serviceBase + 'player/sumByTransactionType').then(function (results) {
                return results;
            });
        };


        usersServiceFactory.getCasinoTransactions = function (type, items_per_page, page) {
            return $http.get(serviceBase + 'player/getCasinoTransactions' + '/' + type + '/' + items_per_page + '/' + page).then(function (results) {
                return results;
            });
        };
        
           usersServiceFactory.geBonusTransactions = function (type, items_per_page, page) {
            return $http.get(serviceBase + 'player/getBonusTransactions' + '/' + type + '/' + items_per_page + '/' + page).then(function (results) {
                return results;
            });
        };

        usersServiceFactory.getPlayerDeposits = function (page) {
            return $http.get(serviceBase + 'player/getPlayerDeposits' + '/' + page).then(function (results) {
                return results;
            });
        };

        usersServiceFactory.getPlayerDepositsStatistics = function () {
            return $http.get(serviceBase + 'player/getPlayerDepositsStatistics').then(function (results) {
                return results;
            });
        };

        usersServiceFactory.getPlayerWithdraws = function (page) {
            return $http.get(serviceBase + 'player/getPlayerWithdraws' + '/' + page).then(function (results) {
                return results;
            });
        };

        usersServiceFactory.getPlayerWithdrawsStatistics = function () {
            return $http.get(serviceBase + 'player/getPlayerWithdrawsStatistics').then(function (results) {
                return results;
            });
        };


        usersServiceFactory.contactUs = function (data) {
            return $http.post(serviceBase + 'player/contactUs', data).then(function (results) {
                return results;
            });
        };
        
        return usersServiceFactory;

    }]);