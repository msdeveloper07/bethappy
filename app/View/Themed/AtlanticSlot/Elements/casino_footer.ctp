<footer>
    <div class="container-fluid">
        <div class="row casino">
            <div class="col-md-4">
                <div class="about">
                    <small class="d-block mb-3 text-muted">
                        <div class="mb-3">
                            <?= __('Atlantic Slot Online Casino is a new and innovative casino where you can play some of the best games, from some of the best providers. Visit! Play! Win!'); ?>
                            <!--                        Atlantic Slot Casino is a new and innovative casino where you can play some of the best games, from some of the best providers.<br/>
                                                    Our priority is to deliver a wide range of top games which will satisfy even the most demanding players.<br/>
                                                    Visit! Play! Win!<br/>-->
                            <!--                        <div>Atlantic Slot casino is powered by</div> 
                                                    <div>ASTRA DIGITAL LTD,</div> 
                                                    <div>str. Georgi Sava Rakovski 161A, et.3, ap.8</div>
                                                    <div  class="mb-3">Sofia, Bulgaria.</div> -->
                        </div>
                        <div class="mb-3">E-mail: <a class="text-link-default" href="mailto:<?= Configure::read('Settings.websiteEmail'); ?>"><?= Configure::read('Settings.websiteEmail'); ?></a></div> 

                        <div class="mb-3">Phone: <a class="text-link-default" href="tel:<?= Configure::read('Settings.websiteContactNumber'); ?>"><?= Configure::read('Settings.websiteContactNumber'); ?></a></div> 
                        <div>Copyright © <?= date('Y') ?> Atlantic Slot Casino. All rights reserved.</div>
                    </small>
                </div>

            </div>
            <div class="col-md-4">
                <ul class="misc list-unstyled d-block">
                    <li>
                        <?= $this->Html->image('casino/footer/white/18+.png', array('alt' => '18+', 'height' => '50')); ?>
                        <small>18 and over only</small>
                    </li>
                    <!--                    <li>
                                            <a href="https://itechlabs.com/">
                    <?= $this->Html->image('casino/footer/white/i-tech-labs.png', array('alt' => 'iTech Labs Game Payout Validation Security', 'height' => '50')); ?> 
                                                <small>Game Payout Validation Security</small>
                                            </a>
                                        </li>-->
                    <li>
                        <?= $this->Html->image('casino/footer/white/devices.png', array('alt' => 'Play on any device', 'height' => '50')); ?>
                        <small>Play on any device</small>
                    </li>
                    <li>
                        <div class="social d-flex justify-content-start align-items-center my-3">
                            <a href="https://www.facebook.com/AtlanticSlotWhereWinnersPlay"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://www.instagram.com/atlanticslot/"><i class="fab fa-instagram"></i></a>
                            <!--<a href=""><i class="fab fa-youtube"></i></a>-->
                        </div>

                    </li>
                </ul>
            </div>
            <div class="col-md-4">
                <p><small>Gambling is fun and can be addictive. Please play responsibly.</small></p>
                <ul class="payments list-unstyled d-block">
                    <li>
                        <a href="https://gamblersanonymous.org">
                            <?= $this->Html->image('casino/footer/white/gamblers-anonymous.png', array('alt' => 'Gamblers Anonymous', 'height' => '50')); ?>
                        </a>
                    </li>
                    <li>
                        <a href="https://gamcare.org.uk">
                            <?= $this->Html->image('casino/footer/white/gam-care.png', array('alt' => 'Gam Care', 'height' => '50')); ?>
                        </a>
                    </li>
                    <li>
                        <a href="https://begambleaware.org"> 
                            <?= $this->Html->image('casino/footer/white/be-gamble-aware.png', array('alt' => 'Be Gamble Aware', 'height' => '50')); ?>
                        </a>
                    </li>
                    <li>
                        <a href="https://gamblingtherapy.org"> 
                            <?= $this->Html->image('casino/footer/white/gambling-therapy.png', array('alt' => 'Gambling Therapy', 'height' => '50')); ?>
                        </a>
                    </li>
                    <!--                    <li>
                                            <a href="https://www.gordonmoody.org.uk">
                    <?= $this->Html->image('casino/footer/white/gordon-moody.png', array('alt' => 'Gordon Moody', 'height' => '50')); ?>
                                            </a>
                                        </li>-->
                    <li>
                        <a href="https://www.gx4.com">
                            <?= $this->Html->image('casino/footer/white/gx4.png', array('alt' => 'GX4', 'height' => '50')); ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <ul class="footer-menu list-unstyled d-block text-center text-uppercase">
                    <li><a href="/#!/about-us">About Us</a></li>
                    <li><a href="/#!/affiliates-program">Affiliates program</a></li>
                    <li><a href="/#!/payments">Payments</a></li>
                    <li><a href="/#!/jackpots">Jackpots</a></li>
                    <!--<li><a href="/#!/self-exclusion">Self-exclusion </a></li>-->
                    <!--<li><a href="/#!/complaints">Complaints </a></li>-->
                    <li><a href="/#!/faq">FAQ</a></li>
                    <li><a href="/#!/contact-us">Contact us</a></li>
                    <li><a href="/#!/sitemap">Sitemap</a></li>
                </ul>
            </div>
            <div class="col-md-12">
                <ul class="footer-menu list-unstyled d-block text-center text-uppercase">
                    <li><a href="/#!/terms-of-use">Terms of use</a></li>
                    <li><a href="/#!/privacy-policy">Privacy policy</a></li>
                    <li><a href="/#!/deposits-policy">Deposits policy</a></li>
                    <li><a href="/#!/withdrawals-policy">Withdrawals policy</a></li>
                    <li><a href="/#!/refunds-policy">Refunds policy</a></li>
                    <li><a href="/#!/anti-money-laundering-policy">Anti-money laundering policy</a></li>
                    <li><a href="/#!/gdpr-policy">GDPR policy</a></li>
                    <li><a href="/#!/responsible-gaming">Responsible gaming</a></li>
                </ul>
            </div>
        </div>
        <hr>
        <!--<div class="row">
            <div class="col-md-12">
                <ul class="games list-unstyled d-block text-center text-uppercase">
                    <li><?= $this->Html->image('casino/footer/white/betsoft.png', array('alt' => 'Betsoft', 'height' => '24')); ?></li>
                    <li><?= $this->Html->image('casino/footer/white/booongo.png', array('alt' => 'Booongo', 'height' => '24')); ?></li>
                    <li><?= $this->Html->image('casino/footer/white/habanero.png', array('alt' => 'Habanero', 'height' => '24')); ?></li>
                    <li><?= $this->Html->image('casino/footer/white/spinomenal.png', array('alt' => 'Spinomenal', 'height' => '24')); ?></li>
                    <li><?= $this->Html->image('casino/footer/white/tomhorn.png', array('alt' => 'Tom Horn', 'height' => '24')); ?></li>
                    <li><?= $this->Html->image('casino/footer/white/platipus.png', array('alt' => 'Platipus', 'height' => '24')); ?></li>
                </ul>
            </div>
        </div>
        <hr class="hr-sm-show">-->
        <div class="row">
            <div class="col-md-12">
                <ul class="payments list-unstyled d-block text-center text-uppercase mb-0">
