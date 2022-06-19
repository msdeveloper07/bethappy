<div class="right_column_content">
    <div class="blue-box-wrapper">
        <div class="blue-box-header"><h3 class="text-shadow"><?= __('Cash Out Your Bets');?></h3></div>
        <div class="blue-box-content">
            <img src="/Layout/images/cash-out-img.jpg"/>
            <p>Control your single pre-match and live bets and enjoy an early payout before an event has finished!</p>
            <button class="btn btn-black darker pull-right" type="button">Learn More</button>
            <div class="clearfix"></div>
            <div class="footerbg"></div>
        </div>
    </div>
    <div class="blue-box-wrapper">
        <div class="blue-box-header"><h3 class="text-shadow"><?= __('Virtual Sports');?></h3></div>
        <div class="blue-box-content">
            <li>
                <button class="btn btn-black darker pull-left" type="button">Virtual Soccer</button>
                <img src="/Layout/images/v-foot.jpg" class="pull-right"/>
                <div class="clearfix"></div>
            </li>
            <li>
                <button class="btn btn-black darker pull-left" type="button">Virtual Horses</button>
                <img src="/Layout/images/v-race.jpg" class="pull-right"/>
                <div class="clearfix"></div>
            </li>
            <li>
                <button class="btn btn-black darker pull-left" type="button">Virtual Dogs</button>
                <img src="/Layout/images/v-dog.jpg" class="pull-right"/>
                <div class="clearfix"></div>
            </li>
            <div class="footerbg"></div>
        </div>
    </div>
    <div class="blue-box-wrapper" ng-controller="BannerController">
        <div ng-if="rightbanner">
            <div class="blue-box-header"><h3 class="text-shadow">{{bannerHeader}}</h3></div>
            <div class="blue-box-content" ng-bind-html="bannerBody"></div>
        </div>
    </div>
    <div class="blue-box-wrapper">
        <div class="blue-box-header"><h3 class="text-shadow"><?= __('Need Help?');?></h3></div>
        <div class="blue-box-content">
            <img src="/Layout/images/help.jpg"/>
            <p>Our support team is here to help</p>
            <button class="btn btn-black darker pull-right" type="button">Learn More</button>
            <div class="clearfix"></div>
            <div class="footerbg"></div>
        </div>
    </div>
    <!-- end help panel -->
</div>