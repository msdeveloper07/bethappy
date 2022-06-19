'use strict';
app.factory('footerService', ['$http', 'appSettings', function ($http, appSettings) {

        var serviceBase = appSettings.serviceBaseUri;
        var footerServiceFactory = {};

        footerServiceFactory.getFooterMenus = function () {
            return $http.get(serviceBase + 'mb_menus/getMenuJson')
                    .then(function (results) {
                        return results;
                    });
        };



        return footerServiceFactory;

    }]);