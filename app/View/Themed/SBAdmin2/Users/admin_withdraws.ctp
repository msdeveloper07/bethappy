<div class="tickets index">
    <?php echo $this->element('flash_message'); ?>
    <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => __('List %s', $pluralName), 1 => __('%s Withdraws', $singularName))))); ?>
    <h2><?php echo __('Withdraws'); ?></h2>
    <hr>

    <?php
    echo $this->element('list');
    ?>

</div>

