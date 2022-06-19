<?php //echo $this->Html->script('/assets/ace-builds-master/src-noconflict/ace.js');  ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('IP Settings'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('IP Settings'); ?></h1>
            </div>
            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <div class="col-md-12">

                <?php echo $this->Form->create('Setting'); ?>
                <ul class="list-group">
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('IPs'); ?><br>
                                    <small class="text-muted font-italic">
                                        <?= __('Type the IP address you want to ban and the word deny next to it in the text area below (example 192.168.1.256 deny)'); ?>
                                    </small>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input('ips', array('class' => 'form-control form-control-sm', 'label' => false, 'div' => false)); ?>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="row form-group">
                    <div class="col-sm-12 col-md-2 offset-md-10 my-3">
                        <?php echo $this->Form->submit(__('Save', true), array('class' => 'btn btn-success btn-block')); ?>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
                <div id="editor" style="width:100%;height:600px;"><?php echo $contents; ?></div>



            </div>

        </div>
    </div>
</div>
<script>
//    var editor = ace.edit("editor");
    //editor.getSession().setMode("ace/mode/javascript");

//    $('.submit').click(function (ev) {
//        $('#SettingIps').val(editor.getValue());
//    });
</script>