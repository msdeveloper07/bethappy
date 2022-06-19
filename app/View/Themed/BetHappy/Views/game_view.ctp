<div class="card game-card mb-3" ng-repeat="item in limited = (filtered = (games| orderBy: '-item.order' | filter: {category_id: tab} | filter: filterByCompany | filter: filterSearch)| limitTo: itemsLimit())" id="{{item.id}}">
    <!--<div class="content-overlay"></div>-->
    <img class="card-img game-card-img-top fill content-image" ng-src="{{item.image}}" err-src="{{myDefaultImage}}" alt="{{item.image}}"/>

    <div class="card-img-overlay">
        <!--content-details fadeIn-bottom-->

        <a ng-if="loadGameModal() === true && isMobile() === false" class="btn text-uppercase game-btn" href="/#/game/{{item.id}}"><?= __('Play'); ?></a>

        <a ng-if="loadGameModal() === true && isMobile() === true" class="btn text-uppercase game-btn" ng-click="loadGameMobile(item.id)" href="javascript://"><?= __('Play'); ?></a>
        <a ng-if="loadGameModal() === false" class="btn text-uppercase game-btn" role="button" href="javascript://" ng-click="showAdvanced($event, 'real_money_user')"><?= __('Play'); ?></a>
        <!--<a ng-if="loadGameModal() === 'empty'" class="btn text-uppercase game-btn" role="button" href="javascript://" ng-click="showAdvanced($event, 'register', controllers.Register)"><?= __('Play'); ?></a>-->
        <a class="btn text-uppercase game-btn-outline" ng-if="item.funplay == 1 && isMobile() === true" href="javascript://" ng-click="loadGameMobile(item.id, true)"><?= __('Try it'); ?></a>
        <a class="btn text-uppercase game-btn-outline" ng-if="item.funplay == 1 && isMobile() === false" href="/#/game/{{item.id}}/{{true}}"><?= __('Try it'); ?></a>
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