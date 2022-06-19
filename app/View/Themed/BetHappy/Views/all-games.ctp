<div class="container-fluid conatiner-custom-padding home">
    <section ng-controler="gamesController">

        <div ng-if="!Loader">
            <div class="row mb-4 mt-4">             
                <div class="col-md-5ths mb-4 gameBox" ng-repeat="game in games = (filtered = (Games | orderBy: '-game.order'))" id="{{game.id}}">
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
    
        <div class="d-flex justify-content-center align-items-center"  ng-if="Loader">
            <div class="lds-ellipsis">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div> 

    </section>
</div>








