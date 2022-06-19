'use strict';
app.factory('currenciesService', ['$http', 'appSettings', function ($http, appSettings) {

        var serviceBase = appSettings.serviceBaseUri;
        var currenciesServiceFactory = {};

        currenciesServiceFactory.getCurrencies = function () {
            return $http.get(serviceBase + 'currencies/getCurrenciesJson')
                    .then(function (results) {
                        return results;
                    });
        };

        return currenciesServiceFactory;

    }]);