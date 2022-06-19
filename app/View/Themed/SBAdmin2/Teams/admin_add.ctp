<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $singularName, 2 => __('List %s', $pluralName))))); ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?php echo $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">

                <!-- BEGIN INLINE TABS PORTLET-->
                <div class="widget">
                    <div class="widget-body">
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                    <?php echo $this->element('tabs');?>
                                    <div class="tab-content">
                                        <?= $this->MyForm->create('Team');  ?>
    <table class="table table-bordered table-striped">
        
        <tr>
            <td>
                <span><i class="icon-wrench"></i></span>
                <?= __('Name'); ?>
            </td>
            <td>
                <?php foreach ($languages as $key => $language): ?>
                    <?= __($language);?>
                    <?php if ($key == 'en_us') {
                        $required = true;
                    } else {
                        $required = false;
                    } ?>
                        
                    <?= $this->Form->input($key . '_name', array('label' => false, 'required' => $required)); ?>
                <?php endforeach; ?>
            </td>
        </tr>
        
        <tr>
            <td>
                <span><i class="icon-flag"></i></span>
                <?= __('Sport'); ?>
            </td>
            <td><?= $this->Form->input('sport_id', array('options' => $sports, 'label' => false)); ?></td>
        </tr>
        
        <tr>
            <td>
                <span><i class="icon-check"></i></span>
                <?= __('Active'); ?>
            </td>
            <td><?= $this->Form->input('active', array('options' => $active, 'label' => false)); ?></td>
        </tr>

    </table>

<?= $this->MyForm->submit(__('Create Team', true), array('class' => 'btn', 'style' => 'margin-top: 15px;'));?>
<?= $this->MyForm->end();?>
                                    </div>
                                </div>
                                <!--END TABS-->
                            </div>
                            <div class="space10 visible-phone"></div>
                        </div>
                    </div>
                </div>
                <!-- END INLINE TABS PORTLET-->
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>