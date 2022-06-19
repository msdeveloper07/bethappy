<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __($singularName), 2 => __('List %s', __($pluralName)))))); ?></div>
    </div>
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid "><div class="span12"><div class="span12"><?= $this->element('search');?></div></div></div>
        <br />
        <div class="row-fluid">
            <div class="span12">
                <div class="table table-custom">
                    <?= $this->element('tabs');?>
                    <?= $this->Html->link(__('Re-Calculate Events Rank', true), array('plugin' => false, 'controller' => 'Events', 'action' => 'admin_rankrecalculate'),  array('class' => 'btn btn-mini btn-danger','style'=>'color:#000')); ?>
                    <div class="tab-content"><?= $this->element('list', array('title' => 'Teams'));?></div>
                </div>
            </div>
            <div class="space10 visible-phone"></div>
        </div>
    </div>
</div>