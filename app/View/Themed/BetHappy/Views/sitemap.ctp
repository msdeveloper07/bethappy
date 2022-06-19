<div class="container-fluid conatiner-custom-padding">
    <div class="row">
        <div class="col-md-10 offset-md-1">


            <div class="row w-100">
                <div class="col-md-12 col-lg-12"> 
                    <h2 class="mb-4"><?= __('Categories'); ?></h2>
                </div>
            </div>

            <div class="row w-100 mb-4">
                <div class="col-sm-6 col-md-3 col-lg-3" ng-repeat="category in Categories">
                    <a href="/#!/games/categories/{{category.slug}}" class="">{{category.name}}</a>
                </div>
            </div>



            <div class="row w-100">
                <div class="col-md-12 col-lg-12"> 
                    <h2 class="mb-4"><?= __('Providers'); ?></h2>
                </div>
            </div>
            <div class="row w-100 mb-4">
                <div class="col-sm-6 col-md-3 col-lg-3" ng-repeat="provider in Providers">
                    <a href="/#!/games/providers/{{provider.slug}}" class="">{{provider.name}}</a>
                </div>
            </div>

            <div class="row w-100">
                <div class="col-md-12 col-lg-12"> 
                    <h2 class="mb-4"><?= __('Pages'); ?></h2>
                </div>
            </div>
            <div class="row w-100 mb-4">
<!--                <div class="col-sm-6 col-md-3 col-lg-3">
                    <a href="/#!/" class="">Casino</a>
                </div>

                <div class="col-sm-6 col-md-3 col-lg-3">
                    <a href="/#!/games/live-casino" class="">Live casino</a>
                </div>-->

                <!--                    <div class="col-sm-6 col-md-3 col-lg-3">
                                        <a href="/#!/jackpots" class="">Jackpots</a>
                                    </div>-->
                <!--                    <div class="col-sm-6 col-md-3 col-lg-3">
                                        <a href="/#!/about-us" class="">About us</a>
                                    </div>-->
                <div class="col-sm-6 col-md-3 col-lg-3">
                    <a href="/#!/affiliates-program"><?= __('Affiliate Program'); ?></a>
                </div>

                <div class="col-sm-6 col-md-3 col-lg-3">
                    <a href="/#!/contact-us" class=""><?= __('Contact us'); ?></a>
                </div>

                <div class="col-sm-6 col-md-3 col-lg-3">
                    <a href="/#!/payment-methods" class=""><?= __('Payment methods'); ?></a>
                </div>

                <!--        <div class="col-sm-6 col-md-3 col-lg-3">
                            <a href="/#!/complaints">Complaints</a>
                        </div>-->

                <div class="col-sm-6 col-md-3 col-lg-3">
                    <a href="/#!/faq"><?= __('FAQ'); ?></a>
                </div>


                <div class="col-sm-6 col-md-3 col-lg-3"><a href="/#!/terms-of-use" class=""><?= __('Terms of use'); ?></a></div>
                <div class="col-sm-6 col-md-3 col-lg-3"><a href="/#!/privacy-policy" class=""><?= __('Privacy policy'); ?></a></div>
                <div class="col-sm-6 col-md-3 col-lg-3"><a href="/#!/deposits-policy" class=""><?= __('Deposits policy'); ?></a></div>
                <div class="col-sm-6 col-md-3 col-lg-3"><a href="/#!/withdrawals-policy" class=""><?= __('Withdrawals policy'); ?></a></div>
                <div class="col-sm-6 col-md-3 col-lg-3"><a href="/#!/refunds-policy" class=""><?= __('Refunds policy'); ?></a></div>
                <div class="col-sm-6 col-md-3 col-lg-3"><a href="/#!/anti-money-laundering-policy" class=""><?= __('Anti-money laundering policy'); ?></a></div>
                <div class="col-sm-6 col-md-3 col-lg-3"><a href="/#!/gdpr-policy" class=""><?= __('GDPR policy'); ?></a></div>
                <div class="col-sm-6 col-md-3 col-lg-3"><a href="/#!/responsible-gaming" class=""><?= __('Responsible gaming'); ?></a></div>


            </div>

        </div>

    </div>
</div>




