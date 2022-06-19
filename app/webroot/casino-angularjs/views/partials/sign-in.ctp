<div class="default-modal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="$root.closeSignInModal()">
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
                    <h3 class="text-center font-weight-bold mb-4">{{'Welcome back!' | translate}}</h3>

                    <form role="form" name="userSignInForm" novalidate class="needs-validation">
                        <div class="form-group">
                            <label for="inputUsername">{{'Username' | translate}}</label>
                            <input type="text" id="inputUsername" name="username" ng-model="userSignIn.username" required="" autofocus=""  
                                   class="form-control form-control-lg" ng-class="{'is-invalid': userSignInForm.username.$error.required && !userSignInForm.username.$pristine}"/>
                            <span ng-show="userSignInForm.username.$invalid && !userSignInForm.username.$pristine" class="help-block text-danger">{{'Username is required.' | translate}}</span>
                        </div>

                        <div class="form-group">
                            <label for="inputPassword">{{'Password' | translate}}</label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-lg" id="inputPassword" name="password" ng-model="userSignIn.password" ng-attr-type="{{showSignInPassword ? 'text':'password'}}"
                                       ng-class="{'is-invalid' : userSignInForm.password.$error.required && !userSignInForm.password.$pristine}" required/>
                                <div class="input-group-append" style="cursor: pointer;"  ng-click="toggleShowPassword('sign-in')">
                                    <div class="input-group-text"><i ng-class="{'fas fa-eye': showSignInPassword, 'fas fa-eye-slash': !showSignInPassword}" style="width:20px"></i></div>
                                </div>
                            </div>
                            <span ng-show="userSignInForm.password.$error.required && !userSignInForm.password.$pristine" class="text-danger">{{'Password is required.' | translate}}</span>
                        </div>
                        <!--                        <div class="custom-control custom-checkbox mb-3">
                                                    <input type="checkbox" class="custom-control-input" id="inputRememberMe" name="remember_me" ng-model="userSignIn.remember_me"/>
                                                    <label class="custom-control-label" for="inputRememberMe">Remember me</label>
                                                </div>-->
                        <button class="btn btn-lg btn-default btn-block text-uppercase font-weight-bold mb-2" type="submit" ng-click="signIn()" ng-disabled="userSignInForm.$invalid" ng-if="!Loader">{{'Login' | translate}}</button>
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


                        <div class="text-center">
                            <div class="small text-link-default mt-4 mb-0" ng-click="openForgotPasswordModal()">{{'Forgot password?' | translate}}</div>
                        </div>
                    </form>

                    <hr>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <p>{{'Not a member yet?' | translate}}
                                <span class="text-link-default" ng-click="openSignUpModal()">{{'Register now for free.' | translate}}</span>
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