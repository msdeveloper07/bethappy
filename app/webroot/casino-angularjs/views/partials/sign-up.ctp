<div class="default-modal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="{{'Close' | translate}}" ng-click="$root.closeSignUpModal()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="modal-body">
        <div class="container-fluid">
            <div class="row no-gutter">
                <div class="col-sm-12 col-md-12 col-lg-12">

                    <div class="text-center mx-auto main-brand mb-3">
                        <img class="text-center" src="img/casino/bet-happy-logo-lg.png"/>
                    </div>

                    <h3 class="text-center font-weight-bold mb-4">{{'Welcome!' | translate}}</h3>

                    <form role="form" name="userSignUpForm" novalidate class="needs-validation mt-4">
                        <wizard indicators-position="top" name="signUpWizard"> 
                            <wz-step wz-title="" canexit="validateSignUpStep1">

                                <legend class="no-border f-w-700 p-b-0 m-t-0 m-b-20 f-s-16 text-inverse">
                                    {{'Account information' | translate}}
                                </legend>
                                <div id="form-step-0" role="form" data-toggle="validator">

                                    <div class="form-group">
                                        <label for="inputUsername">{{'Username' | translate}}*</label>
                                        <input type="text" id="inputUsername" name="username" ng-model="userSignUp.username" ng-required="true" autofocus=""  
                                               class="form-control" ng-class="{'is-invalid': userSignUpForm.username.$error.required && !userSignUpForm.username.$pristine}"/>
                                        <span ng-show="userSignUpForm.username.$invalid && !userSignUpForm.username.$pristine" class="help-block text-danger">{{'Username is required.' | translate}}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail">{{'E-mail' | translate}}*</label>
                                        <input type="email" id="inputEmail" name="email" ng-model="userSignUp.email" ng-pattern="emailFormat" ng-required="true" autofocus="" class="form-control" 
                                               ng-class="{'is-invalid': userSignUpForm.email.$error.required && !userSignUpForm.email.$pristine}"/>
                                        <span ng-show="userSignUpForm.email.$error.required && !userSignUpForm.email.$pristine" class="text-danger">{{'E-mail is required.' | translate}}</span>
                                        <span ng-show="userSignUpForm.email.$error.pattern && !userSignUpForm.email.$pristine" class="text-danger">{{'This is not a valid e-mail address.' | translate}}</span>
                                        <span ng-show="userSignUpForm.email.$error.uniqueField" class="text-danger">{{'E-mail is already taken!' | translate}}</span>
                                    </div> 

                                    <div class="form-group">
                                        <label for="inputMobileNumber">{{'Phone number' | translate}}*</label>
                                        <input class="form-control" type="tel" id="inputMobile" name="mobile_number" ng-model="userSignUp.mobile_number" ng-required="true" autofocus="" ng-intl-tel-input ng-intl-tel-input-options="intlTelInputOptions"/>
                                        <span ng-show="userSignUpForm.mobile_number.$invalid && !userSignUpForm.mobile_number.$pristine" class="help-block text-danger">{{'Phone number is required.' | translate}}</span>

                                    </div>

                                    <div class="form-group">
                                        <label for="inputCurrency">{{'Currency' | translate}}*</label>
                                        <ui-select ng-model="userSignUpCurrency.selected" theme="selectize" ng-required="true">
                                            <ui-select-match placeholder="">
                                                {{$select.selected.name}}
                                            </ui-select-match>
                                            <ui-select-choices repeat="userSignUpCurrency in Currencies | filter: $select.search">
                                                <span>{{userSignUpCurrency.name}}</span>
                                            </ui-select-choices>
                                        </ui-select>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputLanguage">{{'Language' | translate}}*</label>
                                        <ui-select ng-model="userSignUpLanguage.selected" theme="selectize" ng-required="true">
                                            <ui-select-match placeholder="">
                                                <img ng-src="https://flagcdn.com/{{($select.selected.ISO6391_code | lowercase) == 'en' ? 'gb' : (($select.selected.ISO6391_code | lowercase) == 'hi' ? 'in' : $select.selected.ISO6391_code | lowercase)}}.svg" width="22"/>
                                                {{$select.selected.name}}
                                            </ui-select-match>
                                            <ui-select-choices repeat="userSignUpLanguage in Languages | filter: $select.search">
                                                <img ng-src="https://flagcdn.com/{{(userSignUpLanguage.ISO6391_code | lowercase) == 'en' ? 'gb' : ((userSignUpCountry.ISO6391_code | lowercase) == 'hi' ? 'in' : userSignUpLanguage.ISO6391_code | lowercase)}}.svg" width="22"/>

                                                <span>{{userSignUpLanguage.name}}</span>
                                            </ui-select-choices>
                                        </ui-select>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputPassword">{{'Password' | translate}}*</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control passwordStrengthMeter" id="inputPassword" name="password" ng-model="userSignUp.password" ng-attr-type="{{showSignUpPassword ? 'text':'password'}}"
                                                   ng-class="{'is-invalid' : userSignUpForm.password.$error.required && !userSignUpForm.password.$pristine}" ng-required="true"/>
                                            <div class="input-group-append" style="cursor: pointer;" ng-click="toggleShowPassword('sign-up')">
                                                <div class="input-group-text"><i ng-class="{'fas fa-eye': showSignUpPassword, 'fas fa-eye-slash': !showSignUpPassword}" style="width:20px"></i></div>
                                            </div>
                                        </div>

                                        <div class="strength-meter">
                                            <div class="strength-meter-fill" data-strength="{{passwordStrength}}"></div>
                                        </div>
                                        <span ng-show="userSignUpForm.password.$error.passwordStrengthMeter && !userSignUpForm.password.$pristine" class="text-danger">{{'Password is weak.' | translate}}</span>
                                        <span ng-show="userSignUpForm.password.$error.required && !userSignUpForm.password.$pristine" class="text-danger">{{'Password is required.' | translate}}</span>

                                        <small id="passwordHelpBlock" class="form-text font-italic text-muted font-weight-normal">
                                            <ul>
                                                <li>{{'Your password must be 8 or more characters long.' | translate}}</li>
                                                <li>{{'Your password must contain at least one uppercase letter, one lowercase letter, one number and one special character.' | translate}}</li>
                                                <li>{{'Your password may not contain spaces and emojis.' | translate}}</li>
                                                <li>{{'Do not use your username, date of birth or email as password.' | translate}}</li>
                                            </ul>
                                        </small>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputConfirmPassword">{{'Confirm password' | translate}}*</label>
                                        <div class="input-group">
                                            <input type="password" id="inputConfirmPassword" name="confirm_password" ng-model="userSignUp.confirm_password" ng-attr-type="{{showPasswordConfirm ? 'text':'password'}}"
                                                   class="form-control"  
                                                   ng-class="{'is-invalid':(userSignUpForm.confirm_password.$error.required && !userSignUpForm.confirm_password.$pristine) || (userSignUpForm.confirm_password.$modelValue !== userSignUpForm.password.$modelValue)}" ng-required="true"/>
                                            <div class="input-group-append" ng-click="toggleShowPassword('confirm')">
                                                <div class="input-group-text">
                                                    <i ng-class="{'fas fa-eye': showPasswordConfirm, 'fas fa-eye-slash': !showPasswordConfirm}" style="width:20px"></i></div>
                                            </div>
                                        </div>
                                        <span ng-show="userSignUpForm.confirm_password.$error.required && !userSignUpForm.confirm_password.$pristine" class="text-danger">{{'Confirm password is required.' | translate}}</span>
                                        <span ng-show="userSignUpForm.confirm_password.$modelValue !== userSignUpForm.password.$modelValue" class="text-danger">{{'Your passwords do not match!' | translate}}</span>
                                        <small id="passwordConfirmHelpBlock" class="form-text font-italic text-muted">
                                            {{'Ensure that password and confirm password are identical.' | translate}}
                                        </small>
                                    </div>


                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" name="terms" id="inputTerms" ng-model="userSignUp.terms" ng-required="true" ng-checked="true" checked disabled/>
                                        <label class="custom-control-label" for="inputTerms">
                                            {{'I accept the' | translate}} <a class="text-link-default" href="/#!/terms-of-use" target="_blank">{{'Terms of use' | translate}}</a>, 
                                            <a class="text-link-default" href="/#!/privacy-policy" target="_blank">{{'Privacy policy' | translate}}</a> {{'and confirm that I am over 18 years of age.' | translate}}*
                                        </label>

                                        <span ng-show="userSignUpForm.terms.$invalid && !userSignUpForm.terms.$pristine" class="help-block text-danger">{{'You need to accept the Terms of use and Privacy policy in order to continue.' | translate}}</span>

                                    </div>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="inputNewsletter" ng-model="userSignUp.newsletter" ng-checked="true" checked/>
                                        <label class="custom-control-label" for="inputNewsletter">
                                            {{'Newsletter' | translate}}
                                        </label>
                                    </div>

                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="inputBonusAllow" ng-model="userSignUp.bonus_allow"/>
                                        <label class="custom-control-label" for="inputBonusAllow">
                                            {{'I want to receive bonuses and promotions' | translate}}
                                        </label>
                                    </div>




                                    <!--                                    <div class="form-group mt-3">
                                                                            <label for="inputAffiliateCode">{{'Affiliate/Referral Code' | translate}} <small class="form-text font-italic text-muted">({{'optional' | translate}})</small></label>
                                                                            <input class="form-control" type="text" id="inputAffiliateCode" name="affiliate_code" ng-model="userSignUp.affiliate_code" autofocus=""/>
                                                                        </div>-->

                                </div>
                                <div class="float-right my-4">
                                    <input class="btn btn-default btn-wizard" type="submit" wz-next value="{{'Next' | translate}}"/>
                                </div>
                                <div class="clearfix"></div>
                            </wz-step>

                            <wz-step wz-title="" wz-disabled="{{signUpWizardStepsDisabled}}">
                                <legend class="no-border f-w-700 p-b-0 m-t-0 m-b-20 f-s-16 text-inverse">
                                    {{'Personal information' | translate}}
                                </legend>

                                <div id="form-step-1" role="form" data-toggle="validator" novalidate="true">
                                    <div class="form-group">
                                        <label for="inputFirstName">{{'First name' | translate}}*</label>
                                        <input type="text" id="inputFirstName" name="first_name" ng-model="userSignUp.first_name" required="" autofocus=""  
                                               class="form-control" ng-class="{'is-invalid': userSignUpForm.first_name.$error.required && !userSignUpForm.first_name.$pristine}"/>
                                        <span ng-show="userSignUpForm.first_name.$invalid && !userSignUpForm.first_name.$pristine" class="help-block text-danger">{{'First name is required.' | translate}}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputLastName">{{'Last name' | translate}}*</label>
                                        <input type="text" id="inputLastName" name="last_name" ng-model="userSignUp.last_name" required="" autofocus=""  
                                               class="form-control" ng-class="{'is-invalid': userSignUpForm.last_name.$error.required && !userSignUpForm.last_name.$pristine}"/>
                                        <span ng-show="userSignUpForm.last_name.$invalid && !userSignUpForm.last_name.$pristine" class="help-block text-danger">{{'Last name is required.' | translate}}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputDateOfBirth">{{'Date of birth' | translate}}*</label>
                                        <!--//ui-date="defaultDatePickerOptions"-->
                                        <input ng-model="userSignUp.date_of_birth" uib-datepicker-popup datepicker-options="defaultDatePickerOptions" is-open="dateOfBirthPopup.opened" show-weeks="false" show-button-bar="false" class="form-control" ng-required="true" ng-click="openDateOFBirthPopup()"/>
                                        <span ng-show="userSignUpForm.date_of_birth.$invalid && !userSignUpForm.date_of_birth.$pristine" class="help-block text-danger">{{'Date of birth is required.' | translate}}</span>


                                        <!--
                                                                                <div class="input-group">
                                                                                    <select class="custom-select" required ng-model="User.birth_day">
                                                                                        <option selected="true" disabled ng-selected="true" value="">Day</option>
                                                                                        <option ng-repeat="n in [] | range:1:31" value="{{n}}">{{n}}</option>
                                                                                    </select>
                                                                                    <select class="custom-select" required ng-model="User.birth_month">
                                                                                        <option selected="true" disabled ng-selected="true" value="">Month</option>
                                                                                        <option ng-repeat="month in Months" value="{{month.numeric_value}}">{{month.name}}</option>
                                                                                    </select>
                                                                                    <select class="custom-select" required ng-model="User.birth_year">
                                                                                        <option selected="true" disabled ng-selected="true" value="">Year</option>
                                                                                        <option ng-repeat="year in Years" value="{{year.id}}">{{year.name}}</option>
                                                                                    </select>
                                                                                </div>-->
                                    </div>



                                    <div class="form-group">
                                        <label for="inputGender">{{'Gender' | translate}}*</label>
                                        <br>
                                        <div class="btn-group btn-group-toggle btn-group-gender" data-toggle="buttons">

                                            <label class="btn btn-default px-4 active">
                                                <input type="radio" name="gender" id="inputMale" autocomplete="off" ng-model="userSignUp.gender" value="male" ng-click="setGender(male)"/> <i class="fas fa-male" aria-hidden="true"></i> {{'Male' | translate}}
                                            </label>
                                            <label class="btn btn-default px-4">
                                                <input type="radio" name="gender" id="inputFemale" autocomplete="off" ng-model="userSignUp.gender" value="female" ng-click="setGender(female)"/> <i class="fas fa-female" aria-hidden="true"></i> {{'Female' | translate}}
                                            </label>

                                        </div>
                                    </div>
                                </div>
                                <div class="float-right my-4">
                                    <input class="btn btn-light btn-wizard" type="button" wz-previous value="{{'Previous' | translate}}" />
                                    <input class="btn btn-default btn-wizard" type="submit" wz-next value="{{'Next' | translate}}" />
                                </div>

                                <div class="clearfix"></div>
                            </wz-step>

                            <wz-step wz-title="" wz-disabled="{{signUpWizardStepsDisabled}}">
                                <legend class="no-border f-w-700 p-b-0 m-t-0 m-b-20 f-s-16 text-inverse">
                                    {{'Address information' | translate}}
                                </legend>

                                <div id="form-step-2" role="form" data-toggle="validator" novalidate="true" data-select2-id="form-step-2">
                                    <div class="form-group">
                                        <label for="inputAddress">{{'Address' | translate}}*</label>
                                        <input type="text" id="inputAddress" name="address" ng-model="userSignUp.address" required="" autofocus=""  
                                               class="form-control" ng-class="{'is-invalid': userSignUpForm.address.$error.required && !userSignUpForm.address.$pristine}"/>
                                        <span ng-show="userSignUpForm.address.$invalid && !userSignUpForm.address.$pristine" class="help-block text-danger">{{'Address is required.' | translate}}</span>
                                    </div>
                                    <div class="d-flex">
                                        <div class="form-group w-25 mr-1">
                                            <label for="inputZipCode">{{'Zip Code' | translate}}*</label>
                                            <input type="text" id="inputZipCode" name="zip_code" ng-model="userSignUp.zip_code" required="" autofocus=""  
                                                   class="form-control" ng-class="{'is-invalid': userSignUpForm.zip_code.$error.required && !userSignUpForm.zip_code.$pristine}"/>
                                            <span ng-show="userSignUpForm.zip_code.$invalid && !userSignUpForm.zip_code.$pristine" class="help-block text-danger">{{'Zip Code is required.' | translate}}</span>
                                        </div>
                                        <div class="form-group w-75">
                                            <label for="inputCity">{{'City' | translate}}*</label>
                                            <input type="text" id="inputCity" name="city" ng-model="userSignUp.city" required="" autofocus=""  
                                                   class="form-control" ng-class="{'is-invalid': userSignUpForm.city.$error.required && !userSignUpForm.city.$pristine}"/>
                                            <span ng-show="userSignUpForm.city.$invalid && !userSignUpForm.city.$pristine" class="help-block text-danger">{{'City is required.' | translate}}</span>
                                        </div>

                                    </div>


                                    <div class="form-group">
                                        <label for="inputCountry">{{'Country' | translate}}*</label>
                                        <ui-select ng-model="userSignUpCountry.selected" theme="selectize">
                                            <ui-select-match placeholder="">
                                                <!-- <img ng-src="https://www.countryflags.io/{{$select.selected.alpha2_code}}/shiny/24.png">-->
                                                <img ng-src="https://flagcdn.com/{{($select.selected.alpha2_code | lowercase) == 'en' ? 'gb' : (($select.selected.alpha2_code | lowercase) == 'hi' ? 'in' : $select.selected.alpha2_code | lowercase)}}.svg" width="22"/>
                                                {{$select.selected.name}}
                                            </ui-select-match>
                                            <ui-select-choices repeat="userSignUpCountry in Countries | filter: $select.search">
                                                <img ng-src="https://flagcdn.com/{{(userSignUpCountry.alpha2_code | lowercase) == 'en' ? 'gb' : ((userSignUpCountry.alpha2_code | lowercase) == 'hi' ? 'in' : userSignUpCountry.alpha2_code | lowercase)}}.svg" width="22"/>
                                                <!--<img ng-src="https://www.countryflags.io/{{userSignUpCountry.alpha2_code}}/shiny/24.png"/>-->
                                                <span>{{userSignUpCountry.name}}</span>
                                            </ui-select-choices>
                                        </ui-select>

                                    </div>

                                    <!--{"id":"329","name":"Turkey","alpha2_code":"TR","alpha3_code":"TUR","iso31662_code":"ISO 3166-2:TR","numeric_code":"792","active":true}-->
