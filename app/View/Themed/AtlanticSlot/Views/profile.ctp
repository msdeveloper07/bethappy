<main class="main">
    <div class="container">

        <div class="row">
            <div class="col-md-12"> <h1 class="title mb-5 ng-binding"><?= __('Profile'); ?></h1></div>

            <div class="col-md-12">
                <form name="userForm" id="user-edit-form" class="needs-validation w-100" novalidate>

                    <div class="d-md-flex flex-sm-column flex-md-row justify-content-sm-start justify-content-md-between align-items-center mb-2">
                        <legend class="mt-3"><?= __('Account information'); ?></legend>
                        <a class="btn btn-outline-light w-50" ng-click="$root.switchAdvanced($event, 'request-password-reset', $root.controllers.Tools, {type: 'request-password-reset'})"><?= __('Change password'); ?></a>
                    </div>

                    <div class="form-group">
                        <label class="" for="username"> <?= __('Username', true); ?></label>
                        <input class="form-control" name="username" type="text" ng-class="{'error' : userForm.username.$invalid && !userForm.username.$pristine}" ng-model="User.username" disabled/>
                        <span ng-show="userForm.username.$invalid && !userForm.username.$pristine" class="text-danger"><?= __('Username', true); ?> <?= __('is required.', true); ?></span>
                    </div>

                    <div class="form-group">
                        <label class="" for="email"><?= __('Email', true); ?></label>  
                        <input name="email" class="form-control" type="text" id="email" ng-class="{
                                'error'
                                : userForm.email.$invalid && !userForm.email.$pristine}" ng-model="User.email" required disabled/>
                        <span ng-show="userForm.email.$invalid && !userForm.email.$pristine" class="text-danger"><?= __('Email', true); ?> <?= __('is required.', true); ?></span>
                    </div>

                    <div class="form-group">
                        <label class="" for="mobile_number"><?= __('Mobile number', true); ?></label>
                        <input class="form-control mobile_number" name="mobile_number" type="text" id="mobile_number" ng-class="{'error': userForm.mobile_number.$invalid && !userForm.mobile_number.$pristine}" ng-model="User.mobile_number" international-phone-number="{{User.mobile_number}}"  value="{{User.mobile_number}}"  disabled/>
                        <span ng-show="userForm.mobile_number.$invalid && !userForm.mobile_number.$pristine" class="text-danger"><?= __('Mobile number', true); ?> <?= __('is required.', true); ?></span>
                    </div>


                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="inputTerms" ng-model="User.terms" disabled>
                        <label class="custom-control-label" for="inputTerms">
                            <?= __('I accept the'); ?> <a href="/#/page/terms-of-use" target="_blank"><?= __('Terms of use'); ?></a>, 
                            <a href="/#/page/privacy-policy" target="_blank"><?= __('Privacy policy'); ?></a> <?= __('and confirm that I am over 18 years of age.'); ?>
                        </label>
                    </div>

                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="newsletter" name="newsletter" ng-model="User.newsletter"/>
                        <label class="custom-control-label" for="newsletter">
                            <?= __('Do not send me bonus offers, news or service updates.'); ?>
                        </label>
                    </div>

                    <legend class="mt-3 mb-2"><?= __('Personal information'); ?></legend>
                    <div class="form-group">
                        <label class="" for="first_name"><?= __('First name', true); ?></label>
                        <input class="form-control" name="first_name" type="text" id="first_name" ng-class="{'error': userForm.first_name.$invalid && !userForm.first_name.$pristine}" ng-model="User.first_name" disabled required/>
                        <span ng-show="userForm.first_name.$invalid && !userForm.first_name.$pristine" class="text-danger"><?= __('First name', true); ?> <?= __('is required.', true); ?></span>
                    </div>
                    <div class="form-group">
                        <label class="" for="last_name"><?= __('Last name', true); ?></label>  
                        <input name="last_name" class="form-control" type="text" id="last_name" ng-class="{'error': userForm.last_name.$invalid && !userForm.last_name.$pristine}" ng-model="User.last_name" disabled required/>
                        <span ng-show="userForm.last_name.$invalid && !userForm.last_name.$pristine" class="text-danger"><?= __('Last name', true); ?> <?= __('is required.', true); ?></span>

                    </div>


                    <div class="form-group">
                        <label class=""><?= __('Date of birth', true); ?></label>
                         <input name="date_of_birth" class="form-control"  type="date" id="date_of_birth" value="{{User.date_of_birth}}"
                                   ng-class="{'is-invalid' : userForm.date_of_birth.$error.required && !userForm.date_of_birth.$pristine}" 
                                   ng-model="User.date_of_birth" ng-model-options="{updateOn: 'blur'}" disabled required />
                        
                        
