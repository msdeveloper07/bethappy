l<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __($singularName), 2 => __('List %s', __($pluralName)))))); ?></div>
    </div>
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="table table-custom">                                    
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="<?= $this->Html->URL(array('plugin' => false, 'controller' => 'Affiliates', 'action' => 'view', $this->request->pass[0]),  array('class' => 'btn btn-mini btn-info','style'=>'color:#000')); ?>"><?=__('View');?></a>
                                        <li><a href="<?= $this->Html->URL(array('plugin' => false, 'controller' => 'Affiliates', 'action' => 'edit', $this->request->pass[0]),  array('class' => 'btn btn-mini btn-info','style'=>'color:#000')); ?>"><?=__('Edit');?></a>
                                    </ul>
                                    <div class="tab-content"><?= $this->element('view');?></div>
                                </div>
                            </div>
                            <div class="space10 visible-phone"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

