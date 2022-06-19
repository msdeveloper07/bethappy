<div class="container-fluid conatiner-custom-padding home">
    <section ng-controler="gamesController">
        <uib-tabset active="activeJustified" justified="true" class="categories-tabset">
            <uib-tab index="0" heading="Justified">
                <uib-tab-heading>
                    <div id="category-featured" class="tab-category active">
                        <i class="fas fa-star category-icon"></i>
                        <h2 class="tab-category-name"><?= __('Casino'); ?></h2>
                    </div>
                </uib-tab-heading>
                <div ng-if="!Loader">

                    <span class="d-none">
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h2 class="text-uppercase"><?= __('Featured games'); ?></h2>
                                    <a href="#!/games/featured"><span class="mr-4 text-uppercase"><?= __('See all'); ?></span> <i class="fas fa-chevron-right"></i></a> 
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-5ths mb-4 gameBox" ng-repeat="game in limited = (filteredFeatured = (Featured | orderBy: '-game.order') | orderBy: sort.selected.sort_type | limitTo: 10)" id="{{game.id}}">
                                <div class="card card-game card-game-no-slider text-white">
                                    <div class="game-hover-effect card-gradient-default ">
                                        <img ng-src="{{game.image}}" alt="{{game.name}}" err-src="{{fallbackImageSrc}}" class="img-responsive "/>
                                        <div class="gradient-view category-{{game.category_id}}">
                                            <div class="gradient-view-top"></div>
                                            <div class="gradient-view-bottom">
                                                <h5 class="text-center card-title text-uppercase">{{game.category_name}}</h5>
                                            </div>
                                        </div>
                                        <div class="card-img-overlay">
                                            <div class="game-ribbon" ng-if="game.new === true">
                                                <span class="badge badge-rounded badge-danger text-uppercase px-2 py-1"><?= __('New'); ?></span>
                                            </div>
                                            <h5 class="card-title mb-1 ">{{game.name}}</h5>

                                            <div class="overlay-buttons">
                                                <?php if ($this->Session->check('Auth.User')): ?>
                                                    <a class="btn btn-default btn-play btn-md rounded-pill px-4 mb-2"  href="/#!/game/{{game.id}}">
                                                        <i class="fas fa-play "></i>
                                                    </a>
                                                <?php endif; ?>
                                                <a class="btn btn-light btn-md btn-demo px-4 fun-{{game.fun_play}}" ng-show="game.fun_play === true"   href="/#!/game/{{game.id}}/{{true}}"><?= __('Demo'); ?></a>
                                            </div>
                                            <h6 class="card-text smallmt-3 ">{{game.brand_name}}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </span>
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="text-uppercase"><?= __('New games'); ?></h2>
                                <a href="#!/games/new"><span class="mr-4 text-uppercase"><?= __('See all'); ?></span> <i class="fas fa-chevron-right"></i></a> 
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-5ths mb-4 gameBox" ng-repeat="game in limited = (filteredNew = (New | orderBy: '-game.order') | orderBy: sort.selected.sort_type | limitTo: 10)" id="{{game.id}}">
                            <div class="card card-game card-game-no-slider text-white">
                                <div class="game-hover-effect card-gradient-default ">
                                    <img ng-src="{{game.image}}" alt="{{game.name}}" err-src="{{fallbackImageSrc}}" class="img-responsive "/>
                                    <div class="gradient-view category-{{game.category_id}}">
                                        <div class="gradient-view-top"></div>
                                        <div class="gradient-view-bottom">
                                            <h5 class="text-center card-title text-uppercase">{{game.category_name}}</h5>
                                        </div>
                                    </div>
                                    <div class="card-img-overlay">
                                        <div class="game-ribbon" ng-if="game.new === true">
                                            <span class="badge badge-rounded badge-danger text-uppercase px-2 py-1"><?= __('New'); ?></span>
                                        </div>
                                        <h5 class="card-title mb-1 ">{{game.name}}</h5>

                                        <div class="overlay-buttons">
                                            <?php if ($this->Session->check('Auth.User')): ?>
                                                <a class="btn btn-default btn-play btn-md rounded-pill px-4 mb-2"  href="/#!/game/{{game.id}}">
                                                    <i class="fas fa-play "></i>
                                                </a>
                                            <?php endif; ?>
                                            <a class="btn btn-light btn-md btn-demo px-4 fun-{{game.fun_play}}" ng-show="game.fun_play === true"   href="/#!/game/{{game.id}}/{{true}}"><?= __('Demo'); ?></a>
                                        </div>
                                        <h6 class="card-text smallmt-3 ">{{game.brand_name}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="text-uppercase"><?= __('Top games'); ?></h2>
                                <a href="#!/games/trending"><span class="mr-4 text-uppercase"><?= __('See all'); ?></span> <i class="fas fa-chevron-right"></i></a> 
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-5ths mb-4 gameBox" ng-repeat="game in limited = (filteredTrending = (Trending | orderBy: '-game.order') | orderBy: sort.selected.sort_type | limitTo: 10)" id="{{game.id}}">
                            <div class="card card-game card-game-no-slider text-white">
                                <div class="game-hover-effect card-gradient-default ">
                                    <img ng-src="{{game.image}}" alt="{{game.name}}" err-src="{{fallbackImageSrc}}" class="img-responsive "/>
                                    <div class="gradient-view category-{{game.category_id}}">
                                        <div class="gradient-view-top"></div>
                                        <div class="gradient-view-bottom">
                                            <h5 class="text-center card-title text-uppercase">{{game.category_name}}</h5>
                                        </div>
                                    </div>
                                    <div class="card-img-overlay">
                                        <div class="game-ribbon" ng-if="game.new === true">
                                            <span class="badge badge-rounded badge-danger text-uppercase px-2 py-1"><?= __('New'); ?></span>
                                        </div>
                                        <h5 class="card-title mb-1 ">{{game.name}}</h5>

                                        <div class="overlay-buttons">
                                            <?php if ($this->Session->check('Auth.User')): ?>
                                                <a class="btn btn-default btn-play btn-md rounded-pill px-4 mb-2"  href="/#!/game/{{game.id}}">
                                                    <i class="fas fa-play "></i>
                                                </a>
                                            <?php endif; ?>
                                            <a class="btn btn-light btn-md btn-demo px-4 fun-{{game.fun_play}}" ng-show="game.fun_play === true"   href="/#!/game/{{game.id}}/{{true}}"><?= __('Demo'); ?></a>
                                        </div>
                                        <h6 class="card-text smallmt-3 ">{{game.brand_name}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center align-items-center"  ng-if="Loader">
                    <div class="lds-ellipsis">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
            </uib-tab>

            <uib-tab index="1" heading="Justified">
                <uib-tab-heading>
                    <div id="category-live" class="tab-category">
                        <i class="fas fa-user category-icon"></i>
                        <h2 class="tab-category-name"><?= __('Live Casino'); ?></h2>
                    </div>
                </uib-tab-heading>
                <div ng-if="!Loader">
                    <div class="row mb-4">
                        <div class="col-md-5ths mb-4 gameBox" ng-repeat="game in limited = (filteredLive = (Livecasino | orderBy: '-game.order') | orderBy: sort.selected.sort_type | limitTo: itemsLimit())" id="{{game.id}}">
                            <div class="card card-game card-game-no-slider text-white">
                                <div class="game-hover-effect card-gradient-default ">
                                    <img ng-src="{{game.image}}" alt="{{game.name}}" err-src="{{fallbackImageSrc}}" class="img-responsive "/>
                                    <div class="gradient-view category-{{game.category_id}}">
                                        <div class="gradient-view-top"></div>
                                        <div class="gradient-view-bottom">
                                            <h5 class="text-center card-title text-uppercase">{{game.category_name}}</h5>
                                        </div>
                                    </div>
                                    <div class="card-img-overlay">
                                        <div class="game-ribbon" ng-if="game.new === true">
                                            <span class="badge badge-rounded badge-danger text-uppercase px-2 py-1"><?= __('New'); ?></span>
                                        </div>
                                        <h5 class="card-title mb-1 ">{{game.name}}</h5>

                                        <div class="overlay-buttons">
                                            <?php if ($this->Session->check('Auth.User')): ?>
                                                <a class="btn btn-default btn-play btn-md rounded-pill px-4 mb-2"  href="/#!/game/{{game.id}}">
                                                    <i class="fas fa-play "></i>
                                                </a>
                                            <?php endif; ?>
                                            <a class="btn btn-light btn-md btn-demo px-4 fun-{{game.fun_play}}" ng-show="game.fun_play === true"   href="/#!/game/{{game.id}}/{{true}}"><?= __('Demo'); ?></a>
                                        </div>
                                        <h6 class="card-text smallmt-3 ">{{game.brand_name}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" ng-hide="hasMoreItemsToShow() || (filtered.length === limited.length)" ng-show="filtered.length === 0 || limited.length === 0">
                        <div class="col-md-12 col-12 text-center">
                            <p><?= __('No games to show.'); ?></p>
                        </div>
                    </div>
                    <div class="row" ng-show="hasMoreItemsToShow()" ng-hide="itemsLimit() >= filtered.length || (filtered.length - limited.length) === 0">
                        <div class="col-md-4 offset-md-4 text-center">
                            <button class="btn btn-default text-uppercase" ng-click="showMoreItems()"><?= __('More games'); ?></button></div>
                    </div>
                </div>

                <div class="d-flex justify-content-center align-items-center"  ng-if="Loader">
                    <div class="lds-ellipsis">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
            </uib-tab>

            <uib-tab index="2" heading="Justified">
                <uib-tab-heading>
                    <div id="category-all" class="tab-category">
                        <i class="fa fa-trophy category-icon"></i>
                        <h2 class="tab-category-name"><?= __('Sports'); ?></h2>
                    </div>
                </uib-tab-heading>

            </uib-tab>
            <uib-tab index="3" heading="Justified">
                <uib-tab-heading>
                    <div id="category-all" class="tab-category">
                        <i class="fa fa-play category-icon"></i>
                        <h2 class="tab-category-name"><?= __('In Play'); ?></h2>
                    </div>
                </uib-tab-heading>

            </uib-tab>

            <uib-tab index="4" heading="Justified">
                <uib-tab-heading>
                    <div id="category-all" class="tab-category">
                        <i class="fa fa-futbol category-icon"></i>
                        <h2 class="tab-category-name"><?= __('Virtual'); ?></h2>
                    </div>
                </uib-tab-heading>

            </uib-tab>

            <uib-tab index="5" heading="Justified">
                <uib-tab-heading>
                    <div id="category-search" class="tab-category">
                        <i class="fas fa-search category-icon"></i>
                        <h2 class="tab-category-name"><?= __('Search'); ?></h2>
                    </div>
                </uib-tab-heading>

                <div class="row">
                    <div class="col-md-6">
                        <div ng-repeat="group in groups" ng-init="filter[group]={}">
                            <div  class="d-flex flex-wrap">
                                <div class="btn-group-toggle" data-toggle="buttons" ng-repeat="value in getItems(group, Games)">
                                    <label class="btn btn-default mr-2 mb-2" >
                                        <input type="checkbox" ng-model="filter[group][value]" ng-model="value">{{value}}
                                    </label>
                                </div>
                            </div>
                            <hr class="border-default">
                        </div>

                        <div class="btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-default mr-2 mb-2">
                                <input type="checkbox" value="1" ng-model="filterByNew" ng-true-value="true" ng-false-value="'!null'"><?= __('New'); ?>
                            </label>
                            <label class="btn btn-default mr-2 mb-2">
                                <input type="checkbox" value="1"  ng-model="filterByJackpot" ng-true-value="true" ng-false-value="'!null'"><?= __('Jackpot'); ?>
                            </label>
                        </div>

                    </div>

                    <div class="col-md-6">
                        <div class="search-wrapper">
                            <input type="text" ng-keydown="searchGames()" class="search-game" ng-model="filterSearch"/>
                        </div>
                    </div>
                </div>

                <div ng-if="!Loader">
                    <div class="row mb-4 mt-4">             
                        <div class="col-md-5ths mb-4 gameBox" ng-repeat="game in limited = (filtered = (Games | orderBy: '-game.order' | filter: {new : (filterByNew == true ? true: undefined)}| filter: {jackpot : (filterByJackpot == true ? true: undefined)} | filter:filterByPropertiesMatchingAND | filter: filterSearch) | orderBy: sort.selected.sort_type | limitTo: itemsLimit()) track by $index" id="{{game.id}}">
                            <div class="card card-game card-game-no-slider text-white">
                                <div class="game-hover-effect card-gradient-default ">
                                    <img ng-src="{{game.image}}" alt="{{game.name}}" err-src="{{fallbackImageSrc}}" class="img-responsive "/>
                                    <div class="gradient-view category-{{game.category_id}}">
                                        <div class="gradient-view-top"></div>
                                        <div class="gradient-view-bottom">
                                            <h5 class="text-center card-title text-uppercase">{{game.category_name}}</h5>
                                        </div>
                                    </div>
                                    <div class="card-img-overlay">
                                        <div class="game-ribbon" ng-if="game.new === true">
                                            <span class="badge badge-rounded badge-danger text-uppercase px-2 py-1"><?= __('New'); ?></span>
                                        </div>
                                        <h5 class="card-title mb-1 ">{{game.name}}</h5>

                                        <div class="overlay-buttons">
                                            <?php if ($this->Session->check('Auth.User')): ?>
                                                <a class="btn btn-default btn-play btn-md rounded-pill px-4 mb-2"  href="/#!/game/{{game.id}}">
                                                    <i class="fas fa-play "></i>
                                                </a>
                                            <?php endif; ?>
                                            <a class="btn btn-light btn-md btn-demo px-4 fun-{{game.fun_play}}" ng-show="game.fun_play === true"   href="/#!/game/{{game.id}}/{{true}}"><?= __('Demo'); ?></a>
                                        </div>
                                        <h6 class="card-text smallmt-3 ">{{game.brand_name}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                                   
                </div>
                <div class="row" ng-hide="hasMoreItemsToShow() || (filtered.length === limited.length)" ng-show="filtered.length === 0 || limited.length === 0">
                    <div class="col-md-12 col-12 text-center">
                        <p><?= __('No games to show.'); ?></p>
                    </div>
                </div>
                <div class="row" ng-show="hasMoreItemsToShow()" ng-hide="itemsLimit() >= filtered.length || (filtered.length - limited.length) === 0">
                    <div class="col-md-4 offset-md-4 text-center">
                        <button class="btn btn-default text-uppercase" ng-click="showMoreItems()"><?= __('More games'); ?></button></div>
                </div>
                <div class="d-flex justify-content-center align-items-center"  ng-if="Loader">
                    <div class="lds-ellipsis">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div> 
            </uib-tab>
        </uib-tabset>

    </section>
</div>









