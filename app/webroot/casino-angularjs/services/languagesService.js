'use strict';
app.factory('languagesService', ['$http', 'appSettings', function ($http, appSettings) {

        var serviceBase = appSettings.serviceBaseUri;
        var languagesServiceFactory = {};

        languagesServiceFactory.getLanguages= function () {
            return $http.get(serviceBase + 'languages/getLanguagesJson')
                    .then(function (results) {
                        return results;
                    });
        };

        return languagesServiceFactory;

    }]);