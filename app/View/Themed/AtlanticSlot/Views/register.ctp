
<form name="userForm" id="registrationform" ng-submit="registerForm(userForm)" novalidate>
    <div class="tabsdemoStaticTabs">
        <md-content class="md-padding">
            <md-tabs class="md-accent-2 redister-inprogress" md-selected="data.selectedIndex" md-align-tabs="{{data.bottom ? 'bottom' : 'top'}}">
                <md-tab id="step1" ng-disabled="data.firstLocked">
                    <md-tab-label><span class="step-title"><?= __('Step') . ' 1'; ?></span><span class="step-subtitle"><?= __('Personal Information'); ?></span></md-tab-label>
                    <md-tab-body>
                        <div class="row"><div class="col-md-12"><div class="alert alert-danger" role="alert" ng-show="message">{{message}}</div></div></div>
                        <div class="row">
                            <div class="col-md-6 half-row">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon default-addon" ng-if="userForm.first_name.$invalid && !userForm.first_name.$pristine" style="background: #a94442"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></span>
                                        <span class="input-group-addon default-addon" ng-if="!(userForm.first_name.$invalid && !userForm.first_name.$pristine)"><i class="fa fa-user" aria-hidden="true"></i></span>
                                        <input class="form-control default-input" name="first_name"  type="text" placeholder="<?= __('First name'); ?>" id="first_name" ng-class="{ 'error' : userForm.first_name.$invalid && !userForm.first_name.$pristine}" ng-model="User.first_name" ng-model-options="{updateOn: 'blur'}" ng-minlength="1" required />
                                    </div>
                                    <span ng-show="userForm.first_name.$invalid && !userForm.first_name.$pristine" class="help-block text-right text-danger"><?= __('First name', true); ?> <?= __('is required', true); ?></span>
                                </div>

                                <div class="form-group">
                                    <label class="help-block text-left text-white"><i class="fa fa-calendar" aria-hidden="true"></i> <?= __('Date of birth'); ?></label>
                                    <div class="input-group dateoptions">
                                        <select class="custom-select select-control"
                                                ng-change="checkDate()" 
                                                ng-model="dateSelected.day" 
                                                ng-options="item for item in $root.dateOptions.days" 
                                                id="days" 
                                                placeholder="Day">
                                            <option ng-selected="true" value=""><?= __('Day'); ?></option>
                                        </select>

                                        <select class="custom-select select-control"
                                                ng-change="checkDate()" 
                                                ng-model="dateSelected.month" 
                                                ng-options="$root.monthNames[item] for item in $root.dateOptions.months" 
                                                id="months" placeholder="Month">
                                            <option ng-selected="true" value=""><?= __('Month'); ?></option>
                                        </select>

                                        <select class="custom-select select-control"
                                                ng-change="checkDate()" 
                                                ng-model="dateSelected.year" 
                                                ng-options="item for item in $root.dateOptions.years" 
                                                id="year">
                                            <option ng-selected="true" value=""><?= __('Year'); ?></option>
                                        </select>
                                    </div>
                                    <div ng-show="!dateValid" class="help-block text-left text-danger"><?= __('Date of birth', true); ?> <?= __('is required', true); ?></div>
                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon default-addon" ng-if="userForm.address1.$invalid && !userForm.address1.$pristine" style="background: #a94442"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></span>
                                        <span class="input-group-addon default-addon default-addon" ng-if="!(userForm.address1.$invalid && !userForm.address1.$pristine)"><i class="fa fa-address-book" aria-hidden="true"></i></span>
                                        <input ng-class="{'error' : userForm.address1.$invalid && !userForm.address1.$pristine}" placeholder="<?= __('Address'); ?>" class="form-control default-input"  name="address1" type="text" id="address1" ng-model="User.address1" required/>
                                    </div>
                                    <span ng-show="userForm.address1.$invalid && !userForm.address1.$pristine" class="help-block text-right text-danger"><?= __('Address', true); ?> <?= __('is required', true); ?></span>
                                </div> 

                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon default-addon" ng-if="userForm.mobile_number.$invalid && !userForm.mobile_number.$pristine" style="background: #a94442"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></span>
                                        <span class="input-group-addon default-addon" ng-if="!(userForm.mobile_number.$invalid && !userForm.mobile_number.$pristine)"><i class="fa fa-phone" aria-hidden="true"></i></span>
                                        <input ng-class="{'error': userForm.mobile_number.$invalid && !userForm.mobile_number.$pristine}" placeholder="<?= __('Mobile number'); ?>" type="tel" international-phone-number name="mobile_number" ng-model="User.mobile_number" required id="mobile_number" class="mobileInput">
                                    </div>       
                                    <span ng-show="userForm.mobile_number.$invalid && !userForm.mobile_number.$pristine" class="help-block text-right text-danger"><?= __('Mobile number', true); ?> <?= __('is required', true); ?></span>
                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon default-addon" ng-if="(userForm.email.$invalid && !userForm.email.$pristine) || userForm.email.$error.unique && !userForm.email.$pristine" style="background: #a94442"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></span>
                                        <span class="input-group-addon default-addon"  ng-if="!((userForm.email.$invalid && !userForm.email.$pristine) || userForm.email.$error.unique && !userForm.email.$pristine)"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                        <input ng-class="{'error': userForm.email.$invalid && !userForm.email.$pristine}" placeholder="<?= __('E-mail'); ?>" class="form-control default-input"  name="email" type="email" id="email" ng-model="User.email" ng-model-options="{updateOn: 'blur'}" required ng-change="checkEmail()"/>
                                    </div>        
                                    <span ng-show="userForm.email.$invalid && !userForm.email.$pristine" class="help-block text-right text-danger"><?= __('E-mail:', true); ?> <?= __('is required', true); ?></span>
                                    <span ng-show="userForm.email.$error.unique && !userForm.email.$pristine" class="help-block text-right text-danger"><?= __('E-mail:', true); ?> <?= __('is taken', true); ?></span>
                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon default-addon" ng-if="(userForm.email_confirm.$invalid && !userForm.email_confirm.$pristine) || userForm.email_confirm.$modelValue != userForm.email.$modelValue" style="background: #a94442"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></span>
                                        <span class="input-group-addon default-addon" ng-if="!((userForm.email_confirm.$invalid && !userForm.email_confirm.$pristine) || userForm.email_confirm.$modelValue != userForm.email.$modelValue)"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                        <input ng-class="{'error': userForm.email_confirm.$invalid && !userForm.email_confirm.$pristine}" placeholder="<?= __('Confirm E-mail'); ?>" class="form-control default-input"  name="email_confirm" type="email" id="email_confirm" ng-model="User.email_confirm" required/>
                                    </div>
                                    <span ng-show="userForm.email_confirm.$invalid && !userForm.email_confirm.$pristine" class="help-block text-right text-danger"><?= __('E-mail', true); ?> <?= __('is required', true); ?>.</span>
                                    <span ng-show="userForm.email_confirm.$modelValue != userForm.email.$modelValue" class="help-block text-right text-danger"><?= __('Emails do not match'); ?></span>
                                </div>
                            </div><!--col-md-6-->

                            <div class="col-md-6 half-row">

                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon default-addon" ng-if="userForm.last_name.$invalid && !userForm.last_name.$pristine" style="background: #a94442"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></span>
                                        <span class="input-group-addon default-addon" ng-if="!(userForm.last_name.$invalid && !userForm.last_name.$pristine)"><i class="fa fa-user" aria-hidden="true"></i></span>
                                        <input name="last_name" class="form-control default-input"  type="text" placeholder="<?= __('Last name'); ?>" id="last_name" 
                                               ng-class="{'error' : userForm.last_name.$invalid && !userForm.last_name.$pristine}" ng-model="User.last_name" ng-model-options="{updateOn: 'blur'}" ng-minlength="2" required
                                               />
                                    </div>       
                                    <span ng-show="userForm.last_name.$invalid && !userForm.last_name.$pristine" class="help-block text-right text-danger"><?= __('Last name', true); ?> <?= __('is required', true); ?></span>
                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="form-check form-check-inline" style="margin-bottom: 26px;">
                                            <label class="custom-control custom-radio">
                                                <input type="radio" ng-model="User.gender" name="gender" id="male" value="male" class="custom-control-input">
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description"><?= __('Male'); ?></span>
                                            </label>
                                            <label class="custom-control custom-radio">
                                                <input type="radio" ng-model="User.gender" name="gender" id="female" value="female" class="custom-control-input">
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description"><?= __('Female'); ?></span>
                                            </label>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon default-addon" ng-if="userForm.zip_code.$invalid && !userForm.zip_code.$pristine" style="background: #a94442"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></span>
                                        <span class="input-group-addon default-addon" ng-if="!(userForm.zip_code.$invalid && !userForm.zip_code.$pristine)"><i class="fa fa-hashtag" aria-hidden="true"></i></span>
                                        <input ng-class="{'error' : userForm.zip_code.$invalid && !userForm.zip_code.$pristine}" placeholder="<?= __('Zip / Postal Code'); ?>" class="form-control default-input"  name="zip_code" type="text" id="zip_code"ng-model="User.zip_code" required/>
                                    </div>        
                                    <span ng-show="userForm.zip_code.$invalid && !userForm.zip_code.$pristine" class="help-block text-right text-danger"><?= __('Zip / Postal Code', true); ?> <?= __('is required', true); ?></span>
                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon default-addon" ng-if="userForm.city.$invalid && !userForm.city.$pristine" style="background: #a94442"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></span>
                                        <span class="input-group-addon default-addon" ng-if="!(userForm.city.$invalid && !userForm.city.$pristine)"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                                        <input ng-class="{'error' : userForm.city.$invalid && !userForm.city.$pristine}" class="form-control default-input"  placeholder="<?= __('City'); ?>" name="city" type="text" id="city" ng-model="User.city" required/>
                                    </div>        
                                    <span ng-show="userForm.city.$invalid && !userForm.city.$pristine" class="help-block text-right text-danger"><?= __('City', true); ?> <?= __('is required', true); ?></span>
                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon default-addon" ng-if="selectedCountry.inv" style="background: #a94442"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></span>
                                        <span class="input-group-addon default-addon" ng-if="!selectedCountry.inv"><i class="fa fa-map-marker" aria-hidden="true"></i></span>

                                        <select id="selectcntr" class="selectjs dropdown-toggle custom-select select-control" 
                                                ng-options="countr.name for (countryKey, countr) in countries" ng-model="countryKey"
                                                ng-change="setSelectedField('country', countryKey)"></select>
                                    </div>
                                    <span ng-show="selectedCountry.invalid" class="invalid-feedback"><?= __('Country', true); ?> <?= __('is required', true); ?></span>
                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon default-addon" ng-if="selectedCurr.inv" style="background: #a94442"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></span>
                                        <span class="input-group-addon default-addon" ng-if="!selectedCurr.inv"><i class="fa fa-dollar" aria-hidden="true"></i></span>

                                        <select id="selectcurr" class="selectjs dropdown-toggle custom-select select-control" 
                                                ng-options="curr.name for (currKey, curr) in currencies" ng-model="currKey"
                                                ng-change="setSelectedField('currency', currKey)"></select>
                                    </div>
                                    <span ng-show="selectedCurrency.invalid" class="help-block text-right text-danger"><?= __('Currency', true); ?> <?= __('is required', true); ?></span>
                                </div>


                            </div>
                        </div>
                        <div class="row justify-content-center mt-5">
                            <div class="mx-auto">
                                <button class="btn-modal" ng-click="openSecondStep(userForm)"><?= __('Continue to step 2'); ?> <span class="ml-3"><i class="fa fa-angle-right"></i></span></button>
                            </div>
                        </div>
                    </md-tab-body>
                </md-tab>
                <md-tab id="step2" ng-disabled="data.secondLocked">
                    <md-tab-label><span class="step-title"><?= __('Step') . ' 2'; ?></span><span class="step-subtitle"><?= __('Account Information'); ?></span></md-tab-label>
                    <md-tab-body>
                        <div class="row"><div class="col-md-12"><div class="alert alert-danger" role="alert" ng-show="message">{{message}}</div></div></div>
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon default-addon" ng-if="userForm.username.$invalid && !userForm.username.$pristine" style="background: #a94442"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></span>
                                        <span class="input-group-addon default-addon" ng-if="!(userForm.username.$invalid && !userForm.username.$pristine)"><i class="fa fa-user" aria-hidden="true"></i></span>
                                        <input ng-class="{'error': userForm.username.$invalid && !userForm.username.$pristine}" placeholder="<?= __('Username'); ?>" class="form-control default-input" name="username" type="text" id="username" ng-model="User.username" ng-model-options="{updateOn: 'blur'}" required ng-change="checkUsername()"/>
                                    </div>        
                                    <span ng-show="userForm.username.$invalid && !userForm.username.$pristine" class="help-block text-danger"><?= __('Username', true); ?> <?= __('is required', true); ?></span>
                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon default-addon" ng-if="userForm.password.$invalid && !userForm.password.$pristine" style="background: #a94442"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></span>
                                        <span class="input-group-addon default-addon" ng-if="!(userForm.password.$invalid && !userForm.password.$pristine)"><i class="fa fa-lock" aria-hidden="true"></i></span>
                                        <input ng-class="{'error': userForm.password.$invalid && !userForm.password.$pristine}" placeholder="<?= __('Password'); ?>" class="form-control default-input" name="password" type="password" id="password" ng-model="User.password" required/>
                                    </div>        
                                    <span ng-show="userForm.password.$invalid && !userForm.password.$pristine" class="help-block  text-danger"><?= __('Password', true); ?> <?= __('is required', true); ?></span>
                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon default-addon" ng-if="(userForm.password_confirm.$invalid && !userForm.password_confirm.$pristine) || userForm.password_confirm.$modelValue != userForm.password.$modelValue" style="background: #a94442"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></span>
                                        <span class="input-group-addon default-addon" ng-if="!((userForm.password_confirm.$invalid && !userForm.password_confirm.$pristine) || userForm.password_confirm.$modelValue != userForm.password.$modelValue)"><i class="fa fa-lock" aria-hidden="true"></i></span>
                                        <input ng-class="{'error': userForm.password_confirm.$invalid && !userForm.password_confirm.$pristine}" placeholder="<?= __('Confirm password'); ?>" class="form-control default-input" name="password_confirm" type="password" id="password" ng-model="User.password_confirm" required/>
                                    </div>      
                                    <span ng-show="userForm.password_confirm.$invalid && !userForm.password_confirm.$pristine" class="help-block text-danger"><?= __('Confirm password', true); ?> <?= __('is required', true); ?></span>
                                    <span ng-show="userForm.password_confirm.$modelValue != userForm.password.$modelValue" class="help-block text-danger "><?= __('Your password does not match'); ?></span>
                                </div> 
                                <hr>
                                <div class="text-center mt-5">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="form-check form-check-inline mx-auto">
                                                <?= __("I am over 18 years of age and have read and accepted"); ?><br>
                                                <a href ng-click="$root.showAdvanced($event, 'page', $root.controllers.Page, 'general-terms-conditions')" class="hover-underline load-modal-page"><?= __('Terms And Conditions'); ?></a>
                                                <br>
                                                <label class="custom-control custom-radio">
                                                    <input name="terms" type="radio" ng-model="User.agree" id="agree" value="1" class="custom-control-input">
                                                    <span class="custom-control-indicator"></span>
                                                    <span class="custom-control-description"><?= __('Yes'); ?></span>
                                                </label>
                                                <label class="custom-control custom-radio">
                                                    <input name="terms" type="radio" ng-model="User.agree" id="disagree" value="0"  class="custom-control-input">
                                                    <span class="custom-control-indicator"></span>
                                                    <span class="custom-control-description"><?= __('No'); ?></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <p>
                                        <span ng-show="userForm.agree.$invalid && !userForm.agree.$pristine || (User.agree != 1 && !userForm.agree.$pristine)" class="help-block text-danger"><?= __('You need to accept the Terms Of Use to be able to register', true); ?></span>
                                        <?= __(Configure::read('Config.Platform.age_txt'), true); ?>
                                    </p>
                                    <p><button type="submit" class="btn-modal" ng-disabled="((userForm.$invalid) || (message) || (User.agree != 1 && !userForm.agree.$pristine))"><?= __('Confirm registration', true); ?></button></p>
                                </div>
                            </div>
                        </div>
                    </md-tab-body>
                </md-tab>

                <md-tab id="step3" ng-disabled="data.thirdLocked">
                    <md-tab-label><span class="step-title"><?= __('Step') . ' 3'; ?></span><span class="step-subtitle"><?= __('Successful Registration'); ?></span></md-tab-label>
                    <md-tab-body>
                        <div class="row"><div class="col-md-12"><div class="alert alert-success" role="alert" ng-show="message && !errormessage && !resendmessage">{{message}}!</div></div></div>
                        <div class="row"><div class="col-md-12"><div class="alert alert-success" role="alert" ng-show="!errormessage && resendmessage">{{resendmessage}}!</div></div></div>
                        <div class="row"><div class="col-md-12"><div class="alert alert-danger" role="alert" ng-show="errormessage">{{errormessage}}</div></div></div>
                        <div class="redister-done">
                            <div class="row text-center">
                                <div class="col-12 text-center" style="padding:30px 0;margin-bottom:13px;;"><h1 class="text-uppercase"><?= __('Registration Completed'); ?></h1></div>
                                <hr>
                                <div class="container">
                                    <p class="p-modal"><?= __('Welcome to %s', Configure::read('Settings.defaultTitle')); ?></p>
                                    <p class="text-orange"><?= __('Your account has been created.'); ?></p>
                                    <p class="p-modal"><?= __('You must activate your account in order to sign in and start playing. ') . __('Please look for your activation mail and click on the activation link.'); ?></p>
                                    <!--<p class="p-modal"><?= __('If you wish to change your e-mail address, please press the "I have another e-mail address".'); ?></p>-->
                                    <p class="p-modal"><?= __('If you cannot find your activation mail, you should check your junk, spam folder.'); ?></p>

                                    <div class="row mb-5">
                                        <!--                                        <div class="col-sm-12 p-0 align-self-center">
                                                                                    <p class="p-modal"><a class="link-main link-inverse" role="button" data-toggle="modal" href="#newRegisteredEmail"><?= __('I have another email address.'); ?></a></p>
                                                                                </div>-->

                                        <!--                                        <div class="col-sm-12 p-0 mb-2 align-self-center">
                                                                                    <div class="link-main link-secondary" ng-click="resendCode(User.username, 'confirm')"><?= __('Resend verification mail'); ?></div>
                                                                                </div>-->
                                        <div class="col-sm-12 p-0 align-self-center">
                                            <a class="link-main link-secondary" role="button" ng-click="$root.showAdvanced($event, 'page', $root.controllers.Page, 'contact-us')"><?= __('Need Help?'); ?></a>
                                        </div>

                                    </div>
                                    <div class="col-sm-12 p-0 align-self-center">
                                        <button type="button" class="btn-modal" ng-click="resetDataAfterSuccess()"><?= __('OK'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </md-tab-body>
                </md-tab>
            </md-tabs>
        </md-content>
    </div>
</form>

<script type="text/javascript">
            $(document).ready(function () {
                $("#mobile_number").intlTelInput();
                $(".selectjs").select2();
            });
</script>