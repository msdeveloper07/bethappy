<!-- start footer -->
<div id="pages" ng-hide="isLanding">
    <footer>
        <div class="row d-flex justify-content-center align-items-center flex-wrap text-center small my-4">
            <div class="col-md-6">
                <img src="../Layout/images/footer/devices-img.png" alt="<?= __('Play anywhere, anytime'); ?>" width="100%"/>

            </div>
        </div>


        <div class="row d-flex justify-content-center align-items-center flex-wrap text-center small my-4">
            <div class="col-md-8">

                <p>
                    <?= __('At ' . Configure::read('Settings.websiteName') . ' we give you high end, high class casino experience with all the glamour, best in class slots games from providers like Habanero, Platipus and many more.'); ?>
                </p>
                <p>
                    <?= __(Configure::read('Settings.websiteName') . ' not only gives you a high-end experience in games, we also make sure we keep all of our players happy by giving them highest level customer service and satisfaction.'); ?>
                </p> 
                <p>
                    <?= __(Configure::read('Settings.websiteName') . ' also keep players\' security and safety in mind. We are fully licensed by top renowned gambling authorities. All player data and personal details are totally safe and secure with us. We will never let you down.'); ?>
                </p>

            </div>
        </div>
        <div class="row d-flex justify-content-center align-items-center flex-wrap my-4 row-links"> 
            <div class="d-flex justify-content-center align-items-center flex-wrap">
                <a href="/#/page/about-us"><?= __('About us'); ?></a>
                <a href="#"><?= __('Affiliates'); ?></a>
                <a href="/#/page/bonus-terms"><?= __('Bonus terms'); ?></a>
                <a href="/#/contact-us"><?= __('Contact us'); ?></a>
            </div>
            <div class="d-flex justify-content-center align-items-center flex-wrap">
                <a href="/#/page/terms-of-use"><?= __('Terms of use'); ?></a>
                <a href="/#/page/privacy-policy"><?= __('Privacy policy'); ?></a>
                <a href="/#/page/responsible-gambling"><?= __('Responsible gambling'); ?></a>
                <a href="/#/page/anti-money-laundering"><?= __('Anti-money laundering'); ?></a>
                <a href="/#/page/self-exclusion"><?= __('Self-exclusion'); ?></a>
                <a href="/#/page/complaints"><?= __('Complaints'); ?></a>
                <a href="/#/page/game-rules"><?= __('Game rules'); ?></a>
            </div>


        </div>

        <div class="row d-flex justify-content-center align-items-center flex-wrap text-center row-payments my-4">
            <div class="col-logos">
                <img src="/Layout/Artofslots/images/footer/white/maestro.png" alt="Maestro"/>
                <img src="/Layout/Artofslots/images/footer/white/master-card.png" alt="MasterCard"/>
                <img src="/Layout/Artofslots/images/footer/white/visa.png" alt="Visa"/>
            </div>
            <div class="col-ofage">
                <div class="ofage"> 
                    <img src="/Layout/Artofslots/images/footer/white/18+.png" alt="18+"/>
                </div>

            </div>
        </div>

        <div class="row d-flex justify-content-center align-items-center flex-sm-column flex-md-row row-games my-4">
            <img src="/Layout/Artofslots/images/footer/white/habanero.png" alt="Habanero" height="30"/>
            <img src="/Layout/Artofslots/images/footer/white/platipus.png" alt="Platipus" height="30"/>
<!--            <img src="/Layout/Artofslots/images/footer/white/booongo.png" alt="Booongo" height="30"/>
            <img src="/Layout/Artofslots/images/footer/white/spinomenal.png" alt="Spinomenal" height="30"/>
            <img src="/Layout/Artofslots/images/footer/white/tomhorn.png" alt="Tom Horn" height="30"/>
            <img src="/Layout/Artofslots/images/footer/white/betsoft.png" alt="Betsoft" height="30"/>     -->
        </div>


        <div class="row d-flex justify-content-between align-items-start small my-4">
            <div class="col-md-6 col-responsibility mt-2">
                <p class="text-sm-center text-md-left mb-0"><?= __('Art of Slots is an online casino that continuously strives to provide you with the best range of online slots from different suppliers, all in one place that is easy and fun to use. '); ?></p>

            </div>
            <div class="col-md-6 col-help mt-2">
                <p class="text-sm-center text-md-right">
                    <?= __('Gambling is fun and can be addictive. Please play responsibly.'); ?>
                </p>

                <p class="text-sm-center text-md-right">
                    <a href="https://www.begambleaware.org">
                        <img src="/Layout/Artofslots/images/footer/white/be-gamble-aware.png" alt="Be Gamble Aware" height="50"/>
                    </a> 
                    <a href="http://www.gamcare.org.uk/">
                        <img src="/Layout/Artofslots/images/footer/white/gam-care.png" alt="GamCare" height="50"/>
                    </a> 
                    <a href="http://www.gamblersanonymous.org">
                        <img src="/Layout/Artofslots/images/footer/white/gamblers-anonymous.png" alt="Gamblers Anonymous" height="50"/>
                    </a>

                    <a href="https://www.gordonmoody.org.uk/">
                        <img src="/Layout/Artofslots/images/footer/white/gordon-moody.png" alt="Gordon Moody" height="50"/>
                    </a>
                    <a href="https://www.gamblingtherapy.org/en">
                        <img src="/Layout/Artofslots/images/footer/white/gambling-therapy.png" alt="Gambling Therapy" height="65"/>
                    </a>

                    <a href="https://www.gx4.com/">
                        <img src="/Layout/Artofslots/images/footer/white/gx4.png" alt="Gambling Therapy" height="50"/>
                    </a>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <p class="text-center mb-0 mt-3"><?= __('Copyright ' . date('Y') . ' artofslots.com. All rights reserved.'); ?></p>
            </div>
        </div>
    </footer>
</div>
<div id="landing-pages" ng-show="isLanding">
    <div class="footer">
        <div class="inner_center">
            <div class="footer_icons"><img src="/Layout/images/landing/footer_icons.png" alt=""></div>
        </div>
        <div class="footer_bg"></div>
    </div>
</div>
<!-- end footer -->


