<div class="tickets index">
    <?= $this->element('flash_message'); ?>
    <?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => __('List %s', __($pluralName)), 1 => __('%s Deposits', __($singularName)))))); ?>
    <h2><?= __('Deposits'); ?></h2>
    <h3><?= __('User:') . ' ' . $this->Html->link($data[0]['User']['username'], array('controller'=>'users', 'action'=>'view', $id)); ?></h3>
    <hr>
    <?= $this->element('list'); ?>
</div>

