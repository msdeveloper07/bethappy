'use strict';
app.factory('sliderService', ['$http', 'appSettings', function ($http, appSettings) {

        var serviceBase = appSettings.serviceBaseUri;
        var sliderServiceFactory = {};

        sliderServiceFactory.getSlides = function () {
            return $http.get(serviceBase + 'slides/getSlides')
                    .then(function (results) {
                        return results;
                    });
        };

        return sliderServiceFactory;

    }]);