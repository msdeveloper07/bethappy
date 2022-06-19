<!--ng-if="location.path().includes('/home') || location.path().includes('/casino') ||  location.path().includes('/games') || location.path() == '/' && !location.path().includes('/game')" ng-cloak-->

<div ng-controller="headerController" ng-if="!location.path().includes('/game') || !location.path().includes('/account')" ng-cloak>
    <section  class="banners-section">
        <div class="banners-wrapper">
            <div uib-carousel active="activeSlide" interval="sliderInterval" no-wrap="false">
                <div uib-slide ng-repeat="slide in Slides track by slide.id" index="$index">
                    <!--<div style="background: url('https://bh.msztsol.com/img/casino/bet-happy/banners/{{slide.image}}')"></div>-->
                    
                    <picture>
                        <source media="(max-width: 768px)" srcset="https://bh.msztsol.com/img/casino/bet-happy/banners/{{slide.image_mobile}}" />
                        <img ng-src="https://bh.msztsol.com/img/casino/bet-happy/banners/{{slide.image}}" class="d-block w-100 img-responsive" alt="{{slide.title}}" />
                    </picture>
                    <div class="carousel-caption" ng-bind-html="slide.description | trustAsHtml"></div>
                </div>
            </div>
        </div>
    </section>

    <section id="slider-section" ng-if="location.path().includes('/account') && (!location.path().includes('/casino') && !location.path().includes('/games') && !location.path().includes('/game') && !location.path() == '/')" ng-cloak>
        <div class="container-xl">
            <!--for account-->
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="jumbotron">
                        <h1 class="display-4 text-uppercase">{{title | translate}}</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="slider-section" ng-if="location.path().includes('/game')" ng-cloak>
    </section>
</div>

<!--<div ng-show="!location.path().includes('/game') && !location.path().includes('/account')" style="height:100px; background-color: #C4DD31;"></div>-->

