<style>
.bg-dark-blue {
    background-color: #151338 !important;
}
.bg-blue-light {
    background-color: #c4dd31 !important;
}
.deposit {
    overflow-x: hidden;
    border-radius: 30px;
}
.head-title{
    font-size: 1.125rem;
    line-height: 1.2778;
    font-weight: 700;
    margin-bottom: .75rem;
}
.deposit-options {
    padding: 35px !important;
}

.deposit .card-width {
    width: 558px;
}

.deposit .card-width {
    min-width: 320px;
}
.payment-card.small {
    width: 119px !important;
    height: 80px !important;
    margin: initial;
}
.deposit-method-name {
    font-size: 1.4375rem;
    line-height: 1.2609;
    font-weight: 700;
    text-transform:capitalize;
}
.btn.btn-link-secondary-before, .btn.btn-link-secondary-before.btn-lg, .btn-group-lg>.btn.btn-link-secondary-before {
    font-size: 1rem;
}
.btn.btn-link-secondary-before, .btn.btn-link-secondary-before.btn-lg, .btn-group-lg>.btn.btn-link-secondary-before {
    color: #2ae8de;
    font-size: .875rem;
    position: relative;
    margin-left: 11px;
}
.btn.btn-link-secondary-before::before, .btn.btn-link-secondary-before.btn-lg::before, .btn-group-lg>.btn.btn-link-secondary-before::before{
    content: "";
    margin-right: 0.3rem;
    width: 10px;
    height: 10px;
    position: absolute;
    border-left: 5px solid transparent;
    border-top: 5px solid transparent;
    border-bottom: 5px solid transparent;
    border-right: 8px solid #2ae8de;
    left: -16px;
    top: 5px;
}
.btn.btn-link-secondary-before, .btn.btn-link-secondary-before.btn-lg, .btn-group-lg>.btn.btn-link-secondary-before {
    font-size: 1rem;
    text-transform: uppercase;
    font-weight: 700;
}
.mb-0_25, .my-0_25 {
    margin-bottom: .125rem !important;
}

.h4 {
	font-size: 1.125rem !important;
	margin-top: 1rem;
}

.deposit-sum h4, .deposit-sum .h4,
.bonus-selection h4, .bonus-selection .h4 {
    font-size: 1.125rem;
    line-height: 1.2778;
    font-weight: 700;
    margin-top: 1rem;
    margin-bottom: 0;
}

.pl-2_5, .px-2_5 {
    padding-left: 1.25rem !important;
}
.pr-2_5, .px-2_5 {
    padding-right: 1.25rem !important;
}

.deposit-sum .deposit-value-cell:not(:first-child) {
    margin-left: 8px;
}
.deposit-sum .deposit-value-cell {
    height: 50px;
    border-radius: 30px;
    padding: 14px 0 !important;
	color: black;
}
.mt-2_5, .my-2_5 {
    margin-top: 1.25rem !important;
}
.text-primary {
    color: #f8b221 !important;
}
.deposit-sum .deposit-value-cell.active {
    background-color: #ffc107 !important;
    padding: 11px 0;
}
.border-secondary {
    border-color: #2ae8de !important;
}
.deposit .btn-lg, .deposit .btn-group-lg>.btn {
    border-radius: 2rem;
}


.payment-logo {
	width: 210px;
    background-color: #dee2e6 !important;
    border-radius: 18px;
    padding: 10px;
}

.control-label {
	font-size: 1rem;
	font-weight: bold;
	margin-left: 20px;
}

.amount-list {
	display: flex;
	width: 100%;
}

.amount-list .deposit-value-cell {
    height: 50px;
    border-radius: 30px;
    padding: 14px 0 !important;
    color: black;
}

.amount-list .deposit-value-cell.active {
	background-color: #ffc107 !important;
}

.amount-list .deposit-value-cell:not(:first-child) {
    margin-left: 8px;
}

.btn-submit {
	border: none;
	width: 40%;
    padding: 12px 0px;
    border-radius: 100px;
    font-size: 1.1rem;
    font-weight: bold;
}