<!--                    <li><?//= $this->Html->image('casino/footer/white/visa.png', array('alt' => 'VISA', 'height' => '20')); ?></li>
                    <li><?//= $this->Html->image('casino/footer/white/master-card.png', array('alt' => 'MasterCard', 'height' => '25')); ?></li>
                    <li><?//= $this->Html->image('casino/footer/white/maestro.png', array('alt' => 'Maestro', 'height' => '25')); ?></li>-->
                    <li><?= $this->Html->image('casino/footer/white/bitcoin.png', array('alt' => 'Bitcoin', 'height' => '20')); ?></li>
                    <li><?= $this->Html->image('casino/footer/white/bitcoin-cash.png', array('alt' => 'Bitcoin Cash', 'height' => '20')); ?></li>
                    <li><?= $this->Html->image('casino/footer/white/dash.png', array('alt' => 'DashФ', 'height' => '20')); ?></li>
                    <li><?= $this->Html->image('casino/footer/white/litecoin.png', array('alt' => 'Litecoin', 'height' => '25')); ?></li>
                    <li><?= $this->Html->image('casino/footer/white/ethereum.png', array('alt' => 'Ethereum', 'height' => '25')); ?></li>
                </ul>
            </div>
        </div>
        <!--<hr class="hr-sm-show">
               <div class="row">
                    <div class="col-md-12">
                        <ul class="payments list-unstyled d-block text-center text-uppercase">
                            <li><?= $this->Html->image('casino/footer/white/gambling-judge.png', array('alt' => 'Gambling Judge', 'height' => '32')); ?></li>
                            <li><?= $this->Html->image('casino/footer/white/ask-gamblers.png', array('alt' => 'Ask Gamblers', 'height' => '32')); ?></li>
                            <li><a href="http://gaming-curacao.com"><?= $this->Html->image('casino/footer/white/gaming-curacao.png', array('alt' => 'Gaming Curacao', 'height' => '50')); ?></a></li>
                            <li><a href="https://mga.org.mt"><?= $this->Html->image('casino/footer/white/mga.png', array('alt' => 'Malta Gaming Authority', 'height' => '50')); ?></a></li>
                        </ul>
                    </div>
                </div>-->
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-center align-items-start licence">
                    <div class="d-flex justify-content-center align-items-center px-2">
                        <a href="https://validator.digital/validate?domain=atlanticslot.com&seal_id=Z4VIz4gvnb0NQ6KHUej1lpCiYVbFdArA2Hw9RdkPyeftFGnaiwDEsPm7JjuLWSxMBDhZ72KT56xIkhqQtrmgGozaM0uf9cX83U15">
                            <?= $this->Html->image('casino/footer/seals/costa-rica-seal.png', array('alt' => 'Costa Rica', 'height' => '72')); ?>
                        </a>
                    </div>
                    <p class="text-muted m-0 px-2">
                        <small>Copyright © <?= date('Y') ?>, atlanticslot.com is licensed by NEW ONLINE ENTRETAINMENT, S.A a company registered and established under the laws of Costa Rica with No. 3-101-683934, and its registered address is Avenida 2 entre calles 38 y 40, San Jose, Costa Rica.<br/>
                            atlanticslot.com is marketed by NEW ONLINE ENTRETAINMENT, S.A. The domain is ownership of Astra Digital Ltd with registered address at str.Georgi Sava Rakovski 161A et.3/ap.8, Sofia, Bulgaria with registration number 204972870.<br/> 
                            It is the player’s sole responsibility to inquire about the existing laws and regulations of the given jurisdiction for online gaming.<br/>
                            Please play responsibly!
                        </small>
                    </p>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-center align-items-start">
                    <p class="text-muted m-0 px-2">Powered by <a href="https://digitalpoint.solutions/"><img src="https://digitalpoint.solutions/img/logos/website_logo_transparent_background.png" height="50"/></a></p>
                </div>
            </div>
        </div>
    </div>
</footer>
<!--SMART SOLUTIONS SERVICES LTD, str. Volgogradska 4/4-7 Skopje, North Macedonia. Astra Digital Ltd with registered address at str.Georgi Sava Rakovski 161A et.3/ap.8, Sofia, Bulgaria with registration number 204972870.-->