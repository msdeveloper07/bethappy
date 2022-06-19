'use strict';
app.factory('pagesService', ['$http', 'appSettings', function ($http, appSettings) {

        var serviceBase = appSettings.serviceBaseUri;
        var pagesServiceFactory = {};

        pagesServiceFactory.getPage = function (url) {
            return $http.get(serviceBase + 'pages/getPageJson/' + url)
                    .then(function (results) {
                        return results;
                    });
        };



        return pagesServiceFactory;

    }]);