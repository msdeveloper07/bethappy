<main class="main">
    <div class="container">

        <div class="row">
            <!--            <div class="col-md-12">
                            <div id="msgSubmit" class="text-center hidden"></div>
                        </div>-->

            <div class="col-md-12"><h1 class="mb-5"><?= __("Contact us") ?></h1></div>
            <div class="col-sm-12 col-md-6 mb-5">

                <p><?= __("Feel free to get in touch with us."); ?><p>
                <p><?= __("Contact us about anything related to our company, services or issues you may have. We will do our best to get back to you as soon as possible.") ?><p>
                <p><a href="mailto:<?= Configure::read('Settings.websiteSupportEmail'); ?>"><?= Configure::read('Settings.websiteSupportEmail'); ?></a></p>

            </div>
            <div class="col-sm-12 col-md-6">
                <form role="form" name="contactForm" id="contactForm" class="needs-validation w-100" novalidate>

                    <div class="form-group">
                        <label for="full_name" class="text-white"><?= __('Full name') ?></label>
                        <input type="text" class="form-control" ng-class="{'is-invalid': contactForm.username.$error.required && !contactForm.username.$pristine}" id="full_name" name="full_name" ng-model="Contact.full_name" required>
                        <small id="nameHelpBlock" class="form-text font-italic text-muted">
                            <?= __('Please enter your full name.'); ?>
                        </small>
                        <span class="text-danger" ng-show="contactForm.full_name.$error.required && !contactForm.full_name.$pristine"><?= __('Full name is required.') ?></span>
                    </div>

                    <div class="form-group">
                        <label for="email" class="text-white"><?= __('E-mail') ?></label>
                        <input type="email" name="email" ng-if="<?= $this->Session->check('Auth.User'); ?>" class="form-control" ng-class="{'is-invalid': contactForm.from.$error.required && !contactForm.from.$pristine}" id="email" ng-model="Contact.from" required ng-pattern="emailFormat" ng-init="Contact.from = '<?= CakeSession::read('Auth.User.email'); ?>'" />
                        <input type="email" name="email" ng-if="<?= !$this->Session->check('Auth.User'); ?>" class="form-control" ng-class="{'is-invalid': contactForm.from.$error.required && !contactForm.from.$pristine}" id="email" ng-model="Contact.from" required ng-pattern="emailFormat"/>
                        <small id="emailHelpBlock" class="form-text font-italic text-muted">
                            <?= __('Example: example@mail.com'); ?>
                        </small>
                        <span ng-show="contactForm.from.$error.required && !contactForm.from.$pristine" class="text-danger"><?= __('E-mail', true); ?> <?= __('is required.', true); ?></span>
                        <span ng-show="contactForm.from.$error.pattern && !contactForm.from.$pristine" class="text-danger"><?= __('This is not a valid e-mail address.', true); ?></span>
                    </div>

                    <div class="form-group">
                        <label for="subject" class="text-white"><?= __('Subject') ?></label>
                        <input type="text" class="form-control"  ng-class="{'is-invalid': contactForm.subject.$error.required && !contactForm.subject.$pristine}" id="subject" name="subject"  ng-model="Contact.subject" required>
                        <span class="text-danger" ng-show="contactForm.subject.$error.required && !contactForm.subject.$pristine"><?= __('Subject is required.') ?></span>
                    </div>

                    <div class="form-group">
                        <label for="message" class="text-white"><?= __('Message') ?></label>
                        <textarea id="message" class="form-control"   ng-class="{'is-invalid': contactForm.message.$error.required && !contactForm.message.$pristine}" rows="5" name="message" ng-model="Contact.message" required></textarea>
                        <span class="text-danger" ng-show="contactForm.message.$error.required && !contactForm.message.$pristine"><?= __('Message is required.') ?></span>
                    </div>

                    <div class="form-group col-sm-12 col-md-6 offset-md-6 px-0">
                        <button type="submit" class="btn btn-primary w-100"  ng-click="contactUs()" ng-disabled="contactForm.$invalid"><?= __('Send') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>