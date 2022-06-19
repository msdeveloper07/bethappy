<div class="modal-dialog login" role="document">
    <div class="modal-content bg-primary">
        <div class="modal-header text-white">
            <h5 class="modal-title text-uppercase"><?= __('Sign in'); ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="$root.cancel()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger text-center" role="alert" ng-show="$root.errormessage">{{$root.errormessage}}</div>   
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success text-center" role="alert" ng-show="$root.message">{{$root.message}}</div>
                </div>
            </div>
            <?php if (!$this->Session->check('Auth.User') && Configure::read('Settings.login') == 1): ?>
                <form name="userlogin" ng-submit="$root.login(loginform)" novalidate>
                    <div class="form-group">
                        <label class="text-white small"><?= __('Username'); ?></label>
                        <div class="input-group mb-2">
                            <input class="form-control login-input" name="username" type="text" id="username"  placeholder="<?= __('Username'); ?>" ng-model="loginform.username" ng-model-options="{ updateOn: 'blur' }" ng-minlength="1" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-white small"><?= __('Password'); ?></label>
                        <div class="input-group mb-2">
                            <input class="form-control login-input" name="password" type="password" id="password" placeholder="<?= __('Password'); ?>" ng-model="loginform.password" ng-model-options="{ updateOn: 'blur' }" ng-minlength="1" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <!--id="modal-login-btn"-->
                        <button type="submit"  id="modal-login-btn" class="btn btn-lg btn-primary btn-block"><?= __('Sign in', true); ?></button>
                    </div>
                    <div class="form-group text-center">
                        <?php if (Configure::read('Settings.passwordReset') == 1): ?>
                            <a class="text-white small" ng-click="$root.switchAdvanced($event, 'recovery', $root.controllers.Recovery, {type: 'reset'})"><?= __('Forgot Password?', true); ?></a>
                        <?php endif; ?>
                    </div>

                </form>

                <?php if (Configure::read('Settings.registration') == 1): ?>
                    <button ng-click="$root.switchAdvanced($event, 'register', $root.controllers.Register)" class="btn btn-lg btn-secondary btn-block">
                        <?= __('Sign up', true); ?>
                    </button>
                <?php endif; ?>
            <?php endif; ?>   
        </div>
    </div>
</div>