<!--                        <div class="dateoptions-wrapper">
                            <div class="input-group  dateoptions">
                                <select class="custom-select select-control"
                                        ng-change="checkDate()" 
                                        ng-model="selectedDate.day" 
                                        ng-options="item for item in $root.dateOptions.days"
                                        id="days" 
                                        placeholder="Day" disabled>
                                    <option ng-selected="true" value=""><?= __('Day'); ?></option>
                                </select>

                                <select class="custom-select select-control"
                                        ng-change="checkDate()" 
                                        ng-model="selectedDate.month" 
                                        ng-options="$root.monthNames[item] for item in $root.dateOptions.months" 
                                        id="months" placeholder="Month" disabled>
                                    <option ng-selected="true" value=""><?= __('Month'); ?></option>
                                </select>

                                <select class="custom-select select-control"
                                        ng-change="checkDate()" 
                                        ng-model="selectedDate.year" 
                                        ng-options="item for item in $root.dateOptions.years" 
                                        id="year" disabled>
                                    <option ng-selected="true" value=""><?= __('Year'); ?></option>
                                </select>
                            </div>
                        </div>-->
                    </div>

                    <div class="form-group mt-3">
                        <div class="btn-group btn-group-toggle rounded-pill" data-toggle="buttons" >
                            <label class="btn btn-primary" ng-model="User.gender" ng-class="{'active focus': User.gender === 'male'}">
                                <input type="radio" name="gender" id="inputMale" disabled autocomplete="off" ng-click="setGender('male')">  
                                <?= __('Male', true); ?>
                            </label>
                            <label class="btn btn-primary" ng-model="User.gender" ng-class="{'active focus': User.gender === 'female'}">
                                <input type="radio" name="gender" id="inputFemale" disabled autocomplete="off" ng-click="setGender('female')">                 
                                <?= __('Female', true); ?>
                            </label>
                        </div>

                    </div>

                    <legend class="mt-3 mb-2"><?= __('Address information'); ?></legend>

                    <div class="form-group">
                        <label class="" for="address"> <?= __('Address', true); ?></label>
                        <input class="form-control" name="address1" type="text" id="address1" ng-class="{'error': userForm.address1.$invalid && !userForm.address1.$pristine}" ng-model="User.address1" required disabled/>
                        <span ng-show="userForm.address1.$invalid && !userForm.address1.$pristine" class="text-danger"><?= __('Address', true); ?> <?= __('is required.', true); ?></span>
                    </div>

                    <div class="d-flex address-flex">
                        <div class="form-group w-25 mr-2">
                            <label class="" for="zip_code"><?= __('Zip/Postal code', true); ?></label>
                            <input class="form-control" name="zip_code" type="text" id="zip_code" ng-class="{'error': userForm.zip_code.$invalid && !userForm.zip_code.$pristine}" ng-model="User.zip_code" required disabled/>
                            <span ng-show="userForm.zip_code.$invalid && !userForm.zip_code.$pristine" class="text-danger"><?= __('Zip/Postal code', true); ?> <?= __('is required.', true); ?></span>

                        </div>
                        <div class="form-group w-75">
                            <label class="" for="city"> <?= __('City', true); ?></label>
                            <input class="form-control" name="city" type="text" id="city" ng-class="{'error' : userForm.city.$invalid && !userForm.city.$pristine}" ng-model="User.city" required disabled/>
                            <span ng-show="userForm.city.$invalid && !userForm.city.$pristine" class="text-danger"><?= __('City', true); ?> <?= __('is required.', true); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="" for="country"><?= __('Country', true); ?></label>
                        <select class="form-control custom-select select2-country" ng-options="country.name for country in User.country track by country.id" ng-model="selectedCountry" required disabled></select>
                        <span ng-show="selectedCountry.invalid" class="text-danger"><?= __('Country', true); ?> <?= __('is required.', true); ?></span>
                    </div>

                    <div class="form-group col-sm-12 col-md-3 offset-md-9 mt-3 px-0">
                        <button class="btn btn-primary w-100" ng-click="setAccount()" ng-disabled="userForm.$pristine"><?= __('Save', true); ?></button>
                    </div>

                </form>

                <legend class="mt-3 mb-2"><?= __('Account settings'); ?></legend>


                <form name="settingsForm" id="settingsform" novalidate class="needs-validation w-100">

                    <div class="form-group">
                        <label for="time_zone"><?= __('Time zone', true); ?></label>
                        <select class="custom-select select2-time-zone" ng-options="time_zone.name for time_zone in settings.TimeZones track by time_zone.id" ng-model="selectedTimezone" required></select>
                    </div>

                    <div class="form-group">
                        <label for="language"><?= __('Language', true); ?></label>
                        <select class="custom-select" ng-options="language.name for language in settings.Languages track by language.id" ng-model="selectedLanguage" required></select>

                    </div>

                    <div class="form-group">
                        <label for="currency"><?= __('Currency', true); ?></label>
                        <select class="custom-select" ng-options="currency.name for currency in settings.Currencies track by currency.id" ng-model="selectedCurrency" disabled="disabled"></select>

                    </div>

                    <div class="form-group col-sm-12 col-md-3 offset-md-9 px-0">
                        <button class="btn btn-primary w-100" ng-click="setSettings()" ng-disabled="settingsForm.$pristine"><?= __('Save', true); ?></button>
                    </div>

                </form>




            </div>
        </div>
    </div>
</main>









<script type="text/javascript">
    $(document).ready(function () {
        $(".select2-time-zone").select2();
        $(".select2-country").select2();
        if ($("#mobile_number:disabled")) {
            $('.flag-container').addClass('disabled');

        }

    });</script>