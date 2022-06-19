<main class="pt-0">
    <div ng-controller="SliderController" class="main-carousel">
        <ng-include src="'/Views/view/slider'"></ng-include>
    </div>
    <nav class="navbar justify-content-center w-100" id="game-options-nav">
        <ul class="d-flex flex-wrap justify-content-center mb-0">
            <li class="nav-item active"
                ng-init="selectTab('')" 
                ng-click="selectTab('')" 
                ng-class="{active:isSelected('')}"
                id="all"
                ng-hide="category">
                <a class="nav-link text-uppercase small"><?= __('All'); ?></a>
            </li>

            <li class="nav-item"
                ng-repeat="category in categories| orderObjectBy:'order'"
                ng-click="selectTab(category.id)" 
                ng-class="{active:isSelected(category.id)}"
                id="{{category.id}}-{{category.order}}">
                <a class="nav-link text-uppercase small">{{category.title}}</a>
            </li>
        </ul>
    </nav>
    <div class="container">
        <div class="row no-gutters">
            <div class="col-sm-12 col-md-8 offset-md-4 col-lg-5 offset-lg-7 d-flex justify-content-between align-items-center my-3 filters">
                <div clas="form-group" style="width:49%">
                    <label class="small"><?= __('Filter by provider'); ?></label>
                    <select class="custom-select mr-1" name="filter" id="filter" required
                            ng-options="provider for provider in companies" ng-model="filterByCompany">
                        <option value="" ng-selected="true"><?= __('All'); ?></option>
                    </select>
                </div>
                <div clas="form-group" style="width:49%">
                    <label class="small"><?= __('Search games'); ?></label>
                    <input type="text" class="form-control input-search" name="q" ng-model="filterSearch"/>
                </div>
            </div>
        </div>
    </div>

    <div class="games-content">

        <div class="games-row">

            <div class="card game-card mb-3" ng-repeat="item in limited = (filtered = (games| orderBy: '-item.order' | filter: {category_id: tab} | filter: filterByCompany | filter: filterSearch) | limitTo: itemsLimit())" id="{{item.id}}">
                <img class="card-img game-card-img-top fill content-image" ng-src="{{item.image}}" err-src="{{myDefaultImage}}" alt="{{item.image}}"/>

                <div class="card-img-overlay game-overlay"></div>

                <div class="game-overlay-content">
                    <!--load desktop game link-->
                    <a class="btn btn-lg btn-primary text-uppercase w-100 mb-2" ng-if="loadGameModal() === true && isMobile() === false" href="/#/game/{{item.id}}"><?= __('Play'); ?></a>
                    <!--load mobile game link-->
                    <a class="btn btn-lg btn-primary text-uppercase w-100 mb-2" ng-if="loadGameModal() === true && isMobile() === true" ng-click="loadGameMobile(item.id)" href="javascript://"><?= __('Play'); ?></a>
                    <!--no money dialog-->
                    <a class="btn btn-lg btn-primary text-uppercase w-100 mb-2" ng-if="loadGameModal() === false" role="button" href="javascript://" ng-click="showAdvanced($event, 'real_money_user')"><?= __('Play'); ?></a>

                                <!--<a ng-if="loadGameModal() === 'empty'" class="btn text-uppercase game-btn" role="button" href="javascript://" ng-click="showAdvanced($event, 'register', controllers.Register)"><?= __('Play'); ?></a>-->
                    <!--load desktop game link-->
                    <a class="btn btn-lg btn-secondary text-uppercase w-100" ng-if="item.fun_play == 1 && ((item.desktop == 0 && item.mobile == 1) || isMobile() === true)" href="javascript://" ng-click="loadGameMobile(item.id, true)"><?= __('Demo'); ?></a>
                    <!--load mobile game link-->
                    <a class="btn btn-lg btn-secondary text-uppercase w-100" ng-if="item.fun_play == 1 && ((item.desktop == 1 && item.mobile == 1) || isMobile() === false)" href="/#/game/{{item.id}}/{{true}}"><?= __('Demo'); ?></a>

                </div>
                <div class="game-card-footer">
                    <a href="#">
                        <h6 class="game-title">{{item.name}}</h6>
                        <p class="card-text game-subtitle m-0">
                            <span ng-if="item.brand_id">{{item.brand_name}}</span>
                        </p>
                    </a>
                </div>
            </div>

        </div>

        <div class="container py-5" id="load_more" ng-show="hasMoreItemsToShow()" ng-hide="itemsLimit() >= filtered.length">
            <div class="col text-center">
                <p class="load-more-desc"><?= __('There are %s more games awaiting you', '<strong>{{filtered.length - limited.length}}</strong>'); ?></p>
            </div>
            <div class="col text-center">                           
                <button  class="btn btn-show" ng-click="showMoreItems()" ><?= __('Show more'); ?></button>    
            </div>
        </div>
        <div class="container pt-5" id="no-load_more" ng-hide="hasMoreItemsToShow() && (filtered.length === 0 || limited.length === 0)" ng-show="itemsLimit() >= filtered.length">
            <div class="text-center"><?= __('Showing {{limited.length}} games of {{filtered.length}}'); ?></div>
        </div>
        <div class="container pt-5" id="no-load_more" ng-hide="hasMoreItemsToShow() || (filtered.length === limited.length)" ng-show="filtered.length === 0 || limited.length === 0">
            <div class="text-center"><?= __('There are no games to show. Please try a different search and/or filter.'); ?></div>
        </div>

    </div>

</main>