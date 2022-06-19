
<section id="slider-section" ng-if="location.path().includes('/casino') ||  location.path().includes('/games') || location.path() == '/' && !location.path().includes('/game')" ng-cloak>
    <div class="container-xl">
        <div class="row">
            <div class="col-md-12 mb-4">
                <div style="height: 260px">
                    <div uib-carousel active="activeSlide" interval="sliderInterval" no-wrap="false">
                        <div uib-slide ng-repeat="slide in Slides track by slide.Slide.id" index="$index">
                            <picture>
                                <source media="(max-width: 768px)" srcset="img/casino/bet-happy/banners/{{slide.Slide.image_mobile}}" />
                                <img ng-src="img/casino/bet-happy/banners/{{slide.Slide.image}}" class="d-block w-100" alt="..." />
                            </picture>
                            <div class="carousel-caption" ng-bind-html="slide.Slide.description"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="slider-section" ng-if="location.path().includes('/account') && (!location.path().includes('/casino') && !location.path().includes('/games') && !location.path().includes('/game') && !location.path() == '/')" ng-cloak>
    <div class="container-xl">
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="jumbotron">
                    <h1 class="display-4 text-uppercase">{{title | translate}}</h1>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="slider-section" ng-if="!location.path().includes('/casino') && !location.path().includes('/games') && location.path().includes('/game') && !location.path() == '/'" ng-cloak></section>