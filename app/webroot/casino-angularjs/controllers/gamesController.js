'use strict';
ctrls.controller('gamesController', ['__', '$rootScope', '$scope', '$routeParams', '$sce', '$route', '$location', '$window', '$filter', 'SweetAlert', 'gamesService', 'appSettings', function (__, $rootScope, $scope, $routeParams, $sce, $route, $location, $window, $filter, SweetAlert, gamesService, appSettings) {
        $scope.Loader = false;

        //var serviceBase = appSettings.serviceBaseUri;
        $scope.Games = [];

        $scope.New = [];
        $scope.Jackpot = [];
        $scope.Featured = [];
        $scope.Trending = [];
        $scope.Livecasino = [];
        $scope.Slots = [];
        $scope.Table = [];

        $scope.Categories = [];
        $scope.Providers = [];



        $scope.showCategories = true;
        $scope.showProviders = true;

        $scope.showClearSort = false;

        //for ui select filter
        $scope.category = {};
        $scope.provider = {};
        $scope.sort = {};

        $scope.Loader = true;

        $scope.fallbackImageSrc = "https://bethappy.com/img/casino/image-not-found.png";
        //$scope.Game = null;
        //$scope.GameiFrame = null;
        $scope.closeGame = function () {
            $window.location.href = 'https://bethappy.com';
        };


//        $scope.clearCategory = function ($event) {
//            $event.stopPropagation();
//            $scope.category.selected = undefined;
//        };
//        $scope.clearProvider = function ($event) {
//            $event.stopPropagation();
//            $scope.provider.selected = undefined;
//        };
//        $scope.clearSort = function ($event) {
//            $event.stopPropagation();
//            $scope.sort.selected = undefined;
//        };


        $scope.setProviders = function (value) {
            if (value.IntBrand.id !== null) {
                if (!$scope.Providers.some(element => element.name.includes(value.IntBrand.name)))
                    $scope.Providers.push({id: value.IntBrand.id, name: value.IntBrand.name, slug: value.IntBrand.slug});
            }
        };


        $scope.setCategories = function (value) {
            if (value.IntCategory.id !== null) {
                if (!$scope.Categories.some(element => element.name.includes(value.IntCategory.name)))
                    $scope.Categories.push({id: value.IntCategory.id, name: value.IntCategory.name, slug: value.IntCategory.slug});
            }
        };

        $scope.setNew = function (value) {
            if (value.IntGame.new == 1)
                $scope.New.push(value.IntGame);
        };

        $scope.setJackpot = function (value) {
            if (value.IntGame.jackpot == 1)
                $scope.Jackpot.push(value.IntGame);
        };

        $scope.setFeatured = function (value) {
            if (value.IntGame.featured == 1)
                $scope.Featured.push(value.IntGame);
        };

        $scope.setTrending = function (value) {
            if (value.IntGame.open_stats != 0)
                $scope.Trending.push(value.IntGame);
        };

        $scope.setLivecasino = function (value) {
            if (value.IntCategory.slug === 'live-casino')
                $scope.Livecasino.push(value.IntGame);
        };


        $scope.setSlots = function (value) {
            if (value.IntGame.category_id == 1)
                $scope.Slots.push(value.IntGame);
        };

        $scope.setTable = function (value) {
            if (value.IntGame.category_id == 2)
                $scope.Table.push(value.IntGame);
        };




        if ($routeParams.category) {
            ($scope.getGamesByCategory = function () {
                $scope.Loader = true;
                gamesService.getCategoryBySlug($routeParams.category).then(function (response) {
                    console.log(response);
                    if (response.data.status === 'success') {
                        var category_id = response.data.category_id;
                        gamesService.getGamesByCategoryId(category_id).then(function (response) {
                            //console.log(response);
                            if (response.status === 200) {
                                $scope.showCategories = false;
                                angular.forEach(response.data, function (value, key) {
                                    value.IntGame.category_name = value.IntCategory.name;
                                    value.IntGame.brand_name = value.IntBrand.name;

                                    if (__.isMobile()) {
                                        if (value.IntGame.mobile === 1 || value.IntGame.mobile === true) {
                                            $scope.Games.push(value.IntGame);
                                            $scope.setProviders(value);
                                        }
                                    } else {
                                        if (value.IntGame.desktop === 1 || value.IntGame.desktop === true) {
                                            $scope.Games.push(value.IntGame);
                                            $scope.setProviders(value);
                                        }
                                    }

                                });
                            } else {
                                console.log(response);
                            }
                        }, function (error) {
                            console.log(error);
                        });
                    } else {
                        console.log(response);
                    }
                }, function (error) {
                    console.log(error);
                });
                $scope.Loader = false;
            })();
        } else if ($routeParams.brand) {
            ($scope.getGamesByBrand = function () {
                $scope.Loader = true;

                gamesService.getBrandBySlug($routeParams.brand).then(function (response) {
                    console.log(response);
                    if (response.data.status === 'success') {
                        var brand_id = response.data.brand_id;
                        gamesService.getGamesByBrandId(brand_id).then(function (response) {
                            //console.log(response);
                            if (response.status === 200) {
                                $scope.showProviders = false;
                                angular.forEach(response.data, function (value, key) {
                                    value.IntGame.category_name = value.IntCategory.name;
                                    value.IntGame.brand_name = value.IntBrand.name;

                                    if (__.isMobile()) {
                                        if (value.IntGame.mobile === 1 || value.IntGame.mobile === true) {
                                            $scope.Games.push(value.IntGame);
                                            $scope.setCategories(value);
                                        }
                                    } else {
                                        if (value.IntGame.desktop === 1 || value.IntGame.desktop === true) {
                                            $scope.Games.push(value.IntGame);
                                            $scope.setCategories(value);
                                        }
                                    }
                                });
                            } else {
                                console.log(response);
                            }
                        }, function (error) {
                            console.log(error);
                        });

                    } else {
                        console.log(response);
                    }
                }, function (error) {
                    console.log(error);
                });
                $scope.Loader = false;
            })();
        } else {
            ($scope.getGames = function () {
                $scope.Loader = true;
                gamesService.getGames().then(function (response) {
                    //console.log(response);
                    if (response.status === 200) {
                        angular.forEach(response.data, function (value, key) {

                            value.IntGame.category_name = value.IntCategory.name;
                            value.IntGame.brand_name = value.IntBrand.name;

                            if (__.isMobile()) {
                                //console.log(value.IntGame.mobile);
                                if (value.IntGame.mobile === 1 || value.IntGame.mobile === '1' || value.IntGame.mobile === true) {
                                    //console.log('mobile');
                                    $scope.Games.push(value.IntGame);
//                                    $scope.setProviders(value);
//                                    $scope.setCategories(value);
//                                    $scope.setNew(value);
//                                    $scope.setJackpot(value);
//                                    $scope.setFeatured(value);
//                                    $scope.setLivecasino(value);
//                                    $scope.setSlots(value);
//                                    $scope.setTable(value);
                                }
                            } else {
                                //console.log(value.IntGame.desktop);
                                if (value.IntGame.desktop === 1 || value.IntGame.desktop === '1' || value.IntGame.desktop === true) {
                                    //console.log('desktop');
                                    $scope.Games.push(value.IntGame);
//                                    $scope.setProviders(value);
//                                    $scope.setCategories(value);
//                                    $scope.setNew(value);
//                                    $scope.setJackpot(value);
//                                    $scope.setFeatured(value);
//                                    $scope.setLivecasino(value);
//                                    $scope.setSlots(value);
//                                    $scope.setTable(value);
                                }
                            }

                        });

                        //$scope.Trending = $filter('orderObjectBy')($scope.Games, 'open_stats').reverse().slice(0, 30);
                        //console.log($scope.Trending);
                        //console.log($scope.New);
                        $scope.Loader = false;
                    } else {
                        console.log(response);
                    }
                }, function (error) {
                    console.log(error);
                });
            })();


            ($scope.getSubSets = function () {
                $scope.Loader = true;
                gamesService.getGames().then(function (response) {
                    //console.log(response);
                    if (response.status === 200) {
                        angular.forEach(response.data, function (value, key) {
                            value.IntGame.category_name = value.IntCategory.name;
                            value.IntGame.brand_name = value.IntBrand.name;

                            if (__.isMobile()) {
                                //console.log(value.IntGame.mobile);
                                if (value.IntGame.mobile === 1 || value.IntGame.mobile === '1' || value.IntGame.mobile === true) {
                                    //console.log('mobile');
                                    //$scope.Games.push(value.IntGame);
                                    $scope.setProviders(value);
                                    $scope.setCategories(value);
                                    $scope.setNew(value);
                                    $scope.setJackpot(value);
                                    $scope.setFeatured(value);
                                    $scope.setTrending(value);
                                    $scope.setLivecasino(value);
                                    $scope.setSlots(value);
                                    $scope.setTable(value);
                                }
                            } else {
                                if (value.IntGame.desktop === 1 || value.IntGame.desktop === '1' || value.IntGame.desktop === true) {
                                    $scope.setProviders(value);
                                    $scope.setCategories(value);
                                    $scope.setNew(value);
                                    $scope.setJackpot(value);
                                    $scope.setFeatured(value);
                                    $scope.setTrending(value);
                                    $scope.setLivecasino(value);
                                    $scope.setSlots(value);
                                    $scope.setTable(value);
                                }
                            }

                        });
                        $scope.Trending = $filter('orderObjectBy')($scope.Trending, 'open_stats').reverse().slice(0, 30);

                        $scope.Loader = false;
                    } else {
                        console.log(response);
                    }
                }, function (error) {
                    console.log(error);
                });
            })();

        }

        var pagesShown = 1;
        var pageSize = 40;
        $scope.itemsLimit = function () {
            return pageSize * pagesShown;
        };
        $scope.hasMoreItemsToShow = function () {
            return pagesShown < ($scope.Games.length / pageSize);
        };
        $scope.showMoreItems = function () {
            console.log(pagesShown);
            pagesShown = pagesShown + 1;
        };

        $scope.addGameToFavorites = function (game_id) {

            gamesService.addGameToFavorites(game_id).then(function (response) {
                console.log(response);
                if (response.data.status === 'success') {
                    SweetAlert.swal({
                        title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
                        text: response.data.message,
                        type: response.data.status,
                        confirmButtonColor: "#F27474",
                        confirmButtonText: "Close",
                        closeOnConfirm: true
                    });
                    //check this

                    gamesService.isGameFavorite(game_id).then(function (response) {
                        //console.log(response);
                        if (response.data.status === 'success') {
                            $scope.Favorite = response.data.data;
                        }
                    }, function (error) {
                        console.log(error);
                    });
                } else {
                    console.log(response);
                    SweetAlert.swal({
                        title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
                        text: response.data.message,
                        type: response.data.status,
                        confirmButtonColor: "#F27474",
                        confirmButtonText: "Close",
                        closeOnConfirm: true
                    });
                }
            }, function (error) {
                console.log(error);
            });

        };

        $scope.removeGameFromFavorites = function (game_id) {

            gamesService.removeGameFromFavorites(game_id).then(function (response) {
                //console.log(response);
                if (response.data.status === 'success') {
                    SweetAlert.swal({
                        title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
                        text: response.data.message,
                        type: response.data.status,
                        confirmButtonColor: "#F27474",
                        confirmButtonText: "Close",
                        closeOnConfirm: true
                    });
                    gamesService.isGameFavorite(game_id).then(function (response) {
                        //console.log(response);
                        if (response.data.status === 'error') {
                            $scope.Favorite = {};
                        }
                    }, function (error) {
                        console.log(error);
                    });

                } else {
                    console.log(response);

                    SweetAlert.swal({
                        title: response.data.status.substring(0, 1).toUpperCase() + response.data.status.substring(1),
                        text: response.data.message,
                        type: response.data.status,
                        confirmButtonColor: "#F27474",
                        confirmButtonText: "Close",
                        closeOnConfirm: true
                    });
                }
            }, function (error) {
                console.log(error);
            });

        };

        $scope.$watch('Favorite', function (newValue, oldValue, scope) {
            //console.log(newValue);
            //console.log(oldValue);
        });

        // used in sorting the games
        //not needed if done by select, only if radio buttons used
        $scope.updateSortFilter = function (attrubute_num) {
            $scope.showClearSort = true;
            switch (attrubute_num) {
                case 0:
                    $scope.selectedSort = '-open_stats';
                    break;
                case 1:
                    $scope.selectedSort = 'name';
                    break;
                case 2:
                    $scope.selectedSort = '-name';
                    break;
            }

        };

        $scope.clearSortFilter = function () {
            $scope.selectedSort = undefined;
            $scope.filterSort = undefined;
            $scope.showClearSort = false;
        };

        $scope.loadGame = function (game_id, fun_play) {
            $scope.Loader = true;

            //console.log($rootScope.isAuthenticated);
//            if (!fun_play)
//                fun_play = 'false';
//
//            if ((fun_play == 'false' || fun_play == false) && $rootScope.isAuthenticated == false) {
//                $rootScope.openSignInModal();
//            } else {
            console.log('load game...');
            //$location.path('/game/' + game_id + '/' + fun_play);

            gamesService.loadGame(game_id, fun_play).then(function (response) {
                console.log(response);
                if (response.data.status === 'success') {
                    console.log('response ok');
                    // $scope.GameiFrame = $sce.trustAsHtml(response.data.content);
                    $scope.GameURL = response.data.URL;
                    $scope.Game = response.data.game;
                    $scope.Favorite = response.data.favorite;
                    $scope.Loader = false;
                }
            }, function (error) {
                console.log(error);
            });
            //}
        };

        console.log($routeParams);
        if ($routeParams.game_id && $routeParams.fun_play) {
            $scope.loadGame($routeParams.game_id, true);
        } else {
            $scope.loadGame($routeParams.game_id, false);
        }



        $scope.filter = {};
        $scope.groups = ['brand_name', 'category_name'];

        $scope.addProps = function (obj, array) {
            //console.log(obj);
            if (typeof array === 'undefined') {
                return false;
            }
            return array.reduce(function (prev, item) {
                //console.log(item);
                if (typeof item[obj] === 'undefined') {
                    return prev;
                }
                return prev + parseFloat(item[obj]);
            }, 0);
        }

        $scope.getItems = function (obj, array) {
            //console.log(obj);
            return (array || []).map(function (w) {
                return w[obj];
            }).filter(function (w, idx, arr) {
                //console.log(arr);
                if (typeof w === 'undefined') {
                    return false;
                }
                return arr.indexOf(w) === idx;
            });
        };
        // matching with AND operator
        $scope.filterByPropertiesMatchingAND = function (data) {
            var matchesAND = true;
            for (var obj in $scope.filter) {
                if ($scope.filter.hasOwnProperty(obj)) {
                    if (noSubFilter($scope.filter[obj]))
                        continue;
                    if (!$scope.filter[obj][data[obj]]) {
                        matchesAND = false;
                        break;
                    }
                }
            }
            return matchesAND;
        };
        // matching with OR operator
        $scope.filterByPropertiesMatchingOR = function (data) {
            var matchesOR = true;
            for (var obj in $scope.filter) {
                if ($scope.filter.hasOwnProperty(obj)) {
                    if (noSubFilter($scope.filter[obj]))
                        continue;
                    if (!$scope.filter[obj][data[obj]]) {
                        matchesOR = false;
                    } else {
                        matchesOR = true;
                        break;
                    }
                }
            }
            return matchesOR;
        };

        function noSubFilter(obj) {
            for (var key in obj) {
                if (obj[key])
                    return false;
            }
            return true;
        }

    }]);

