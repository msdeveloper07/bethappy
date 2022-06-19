<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => __('List Dormant %s', $pluralName), 1 => $singularName)))); ?>
        </div>
    </div>
    <div id="page" class="dashboard">
        <?php echo $this->element('flash_message'); ?>
        <h3>Dormant User List</h3>
        <br/>		
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <?php echo $this->element('dormancyplayers');?>

                        <?php echo $this->element('inactiveplayers');?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>