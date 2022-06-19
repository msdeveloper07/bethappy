<div class="modal-dialog login" role="document">
    <div class="modal-content bg-primary">
        <div class="modal-header bg-purple-green-gradient text-white">
            <h5 class="modal-title"><?= __('Sign in'); ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="$root.cancel()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body">
          
            <?php if (!$this->Session->check('Auth.User') && Configure::read('Settings.login') == 1): ?>
                <form name="userLogin" ng-submit="$root.login(Login)" novalidate>
                    <div class="form-group">
                        <label class="text-white small"><?= __('Username'); ?></label>

                        <input class="form-control login-input" name="username" type="text" id="username"  ng-model="Login.username" ng-model-options="{ updateOn: 'blur' }" ng-minlength="1" required/>

                    </div>
                    <div class="form-group">
                        <label class="text-white small"><?= __('Password'); ?></label>
                        <div class="input-group">
                            <input class="form-control login-input" name="password" type="password" id="password" ng-model="Login.password" ng-model-options="{ updateOn: 'blur' }" ng-minlength="1" ng-attr-type="{{ $root.showPassword ? 'text':'password'}}"  required/>
                            <div class="input-group-append" style="cursor: pointer;">
                                <div class="input-group-text bg-white" ng-click="$root.toggleShowPassword()"><i ng-class="{'fas fa-eye': $root.showPassword,'fas fa-eye-slash': !$root.showPassword}" style="width:20px"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <!--id="modal-login-btn"-->
                        <button type="submit"  id="modal-login-btn" class="btn btn-lg btn-primary btn-block"><?= __('Sign in', true); ?></button>
                    </div>
                    <div class="form-group text-center">
                        <?php if (Configure::read('Settings.passwordReset') == 1): ?>
                            <a class="text-white small" ng-click="$root.switchAdvanced($event, 'request-password-reset', $root.controllers.Tools, {type: 'request-password-reset'})"><?= __('Forgot Password?', true); ?></a>
                        <?php endif; ?>
                    </div>

                </form>

                <?php if (Configure::read('Settings.registration') == 1): ?>
                    <a class="btn btn-lg btn-secondary btn-block" href="/#/sign-up">
                        <?= __('Sign up', true); ?>
                    </a>
                <?php endif; ?>
            <?php endif; ?>   
        </div>
    </div>
</div>

