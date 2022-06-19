<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $singularName)))); ?>
        </div>
    </div>
    <div id="page" class="dashboard">
        
        
        <?php if (CakeSession::read('Auth.User.id') == 9131) { ?>
            <h2 class="jquery_tab_title"><?=__('Welcome Terminal Operator');?></h2>
        <?php } else { ?>
            <h2 class="jquery_tab_title"><?=__('Welcome Operator');?></h2>
            <?= $this->element('inactiveplayers'); ?>
        <?php } ?>
    </div>
</div>