.form-control {
    -webkit-transition: all 0.2s ease-in-out;
    transition: all 0.2s ease-in-out;
    padding-bottom: 0.85rem !important;
    display: block;
    height: 3.25rem;
    padding: .75rem 1.3rem;
    font-size: .875rem;
    font-weight: bold;
    line-height: 1.57;
    color: black;
    background-color: white;
    background-clip: padding-box;
    border: 1px solid rgba(0,0,0,0);
    border-radius: 2rem;
    -webkit-box-shadow: none;
    box-shadow: none;
}

#payment-details {
    background: #ddffdd;
    padding: 20px;
    color: black;
    border-radius: 8px;
}

#payment-details ul {
    list-style: none;
    padding: 0;
    margin: 0
}

#payment-details ul li {
    display: flex;
    justify-content: space-between;
    line-height: 22px;
}
</style>

<div class="aninda">
    <div class="container">
        <div class="row">
			<div class="col-12">
				<div class="d-flex align-items-center justify-content-center">
					<div class="d-flex align-items-center">
						<img alt="" id="selectedCardImage" width="250" src="" class="payment-logo">

						<div class="ml-3">
							<span class="deposit-method-name mb-0_25" id="cName"><?=$paymentMethod['PaymentMethod']['name']?></span>
							<a href="/payments/withdraws/index">
								<button type="button" class="d-none d-sm-block text-left ml-0_5 p-0 btn btn-link-secondary-before" id="changeMethod"><span><?=__('Change')?></span></button>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>

        <div class="row">  
            <div class="col-md-12">
                <!-- <button class="btn edit btn-link mr-3" onclick="window.history.back();"><i class="fas fa-angle-double-left"></i> <?= __('Back'); ?></button> -->

                <!--                <div class="card default-payment-card">
                                    <div class="card-header">
                
                <button class="btn edit btn-link mr-3" onclick="window.history.back();"><i class="fas fa-angle-double-left"></i></button>  
                <h3>Aninda</h3>
                                    </div>
                                    <div class="card-body">-->

                <?= $this->MyForm->create('Aninda', array('novalidate' => true)); ?>


                <div class="form-group col-8 offset-2 mt-4">
                    <label for="amount"><?= __('Amount'); ?></label>
                    <input type="text" class="form-control" id="amount" name="data[amount]" type="text" value="" 
                           pattern="[0-9]+([\,|\.][0-9]+)?" data-parsley-pattern="[0-9]+([\,|\.][0-9]+)?" 
                           data-parsley-group="group-1"
                           data-parsley-min="<?= $minWithdraw; ?>" data-parsley-max="<?= $maxWithdraw; ?>" 
                           required autocomplete="off"/>
                    <small id="amountHelp" class="form-text font-italic text-muted"><?= __('Minimum amount is %s.', $minWithdraw); ?></small>
                </div>

                <div class="form-group col-8 offset-2">
                    <label for="data[indetity_number]"><?= __('Identity number') ?></label>
                    <input id="identity_number" class="form-control" name="data[identity_number]" type="text" required/>
                </div>

                <?php if ($method == 'AH'): ?>
                    <div class="form-group col-8 offset-2">
                        <label for="data[IBAN]"><?= __('IBAN') ?></label>
                        <input id="depositform-btIBAN" class="form-control" name="data[IBAN]" placeholder="" type="text" required/>
                    </div>
                    <div class="btn-group btn-group-toggle d-flex flex-wrap" data-toggle="buttons">

                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="Garanti" name="data[BanksID]" class="custom-control-input" value="1" /> 
                            <img src="https://bethappy.com/img/casino/payments/banks/garanti.png" width="140"/>
                        </label>

                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="Akbank" name="data[BanksID]" class="custom-control-input" value="2" />
                            <img src="https://bethappy.com/img/casino/payments/banks/akbank.png" width="140"/>
                        </label>


                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="Deniz" name="data[BanksID]" class="custom-control-input" value="3" />
                            <img src="https://bethappy.com/img/casino/payments/banks/denizbank.png" width="140"/>

                        </label>

                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="Finans" name="data[BanksID]" class="custom-control-input" value="4" />
                            <img src="https://bethappy.com/img/casino/payments/banks/finansbank.png" width="140"/>
                        </label>


                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="IsBankasi" name="data[BanksID]" class="custom-control-input" value="5" />
                            <img src="https://bethappy.com/img/casino/payments/banks/isbankasi.png" width="140"/>
                        </label>

                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="Teb" name="data[BanksID]" class="custom-control-input" value="7" />
                            <img src="https://bethappy.com/img/casino/payments/banks/teb.png" width="140"/>
                        </label>

                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="VakifBank" name="data[BanksID]" class="custom-control-input" value="9" />
                            <img src="https://bethappy.com/img/casino/payments/banks/vakifbank.png" width="140"/>
                        </label>


                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="Yapikredi" name="data[BanksID]" class="custom-control-input" value="11" />
                            <img src="https://bethappy.com/img/casino/payments/banks/yapikredi.png" width="140"/>
                        </label>

                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="Ziraat" name="data[BanksID]" class="custom-control-input" value="13" />
                            <img src="https://bethappy.com/img/casino/payments/banks/ziraatbankasi.png" width="140"/>

                        </label>
                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="IngBank" name="data[BanksID]" class="custom-control-input" value="15" />
                            <img src="https://bethappy.com/img/casino/payments/banks/ingbank.png" width="140"/>
                        </label>

                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="Sekerbank" name="data[BanksID]" class="custom-control-input" value="17" />
                            <img src="https://bethappy.com/img/casino/payments/banks/sekerbank.png" width="140"/>

                        </label>

                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="KuveytTurk" name="data[BanksID]" class="custom-control-input" value="19" />
                            <img src="https://bethappy.com/img/casino/payments/banks/kuveytturk.png" width="140"/>

                        </label>

                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="PttBank" name="data[BanksID]" class="custom-control-input" value="23" />
                            <img src="https://bethappy.com/img/casino/payments/banks/pttbank.png" width="140"/>

                        </label>

                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="TurkieFinans" name="data[BanksID]" class="custom-control-input" value="25" />
                            <img src="https://bethappy.com/img/casino/payments/banks/turkiefinans.png" width="140"/>

                        </label>

                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="Halkbank" name="data[BanksID]" class="custom-control-input" value="27" />
                            <img src="https://bethappy.com/img/casino/payments/banks/halkbank.png" width="140"/>

                        </label>

                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="Odeabank" name="data[BanksID]" class="custom-control-input" value="29" />
                            <img src="https://bethappy.com/img/casino/payments/banks/odeabank.png" width="140"/>

                        </label>

                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="Albaraka" name="data[BanksID]" class="custom-control-input" value="30" />
                            <img src="https://bethappy.com/img/casino/payments/banks/albaraka.png" width="140"/>
                        </label>

                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="AktifBank" name="data[BanksID]" class="custom-control-input" value="33" />
                            <img src="https://bethappy.com/img/casino/payments/banks/aktifbank.png" width="140"/>
                        </label>

                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="Papara" name="data[BanksID]" class="custom-control-input" value="31" />
                            <img src="https://bethappy.com/img/casino/payments/banks/papara.png" width="140"/>
                        </label>

                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="Enpara" name="data[BanksID]" class="custom-control-input" value="32" />
                            <img src="https://bethappy.com/img/casino/payments/banks/enpara.png" width="140"/>
                        </label>
                    </div>

                    <!--                            <div class="d-flex justify-content-center align-items-center flex-wrap">
                                                                                    <div class="custom-control custom-radio custom-control-inline">
                                                                                        <input type="radio" id="Garanti" name="data[BanksID]" class="custom-control-input" value="1" />
                                                                                        <label class="custom-control-label" for="Garanti"><img src="https://bethappy.com/img/casino/payments/banks/garanti.png" width="140"/></label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-radio custom-control-inline mb-2">
                                                                                        <input type="radio" id="Akbank" name="data[BanksID]" class="custom-control-input" value="2" />
                                                                                        <label class="custom-control-label" for="Akbank"><img src="https://bethappy.com/img/casino/payments/banks/akbank.png" width="140"/></label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-radio custom-control-inline">
                                                                                        <input type="radio" id="Deniz" name="data[BanksID]" class="custom-control-input" value="3" />
                                                                                        <label class="custom-control-label" for="Deniz"><img src="https://bethappy.com/img/casino/payments/banks/denizbank.png" width="140"/></label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-radio custom-control-inline">
                                                                                        <input type="radio" id="Finans" name="data[BanksID]" class="custom-control-input" value="4" />
                                                                                        <label class="custom-control-label" for="Finans"><img src="https://bethappy.com/img/casino/payments/banks/finansbank.png" width="140"/></label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-radio custom-control-inline mb-2">
                                                                                        <input type="radio" id="IsBankasi" name="data[BanksID]" class="custom-control-input" value="5" />
                                                                                        <label class="custom-control-label" for="IsBankasi"><img src="https://bethappy.com/img/casino/payments/banks/isbankasi.png" width="140"/></label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-radio custom-control-inline mb-2">
                                                                                        <input type="radio" id="Teb" name="data[BanksID]" class="custom-control-input" value="7" />
                                                                                        <label class="custom-control-label" for="Teb"><img src="https://bethappy.com/img/casino/payments/banks/teb.png" width="140"/></label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-radio custom-control-inline mb-2">
                                                                                        <input type="radio" id="VakifBank" name="data[BanksID]" class="custom-control-input" value="9" />
                                                                                        <label class="custom-control-label" for="VakifBank"><img src="https://bethappy.com/img/casino/payments/banks/vakifbank.png" width="140"/></label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-radio custom-control-inline">
                                                                                        <input type="radio" id="Yapikredi" name="data[BanksID]" class="custom-control-input" value="11" />
                                                                                        <label class="custom-control-label" for="Yapikredi"><img src="https://bethappy.com/img/casino/payments/banks/yapikredi.png" width="140"/></label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-radio custom-control-inline mb-2">
                                                                                        <input type="radio" id="Ziraat" name="data[BanksID]" class="custom-control-input" value="13" />
                                                                                        <label class="custom-control-label" for="Ziraat"><img src="https://bethappy.com/img/casino/payments/banks/ziraatbankasi.png" width="140"/></label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-radio custom-control-inline mb-2">
                                                                                        <input type="radio" id="IngBank" name="data[BanksID]" class="custom-control-input" value="15" />
                                                                                        <label class="custom-control-label" for="IngBank"><img src="https://bethappy.com/img/casino/payments/banks/ingbank.png" width="140"/></label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-radio custom-control-inline mb-2">
                                                                                        <input type="radio" id="Sekerbank" name="data[BanksID]" class="custom-control-input" value="17" />
                                                                                        <label class="custom-control-label" for="Sekerbank"><img src="https://bethappy.com/img/casino/payments/banks/sekerbank.png" width="140"/></label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-radio custom-control-inline mb-2">
                                                                                        <input type="radio" id="KuveytTurk" name="data[BanksID]" class="custom-control-input" value="19" />
                                                                                        <label class="custom-control-label" for="KuveytTurk"><img src="https://bethappy.com/img/casino/payments/banks/kuveytturk.png" width="140"/></label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-radio custom-control-inline mb-2">
                                                                                        <input type="radio" id="PttBank" name="data[BanksID]" class="custom-control-input" value="23" />
                                                                                        <label class="custom-control-label" for="PttBank"><img src="https://bethappy.com/img/casino/payments/banks/pttbank.png" width="140"/></label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-radio custom-control-inline mb-2">
                                                                                        <input type="radio" id="TurkieFinans" name="data[BanksID]" class="custom-control-input" value="25" />
                                                                                        <label class="custom-control-label" for="TurkieFinans"><img src="https://bethappy.com/img/casino/payments/banks/turkiefinans.png" width="140"/></label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-radio custom-control-inline">
                                                                                        <input type="radio" id="Halkbank" name="data[BanksID]" class="custom-control-input" value="27" />
                                                                                        <label class="custom-control-label" for="Halkbank"><img src="https://bethappy.com/img/casino/payments/banks/halkbank.png" width="140"/></label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-radio custom-control-inline">
                                                                                        <input type="radio" id="Odeabank" name="data[BanksID]" class="custom-control-input" value="29" />
                                                                                        <label class="custom-control-label" for="Odeabank"><img src="https://bethappy.com/img/casino/payments/banks/odeabank.png" width="140"/></label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-radio custom-control-inline">
                                                                                        <input type="radio" id="Albaraka" name="data[BanksID]" class="custom-control-input" value="30" />
                                                                                        <label class="custom-control-label" for="Albaraka"><img src="https://bethappy.com/img/casino/payments/banks/albaraka.png" width="140"/></label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-radio custom-control-inline">
                                                                                        <input type="radio" id="AktifBank" name="data[BanksID]" class="custom-control-input" value="33" />
                                                                                        <label class="custom-control-label" for="AktifBank"><img src="https://bethappy.com/img/casino/payments/banks/aktifbank.png" width="140"/></label>
                                                                                    </div>
                    
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="Papara" name="data[BanksID]" class="custom-control-input" value="31" />
                                                        <label class="custom-control-label" for="Papara"><img src="https://bethappy.com/img/casino/payments/banks/papara.png" width="140"/></label>
                                                    </div>
                    
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="Enpara" name="data[BanksID]" class="custom-control-input" value="32" />
                                                        <label class="custom-control-label" for="Enpara"><img src="https://bethappy.com/img/casino/payments/banks/enpara.png" width="140"/></label>
                                                    </div>
                    
                    
                                                </div>-->
                <?php endif; ?>


                <?php if ($method == 'AP'): ?>
                    <div class="form-group col-8 offset-2">
                        <label for="data[papara_account_number]"><?= __('Papara account number') ?></label>
                        <input id="papara_account_number" class="form-control" name="data[papara_account_number]" type="text" required/>
                    </div>
                <?php endif; ?>
                <?php if ($method == 'AM'): ?>
                    <div class="form-group col-8 offset-2">
                        <label for="data[mefete_account_number]"><?= __('Mefete account number') ?></label>
                        <input id="mefete_account_number" class="form-control" name="data[mefete_account_number]" type="text" required/>
                    </div>
                <?php endif; ?>
                <?php if ($method == 'ABTC'): ?>

                    <div class="form-group col-8 offset-2">
                        <label for="data[btc_wallet_address]"><?= __('Wallet address') ?></label>
                        <input id="btc_wallet_address" class="form-control" name="data[btc_wallet_address]" type="text" required/>
                    </div>
                <?php endif; ?>
                <?php if ($method == 'ACCW'): ?>
                    
                        <div class="form-group col-8 offset-2">
                    <label for="data[credit_card_number]"><?= __('Credit card number') ?></label>
                    <input id="credit_card_number" class="form-control" name="data[credit_card_number]" required="required" data-parsley-creditcard="" type="tel"/>
                </div>
                    
                    
                    
                    <div class="btn-group btn-group-toggle d-flex flex-wrap" data-toggle="buttons">

                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="Garanti" name="data[BanksID]" class="custom-control-input" value="35" /> 
                            <img src="https://bethappy.com/img/casino/payments/banks/garanti.png" width="140"/>
                        </label>
                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="Deniz" name="data[BanksID]" class="custom-control-input" value="36" />
                            <img src="https://bethappy.com/img/casino/payments/banks/denizbank.png" width="140"/>

                        </label>
                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="Finans" name="data[BanksID]" class="custom-control-input" value="37" />
                            <img src="https://bethappy.com/img/casino/payments/banks/finansbank.png" width="140"/>
                        </label>
                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="IsBankasi" name="data[BanksID]" class="custom-control-input" value="38" />
                            <img src="https://bethappy.com/img/casino/payments/banks/isbankasi.png" width="140"/>
                        </label>
                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="Teb" name="data[BanksID]" class="custom-control-input" value="39" />
                            <img src="https://bethappy.com/img/casino/payments/banks/teb.png" width="140"/>
                        </label>
                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="VakifBank" name="data[BanksID]" class="custom-control-input" value="40" />
                            <img src="https://bethappy.com/img/casino/payments/banks/vakifbank.png" width="140"/>
                        </label>
                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="Ziraat" name="data[BanksID]" class="custom-control-input" value="41" />
                            <img src="https://bethappy.com/img/casino/payments/banks/ziraatbankasi.png" width="140"/>
                        </label>
                        <label class="px-4 btn btn-default-outline col-md-6 col-lg-3">
                            <input type="radio" id="Halkbank" name="data[BanksID]" class="custom-control-input" value="44" />
                            <img src="https://bethappy.com/img/casino/payments/banks/halkbank.png" width="140"/>
                        </label>
                    </div>



                    <!--                            <div class="d-flex justify-content-center align-items-center flex-wrap">
                                                                                    <div class="custom-control custom-radio custom-control-inline mb-2">
                                                                                        <input type="radio" id="Garanti" name="data[BanksID]" class="custom-control-input" value="35" />
                                                                                        <label class="custom-control-label" for="Garanti"><img src="https://bethappy.com/img/casino/payments/banks/garanti.png" width="140"/></label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-radio custom-control-inline mb-2">
                                                                                        <input type="radio" id="Deniz" name="data[BanksID]" class="custom-control-input" value="36" />
                                                                                        <label class="custom-control-label" for="Deniz"><img src="https://bethappy.com/img/casino/payments/banks/denizbank.png" width="140"/></label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-radio custom-control-inline mb-2">
                                                                                        <input type="radio" id="Finans" name="data[BanksID]" class="custom-control-input" value="37" />
                                                                                        <label class="custom-control-label" for="Finans"><img src="https://bethappy.com/img/casino/payments/banks/finansbank.png" width="140"/></label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-radio custom-control-inline mb-2">
                                                                                        <input type="radio" id="IsBankasi" name="data[BanksID]" class="custom-control-input" value="38" />
                                                                                        <label class="custom-control-label" for="IsBankasi"><img src="https://bethappy.com/img/casino/payments/banks/isbankasi.png" width="140"/></label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-radio custom-control-inline mb-2">
                                                                                        <input type="radio" id="Teb" name="data[BanksID]" class="custom-control-input" value="39" />
                                                                                        <label class="custom-control-label" for="Teb"><img src="https://bethappy.com/img/casino/payments/banks/teb.png" width="140"/></label>
                                                                                    </div>
                                                                                    <div class="custom-control custom-radio custom-control-inline mb-2">
                                                                                        <input type="radio" id="VakifBank" name="data[BanksID]" class="custom-control-input" value="40" />
                                                                                        <label class="custom-control-label" for="VakifBank"><img src="https://bethappy.com/img/casino/payments/banks/vakifbank.png" width="140"/></label>
                                                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline mb-2">
                                                        <input type="radio" id="Ziraat" name="data[BanksID]" class="custom-control-input" value="41" />
                                                        <label class="custom-control-label" for="Ziraat"><img src="https://bethappy.com/img/casino/payments/banks/ziraatbankasi.png" width="140"/></label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="Yapikredi" name="data[BanksID]" class="custom-control-input" value="42" />
                                                        <label class="custom-control-label" for="Yapikredi"><img src="https://bethappy.com/img/casino/payments/banks/yapikredi.png" width="140"/></label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="Halkbank" name="data[BanksID]" class="custom-control-input" value="44" />
                                                        <label class="custom-control-label" for="Halkbank"><img src="https://bethappy.com/img/casino/payments/banks/halkbank.png" width="140"/></label>
                                                    </div>
                                                </div>-->
                <?php endif; ?>

                <div class="form-group submit-group text-center mx-auto mt-4">
                    <?= $this->MyForm->button(__('Request withdraw', true), array('type' => 'submit', 'class' => 'btn btn-default rounded-pill px-4 btn-withdraw', 'div' => false)); ?> 
                </div> 

                <?= $this->MyForm->end(); ?>   

            </div>
        </div>

    </div>
</div>



<script>

    $('#selectedCardImage').attr('alt', '<?=$paymentMethod['PaymentMethod']['name']?>');
    $('#selectedCardImage').attr('src', '<?=$paymentMethod['PaymentMethod']['image']?>');

    $(function () {
        $('#AnindaWithdrawForm').parsley()
                .on('field:validated', function () {
                    var valid = $('.parsley-error').length === 0;
                    $('.message-success').toggleClass('d-none', !valid);
                    $('.message-danger').toggleClass('d-none', valid);
                })
                .on('form:submit', function () {
                    $('.btn-withdraw').hide();
                    $('.submit-group').append('<div id="loading"></div>');
                });
    });

    function resizeIframe() {
        let iframe = parent.document.querySelector("#withdraw-iframe");
        iframe.style.height = iframe.contentWindow.document.body.offsetHeight + 'px';
    }
    
    $(document).ready(function() {
        resizeIframe();
    });
</script>
