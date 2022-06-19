<div class="modal-dialog recovery" role="document">
    <div class="modal-content bg-primary">
        <div class="modal-header text-white">
            <h5 class="modal-title text-uppercase"><?= __('Reset password'); ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="$root.cancel()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body">




            <!--            <div class="panel-heading modal-heading clearfix">
                            <div class="panel-title pull-left clearfix">
                                <h2 class="modal-title" ng-if="dataItem.type==Recover.password"><?= __("Password Reminder"); ?></h2>
                                <h2 class="modal-title" ng-if="dataItem.type==Recover.email"><?= __("Email Reset"); ?></h2>
                            </div>
                            <div class="pull-right clearfix"><button type="button" class="close" aria-label="Close" ng-click="$root.cancel()"><span aria-hidden="true">&times;</span></button></div>
                        </div>-->

            <div class="row"><div class="col-md-12"><div class="alert alert-danger text-center" role="alert" ng-show="$root.errormessage">{{$root.errormessage}}</div></div></div>
            <div class="row"><div class="col-md-12"><div class="alert alert-success text-center" role="alert" ng-show="$root.message">{{$root.message}}</div></div></div>

            <div class="full-width" id="setnewpass" ng-show="dataItem.type == Recover.password">
                <!-- PASSWORD WAS RESET - MESSAGE -->
                <div ng-show="successresetpass">
                    <p class="p-modal text-center"><?= __('Thank you %s your password has been successfully changed.', '{{dataItem.username}}, <br>'); ?></p>
                    <div class="col-md-12" ng-click="updateDone(<?= $loggedin; ?>)"><div class="text-center"><p><button type="button" class="btn btn-main text-uppercase font-weight-bold txt-wordwrap"><?= __('Done', true); ?></button></p></div></div>
                </div>

                <form name="userForm" id="resetform" ng-submit="submitForm(userForm)" novalidate ng-show="!successresetpass">
                    <?php if ($this->Session->check('Auth.User')) { ?>
                        <!-- SET NEW PASSWORD FROM ACCOUNT SETTINGS -->
                        <p class="p-modal"><?= __('Set new password for user %s', '{{dataItem.username}}'); ?></p>

                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon default-addon"><i class="fa fa-key" aria-hidden="true"></i></span>
                                <input ng-class="{'error': userForm.currentpass.$invalid && !userForm.currentpass.$pristine}" placeholder="<?= __('Current Password', true); ?>" class="form-control default-input" name="currentpass" type="password" ng-model="User.currentpass" required/>
                            </div>
                            <span ng-show="userForm.currentpass.$invalid && !userForm.currentpass.$pristine" class="help-block text-danger"><?= __('Current Password', true); ?> <?= __('is required', true); ?></span>
                        </div>
                    <?php } else { ?>
                        <!-- SET NEW PASSWORD FROM RESET PASS EMAIL -->
                        <p class="p-modal"><?= __('Hello'); ?> {{dataItem.username}}, <?= __('Please enter your new password'); ?></p>
                    <?php } ?>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon default-addon"><i class="fa fa-key" aria-hidden="true"></i></span>
                            <input ng-class="{'error': userForm.newpass.$invalid && !userForm.newpass.$pristine}" placeholder="<?= __('New Password', true); ?>" class="form-control default-input" name="newpass" type="password" ng-model="User.newpass" required/>
                        </div>   
                        <span ng-show="userForm.newpass.$invalid && !userForm.newpass.$pristine" class="help-block text-danger"><?= __('New Password', true); ?> <?= __('is required', true); ?></span>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon default-addon"><i class="fa fa-key" aria-hidden="true"></i></span>
                            <input ng-class="{'error': userForm.confirmpass.$invalid && !userForm.confirmpass.$pristine}" placeholder="<?= __('Retype password', true); ?>" class="form-control default-input" name="confirmpass" type="password" ng-model="User.confirmpass" required/>
                        </div>   
                        <span ng-show="userForm.confirmpass.$invalid && !userForm.confirmpass.$pristine" class="help-block text-danger"><?= __('Retype password', true); ?> <?= __('is required', true); ?></span>
                    </div>       

                    <div class="col-md-12"><div class="text-left"><p><button type="submit" class="button-modal"><?= __('Reset Password', true); ?></button></p></div></div>
                </form>
            </div>

            <!-- AFTER EMAIL CONFIRM - MESSAGE -->
            <div ng-show="dataItem.type == Recover.email && dataItem.status == 'confirmed'">
                <p class="p-modal text-center">{{dataItem.message}}</p>
                <div class="col-md-12" ng-click="updateDone(<?= $loggedin; ?>)"><div class="text-center"><p><button class="button-modal" type="button"><?= __('Done', true); ?></button></p></div></div>
            </div>

            <?php if (!$this->Session->check('Auth.User')) { ?>
                <!-- RESET NEW PASSWORD FORM -->
                <div id="reset" ng-if="dataItem.type == 'reset'">
                    <form name="resetForm" id="reserpassform" ng-submit="submitResetForm(resetForm)" novalidate>
                        <p class="text-white" ng-hide="canResend"><?= __('Fill in your e-mail address and we will send you instructions on how to reset your password via e-mail.'); ?></p>
                        <p class="text-white" ng-hide="canResend"><?= __('Contact us if you need further help.'); ?></p>

                        <p class="text-white text-center" ng-hide="!canResend"><?= __('A password reset link was sent to your e-mail address.'); ?></p>

                        <!--                        <div class="form-group" ng-hide="canResend">
                                                    <label class="text-white small"><?= __('Username'); ?></label>
                                                    <input ng-class="{'error': resetForm.username.$invalid && !resetForm.username.$pristine}" placeholder="<?= __('example-username', true); ?>" class="form-control default-input" name="username" type="username" ng-model="user.username" ng-model-options="{ updateOn: 'blur' }" required/>
                                                    <span ng-show="resetForm.username.$invalid && !resetForm.username.$pristine" class="help-block text-danger"><?= __('Username', true); ?> <?= __('is required', true); ?></span>
                                                </div>-->

                        <div class="form-group" ng-hide="canResend">
                            <label class="text-white small"><?= __('E-mail'); ?></label>
                            <input ng-class="{'error': resetForm.email.$invalid && !resetForm.email.$pristine}" placeholder="<?= __('example@mail.com', true); ?>" class="form-control default-input" name="email" type="email" ng-model="user.email" ng-model-options="{ updateOn: 'blur' }" required/>

                            <span ng-show="resetForm.email.$invalid && !resetForm.email.$pristine" class="help-block text-danger"><?= __('E-mail', true); ?> <?= __('is required', true); ?></span>
                        </div>
                        <div class="form-group">

                            <button ng-hide="canResend" type="submit" class="btn btn-primary w-100"><?= __('Request password reset', true); ?></button>
                            <button ng-show="canResend && user.username" type="button" class="btn btn-primary" ng-click="$root.resendCode(user.username, 'reset')"><?= __('Resend password reset email', true); ?></button>

                        </div>
                    </form>
                </div>
            <?php } else { ?>
                <!-- SET NEW EMAIL FROM ACCOUNT SETTINGS -->
                <div class="full-width" id="newemail" ng-if="dataItem.type == Recover.email && !dataItem.status">
                    <!-- EMAIL WAS RESET - MESSAGE -->
                    <div ng-show="successresetmail">
                        <p class="p-modal"><?= __('Thank you %s your email has been successfully changed. Please confirm the update from your email account.', '{{dataItem.username}}, <br>'); ?></p>
                        <div class="col-md-12 pl-0" ng-click="updateDone(<?= $loggedin; ?>)"><div class="text-left"><p><button class="btn btn-main text-uppercase font-weight-bold txt-wordwrap" type="button"><?= __('Done', true); ?></button></p></div></div>
                    </div>

                    <form name="mailForm" id="newmailform" ng-submit="submitMailForm(mailForm)" novalidate ng-show="!successresetmail">
                        <p class="p-modal"><?= __('Please update your e-mail address'); ?></p>

                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon default-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                <input ng-class="{'error': mailForm.currentemail.$invalid && !mailForm.currentemail.$pristine}" placeholder="<?= __('Current E-mail', true); ?>" class="form-control default-input" name="currentemail" type="email" ng-model="user.currentemail" required/>
                            </div>
                            <span ng-show="mailForm.currentemail.$invalid && !mailForm.currentemail.$pristine" class="help-block text-danger"><?= __('Current E-mail', true); ?> <?= __('is required', true); ?></span>
                        </div>

                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon default-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                <input ng-class="{'error': mailForm.newemail.$invalid && !mailForm.newemail.$pristine}" placeholder="<?= __('New E-mail', true); ?>" class="form-control default-input" name="newemail" type="email" ng-model="user.newemail" ng-model-options="{ updateOn: 'blur' }" required/>
                            </div>      
                            <span ng-show="mailForm.newemail.$invalid && !mailForm.newemail.$pristine" class="help-block text-danger"><?= __('New E-mail', true); ?> <?= __('is required', true); ?></span>
                        </div>

                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon default-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                <input ng-class="{'error': mailForm.confirmemail.$invalid && !mailForm.confirmemail.$pristine}" placeholder="<?= __('Confirm New E-mail', true); ?>" class="form-control default-input" name="confirmemail" type="email" ng-model="user.confirmemail" required/>
                            </div>   
                            <span ng-show="mailForm.confirmemail.$invalid && !mailForm.confirmemail.$pristine" class="help-block text-danger"><?= __('Confirm New E-mail', true); ?> <?= __('is required', true); ?></span>
                        </div>  

                        <div class="col-md-12 pl-0"><div class="text-left"><p><button type="submit" class="btn btn-main text-uppercase font-weight-bold txt-wordwrap"><?= __('OK', true); ?></button></p></div></div>
                    </form>
                </div>
            <?php } ?>

        </div>
    </div>
</div>




