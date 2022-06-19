
<main class="main">
    <div class="container">
        <div class="row">
            <div class="col-md-12"><h1><?= __('Know your customer (KYC)'); ?></h1></div>

            <div class="col-md-12">

                <?php if ($this->Session->read('Auth.User.status') == 0) { ?>
                    <p class="mb-3"><?= __('Please upload your ID to verify your account, in order to continue. Then, you can manually confirm your email.'); ?></p>
                <?php } ?>
                <?php if ($this->Session->read('Auth.User.status') == -4) { ?>
                    <p class="mb-3"><?= __('You have been banned. Upload your ID and Bill to verify your account.'); ?></p>
                <?php } else { ?>

                    <p class="mb-3"><?= __('In order to verify your account we require certain documentation. 
                    You will receive an email from our support team identifying the specific documentation needed.'); ?>
                    </p>
                    <p><?= __('Please ensure that your documents:'); ?></p>
                    <ul class="">
                        <li><?= __('have both front and back submitted'); ?></li>
                        <li><?= __('are in colour and show all details'); ?></li>
                        <li><?= __('are clear and all important information is clearly visible'); ?></li>
                    </ul>
                    <p><?= __('You can upload any image file or a PDF document. You can upload one or multiple files.'); ?></p>

                    <h4><?= __('Identity documents'); ?></h4>
                    <small>
                        <p><?= __('This document needs to be a Government Issued Photo ID which can be either:'); ?></p>
                        <ul class="">
                            <li><?= __('Passport'); ?></li>
                            <li><?= __('National ID Cards'); ?></li>
                            <li><?= __('Driving License'); ?></li>
                        </ul>
                    </small>

                    <h4><?= __('Address documents'); ?></h4>

                    <small>
                        <p><?= __('This document needs to be an official document, and can be either:'); ?></p>
                        <ul class="">
                            <li><?= __('Utility Bill (Gas / Electricity)'); ?></li>
                            <li><?= __('Mobile Phone Bill'); ?></li>
                            <li><?= __('Insurance papers'); ?></li>
                            <li><?= __('Bank Statement'); ?></li>
                        </ul>
                    </small>



                    <h4><?= __('Funding documents'); ?></h4>
                    <small>
                        <p><?= __('This document can be either:'); ?></p>
                        <ul class="">
                            <li><?= __('Credit or debit card'); ?></li>
                            <li><?= __('E-wallet account'); ?></li>
                            <li><?= __('Bank account'); ?></li>
                        </ul>
                    </small>




                <?php } ?>
            </div>

            <div class="col-sm-12 col-md-6 offset-md-3 mb-4">
                <form name="kycForm" class="w-100" novalidate="">
                    <div class="form-group">
                        <select class="custom-select form-control" name="kycType" id="deposit_limit_type" ng-model="kycType"
                                ng-class="{
                                        'is-invalid'
                                        : kycForm.kycType.$error.required && !kycForm.kycType.$pristine}" required>
                            <option value="" ng-selected="true"><?= __('Select document type'); ?></option>
                            <option ng-repeat="(document_type_desc, document_type_value) in document_types" value="{{document_type_value}}">{{document_type_desc}}</option>
                        </select>
                        <span ng-show="kycForm.kycType.$error.required && !kycForm.kycType.$pristine" class="text-danger"><?= __('You must select a document type.', true); ?></span>

                    </div>
                    <div class="form-group">
                        <div class="custom-file">
                            <input type="file" id="kycFile" class="form-control" ng-class="{
                                    'is-invalid': kycForm.kycFiles.$error.required && !kycForm.kycFiles.$pristine}"
                                   ngf-select ng-model="kycFiles" name="kycFiles"    
                                   accept=".pdf, image/*" ngf-max-size="10MB" required multiple
                                   ngf-model-invalid="errorFiles">
                            <label class="custom-file-label" for="kycFile"><?= __('Choose file'); ?></label>
                        </div>

                        <span ng-show="kycForm.kycFiles.$error.required && !kycForm.kycFiles.$pristine" class="text-danger"><?= __('You must choose documents to upload.', true); ?></span>

                    </div>

                    <div ng-show="kycFiles">
                        <p><?= __('Your files:'); ?></p>
                        <ul class="list-unstyled chosen-files">
                            <li ng-repeat="file in kycFiles" class="media">
                                <i class="fas fa-file-pdf" ng-show="file.type == 'application/pdf'"></i>
                                <i class="fas fa-file-image" ng-show="file.type == 'image/jpeg' || file.type == 'image/png' || file.type == 'image/pjpeg' || file.type == 'image/gif'"></i>
                                <div class="media-body small">
                                    {{file.name}} ({{file.size / 1024 / 1024| number:2}} MB)
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="progress my-3" ng-show="kyc_progress >= 0">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" aria-valuenow="{{kyc_progress}}" aria-valuemin="0" aria-valuemax="100" style="width:{{kyc_progress}}%">
                            {{kyc_progress}}% <?= __('Complete'); ?>
                        </div>
        <!--                        <span ng-show="identityFile.result"><?= __('Upload Successful'); ?></span>-->
                    </div>

                    <div class="form-group col-sm-12 col-md-6 offset-md-6 px-0">
                        <button class="btn btn-primary w-100" ng-disabled="!kycForm.$valid"  ng-click="uploadKYC(kycFiles, kycType)"><?= __('Upload', true); ?></button>

                    </div>
                </form>
            </div>

            <div class="col-sm-12 col-md-12">
                <h4><?= __('Identity documents'); ?></h4>
                <div class="row">
                    <div class="col-md-6" ng-repeat="document in documents[1]" ng-if="documents[1]">
                        <div class="card file-card mb-2">
                            <div class="row no-gutters">
                                <div class="col-4 d-flex justify-content-start align-items-center">
                                    <i class="fas fa-file file-icon" ng-show="document.KYC.file_type == ''"></i>
                                    <i class="fas fa-file-pdf file-icon" ng-show="document.KYC.file_type == 'application/pdf'"></i>
                                    <i class="fas fa-file-image file-icon" ng-show="document.KYC.file_type == 'image/jpeg' || document.KYC.file_type == 'image/png' || document.KYC.file_type == 'image/pjpeg' || document.KYC.file_type == 'image/gif'"></i>
                                </div>
                                <div class="col-8">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-end">
                                            <div>
                                                <p class="card-text small">
                                                    {{document.KYC.date}}
                                                </p>
                                                <p class="card-text">
                                                    <span class="badge px-3 py-2" ng-class="{
                                                        'badge-warning'
                                                                : document.KYC.status == 0, 'badge - danger': document.KYC.status == - 1, 'badge-success': document.KYC.status == 1}">
                                                        <span ng-if="document.KYC.status == 1"><?= __('Accepted'); ?></span>
                                                        <span ng-if="document.KYC.status == 0"><?= __('Pending'); ?></span>
                                                        <span ng-if="document.KYC.status == - 1"><?= __('Rejected'); ?></span>
                                                    </span>
                                                </p>
                                            </div>
                                            <!--                                        <div class="dropdown">
                                                                                        <button class="btn btn-transparent dropdown-toggle custom-dropdown" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                            <i class="fas fa-ellipsis-v"></i>
                                                                                        </button>
                                                                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                                                            <a class="dropdown-item" href="#">
                                                                                                <i class="fas fa-eye"></i> <?= __('Preview'); ?>
                                                                                            </a>
                                                                                             <a class="dropdown-item" href ng-click="downloadKYC(document.KYC.id)" download>
                                                                                                <i class="fas fa-download"></i> <?= __('Download'); ?>
                                                                                            </a>                                               
                                                                                        </div>
                                                                                    </div>-->
                                        </div>                         
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div ng-if="!documents[1]"><p><?= __('No documents uploaded.'); ?></p></div>
            </div>

            <div class="col-sm-12 col-md-12">
                <h4><?= __('Address documents'); ?></h4>
                <div class="row">
                    <div class="col-md-6" ng-repeat="document in documents[2]" ng-if="documents[2]">
                        <div class="card file-card mb-2">
                            <div class="row no-gutters">
                                <div class="col-4 d-flex justify-content-start align-items-center">
                                    <i class="fas fa-file-pdf file-icon" ng-show="document.KYC.file_type == 'application/pdf'"></i>
                                    <i class="fas fa-file-image file-icon" ng-show="document.KYC.file_type == 'image/jpeg' || document.KYC.file_type == 'image/png' || document.KYC.file_type == 'image/pjpeg' || document.KYC.file_type == 'image/gif'"></i>
                                </div>
                                <div class="col-8">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-end">
                                            <div>
                                                <p class="card-text small">
                                                    {{document.KYC.date}}
                                                </p>
                                                <p class="card-text">
                                                    <span class="badge px-3 py-2" ng-class="{
                                                        'badge-warning'
                                                                : document.KYC.status == 0, 'badge - danger': document.KYC.status == - 1, 'badge-success': document.KYC.status == 1}">
                                                        <span ng-if="document.KYC.status == 1"><?= __('Accepted'); ?></span>
                                                        <span ng-if="document.KYC.status == 0"><?= __('Pending'); ?></span>
                                                        <span ng-if="document.KYC.status == - 1"><?= __('Rejected'); ?></span>
                                                    </span>
                                                </p>

                                            </div>
                                            <!--                                        <div class="dropdown">
                                                                                        <button class="btn btn-transparent dropdown-toggle custom-dropdown" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                            <i class="fas fa-ellipsis-v"></i>
                                                                                        </button>
                                                                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                                                            <a class="dropdown-item" href="#">
                                                                                                <i class="fas fa-eye"></i> <?= __('Preview'); ?>
                                                                                            </a>
                                                                                            <a class="dropdown-item" href ng-click="downloadKYC(document.KYC.id)">
                                                                                                <i class="fas fa-download"></i> <?= __('Download'); ?>
                                                                                            </a>
                                                                                        </div>
                                                                                    </div>-->
                                        </div>                         
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div ng-if="!documents[2]"><p><?= __('No documents uploaded.'); ?></p></div>


            <div class="col-sm-12 col-md-12">
                <h4><?= __('Funding documents'); ?></h4>
                <div class="row">
                    <div class="col-md-6"ng-repeat="document in documents[3]" ng-if="documents[3]">

                        <div class="card file-card mb-2">
                            <div class="row no-gutters">
                                <div class="col-4 d-flex justify-content-start align-items-center">
                                    <i class="fas fa-file-pdf file-icon" ng-show="document.KYC.file_type == 'application/pdf'"></i>
                                    <i class="fas fa-file-image file-icon" ng-show="document.KYC.file_type == 'image/jpeg' || document.KYC.file_type == 'image/png' || document.KYC.file_type == 'image/pjpeg' || document.KYC.file_type == 'image/gif'"></i>
                                </div>
                                <div class="col-8">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-end">
                                            <div>
                                                <p class="card-text small">
                                                    {{document.KYC.date}}
                                                </p>
                                                <p class="card-text">
                                                    <span class="badge px-3 py-2" ng-class="{
                                                        'badge-warning'
                                                                : document.KYC.status == 0, 'badge - danger': document.KYC.status == - 1, 'badge-success': document.KYC.status == 1}">
                                                        <span ng-if="document.KYC.status == 1"><?= __('Accepted'); ?></span>
                                                        <span ng-if="document.KYC.status == 0"><?= __('Pending'); ?></span>
                                                        <span ng-if="document.KYC.status == - 1"><?= __('Rejected'); ?></span>
                                                    </span>
                                                </p>
                                            </div>
                                            <!--                                        <div class="dropdown">
                                                                                        <button class="btn btn-transparent dropdown-toggle custom-dropdown" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                            <i class="fas fa-ellipsis-v"></i>
                                                                                        </button>
                                                                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                                                            <a class="dropdown-item" href="#">
                                                                                                <i class="fas fa-eye"></i> <?= __('Preview'); ?>
                                                                                            </a>
                                                                                            <a class="dropdown-item" href="#">
                                                                                                <i class="fas fa-download"></i> <?= __('Download'); ?></a>
                                                                                        </div>
                                                                                    </div>-->
                                        </div>                         
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div ng-if="!documents[3]"><p><?= __('No documents uploaded.'); ?></p></div>

        </div>
    </div>
</div>
</main>