<!--                                    <div class="form-group" ng-show="userSignUpCountry.selected.alpha2_code == 'TR'">
                                        <label for="inputCountry">{{'Turkish identity' | translate}}</label>
                                        <input type="text" id="inputTurkishIdentity" name="turkish_identity" ng-model="userSignUp.turkish_identity"  autofocus=""  
                                               class="form-control" /
                                    </div>-->

                                </div>

                                <div class="float-right my-4">
                                    <input class="btn btn-light btn-wizard" type="button" wz-previous value="{{'Previous' | translate}}" />
                                    <input class="btn btn-default btn-wizard" type="submit" wz-next value="{{'Finish' | translate}}" ng-click="SignUp()" ng-if="!Loader"/>


                                    <div ng-if="Loader">
                                        <div class="text-center" class="d-flex justify-content-center align-items-center">
                                            <div class="lds-ellipsis">
                                                <div></div>
                                                <div></div>
                                                <div></div>
                                                <div></div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="clearfix"></div>
                            </wz-step>

                        </wizard>
                        </from>

                        <hr>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <p>{{'Already have an account?' | translate}}
                                    <span class="text-link-default" ng-click="openSignInModal()">{{'Login' | translate}}</span>.
                                </p>
                            </div>
                            <div class="col-md-12">
                                <i class="fas fa-question-circle" aria-hidden="true"></i> {{'Need help?' | translate}} <a href="/#!/contact-us" class="text-link-default" ng-click="closeSignInModal()">{{'Contact us' | translate}}</a>.</div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
