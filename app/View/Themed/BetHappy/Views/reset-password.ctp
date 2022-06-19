<main class="main">
    <div class="container">

        <div class="row">
            <div class="col-md-12"> <h1 class="title mb-5 ng-binding"><?= __('Reset password'); ?></h1></div>

            <div class="col-md-12">
                <!-- PASSWORD RESET -->
                <!--<div ng-if="dataItem.type == 'reset-password'">-->
                <form name="resetForm" id="password-reset-form" novalidate class="needs-validation w-100" ng-submit="resetPassword(resetForm)">
                    <div class="form-group text-white">
                        <label for="passsword"><?= __('New password', true); ?></label>
                        <div class="input-group">
                            <input ng-class="{'is-invalid': resetForm.new_password.$error.required && !resetForm.new_password.$pristine}" class="form-control passwordStrengthMeter" name="new_password" type="password" id="new_password" ng-model="User.new_password" ng-attr-type="{{ $root.showPassword ? 'text':'password'}}" required/>
                            <div class="input-group-append" style="cursor: pointer;">
                                <div class="input-group-text bg-white" >
                                    <div class="password-count btn badge" type="button" data-container="body" data-toggle="popover" data-placement="top" data-trigger="hover" data-content="<?= __('Number of characters in your password'); ?>" ng-class="{'badge-success': password.length > 7 , 'badge-danger': password.length <= 7 || !password.length }">{{ password | passwordCharacterCount:7 }}</div>
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
                        <span ng-show="resetForm.new_password.$error.required && !resetForm.new_password.$pristine" class="text-danger"><?= __('Password', true); ?> <?= __('is required.', true); ?></span>
                        <span ng-show="resetForm.new_password.$error.passwordStrengthMeter && !resetForm.new_password.$pristine" class="text-danger"><?= __('Password is weak.', true); ?></span>

                    </div>

                    <div class="form-group text-white">
                        <label for="password_confirm"><?= __('Confirm password', true); ?></label>
                        <div class="input-group">
                            <input ng-class="{'is-invalid': (resetForm.password_confirm.$error.required && !resetForm.password_confirm.$pristine) || (resetForm.password_confirm.$modelValue !== resetForm.new_password.$modelValue)}" class="form-control" name="password_confirm" type="password" id="password-confirm" ng-model="User.password_confirm" ng-attr-type="{{ $root.showPasswordConfirm ? 'text':'password'}}"  required/>
                            <div class="input-group-append" ng-click="$root.toggleShowPassword('confirm')" style="cursor: pointer;">
                                <div class="input-group-text bg-white"> <i ng-class="{'fas fa-eye': $root.showPasswordConfirm,'fas fa-eye-slash': !$root.showPasswordConfirm}" style="width:20px"></i></div>
                            </div>
                        </div>
                        <small id="passwordConfirmHelpBlock" class="form-text font-italic text-muted">
                            <?= __('Ensure that new password and confrm password are identical.'); ?>
                        </small>
                        <span ng-show="resetForm.password_confirm.$error.required && !resetForm.password_confirm.$pristine" class="text-danger"><?= __('Confirm password', true); ?> <?= __('is required.', true); ?></span>
                        <span ng-show="resetForm.password_confirm.$modelValue !== resetForm.new_password.$modelValue" class="text-danger"><?= __('Your passwords do not match!'); ?></span>
                    </div> 

                    <div class="form-group col-sm-12 col-md-3 offset-md-9 px-0">
                        <button type="submit" class="btn btn-primary w-100" ng-disabled="resetForm.$invalid"><?= __('Reset password'); ?></button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</main>



