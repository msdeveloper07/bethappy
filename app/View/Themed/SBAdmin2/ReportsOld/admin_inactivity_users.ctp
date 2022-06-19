<style type="text/css">
    .span2.responsive .visual a { text-decoration: none; }
</style>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $pluralName, 2 => __('Create %s', __($singularName)))))); ?>
        </div>
    </div>
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid">
            <div class="sortable container-fluid ui-sortable">
                <div class="span12"></div>
                    <?= $this->element('dormancyplayers');?>
                    <?= $this->element('inactiveplayers');?>
                    <?= $this->element('neverloginplayers');?>
            </div>
        </div>
    </div>
</div>