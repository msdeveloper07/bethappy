'use strict';
app.factory('countriesService', ['$http', 'appSettings', function ($http, appSettings) {

        var serviceBase = appSettings.serviceBaseUri;
        var countriesServiceFactory = {};

        countriesServiceFactory.getCountries = function () {
            return $http.get(serviceBase + 'countries/getCountriesJson')
                    .then(function (results) {
                        return results;
                    });
        };

        return countriesServiceFactory;

    }]);