<div class="container-fluid conatiner-custom-padding">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="row">
                <!--{{websiteEmail}}-->
                <div class="col-sm-12 col-md-4 mb-3">
                    <div class="mb-3"><?= __('E-mail'); ?>: <a class="text-link-default" href="mailto:support@bethappy.com">support@bethappy.com</a></div> 
                    <div><?= __('Phone'); ?>: <a class="text-link-default" href="tel:+441234567890">+441234567890</a></div> 
                </div>

                <div class="col-sm-12 col-md-8">

                    <form role="form" name="contactForm" novalidate class="needs-validation">

                        <div class="form-group">
                            <label for="inputFullName"><?= __('Full name'); ?></label>
                            <input type="text" class="form-control form-control-lg" id="inputFullName" name="full_name" ng-model="Contact.full_name" required ng-class="{'is-invalid': contactForm.full_name.$error.required && !contactForm.full_name.$pristine}"/>
                            <span ng-show="contactForm.full_name.$error.required && !contactForm.full_name.$pristine" class="text-danger"><?= __('Full name is required.'); ?></span>
                        </div>

                        <div class="form-group">
                            <label for="inputFrom"><?= __('E-mail'); ?></label>
                            <input type="email" id="inputFrom" name="from" ng-model="Contact.from" ng-pattern="$root.emailFormat" required class="form-control" 
                                   ng-class="{'is-invalid': contactForm.from.$error.required && !contactForm.from.$pristine}"/>
                            <span ng-show="contactForm.from.$error.required && !contactForm.from.$pristine" class="text-danger"><?= __('E-mail is required.'); ?></span>
                            <span ng-show="contactForm.from.$error.pattern && !contactForm.from.$pristine" class="text-danger"><?= __('This is not a valid e-mail address.'); ?></span>
                        </div>

                        <div class="form-group">
                            <label for="inputSubject"><?= __('Subject'); ?></label>
                            <input type="text" class="form-control form-control-lg" id="inputSubject" name="subject" ng-model="Contact.subject" required ng-class="{'is-invalid': contactForm.subject.$error.required && !contactForm.subject.$pristine}"/>
                            <span ng-show="contactForm.subject.$error.required && !contactForm.subject.$pristine" class="text-danger"><?= __('Subject is required.'); ?></span>
                        </div>

                        <div class="form-group">
                            <label for="inputMessage"><?= __('Message'); ?></label>
                            <textarea id="inputMessage" class="form-control form-control-lg" rows="7" name="message" ng-model="Contact.message" required ng-class="{'is-invalid': contactForm.message.$error.required && !contactForm.message.$pristine}">
                            </textarea>
                            <span ng-show="contactForm.message.$error.required && !contactForm.message.$pristine" class="text-danger"><?= __('Message is required.'); ?></span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-default text-uppercase font-weight-bold m-0 mb-2" type="submit" ng-click="contactUs()" ng-disabled="contactForm.$invalid"><?= __('Send'); ?> <i class="fas fa-paper-plane"></i>
                            </button> 
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

