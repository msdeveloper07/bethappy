<footer ng-controller="footerController">
    <div class="container-xl">
        <div class="top-footer">
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="bottom-footer-left">                                             
                        <h6 class="font-weight-bold"><?= __('Bet Happy'); ?></h6>
                        <ul class="list-unstyled text-small">
                            <li ng-repeat="menu_item in footer_menus" ng-if="menu_item.MbMenu.type == 'Item' && menu_item.MbMenu.position == 'Column 1'">
                                <a href="#!/{{menu_item.MbMenu.url}}">{{menu_item.MbMenu.tra_title | translate}}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="bottom-footer-right">
                        <h6 class="font-weight-bold"><?= __('Support'); ?></h6>
                        <ul class="list-unstyled text-small">
                            <li ng-repeat="menu_item in footer_menus" ng-if="menu_item.MbMenu.type == 'Item' && menu_item.MbMenu.position == 'Column 2'">
                                <a href="#!/{{menu_item.MbMenu.url}}">{{menu_item.MbMenu.tra_title}}</a>
                            </li>
                        </ul>
                    </div>
                </div>


                <div class="col-md-6 col-lg-2">
                    <div class="bottom-footer-left">
                        <h6 class="font-weight-bold"><?= __('Affiliates'); ?></h6>
                        <ul class="list-unstyled text-small">
                            <li><a href="#!/affiliate-program"><?= __('Affiliate program'); ?></a></li>
                        </ul>

                        <h6 class="font-weight-bold"><?= __('Supported Devices'); ?></h6>
                        <?= $this->Html->image('casino/footer/white/devices.png', array('alt' => __('Supported devices'), 'height' => '50')); ?>

                        <h6 class="font-weight-bold mt-4"><?= __('Social Media'); ?></h6>
                        <ul class="list-unstyled text-small">
                            <li>
                                <a href="#" class="mx-1"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="mx-1"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="mx-1"><i class="fab fa-instagram"></i></a>
                            </li>
                        </ul>

                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <p><?= __('Bet Happy is operated by Mediasoft Technologies N.V. a company registered under the laws of Curaçao having company registration number 153969 and its registered address at Abraham de Veerstraat 9, Curaçao'); ?> 
                        
                        <!--<?//= __('Bet Happy is registered under Continental Solutions Ltd B.V. incorporated under the laws of Curaçao. © 2021 HappyBet. All Rights Reserved.'); ?>-->
                    </p>
                    <?= $this->Html->image('casino/footer/white/18+.png', array('alt' => '18+', 'height' => '30', 'class' => 'mr-2')); ?>

                    <a href="https://begambleaware.org">
                        <?= $this->Html->image('casino/footer/white/be-gamble-aware.png', array('alt' => 'Be Gamble Aware', 'height' => '50')); ?>
                    </a>
                </div>

            </div>
        </div>
    </div>
</footer>


