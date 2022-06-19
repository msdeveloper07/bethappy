
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Players'); ?></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= $this->Html->link(__('Players List'), ['plugin' => false, 'controller' => 'users', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Players List')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= $this->Html->link(__('Player View'), ['plugin' => false, 'controller' => 'users', 'action' => 'view', 'prefix' => 'admin', $user['User']['id']], ['escape' => false, 'title' => __('Players List')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Upload KYC documents'); ?></li>                
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Upload KYC documents'); ?></h1>
            </div>
            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <div class="col-md-12">
                <?= $this->element('tabs'); ?>
            </div>
            <div class="col-md-12 pt-2">

                <p><?= __('Upload one file at a time. You can upload a max of 2 files for identification and funding (front view and back view).'); ?></p>
                <div class="card mb-4">
                    <div class="card-header"><?= __('Identification'); ?></div>
                    <div class="card-body">
                        <?php echo $this->Form->create('KYC', array('type' => 'file')); ?>
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="identity_file" name="data[KYC][file]"/>
                                <label class="custom-file-label" for="identity_file"><?= __('Choose file'); ?></label>
                            </div>
                        </div>
                        <input type="hidden" id="kyc_type" name="data[KYC][kyc_type]" value="1">
                        <input type="hidden" id="user_id" name="data[KYC][user_id]" value="<?= $user['User']['id']; ?>">
                        <?php echo $this->Form->submit(__('Upload', true), array('class' => 'btn btn-success')); ?>

                        <?php echo $this->Form->end(); ?>

                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header"><?= __('Address'); ?></div>
                    <div class="card-body">
                        <?php echo $this->Form->create('KYC', array('type' => 'file')); ?>
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="identity_file" name="data[KYC][file]"/>
                                <label class="custom-file-label" for="identity_file"><?= __('Choose file'); ?></label>
                            </div>
                        </div>
                        <input type="hidden" id="kyc_type" name="data[KYC][kyc_type]" value="2">
                        <input type="hidden" id="user_id" name="data[KYC][user_id]" value="<?= $user['User']['id']; ?>">
                        <?php echo $this->Form->submit(__('Upload', true), array('class' => 'btn btn-success')); ?>

                        <?php echo $this->Form->end(); ?>

                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><?= __('Funding'); ?></div>
                    <div class="card-body">
                        <?php echo $this->Form->create('KYC', array('type' => 'file')); ?>
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="identity_file" name="data[KYC][file]"/>
                                <label class="custom-file-label" for="identity_file"><?= __('Choose file'); ?></label>
                            </div>
                        </div>
                        <input type="hidden" id="kyc_type" name="data[KYC][kyc_type]" value="3">
                        <input type="hidden" id="user_id" name="data[KYC][user_id]" value="<?= $user['User']['id']; ?>">
                        <?php echo $this->Form->submit(__('Upload', true), array('class' => 'btn btn-success')); ?>

                        <?php echo $this->Form->end(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    console.log($(this).next(".custom-file-label"));
    $("input[type=file]").change(function () {
        var fieldVal = $(this).val();
        console.log(fieldVal);
        // Change the node's value by removing the fake path (Chrome)
        fieldVal = fieldVal.replace("C:\\fakepath\\", "");
        console.log($(this).next(".custom-file-label").html());
        if (fieldVal !== undefined || fieldVal !== "") {
            $(this).next(".custom-file-label").html(fieldVal);
        }

    });
</script>

