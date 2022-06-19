<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?php echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $singularName, 2 => __('List %s', $pluralName))))); ?></div>
    </div>
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
                                        <?= $this->Form->create('GameMenu'); ?>
                                            <fieldset>
                                                <div class="span12">
                                                    <?= $this->Form->input('GameMenu.title', array('label' => 'Title'));?>
                                                    <?= $this->Form->input('GameMenu.url',array('label' => 'URL'));?>
                                                    <?= $this->Form->input('GameMenu.order', array('type' => 'number')); ?>
                                                    
                                                    <?= $this->Form->input('GameMenu.active', array(
                                                        'checked'   => $data['GameMenu']['active'] == 1 ? true: false,
                                                        'div'       =>  'control-group',
                                                        'before'    =>  '<div class="controls" style="margin-top: 15px"><div class="transition-value-toggle-button"><label for="GameMenuActive">'.__('Active').'</label>',
                                                        'type'      =>  'checkbox',
                                                        'class'     => 'toggle',
                                                        'after'     =>  '</div></div>',
                                                        'label'     => false
                                                    )); ?>
                                                    
                                                    <?= $this->Form->input('GameMenu.mobile', array(
                                                        'checked'   => $data['GameMenu']['mobile'] == 1 ? true: false,
                                                        'div'       =>  'control-group',
                                                        'before'    =>  '<div class="controls" style="margin-top: 15px"><div class="transition-value-toggle-button"><label for="GameMenuMobile">'.__('Enable for Mobile').'</label>',
                                                        'type'      =>  'checkbox',
                                                        'class'     => 'toggle',
                                                        'after'     =>  '</div></div>',
                                                        'label'     => false
                                                    )); ?>
                                                    
                                                    <div class="select">
                                                        <select name="data[GameMenu][category]" id="GameMenuCategory">
                                                            <option disabled selected value><?=__('Select from categories (optional)');?></option>
                                                            <?php foreach ($categories as $cat) { ?>
                                                                <option value="<?=$cat['IntCategory']['slug'];?>"><?=$cat['IntCategory']['name'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    
                                                    <?php // echo $this->Form->input('GameMenu.category', array('label' => false, 'div' => 'select', 'type' => 'select', 'options' => $categories)); ?>
                                                </div>
                                                <div class="clearfix"></div>
                                            </fieldset>
                                        <?php echo $this->Form->submit(__('Create', true), array('class' => 'btn', 'style' => 'margin-top: 15px;')); ?>
                                        <?= $this->Form->end(); ?>
                                        
                                    </div>
                                </div>
                                <!--END TABS-->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END INLINE TABS PORTLET-->
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>

<script type="text/javascript">
    $("select#GameMenuCategory").change(function() {
        var slug = this.value;
        $("input#GameMenuUrl").text(slug);
        $("input#GameMenuUrl").val(slug);
    });
</script>