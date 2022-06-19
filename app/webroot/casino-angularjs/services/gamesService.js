'use strict';
app.factory('gamesService', ['$http', 'appSettings', function ($http, appSettings) {

        var serviceBase = appSettings.serviceBaseUri;
        var gamesServiceFactory = {};

        gamesServiceFactory.getGames = function () {
            return $http.get(serviceBase + 'int_games/int_games/getGames').then(function (results) {
                return results;
            });
        };

        gamesServiceFactory.getCategoryBySlug = function (category_slug) {
            return $http.get(serviceBase + 'int_games/int_categories/getCategoryBySlug/' + category_slug).then(function (results) {
                return results;
            });
        };
        gamesServiceFactory.getGamesByCategoryId = function (category_id) {
            return $http.get(serviceBase + 'int_games/int_games/getGamesByCategoryId/' + category_id).then(function (results) {
                return results;
            });
        };

        gamesServiceFactory.getBrandBySlug = function (brand_slug) {
            return $http.get(serviceBase + 'int_games/int_brands/getBrandBySlug/' + brand_slug).then(function (results) {
                return results;
            });
        };

        gamesServiceFactory.getGamesByBrandId = function (brand_id) {
            return $http.get(serviceBase + 'int_games/int_games/getGamesByBrandId/' + brand_id).then(function (results) {
                return results;
            });
        };

        gamesServiceFactory.addGameToFavorites = function (game_id) {
            return $http.get(serviceBase + 'int_games/int_favorites/addGameToFavorites/' + game_id).then(function (results) {
                return results;
            });
        };

        gamesServiceFactory.removeGameFromFavorites = function (game_id) {
            return $http.get(serviceBase + 'int_games/int_favorites/removeGameFromFavorites/' + game_id).then(function (results) {
                return results;
            });
        };
        
        gamesServiceFactory.isGameFavorite = function (game_id) {
            return $http.get(serviceBase + 'int_games/int_favorites/isGameFavorite/' + game_id).then(function (results) {
                return results;
            });
        };

        gamesServiceFactory.loadGame = function (game_id, fun_play) {
            return $http.get(serviceBase + 'int_games/int_games/game/' + game_id + '/' + fun_play).then(function (results) {
                return results;
            });
        };

        return gamesServiceFactory;
    }]);