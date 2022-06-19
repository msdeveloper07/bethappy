<div class="default-modal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="{{'Close' | translate}}" ng-click="closeForgotPasswordModal()">
            <i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body">
        <div class="container-fluid">
            <div class="row no-gutter">
                <div class="col-sm-12 col-md-12 col-lg-12">

                    <div class="text-center mx-auto main-brand mb-3">
                        <img class="text-center" src="img/casino/bet-happy-logo-lg.png" alt="Bet Happy"/>
                    </div>
                    <h3 class="text-center font-weight-bold mb-4">{{'Forgot your password?' | translate}}</h3>
                    <p>{{'Do not worry, happens to the best of us.' | translate}}</p>
                    <p>{{'Just enter your email address and we will send you a reset password link.' | translate}}</p>
                    <form role="form" name="forgotPasswordForm" novalidate class="needs-validation">
                        <div class="form-group">
                            <label for="inputEmail">{{'E-mail' | translate}}</label>
                            <input type="email" id="inputEmail" name="email" ng-model="ForgotPassword.email" ng-pattern="emailFormat" required="" autofocus="" class="form-control form-control-lg" 
                                   ng-class="{'is-invalid':forgotPasswordForm.email.$error.required && !forgotPasswordForm.email.$pristine}"/>
                            <span ng-show="forgotPasswordForm.email.$error.required && !forgotPasswordForm.email.$pristine" class="text-danger">{{'E-mail is required.' | translate}}</span>
                            <span ng-show="forgotPasswordForm.email.$error.pattern && !forgotPasswordForm.email.$pristine" class="text-danger">{{'This is not a valid e-mail address.' | translate}}</span>
                            <span ng-show="forgotPasswordForm.email.$error.uniqueField" class="text-danger">{{'E-mail is already taken!' | translate}}</span>
                        </div>                           
                        <button class="btn btn-lg btn-default btn-block text-uppercase font-weight-bold mb-2" ng-disabled="forgotPasswordForm.$invalid" type="submit" ng-click="forgotPassword(ForgotPassword.email)" ng-if="!Loader">{{'Send' | translate}}</button> 

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
                    <p>{{'Please contact' | translate}} <a href="mailto:{{websiteEmail}}" class="text-link-default">{{websiteEmail}}</a> {{'for additional assistance if you do not receive an email from us soon.' | translate}} 
                    </p>
                    <p>{{'Be sure to check your spam or junk folder, just in case.' | translate}}</p>
                </div>
            </div>
        </div>
    </div>
</div>