'use strict';
app.factory('headerService', ['$http', 'appSettings', function ($http, appSettings) {

        var serviceBase = appSettings.serviceBaseUri;
        var headerServiceFactory = {};

        headerServiceFactory.getMainMenu = function () {
            return $http.get(serviceBase + 'mt_menus/getMenuJson')
                    .then(function (results) {
                        return results;
                    });
        };

        headerServiceFactory.getLanguages = function () {
            return $http.get(serviceBase + 'languages/getLanguagesJson')
                    .then(function (results) {
                        return results;
                    });
        };


        headerServiceFactory.setLanguage = function (language_id) {
            return $http.post(serviceBase + '/Languages/setLanguage/' + language_id)
                    .then(function (results) {
                        //console.log(results);
                        return results;
                    });
        };


        return headerServiceFactory;

    }]);