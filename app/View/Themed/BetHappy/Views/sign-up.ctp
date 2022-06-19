<main class="main">
    <div class="container">
        <div class="row">
            <h1 class="title mb-5"><?= __('Sign up'); ?></h1>
            <!--ng-submit="registerForm(userForm)"--> 
            <form name="userForm" id="registration-form" novalidate class="needs-validation w-100">
                <!--wizard start-->
                <wizard on-finish="Register(userForm)" indicators-position="top"> 
                    <wz-step wz-title="">
                        <!--step 1-->
                        <h2><?= __('Account information'); ?></h2>
                        <div class="form-group">
                            <label for="username"><?= __('Username'); ?></label>
                            <input ng-class="{'is-invalid': (userForm.username.$error.required && !userForm.username.$pristine) || userForm.username.$error.uniqueField}" class="form-control" name="username" type="text" id="username" ng-minlength="4" ng-model="User.username" ng-model-options="{updateOn: 'blur'}" required  unique-field/>
                            <small id="usernameHelpBlock" class="form-text font-italic text-muted">
                                <?= __('Username must be more then 4 characters long.'); ?>
                            </small>
                            <span ng-show="userForm.username.$error.required && !userForm.username.$pristine" class="text-danger"><?= __('Username', true); ?> <?= __('is required.', true); ?></span>
                            <span ng-show="userForm.username.$error.minlength" class="text-danger"><?= __('Username must have at least 3 characters.', true); ?></span>
                            <span ng-show="userForm.username.$error.uniqueField" class="text-danger"><?= __('Username is already taken!', true); ?></span>
                        </div>

                        <div class="form-group">
                            <label for="email"><?= __('E-mail'); ?></label>
                            <input ng-class="{'is-invalid': (userForm.email.$error.required && !userForm.email.$pristine) || userForm.email.$error.uniqueField}" class="form-control" name="email" type="email" id="email" ng-model="User.email" ng-model-options="{updateOn: 'blur'}" ng-pattern="$root.emailFormat" autocomplete="off" required unique-field/>
                            <small id="EmailHelpBlock" class="form-text font-italic text-muted">
                                <?= __('Examples: example@mail.com.'); ?>
                            </small>
                            <span ng-show="userForm.email.$error.required && !userForm.email.$pristine" class="text-danger"><?= __('E-mail', true); ?> <?= __('is required.', true); ?></span>
                            <span ng-show="userForm.email.$error.pattern && !userForm.email.$pristine" class="text-danger"><?= __('This is not a valid e-mail address.', true); ?></span>
                            <span ng-show="userForm.email.$error.uniqueField" class="text-danger"><?= __('E-mail is already taken!', true); ?></span>
                        </div>

                        <div class="form-group">
                            <label for="mobile_number"><?= __('Mobile number'); ?></label>
                            <input  class="form-control" ng-class="{'is-invalid': userForm.mobile_number.$error.required && !userForm.mobile_number.$pristine}" type="tel" name="mobile_number"  id="mobile_number" international-phone-number ng-model="User.mobile_number" required/>
                            <span ng-show="userForm.mobile_number.$error.required && !userForm.mobile_number.$pristine" class="text-danger"><?= __('Mobile number', true); ?> <?= __('is required.', true); ?></span>
                            <span ng-show="userForm.mobile_number.$invalid && userForm.mobile_number.$touched" class="text-danger"><?= __('This is not a valid mobile number.', true); ?></span>
                        </div>

                        <div class="form-group">
                            <label for="currency"><?= __('Currency', true); ?></label>
                            <select id="selectcurr" class="selectjs dropdown-toggle custom-select select-control" required
                                    ng-options="currencyObject.name for (currencyKey, currencyObject) in currencies" ng-model="currencyKey"
                                    ng-change="setSelectedField('currency', currencyKey)">
                                <option value="" ng-if="false"></option>
                            </select>
                            <span ng-show="selectedCurrency.invalid" class="text-danger"><?= __('Currency', true); ?> <?= __('is required.', true); ?></span>
                        </div>


                        <div class="form-group">
                            <label for="passsword"><?= __('Password', true); ?></label>
                            <div class="input-group">
                                <input ng-class="{'is-invalid': userForm.password.$error.required && !userForm.password.$pristine}" class="form-control passwordStrengthMeter" name="password" type="password" id="password" ng-model="User.password" ng-attr-type="{{ $root.showPassword ? 'text':'password'}}" required/>
                                <div class="input-group-append" style="cursor: pointer;">
                                    <div class="input-group-text bg-white" >
                                        <div class="password-count btn badge " type="button" data-container="body" data-toggle="popover" data-placement="top" data-trigger="hover" data-content="<?= __('Number of characters in your password'); ?>" ng-class="{'badge-success': password.length > 7 , 'badge-danger': password.length <= 7 || !password.length }">{{ password | passwordCharacterCount:7 }}</div>
                                    </div>
                                    <div class="input-group-text bg-white" ng-click="$root.toggleShowPassword()" ><i ng-class="{'fas fa-eye': $root.showPassword,'fas fa-eye-slash': !$root.showPassword}" style="width:20px"></i></div>
                                </div>
                            </div>
                            <div class="strength-meter">
                                <div class="strength-meter-fill" data-strength="{{passwordStrength}}"></div>
                            </div>
                            <small id="passwordHelpBlock" class="form-text font-italic text-muted">
                                <?= __('Your password must be 8 or more characters long, contain uppercase and lowecase letters and numbers and special characters, and not contain spaces and emojis. Do not use your username, first name, last name and email as password.'); ?>
                            </small>
                            <span ng-show="userForm.password.$error.required && !userForm.password.$pristine" class="text-danger"><?= __('Password', true); ?> <?= __('is required.', true); ?></span>
                            <span ng-show="userForm.password.$error.passwordStrengthMeter && !userForm.password.$pristine" class="text-danger"><?= __('Password is weak.', true); ?></span>

                        </div>

                        <div class="form-group">
                            <label for="password_confirm"><?= __('Confirm password', true); ?></label>
                            <div class="input-group">
                                <input ng-class="{'is-invalid': (userForm.password_confirm.$error.required && !userForm.password_confirm.$pristine) || (userForm.password_confirm.$modelValue !== userForm.password.$modelValue)}" class="form-control" name="password_confirm" type="password" id="password-confirm" ng-model="User.password_confirm" ng-attr-type="{{ $root.showPasswordConfirm ? 'text':'password'}}"  required/>
                                <div class="input-group-append" ng-click="$root.toggleShowPassword('confirm')" style="cursor: pointer;">
                                    <div class="input-group-text bg-white"> <i ng-class="{'fas fa-eye': $root.showPasswordConfirm,'fas fa-eye-slash': !$root.showPasswordConfirm}" style="width:20px"></i></div>
                                </div>
                            </div>
                            <small id="passwordConfirmHelpBlock" class="form-text font-italic text-muted">
                                <?= __('Ensure that password and confrm password are identical.'); ?>
                            </small>
                            <span ng-show="userForm.password_confirm.$error.required && !userForm.password_confirm.$pristine" class="text-danger"><?= __('Confirm password', true); ?> <?= __('is required.', true); ?></span>
                            <span ng-show="userForm.password_confirm.$modelValue !== userForm.password.$modelValue" class="text-danger"><?= __('Your passwords do not match!'); ?></span>
                        </div> 

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" ng-class="{'is-invalid': userForm.terms.$error.required && !userForm.terms.$pristine}" class="custom-control-input" name="terms" id="terms"  ng-model="User.terms" required>
                                <label class="custom-control-label" for="terms">
                                    <?= __('I accept the'); ?> <a href="/#/page/terms-of-use" target="_blank"><?= __('Terms of use'); ?></a>, 
                                    <a href="/#/page/privacy-policy" target="_blank"><?= __('Privacy policy'); ?></a> <?= __('and confirm that I am over 18 years of age.'); ?>
                                </label>
                            </div>
                            <small id="termsHelpBlock" class="form-text font-italic text-muted">
                                <?= __('Please read our Terms of use and Privacy policy prior to creating an account.'); ?>
                            </small>
                            <span ng-show="userForm.terms.$error.required && !userForm.terms.$pristine" class="text-danger"><?= __('It is required that you must be over 18 years of age and must accept the Terms of use and Privacy policy.', true); ?></span>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="newsletter" id="newsletter" ng-model="User.newsletter">
                                <label class="custom-control-label" for="newsletter">
                                    <?= __('Do not send me bonus offers, news or service updates.'); ?>
                                </label>
                            </div>
                            <small id="newsletterHelpBlock" class="form-text font-italic text-muted">
                                <?= __('We will send you bonus offers news and updates by default. If you do not wish to receive bonus offers, news or service updates please switch this option on.'); ?>
                            </small>
                        </div>

                        <div class="form-group col-sm-12 col-md-3 offset-md-9 px-0">
                            <input type="submit" wz-next value="<?= __('Next'); ?>" class="btn btn-primary w-100"/>
                        </div>
                    </wz-step>

                    <wz-step wz-title="">
                        <h2><?= __('Personal information'); ?></h2>

                        <div class="form-group">
                            <label for="first_name"><?= __('First name'); ?></label>
                            <input class="form-control" name="first_name"  type="text" id="first_name" ng-class="{ 'is-invalid' : userForm.first_name.$error.required && !userForm.first_name.$pristine}" ng-model="User.first_name" ng-model-options="{updateOn: 'blur'}" ng-minlength="1" required />
                            <span ng-show="userForm.first_name.$error.required && !userForm.first_name.$pristine" class="text-danger"><?= __('First name', true); ?> <?= __('is required.', true); ?></span>
                        </div>

                        <div class="form-group">
                            <label for="last_name"><?= __('Last name'); ?></label>
                            <input name="last_name" class="form-control "  type="text" id="last_name" 
                                   ng-class="{'is-invalid' : userForm.last_name.$error.required && !userForm.last_name.$pristine}" 
                                   ng-model="User.last_name" ng-model-options="{updateOn: 'blur'}" ng-minlength="2" required />
                            <span ng-show="userForm.last_name.$error.required && !userForm.last_name.$pristine" class="text-danger"><?= __('Last name', true); ?> <?= __('is required.', true); ?></span>
                        </div>


                        <div class="form-group">
                            <label for="unformatted_date_of_birth"> <?= __('Date of birth'); ?></label>

                            <input name="unformatted_date_of_birth" class="form-control"  type="date" id="unformatted_date_of_birth" placeholder="yyyy-MM-dd" max="{{minAge| date:'yyyy-MM-dd'}}" min="{{maxAge| date:'yyyy-MM-dd'}}"
                                   ng-class="{'is-invalid' : userForm.unformatted_date_of_birth.$error.required && !userForm.unformatted_date_of_birth.$pristine}" 
                                   ng-model="User.unformatted_date_of_birth" ng-model-options="{updateOn: 'blur'}" required />
                            <span ng-show="userForm.unformatted_date_of_birth.$error.required && !userForm.unformatted_date_of_birth.$pristine" class="text-danger"><?= __('Date of birth', true); ?> <?= __('is required.', true); ?></span>

                            <small id="dateOfBirthHelpBlock" class="form-text font-italic text-muted">
                                <?= __('You must be over 18 years old.'); ?>
                            </small>
                        </div>

                        <div class="form-group mt-3">
                            <div class="btn-group btn-group-toggle rounded-pill" data-toggle="buttons">
                                <label class="btn btn-primary active">
                                    <input ng-class="{'active focus': User.gender == 'male'}" type="radio" name="gender" id="male" autocomplete="off" ng-model="User.gender" value="male" ng-checked="setGender('male')"/> 
                                    <?= __('Male'); ?>
                                </label>
                                <label class="btn btn-primary">
                                    <input ng-class="{'active focus': User.gender == 'female'}" type="radio" name="gender" id="female" autocomplete="off" ng-model="User.gender" value="female" ng-checked="setGender('female')"/> 
                                    <?= __('Female'); ?>
                                </label>
                            </div>
                        </div>

                        <div class="form-group col-sm-12 col-md-6 offset-md-6 px-0">
                            <div class="d-flex" role="group">
                                <input type="submit" wz-previous value="<?= __('Previous'); ?>" class="btn btn-outline-light mr-2 w-50"/>
                                <input type="submit" wz-next value="<?= __('Next'); ?>" class="btn btn-primary w-50"/>
                            </div>

                        </div>
                    </wz-step>

                    <wz-step wz-title="" canexit="userForm.$valid">
                        <h2><?= __('Address information'); ?></h2>

                        <div class="form-group">
                            <label for="address"><?= __('Address'); ?></label>
                            <input ng-class="{'is-invalid' : userForm.address1.$error.required && !userForm.address1.$pristine}" class="form-control"  name="address1" type="text" id="address1" ng-model="User.address1" required/>
                            <small id="addressHelpBlock" class="form-text font-italic text-muted">
                                <?= __('Address must include street name and number. Other parts of the address like state, county, building name and/or number etc. are also recommended.'); ?>
                            </small>
                            <span ng-show="userForm.address1.$error.required && !userForm.address1.$pristine" class="text-danger"><?= __('Address', true); ?> <?= __('is required.', true); ?></span>
                        </div> 


                        <div class="d-flex address-flex">
                            <div class="form-group w-25 mr-2">
                                <label for="zip_code"><?= __('Zip/Postal Code'); ?></label>
                                <input ng-class="{'is-invalid' : userForm.zip_code.$error.required && !userForm.zip_code.$pristine}" class="form-control"  name="zip_code" type="text" id="zip_code"ng-model="User.zip_code" required/>
                                <span ng-show="userForm.zip_code.$error.required && !userForm.zip_code.$pristine" class="text-danger"><?= __('Zip/Postal Code', true); ?> <?= __('is required.', true); ?></span>
                            </div>

                            <div class="form-group w-75">
                                <label for="city"><?= __('City'); ?></label>
                                <input ng-class="{'is-invalid' : userForm.city.$error.required && !userForm.city.$pristine}" class="form-control"  name="city" type="text" id="city" ng-model="User.city" required/>
                                <span ng-show="userForm.city.$error.required && !userForm.city.$pristine" class="text-danger"><?= __('City', true); ?> <?= __('is required.', true); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="country"><?= __('Country'); ?></label>
                            <select id="selectcntr" class="form-control select2-country" required
                                    ng-options="countr.name for (countryKey, countr) in countries" ng-model="countryKey"
                                    ng-change="setSelectedField('country', countryKey)">
                                <option value=""></option>
                            </select>
                            <span ng-show="selectedCountry.invalid" class="text-danger"><?= __('Country', true); ?> <?= __('is required.', true); ?></span>
                        </div>

                        <div class="form-group col-sm-12 col-md-6 offset-md-6 px-0">
                            <div class="d-flex" role="group">
                                <input type="submit" wz-previous value="<?= __('Previous'); ?>" class="btn btn-outline-light mr-2 w-50"/>
                                <input type="submit" wz-next value="<?= __('Next'); ?>" class="btn btn-primary w-50"/>
                            </div>
                        </div>
                    </wz-step>

                    <wz-step wz-title="">
                        <div class="jumbotron m-b-0 text-center default-jumbotron">
                            <h2 class="text-inverse"><?= __('Verify Email'); ?></h2>
                            <p class="m-b-30 f-s-16"><?= __('Once you click the "Finish" button, we will sent a verification link to your e-mail.'); ?></p>
                            <p class="m-b-30 f-s-16"><?= __('If you do not see a message in your inbox, make sure the email address you listed  is correct 
                                and check your junk and spam mail folders too.'); ?>
                            </p>
                            <p><?= __('If you have other questions contact support on'); ?> <a href="mailto:<?= Configure::read('Settings.websiteSupportEmail'); ?>"><?= Configure::read('Settings.websiteSupportEmail'); ?></a></p>
                            <div class="form-group col-sm-12 col-md-6 offset-md-3 px-0">
                                <input type="submit" wz-finish value="<?= __('Finish'); ?>" class="btn btn-primary w-50" ng-disabled="userForm.$invalid"/>
                            </div>
                        </div>
                    </wz-step>
                </wizard>
            </form>
        </div>
    </div>
</main>



<script type="text/javascript">
            $(document).ready(function () {
                $(".select2-country").select2();
            });
</script>


