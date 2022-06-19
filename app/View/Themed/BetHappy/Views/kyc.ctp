<div class="container-fluid conatiner-custom-padding">
    <div class="row">
        <div class="col-md-3">
            <?= $this->element('casino_sidebar'); ?>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12 p-0">

                    <div class="row">
                        <div class="col-md-12">
                            <p><?= __('Please take a photo or scan your documents. The uploaded files must be bright and clear and all corners of the documents must be clearly visible.'); ?></p>
                            <p><?= __('You can upload multiple files at the same time.'); ?></p>
                            
                            <p><?= __('Please see our'); ?> <a class="text-link-default" href="/#!/deposits-policy"><?= __('Deposits'); ?></a> <?= __('and'); ?>  <a class="text-link-default" href="/#!/withdrawals-policy"><?= __('Withdrawals'); ?></a> <?= __('policies for more information.'); ?></p>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-gradient">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><?= __('Proof of identity'); ?></span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-4">                         
                                            <p><?= __('The following documents are accepted'); ?>:</p>
                                            <ul>
                                                <li><?= __('Identity card (front view & back view)'); ?></li>
                                                <li><?= __('Passport (cover & data page)'); ?></li>
                                                <li><?= __('Drivers license (front view & back view)'); ?></li>
                                            </ul>
                                        </div>

                                        <div class="col-sm-12 col-md-6">
                                            <h4><?= __('Uploaded files'); ?></h4>
                                            <div class="card kyc-card-full"  ng-if="Identity.length === 0 || !Identity">     
                                                <!--<i class="fas fa-id-card mr-2"></i>--><?= __('No files uploaded yet.'); ?>
                                            </div>

                                            <div ng-if="Identity.length > 0">
                                                <div class="card kyc-card-full" ng-repeat="file in Identity">
                                                    <!--<div class="d-flex justify-content-between align-items-center">-->
                                                    <div class="d-flex flex-column justify-content-center align-items-center">
                                                        <div class="" ng-if="file.KYC.file_type === 'image/jpeg' || file.KYC.file_type === 'image/png'">
                                                            <i class="fas fa-file-image"></i>
                                                        </div>

                                                        <div ng-if="file.KYC.file_type === 'application/pdf'">
                                                            <i class="fas fa-file-pdf"></i>
                                                        </div>

                                                        <!--<div class="small">-->
                                                        <div class="badge px-4 py-2" ng-class="setStatus(file.KYC.status)">
                                                            <span ng-if="file.KYC.status == 1"><?= __('Accepted'); ?></span>
                                                            <span ng-if="file.KYC.status == 0"><?= __('Pending'); ?></span>
                                                            <span ng-if="file.KYC.status == -1"><?= __('Rejected'); ?></span>
                                                        </div>
                                                        <div class="mt-2" ng-if="file.KYC.reason">{{file.KYC.reason}}</div>
                                                        <!--</div>-->                                          
                                                    </div>

                                                    <!--                                            </div>-->

                                                    <div class="small">
                                                        <div class=""><?= __('Uploaded'); ?>: {{file.KYC.created  | toDate | date:'dd-MM-yyyy HH:mm'}}</div>
                                                        <div ng-if="file.KYC.expires"><?= __('Expires'); ?>: {{file.KYC.expires  | toDate | date:'dd-MM-yyyy HH:mm'}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <h4><?= __('Upload files'); ?></h4>
                                            <form name="kycIdentityForm" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <div ngf-select ng-model="kycIdentityFiles" ngf-multiple="true" accept="image/*, application/pdf" ngf-max-size="10MB" required class="drop-box" ngf-drop-available="true">
                                                        <i class="fas fa-file-upload mr-2"></i> <?= __('Select File'); ?>
                                                        <span class="ml-2" ng-show="true"> <?= __('or Drop File'); ?></span>
                                                    </div>

                                                    <div class="col-12" ng-show="kycIdentityFiles.length > 0">
                                                        <p><?= __('Your files'); ?>:</p>
                                                        <p ng-repeat="file in kycIdentityFiles">
                                                            {{$index + 1}}.{{file.name}}({{file.size / 1024 /1024  | number:2}} MB)
                                                        </p>
                                                    </div>
                                                    <div class="col-md-12 text-center" ng-show="progress.identity >= 0">
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-striped bg-success" role="progressbar" aria-valuenow="{{progress.identity}}" aria-valuemin="0" aria-valuemax="100" style="width:{{progress.identity}}%;">
                                                                {{progress.identity}}% <?= __('Complete'); ?>
                                                            </div>
                                                        </div>
                                                        <span ng-show="kycIdentityFiles.result"><?= __('Upload Successful'); ?></span>
                                                    </div>
                                         
                                                    <div class="col-md-12 text-center">
                                                        <button type="submit" class="btn btn-default px-4" ng-disabled="!kycIdentityForm.$valid"  ng-click="saveKYC(kycIdentityFiles, 1)"><?= __('Upload Documents'); ?></button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-gradient">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><?= __('Proof of address'); ?></span>
                                    </div>
                                </div>
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-md-12 mb-4">
                                            <p><?= __('The document must include your first and last name, your current address and must not be older that 3 months (eg. issue date, creation date, period of validity etc.)'); ?></p>
                                            <p><?= __('IMPORTANT: The address on this document must match your address on your identification document.'); ?></p>
                                            <p><?= __('The following documents are accepted'); ?>:</p>
                                            <ul>
                                                <li><?= __('Electricity bill'); ?></li>
                                                <li><?= __('Gas bill'); ?></li>
                                                <li><?= __('Internet bill'); ?></li>
                                                <li><?= __('Landline bill'); ?></li>
                                                <li><?= __('Bank account statement'); ?></li>
                                                <li><?= __('Life insurance policy'); ?></li>
                                                <li><?= __('Registered lease/ Rent agreement'); ?></li>
                                            </ul>
                                        </div>

                                        <div class="col-sm-12 col-md-6">

                                            <h4><?= __('Uploaded files'); ?></h4>
                                            <div class="card kyc-card-full"  ng-if="Address.length === 0 || !Address">     
                                                <!--<i class="fas fa-file-invoice"></i>-->
                                                <?= __('No files uploaded yet.'); ?>
                                            </div>

                                            <div ng-if="Address.length > 0">
                                                <div class="card kyc-card-full" ng-repeat="file in Address">
                                                    <!--<div class="d-flex justify-content-between align-items-center">-->
                                                    <div class="d-flex flex-column justify-content-center align-items-center">
                                                        <div class="" ng-if="file.KYC.file_type === 'image/jpeg' || file.KYC.file_type === 'image/png' || file.KYC.file_type === 'image/gif' || file.KYC.file_type === 'image/pjpeg'">
                                                            <i class="fas fa-file-image"></i>                                             
                                                        </div>

                                                        <div ng-if="file.KYC.file_type === 'application/pdf'">
                                                            <i class="fas fa-file-pdf"></i>
                                                        </div>
                                                        <!--<div class="small">-->
                                                        <div class="badge  px-4 py-2" ng-class="setStatus(file.KYC.status)">
                                                            <span ng-if="file.KYC.status == 1"><?= __('Accepted'); ?></span>
                                                            <span ng-if="file.KYC.status == 0"><?= __('Pending'); ?></span>
                                                            <span ng-if="file.KYC.status == -1"><?= __('Rejected'); ?></span>
                                                        </div>
                                                        <div class="mt-2" ng-if="file.KYC.reason">{{file.KYC.reason}}</div>
                                                        <!--</div>-->
                                                    </div>

                                                    <!--</div>-->
                                                    <div class="small">
                                                        <div><?= __('Uploaded'); ?>: {{file.KYC.created}}</div>
                                                        <div ng-if="file.KYC.expires"><?= __('Expires'); ?>: {{file.KYC.expires}}</div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-6">
                                            <h4><?= __('Upload files'); ?></h4>
                                            <form name="kycAddressForm" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <div ngf-select ng-model="kycAddressFiles" ngf-multiple="true" accept="image/*, application/pdf" ngf-max-size="10MB" required class="drop-box" ngf-drop-available="true">
                                                        <i class="fas fa-file-upload mr-2"></i> <?= __('Select File'); ?>
                                                        <span class="ml-2" ng-show="true"> <?= __('or Drop File'); ?></span>
                                                    </div>

                                                    <div class="col-12" ng-show="kycAddressFiles.length > 0">
                                                        <p><?= __('Your files'); ?>:</p>
                                                        <p ng-repeat="file in kycAddressFiles">
                                                            {{$index + 1}}.{{file.name}}({{file.size / 1024 /1024  | number:2}} MB)
                                                        </p>
                                                    </div>

                                                    <div class="col-md-12 text-center" ng-show="progress.address >= 0">
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-striped bg-success" role="progressbar" aria-valuenow="{{progress.address}}" aria-valuemin="0" aria-valuemax="100" style="width:{{progress.address}}%">
                                                                {{progress.address}}% <?= __('Complete'); ?>
                                                            </div>
                                                        </div>
                                                        <span ng-show="kycAddressFiles.result"><?= __('Upload Successful'); ?></span>

                                                    </div>

                                                    <div class="col-md-12 text-center">
                                                        <button type="submit" class="btn btn-default px-4" ng-disabled="!kycAddressForm.$valid"  ng-click="saveKYC(kycAddressFiles, 2)"><?= __('Upload Documents'); ?></button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-gradient">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><?= __('Proof of funding'); ?></span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-4">
                                            <ul>
                                                <li><?= __('You need to upload the front and the back of the payment card.'); ?></li>
                                                <li><?= __('IMPORTANT: You need to make visible the first 6 and the last 4 digits of your card number, and you need to cover the rest of the digits.'); ?></li>
                                                <li><?= __('IMPORTANT: You need to cover the CVV code on the back of the credit card.'); ?></li>
                                                <li><?= __('IMPORTANT: The card must be signed.'); ?></li>
                                                <li><?= __('IMPORTANT: The name of the card holder must match the name on the identification document.'); ?></li>
                                            </ul>
                                        </div>

                                        <div class="col-sm-12 col-md-6">

                                            <h4><?= __('Uploaded files'); ?></h4>
                                            <div class="card kyc-card-full"  ng-if="Funding.length === 0 || !Funding">     
                                                <!--<i class="fas fa-credit-card"></i>-->
                                                <?= __('No files uploaded yet.'); ?>
                                            </div>

                                            <div ng-if="Funding.length > 0">
                                                <div class="card  kyc-card-full" ng-repeat="file in Funding">
                                                    <!--<div class="d-flex justify-content-between align-items-center">-->
                                                    <div class="d-flex flex-column justify-content-center align-items-center">
                                                        <div class="" ng-if="file.KYC.file_type === 'image/jpeg' || file.KYC.file_type === 'image/png' || file.KYC.file_type === 'image/gif' || file.KYC.file_type === 'image/pjpeg'">
                                                            <i class="fas fa-file-image"></i>
                                                        </div>

                                                        <div ng-if="file.KYC.file_type === 'application/pdf'">
                                                            <i class="fas fa-file-pdf"></i>
                                                        </div>
                                                        <!--<div class="small">-->
                                                        <div class="badge px-4 py-2" ng-class="setStatus(file.KYC.status)">
                                                            <span ng-if="file.KYC.status == 1"><?= __('Accepted'); ?></span>
                                                            <span ng-if="file.KYC.status == 0"><?= __('Pending'); ?></span>
                                                            <span ng-if="file.KYC.status == -1"><?= __('Rejected'); ?></span>
                                                        </div>
                                                        <div class="mt-2" ng-if="file.KYC.reason">{{file.KYC.reason}}</div>
                                                        <!--</div>-->
                                                    </div>

                                                    <!--</div>-->
                                                    <div class="small">
                                                        <div><?= __('Uploaded'); ?>: {{file.KYC.created}}</div>
                                                        <div ng-if="file.KYC.expires"><?= __('Expires'); ?>: {{file.KYC.expires}}</div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <h4><?= __('Upload files'); ?></h4>
                                            <form name="kycFundingForm" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <div ngf-select ng-model="kycFundingFiles" ngf-multiple="true" accept="image/*, application/pdf" ngf-max-size="10MB" required class="drop-box" ngf-drop-available="true">
                                                        <i class="fas fa-file-upload mr-2"></i> <?= __('Select File'); ?>
                                                        <span class="ml-2 ng-show="true"> <?= __('or Drop File'); ?></span>
                                                    </div>

                                                    <div class="col-12" ng-show="kycFundingFiles.length > 0">
                                                        <p><?= __('Your files'); ?>:</p>
                                                        <p ng-repeat="file in kycFundingFiles">
                                                            {{$index + 1}}.{{file.name}}({{file.size / 1024 /1024  | number:2}} MB)
                                                        </p>
                                                    </div>

                                                    <div class="col-md-12 text-center" ng-show="progress.finding >= 0">
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-striped bg-success" role="progressbar" aria-valuenow="{{progress.finding}}" aria-valuemin="0" aria-valuemax="100" style="width:{{progress.finding}}%">
                                                                {{progress.finding}}% <?= __('Complete'); ?>
                                                            </div>
                                                        </div>
                                                        <span ng-show="kycFundingFiles.result"><?= __('Upload Successful'); ?></span>
                                                    </div>

                                                    <div class="col-md-12 text-center">
                                                        <button type="submit" class="btn btn-default px-4" ng-disabled="!kycFundingForm.$valid"  ng-click="saveKYC(kycFundingFiles, 3)"><?= __('Upload Documents'); ?></button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>









