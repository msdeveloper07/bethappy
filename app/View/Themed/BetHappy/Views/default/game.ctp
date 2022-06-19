<script type = 'text/javascript'>
    window.close = function () {
    $(window).off("resize");
    window.location = '/';
    }
    window.gclose = function () {
    $(window).off("resize");
    window.location = '/';
    }
</script>
<style>
    .app-content, main, .container-fluid, .row, .col-md-12, .card, .card-body{height:100%}
    .game-iframe-container{width:100%;height: 100%;}
</style>

<main>
    <div class="container-fluid">
        <div class="row align-items-center text-center">
            <div class="col-md-12">
                <div class="card launch-card text-white">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class=""><span id="time"></span> {{provider}}-{{game_name}}</h6>
                        <div class="ingame-buttons d-flex justify-content-around align-items-center">

                            <!--Add and remove Favourites-->
                            <button class="btn btn-outline-light rounded-circle game-action-btn ml-1" ng-show="is_favourite == '1'" ng-click="removeFromFavourites()" ng-cloak><i class="icon ion-ios-heart"></i>
                            </button>                     
                            <button class="btn btn-outline-light rounded-circle game-action-btn ml-1" ng-show="is_favourite == '0'" ng-click="addToFavourites()" ng-cloak><i class="icon ion-ios-heart-empty"></i>
                            </button>

                            <button class="btn btn-outline-light rounded-circle game-action-btn ml-1" id="toggle-fullscreen"><i class="icon ion-ios-expand"></i>
                            </button>
                            <button class="btn btn-outline-light rounded-circle game-action-btn ml-1" onClick="$(window).off('resize');
                                window.location = '/'" href="javascript://">
                                <i class="icon ion-md-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="game-iframe-container" ng-bind-html="content" id="game-iframe">></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script>

    const element = document.getElementById('game-iframe');
    document.getElementById('toggle-fullscreen').addEventListener('click', () => {
    if (screenfull.isEnabled) {
    screenfull.request(element);
    }
    });
    function checkTime(i) {
    if (i < 10) {
    i = "0" + i;
    }
    return i;
    }

    function startTime() {
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    // add a zero in front of numbers<10
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById('time').innerHTML = h + ":" + m + ":" + s;
    t = setTimeout(function () {
    startTime()
    }, 500);
    }
    startTime();</script>
