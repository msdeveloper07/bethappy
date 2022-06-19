<ul class="navbar-nav flex-row mr-auto">

    <li class="nav-item ml-2" ng-repeat="item in menu" ng-if="!item.sub && item.active == 1" ng-click="activateMenuItem(item.id)" ng-class="{'active':item.id == activeMenuItem}">
        <a ng-click="reloadRoute(item.url)" class="nav-link text-uppercase" href="{{item.url}}">
            <span ng-if="item.id === '1'"><?=('Home')?></span>
            <span ng-if="item.id !== '1'"><?= __('{{item.title}}'); ?></span>
        </a>
    </li>

</ul>