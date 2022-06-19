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
                    <div class="row mb-4 ">             
                        <div class="col-md-5ths mb-4 gameBox" ng-repeat="game in limited = (filtered = (Trending | orderBy: '-game.order' | filter: {new : (filterByNew == true ? true: undefined)}| filter: {jackpot : (filterByJackpot == true ? true: undefined)} | filter: category.selected.name | filter: provider.selected.name | filter: filterSearch) | orderBy: sort.selected.sort_type | limitTo: itemsLimit())" id="{{game.id}}">
                            <div class="card card-game card-game-no-slider text-white">
                                <div class="game-hover-effect card-gradient-default ">
                                    <img ng-src="{{game.image}}" alt="{{game.name}}" err-src="{{fallbackImageSrc}}" class="img-responsive "/>
                                    <div class="card-img-overlay">
                                        <div class="game-ribbon" ng-if="game.new === true">
                                            <span class="badge badge-rounded badge-danger text-uppercase px-2 py-1"><?= __('New'); ?></span>
                                        </div>
                                        <h5 class="card-title mb-1 ">{{game.name}}</h5>

                                        <div class="overlay-buttons ">
                                            <a class="btn btn-default btn-play btn-md rounded-pill px-4 mb-2"  href="/#!/game/{{game.id}}">
                                                <i class="fas fa-play "></i>
                                            </a>
                                            <a class="btn btn-light btn-md btn-demo px-4" ng-show="game.fun_play === true"   href="/#!/game/{{game.id}}/{{true}}"><?= __('Demo'); ?></a>
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
                    <div class="col-md-2 offset-md-5 text-center">
                        <button class="btn btn-default btn-block text-uppercase" ng-click="showMoreItems()"><?= __('More games'); ?></button></div>
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
                    <div id="category-slots" class="tab-category">
                        <i class="fas fa-user category-icon"></i>
                        <h2 class="tab-category-name"><?= __('Live Casino'); ?></h2>
                    </div>
                </uib-tab-heading>
                <div ng-if="!Loader">
                    <div class="row mb-4 ">
                        <div class="col-md-5ths mb-4 gameBox" ng-repeat="game in limited = (filtered = (Livecasino | orderBy: '-game.order' | filter: {new : (filterByNew == true ? true: undefined)}| filter: {jackpot : (filterByJackpot == true ? true: undefined)} | filter: category.selected.name | filter: provider.selected.name | filter: filterSearch) | orderBy: sort.selected.sort_type | limitTo: itemsLimit())" id="{{game.id}}">
                            <div class="card card-game card-game-no-slider text-white">
                                <div class="game-hover-effect card-gradient-default ">
                                    <img ng-src="{{game.image}}" alt="{{game.name}}" err-src="{{fallbackImageSrc}}" class="img-responsive "/>
                                    <div class="card-img-overlay">
                                        <div class="game-ribbon" ng-if="game.new === true">
                                            <span class="badge badge-rounded badge-danger text-uppercase px-2 py-1"><?= __('New'); ?></span>
                                        </div>
                                        <h5 class="card-title mb-1 ">{{game.name}}</h5>

                                        <div class="overlay-buttons ">
                                            <button class="btn btn-default btn-play btn-md rounded-pill px-4 mb-2" ng-click="loadGame(game.id, false)">
                                                <i class="fas fa-play "></i>
                                            </button>
                                            <button class="btn btn-light btn-md btn-demo px-4" ng-show="game.fun_play === true"  ng-click="loadGame(game.id, true)">Demo</button>
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
                        <div class="col-md-2 offset-md-5 text-center">
                            <button class="btn btn-default btn-block text-uppercase" ng-click="showMoreItems()"><?= __('More games'); ?></button></div>
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
                    <div id="category-live" class="tab-category">
                        <i class="fas fa-play category-icon"></i>
                        <h2 class="tab-category-name"><?= __('In Play'); ?></h2>
                    </div>
                </uib-tab-heading>

            </uib-tab>

            <uib-tab index="4" heading="Justified">
                <uib-tab-heading>
                    <div id="category-all" class="tab-category">
                        <i class="fas fa-futbol category-icon"></i>
                        <h2 class="tab-category-name"><?= __('Virtual'); ?></h2>
                    </div>
                </uib-tab-heading>
                <div ng-if="!Loader">
                    <div class="row mb-4 ">
                        <div class="col-sm-6 col-md-4 col-xl-3 col-12 mb-4" ng-repeat="game in limited = (filtered = (Games | orderBy: '-game.order' | filter: {new : (filterByNew == true ? true: undefined)}| filter: {jackpot : (filterByJackpot == true ? true: undefined)} | filter: category.selected.name | filter: provider.selected.name | filter: filterSearch) | orderBy: sort.selected.sort_type | limitTo: itemsLimit())" id="{{game.id}}">
                            <div class="card card-game card-game-no-slider text-white">
                                <div class="game-hover-effect card-gradient-default ">
                                    <img ng-src="{{game.image}}" alt="{{game.name}}" err-src="{{fallbackImageSrc}}" class="img-responsive "/>
                                    <div class="card-img-overlay">
                                        <div class="game-ribbon" ng-if="game.new === true">
                                            <span class="badge badge-rounded badge-danger px-2 py-1">NEW</span>
                                        </div>
                                        <h5 class="card-title mb-1 ">{{game.name}}</h5>

                                        <div class="overlay-buttons ">
                                            <button class="btn btn-default btn-play btn-md rounded-pill px-4 mb-2" ng-click="loadGame(game.id, false)">
                                                <i class="fas fa-play "></i>
                                            </button>
                                            <button class="btn btn-light btn-md btn-demo px-4" ng-show="game.fun_play === true"  ng-click="loadGame(game.id, true)">Demo</button>
                                        </div>
                                        <h6 class="card-text smallmt-3 ">{{game.brand_name}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" ng-hide="hasMoreItemsToShow() || (filtered.length === limited.length)" ng-show="filtered.length === 0 || limited.length === 0">
                        <div class="col-md-12 col-12 text-center">
                            <p>No games to show.</p>
                        </div>
                    </div>
                    <div class="row" ng-show="hasMoreItemsToShow()" ng-hide="itemsLimit() >= filtered.length || (filtered.length - limited.length) === 0">
                        <div class="col-sm-6 offset-sm-6 col-md-4 offset-md-8 col-lg-3 offset-lg-9 col-12 text-center">
                            <button class="btn btn-default btn-block text-uppercase" ng-click="showMoreItems()"><?= __('More games'); ?></button></div>
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

            <uib-tab index="5" heading="Justified">
                <uib-tab-heading>
                    <div id="category-search" class="tab-category">
                        <i class="fas fa-search category-icon"></i>
                        <h2 class="tab-category-name">Search</h2>
                    </div>
                </uib-tab-heading>
<!--
                <div class="row">
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <div class="search-wrapper">
                            <input type="text" class="search-game" placeholder="Enter game name or provider" ng-model="filterSearch"/>
                        </div>
                    </div>
                </div>-->

        <?= $this->Form->create('Search'); ?>  
                <div class="row">
                    <div class="col-md-6">

                        <div class="btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-default mr-2 mb-2"  ng-repeat="provider in Providers">
                                <input type="checkbox" value="{{provider.id}}">{{provider.name}}
                            </label>
                        </div>

                        <hr class="border-default">

                        <div class="btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-default mr-2 mb-2"  ng-repeat="category in Categories">
                                <input type="checkbox" value="{{category.id}}">{{category.name}}
                            </label>
                        </div>

                        <hr class="border-default">

                        <div class="btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-default mr-2 mb-2">
                                <input type="checkbox" value="1"><?= __('New'); ?>
                            </label>
                            <label class="btn btn-default mr-2 mb-2">
                                <input type="checkbox" value="1"><?= __('Jackpot'); ?>
                            </label>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="search-wrapper">
                            <input type="text" ng-keydown="searchGames()" class="search-game" ng-model="filterSearch"/>
                        </div>
                    </div>
                </div>

                <?= $this->Form->end(); ?>
                
                

                <div ng-if="!Loader">
                    <div class="row mb-4 mt-4">             
                        <div class="col-md-5ths mb-4 gameBox" ng-repeat="game in limited = (filtered = (Games | orderBy: '-game.order' | filter: {new : (filterByNew == true ? true: undefined)}| filter: {jackpot : (filterByJackpot == true ? true: undefined)} | filter: category.selected.name | filter: provider.selected.name | filter: filterSearch) | orderBy: sort.selected.sort_type | limitTo: itemsLimit())" id="{{game.id}}">
                            <div class="card card-game card-game-no-slider text-white">
                                <div class="game-hover-effect card-gradient-default ">
                                    <img ng-src="{{game.image}}" alt="{{game.name}}" err-src="{{fallbackImageSrc}}" class="img-responsive "/>
                                    <div class="card-img-overlay">
                                        <div class="game-ribbon" ng-if="game.new === true">
                                            <span class="badge badge-rounded badge-danger text-uppercase px-2 py-1"><?= __('New'); ?></span>
                                        </div>
                                        <h5 class="card-title mb-1 ">{{game.name}}</h5>

                                        <div class="overlay-buttons ">
                                            <a class="btn btn-default btn-play btn-md rounded-pill px-4 mb-2"  href="/#!/game/{{game.id}}">
                                                <i class="fas fa-play "></i>
                                            </a>
                                            <a class="btn btn-light btn-md btn-demo px-4" ng-show="game.fun_play === true"   href="/#!/game/{{game.id}}/{{true}}"><?= __('Demo'); ?></a>
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
                    <div class="col-md-2 offset-md-5 text-center">
                        <button class="btn btn-default btn-block text-uppercase" ng-click="showMoreItems()"><?= __('More games'); ?></button></div>
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
<!--<div class="row mb-3">
    <div class="col-md-12">
        <div class="search-wrapper">
            <input type="text" class="search-game" placeholder="Enter game name or provider" ng-model="filterSearch"/>
        </div>
    </div>
</div>-->

<!--<div class="row mb-4" id="gameFilters">
    <div class="col-sm-6 col-md-4 col-xl-3 col-12">
        <div class="game-filter" ng-show="showProviders">
            <ui-select ng-model="provider.selected" theme="selectize">
                <ui-select-match placeholder="Provider">
                    <span>{{$select.selected.name}}</span>
                    <button class="ui-select-clear" ng-click="clearProvider($event)">X</button>
                </ui-select-match>
                <ui-select-choices repeat="provider in Providers | filter: $select.search">
                    <span>{{provider.name}}</span>
                </ui-select-choices>
            </ui-select>
        </div>
    </div>

    <div class="col-sm-6 col-md-4 col-xl-3 offset-xl-3 col-12 py-2">
        <div class="d-flex justify-content-center align-items-center">
            <div class="game-filter mr-3">
                <div class="custom-control custom-checkbox mr-3">
                    <input type="checkbox" class="custom-control-input" id="inputJackpot" ng-model="filterByJackpot">
                    <label class="custom-control-label" for="inputJackpot">Jackpot</label>
                </div>
            </div>
            <div class="game-filter mr-3">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="inputNew" ng-model="filterByNew">
                    <label class="custom-control-label" for="inputNew">New</label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4 col-xl-3 col-12">
        <div class="game-filter">
            <ui-select ng-model="sort.selected" theme="selectize">
                <ui-select-match placeholder="Sort by">
                    <span>{{$select.selected.name}}</span>
                    <button class="ui-select-clear" ng-click="clearSort($event)">X</button>
                </ui-select-match>
                <ui-select-choices repeat="sort in SortBy | filter: $select.search">
                    <span>{{sort.name}}</span>
                </ui-select-choices>
            </ui-select>
        </div>

    </div>

</div>-->








