<style>
    .control-group,
    .control-label,
    .controls {
        display: inline-block;
        vertical-align: middle;
        margin-right: 5px;
    }

    .submit {
        display: inline-block;
        vertical-align: top;
        margin-right: 5px;
    }

    .control-label {
        margin-bottom: 10px;
    }
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $singularName)))); ?>
        </div>
    </div>
    <div id="page" class="dashboard">
        <div>
            <?php echo $this->element('statistics');?>
        </div>
        <div class="row-fluid sortable ui-sortable">
            <?php echo $this->element('dashboard_charts');?>
        </div>
    </div>
</div>

