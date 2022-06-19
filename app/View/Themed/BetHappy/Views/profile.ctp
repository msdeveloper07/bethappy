<div class="container-fluid conatiner-custom-padding">
    <div class="row">
        <div class="col-md-3">
            <?= $this->element('casino_sidebar'); ?>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12 col-lg-6">
                    <div class="card card-gradient">
                        <div class="card-body">
                            <h5 class="card-title"><?= __('Hello'); ?>, <span class="text-white"><?= CakeSession::read('Auth.User.first_name'); ?>  <?= CakeSession::read('Auth.User.last_name'); ?></span></h5>

                            <div class="row my-2">
                                <div class="col-sm-12 col-md-6">
                                    <?= __('Balance'); ?><br>
                                    <h3><span><?= CakeSession::read('Auth.User.Currency.code'); ?> <?= CakeSession::read('Auth.User.balance'); ?></span></h3>
                                </div>
                                <div class="col-sm-12 col-md-6"> <?= __('Bonus Balance'); ?><br>
                                    <h3><span><?= CakeSession::read('Auth.User.Currency.code'); ?> <?= CakeSession::read('Auth.User.ActiveBonus.balance') ? CakeSession::read('Auth.User.ActiveBonus.balance') : '0.00'; ?></span></h3>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6 col-md-6 col-12">
                                    <a href="/#!/account/deposits" class="btn btn-default btn-sm btn-block font-weight-bold px-4 mr-2"><?= __('Deposits'); ?></a>
                                </div>
                                <div class="col-sm-6 col-md-6 col-12">
                                    <a href="/#!/account/withdraws" class="btn btn-default btn-sm btn-block font-weight-bold px-4 mr-2"><?= __('Withdraws'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-lg-6" ng-controller="gameplayController">
                    <div class="card card-gradient">
                        <div class="card-body">
                            <h5 class="card-title"><?= __('Recently Played'); ?></h5>
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap flex-row">
                                        <img ng-repeat="game in gamesLogs | limitTo:5" ng-if="gamesLogs.length > 0" src="{{game.int_games.image}}" ng-src="{{game.int_games.image}}" alt="{{game.int_games.name}}" class="img-responsive mb-2" height="50">

                                        <p  ng-if="gamesLogs.length === 0"><?= __('No games played yet.'); ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6 col-md-6 col-12">
                                    <a href="/#!/account/favorites" class="btn btn-default btn-sm btn-block font-weight-bold px-4 mr-2"><?= __('Favorites'); ?></a>
                                </div>
                                <div class="col-sm-6 col-md-6 col-12">
                                    <a href="/#!/account/gameplay" class="btn btn-default btn-sm btn-block font-weight-bold px-4 mr-2"><?= __('Gameplay'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">

                    <div class="card card-gradient">
                        <div class="card-body">
                            <h5 class="card-title"><?= __('Account Information'); ?></h5>
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="box-outline mb-4">
                                        <span class="small"><?= __('Username'); ?></span><br>
                                        <h3><?= CakeSession::read('Auth.User.username'); ?></h3>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6">
                                    <div class="box-outline mb-4">
                                        <span class="small"><?= __('Email'); ?></span><br>
                                        <h3><?= CakeSession::read('Auth.User.email'); ?></h3>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6">
                                    <div class="box-outline mb-4">
                                        <span class="small"><?= __('Phone number'); ?></span><br>
                                        <h3><?= CakeSession::read('Auth.User.mobile_number'); ?></h3>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6">
                                    <div class="box-outline mb-4">
                                        <span class="small"><?= __('Currency'); ?></span><br>
                                        <h3><?= CakeSession::read('Auth.User.Currency.name'); ?></h3>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6">
                                    <div class="box-outline py-3 mb-4">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="terms" ng-model="User.terms" ng-checked="<?= CakeSession::read('Auth.User.terms'); ?> == 1 ? true : false">
                                            <label class="custom-control-label small" for="terms"><?= __('Terms of use'); ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6">
                                    <div class="box-outline py-3 mb-4">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="newsletter" ng-model="User.newsletter" ng-checked="<?= CakeSession::read('Auth.User.newsletter'); ?> == 1 ? true : false">
                                            <label class="custom-control-label small" for="newsletter"><?= __('Newsletter'); ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6">
                                    <div class="box-outline py-3 mb-4">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="bonus" ng-model="User.bonus_allow" ng-checked="<?= CakeSession::read('Auth.User.bonus_allow'); ?>== 1 ? true : false">
                                            <label class="custom-control-label small" for="bonus"><?= __('Bonus'); ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h5 class="card-title"><?= __('Account Information'); ?></h5>
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="box-outline mb-4">
                                        <span class="small"><?= __('First name'); ?></span><br>
                                        <h3><?= CakeSession::read('Auth.User.first_name'); ?></h3>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6">
                                    <div class="box-outline mb-4">
                                        <span class="small"><?= __('Last name'); ?></span><br>
                                        <h3><?= CakeSession::read('Auth.User.last_name'); ?></h3>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6">
                                    <div class="box-outline mb-4">
                                        <span class="small"><?= __('Date of birth'); ?></span><br>
                                        <h3><?= CakeSession::read('Auth.User.date_of_birth'); ?></h3>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6">
                                    <div class="box-outline mb-4">
                                        <span class="small"><?= __('Gender'); ?></span><br>
                                        <h3><?= CakeSession::read('Auth.User.gender'); ?></h3>
                                    </div>
                                </div>
                            </div>

                            <h5 class="card-title"><?= __('Address Information'); ?></h5>
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <div class="box-outline mb-4">
                                        <span class="small"><?= __('Address'); ?></span><br>
                                        <h3><?= CakeSession::read('Auth.User.address1'); ?></h3>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4">
                                    <div class="box-outline mb-4">
                                        <span class="small"><?= __('Zip/Postal code'); ?></span><br>
                                        <h3><?= CakeSession::read('Auth.User.zip_code'); ?></h3>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-8">
                                    <div class="box-outline mb-4">
                                        <span class="small"><?= __('City'); ?></span><br>
                                        <h3><?= CakeSession::read('Auth.User.city'); ?></h3>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12">
                                    <div class="box-outline mb-4">
                                        <span class="small"><?= __('Country'); ?></span><br>
                                        <h3><?= CakeSession::read('Auth.User.Country.name'); ?></h3>
                                    </div>
                                </div>
                            </div>

                            <p><?= __('Please contact support if you want to make updates to your information.'); ?></p>

                        </div>
                    </div>

                </div>
            </div>            





            <div class="row">
                <div class="col-md-12">
                    <div class="card card-gradient">
                        <div class="card-body">
                            <h5 class="card-title"><?= __('Loyalty'); ?></h5>

                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-overlay text-white">
                        <img src="img/casino/promo.jpg" class="card-img" alt="<?= __('Promo'); ?>">
                        <div class="card-img-overlay">
                            <h5 class="card-title"><?= __('Promo'); ?></h5>
                            <button type="button" class="btn btn-default btn-sm font-weight-bold px-4 mr-2"><?= __('Claim now'); ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-gradient">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="card-title"><?= __('Bonuses'); ?></h5>
                                <button type="button" class="btn btn-inverse btn-sm text-capitalize font-weight-bold px-4"><?= __('Use bonus code'); ?></button>

                            </div>

                            <div class="list-group list-group-default">
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center bonus-wrapper">
                                        <div class="d-flex flex-column">
                                            <p class="mb-1"><?= __('Bonus name'); ?></p>
                                            <h5 class="mb-1 text-yellow-lemon font-weight-bold">Name of Bonus</h5>
                                        </div>

                                        <div class="d-flex flex-column">
                                            <?= __('Amount', true); ?> <span class="text-yellow-lemon font-weight-bold">€20</span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <?= __('Wagering'); ?> <span class="text-yellow-lemon font-weight-bold">€200</span>
                                        </div>
                                        <button type="button" class="btn btn-default btn-sm font-weight-bold px-4 mr-2"><?= __('Activate'); ?></button>
                                    </div>
                                </div>

                                <div class="list-group list-group-default">
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center bonus-wrapper">
                                            <div class="d-flex flex-column">
                                                <p class="mb-1"><?= __('Bonus name'); ?></p>
                                                <h5 class="mb-1 text-yellow-lemon font-weight-bold">Name of Bonus</h5>
                                            </div>

                                            <div class="d-flex flex-column">
                                                <?= __('Amount'); ?> <span class="text-yellow-lemon font-weight-bold">€20</span>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <?= __('Wagering'); ?> <span class="text-yellow-lemon font-weight-bold">€200</span>
                                            </div>
                                            <button type="button" class="btn btn-default btn-sm font-weight-bold px-4 mr-2"><?= __('Activate'); ?></button>
                                        </div>
                                    </div>
                                    <div class="list-group list-group-default">
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center bonus-wrapper">
                                                <div class="d-flex flex-column">
                                                    <p class="mb-1"><?= __('Bonus name'); ?></p>
                                                    <h5 class="mb-1 text-yellow-lemon font-weight-bold">Name of Bonus</h5>
                                                </div>

                                                <div class="d-flex flex-column">
                                                    <?= __('Amount'); ?> <span class="text-yellow-lemon font-weight-bold">€20</span>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <?= __('Wagering'); ?> <span class="text-yellow-lemon font-weight-bold">€200</span>
                                                </div>
                                                <button type="button" class="btn btn-default btn-sm font-weight-bold px-4 mr-2"><?= __('Activate'); ?></button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $("#mobile_number").intlTelInput();
</script>
