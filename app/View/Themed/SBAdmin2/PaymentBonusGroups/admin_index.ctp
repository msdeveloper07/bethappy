<div class="space10 visible-phone"></div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $singularName, 2 => __('List %s', $pluralName))))); ?>
        </div>
    </div>
    <div id="page" class="dashboard">
        <?php echo $this->element('flash_message'); ?>
        <div class="row-fluid">
            <div class="span12">
                <div class="span12"><?php echo $this->element('search');?></div>                
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12"><?php echo $this->element('tabs');?></div>
        </div>
        <div class="tab-content">
            <?php echo $this->element('list', array('title'));?>
        </div>
    </div>
</div>


