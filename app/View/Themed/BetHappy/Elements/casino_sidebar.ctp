<sidebar>
    <div class="card card-default mb-4">
        <div class="card-body">
            <a href="/#!/account/deposits" class="btn btn-default btn-sm btn-block font-weight-bold text-uppercase px-4 mr-2 mb-4"><?= __('Deposit'); ?></a>
            <div class="loyalty-image"></div>

            <p class="loyalty-points"><?= __('Loyalty points'); ?> <span class="text-yellow-lemon">5,252</span></p>

            <div class="accordion" id="accordionExample">
                <div class="card accordion-card">
                    <div class="card-header collapsed" id="headingOne" data-toggle="collapse" data-target="#collapseAccount" aria-expanded="false" aria-controls="collapseAccount">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-center text-yellow-lemon font-weight-bold mb-0"><?= __('Account menu'); ?></h6>
                            <i class="accordion-icon"></i>
                        </div>
                    </div>

                    <div id="collapseAccount" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">

        
         

                            <ul class="list-unstyled">
                                <li><i class="far fa-question-circle text-yellow-lemon mr-2"></i> <a class="text-white" href="/#!/account/profile"><?= __('Profile');?></a></li>
                                <li><i class="far fa-question-circle text-yellow-lemon mr-2"></i> <a class="text-white" href="/#!/account/kyc"><?= __('KYC');?></a></li>
                                <li><i class="far fa-question-circle text-yellow-lemon mr-2"></i> <a class="text-white" href="/#!/account/limits"><?= __('Limits');?></a></li>
                                <li><i class="far fa-question-circle text-yellow-lemon mr-2"></i> <a class="text-white" href="/#!/account/gameplay"><?= __('Gameplay');?></a></li>
                                <li><i class="far fa-question-circle text-yellow-lemon mr-2"></i> <a class="text-white" href="/#!/account/favorites"><?= __('Favorites');?></a></li>

                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card accordion-card">
                    <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseRewards" aria-expanded="true" aria-controls="collapseRewards">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-center text-yellow-lemon font-weight-bold mb-0"><?= __('Rewards');?></h6>
                            <i class="accordion-icon"></i>
                        </div>
                    </div>

                    <div id="collapseRewards" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li><i class="far fa-question-circle text-yellow-lemon mr-2"></i> <a href="" class="text-white"> <?= __('Promotions');?></a></li>
                                <li><i class="far fa-question-circle text-yellow-lemon mr-2"></i> <a href="" class="text-white"> <?= __('Bonuses');?></a></li>
                                <li><i class="far fa-question-circle text-yellow-lemon mr-2"></i> <a href="" class="text-white"> <?= __('Free Spins');?></a></li>
                                <li><i class="far fa-question-circle text-yellow-lemon mr-2"></i> <a href="" class="text-white"> <?= __('Refer a Friend');?></a></li>
                                <li><i class="far fa-question-circle text-yellow-lemon mr-2"></i> <a href="" class="text-white"> <?= __('Loyalty');?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>



                <div class="card accordion-card">
                    <div class="card-header collapsed" id="headingOne" data-toggle="collapse" data-target="#collapseInformation" aria-expanded="false" aria-controls="collapseInformation">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-center text-yellow-lemon font-weight-bold mb-0"><?= __('Information');?></h6>
                            <i class="accordion-icon"></i>
                        </div>
                    </div>

                    <div id="collapseInformation" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                            <?= __('Information menu');?>
                        </div>
                    </div>
                </div>
<!--                <div class="card accordion-card">
                    <div class="card-header collapsed" id="headingOne" data-toggle="collapse" data-target="#collapseJackpots" aria-expanded="false" aria-controls="collapseJackpots">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-center text-yellow-lemon font-weight-bold mb-0">Jackpots</h6>
                            <i class="accordion-icon"></i>
                        </div>
                    </div>

                    <div id="collapseJackpots" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                            Jackpots menu
                        </div>
                    </div>
                </div>-->


                <div class="card accordion-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-center text-yellow-lemon font-weight-bold mb-0"><?= __('Live Chat');?></h6>
                            <i class="fas fa-headphones-alt text-yellow-lemon"></i>
                        </div>
                    </div>


                </div>
            </div>

        </div>
    </div>
</sidebar>