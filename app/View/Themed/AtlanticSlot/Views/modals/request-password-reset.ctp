
<div class="modal-dialog tools" role="document">
    <div class="modal-content bg-primary">
        <div class="modal-header bg-purple-green-gradient text-white">
            <h5 class="modal-title"><?= __('Reset password'); ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="$root.cancel()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!--1-user is logged in and wants to change password for security reasons
            2-user forgot his password and is trying to reset
            No email reset allowed.-->


        <div class="modal-body">
            <!-- REQUEST PASSWORD RESET -->
            <div ng-if="dataItem.type == 'request-password-reset'">
                <form name="resetRequestForm" id="resetRequestForm" ng-submit="requestPasswordReset(resetRequestForm)" novalidate class="needs-validation w-100">

                    <p class="text-white"  ng-hide="canResend">
                        <?= __('Fill in your e-mail address and we will send you a password reset link.'); ?>
                        <?= __('Need further help?'); ?>
                        <a href="/#/contact-us"><?= __('Contact us'); ?>.</a>
                    </p>

                    <div class="form-group" ng-hide="canResend">
                        <label class="text-white small"><?= __('E-mail'); ?></label>
                        <input ng-class="{'error': resetRequestForm.email.$invalid && !resetRequestForm.email.$pristine}" placeholder="<?= __('example@mail.com', true); ?>" class="form-control" name="email" type="email" ng-model="User.email" ng-pattern="$root.emailFormat" ng-model-options="{ updateOn: 'blur' }" required/>

                        <span ng-show="resetRequestForm.email.$error.required && !resetRequestForm.email.$pristine" class="text-danger"><?= __('E-mail', true); ?> <?= __('is required.', true); ?></span>
                        <span ng-show="resetRequestForm.email.$error.pattern && !resetRequestForm.email.$pristine" class="text-danger"><?= __('This is not a valid e-mail address.', true); ?></span>

                    </div>
                    <div class="form-group"  ng-show="!loading"  ng-hide="canResend">
                        <button type="submit" class="btn btn-primary w-100" ng-disabled="resetRequestForm.$invalid">
                            <?= __('Request password reset', true); ?>
                        </button>
                        <!--<button ng-show="canResend && user.username" type="button" class="btn btn-primary" ng-click="$root.resendCode(User.username, 'reset')"><?= __('Resend password reset email', true); ?></button>-->
                    </div>

                    <div class="form-group col-md-12 text-center text-white mt-4"  ng-show="loading">
                        <i class="fas fa-sync-alt fa-spin" style="font-size: 40px;"></i> 
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>



