<div class="default-modal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="{{'Close' | translate}}" ng-click="closeResetPasswordModal()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="modal-body">
        <div class="container-fluid">
            <div class="row no-gutter">
                <div class="col-sm-12 col-md-12 col-lg-12">

                    <div class="text-center mx-auto main-brand mb-3">
                        <img class="text-center" src="img/casino/bet-happy-logo-lg.png" alt="Bet Happy"/>
                    </div>
                    <h3 class="text-center font-weight-bold mb-4">{{'Reset password' | translate}}</h3>

                    <form role="form" name="resetPasswordForm" novalidate class="needs-validation">
                        <div class="form-group">
                            <label for="inputPassword">{{'Password' | translate}}</label>
                            <div class="input-group">
                                <input type="password" class="form-control passwordStrengthMeter" id="inputPassword" name="password" ng-model="ResetPassword.password" ng-attr-type="{{showSignUpPassword ? 'text':'password'}}"
                                       ng-class="{'is-invalid' : resetPasswordForm.password.$error.required && !resetPasswordForm.password.$pristine}" required/>
                                <div class="input-group-append" style="cursor: pointer;" ng-click="toggleShowPassword('sign-up')">
                                    <div class="input-group-text bg-white"><i ng-class="{'fas fa-eye': showSignUpPassword, 'fas fa-eye-slash': !showSignUpPassword}" style="width:20px"></i></div>
                                </div>
                            </div>

                            <div class="strength-meter">
                                <div class="strength-meter-fill" data-strength="{{passwordStrength}}"></div>
                            </div>
                            <span ng-show="resetPasswordForm.password.$error.passwordStrengthMeter && !resetPasswordForm.password.$pristine" class="text-danger">{{'Password is weak.' | translate}}</span>
                            <span ng-show="resetPasswordForm.password.$error.required && !resetPasswordForm.password.$pristine" class="text-danger">{{'Password is required.' | translate}}</span>

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
                            <label for="inputConfirmPassword">{{'Confirm password' | translate}}</label>
                            <div class="input-group">
                                <input type="password" id="inputConfirmPassword" name="confirm_password" ng-model="ResetPassword.confirm_password" ng-attr-type="{{showPasswordConfirm ? 'text':'password'}}"
                                       class="form-control"  
                                       ng-class="{'is-invalid':(resetPasswordForm.confirm_password.$error.required && !resetPasswordForm.confirm_password.$pristine) || (resetPasswordForm.confirm_password.$modelValue !== resetPasswordForm.password.$modelValue)}" required/>
                                <div class="input-group-append" ng-click="toggleShowPassword('confirm')">
                                    <div class="input-group-text bg-white">
                                        <i ng-class="{'fas fa-eye': showPasswordConfirm, 'fas fa-eye-slash': !showPasswordConfirm}" style="width:20px"></i></div>
                                </div>
                            </div>
                            <span ng-show="resetPasswordForm.confirm_password.$error.required && !resetPasswordForm.confirm_password.$pristine" class="text-danger">{{'Confirm password is required.' | translate}}</span>
                            <span ng-show="resetPasswordForm.confirm_password.$modelValue !== resetPasswordForm.password.$modelValue" class="text-danger">{{'Your passwords do not match!' | translate}}</span>
                            <small id="passwordConfirmHelpBlock" class="form-text font-italic text-muted">
                                {{'Ensure that password and confirm password are identical.' | translate}}
                            </small>
                        </div>                          
                        <button class="btn btn-lg btn-default btn-block text-uppercase font-weight-bold mb-2" ng-disabled="resetPasswordForm.$invalid" type="submit" ng-click="resetPassword()" ng-if="!Loader">Send</button>                             
                        <div ng-if="Loader">
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="lds-ellipsis">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                            </div>
                        </div>

                    </form>
                    <p>{{'Please contact' | translate}} <a href="mailto:{{websiteEmail}}" class="text-link-default">{{websiteEmail}}</a> {{'if you need additional assistance' | translate}}. 
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>
