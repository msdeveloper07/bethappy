<div id="main-carousel" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#main-carousel" data-slide-to="0" class="active"></li>
        <li data-target="#main-carousel" data-slide-to="1"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="/Layout/images/slider/banner-1.jpg" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
            <img src="/Layout/images/slider/banner-2.jpg"  class="d-block w-100" alt="...">
        </div>
    </div>
    <a class="carousel-control-prev" href="#main-carousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only"><?= __('Previous'); ?></span>
    </a>
    <a class="carousel-control-next" href="#main-carousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only"><?= __('Next'); ?></span>
    </a>
</div>


<!--<div ng-controller="SliderController">
    <div style="height: 370px">
        <div uib-carousel active="active" interval="myInterval" no-wrap="noWrapSlides" class="carousel slide" data-ride="carousel">
            <div uib-slide ng-repeat="slide in slides track by slide.id" index="slide.id" >
                <img ng-src="/Layout/images/slider/{{slide.image}}" style="margin:auto;">
            </div>
        </div>
    </div>
</div>-->