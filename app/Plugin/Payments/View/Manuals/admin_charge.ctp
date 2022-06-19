<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => __('List %s', __($pluralName)), 1 => __($singularName))))); ?></div>
    </div>
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
                                    <?= "<h3>" . __('Charge user') . ' <b>' . $user['User']['username'] . "</b></h3>" ?>
                                    <?= __('To charge enter amount and money will be reduced from user account. For example if you want to charge just credit user with 200 ') . Configure::read('Settings.currency') . __(' USD just enter that amount into field and press submit'); ?>
                                    
                                    <br /><br />
                                    <div class="tab-content">
                                        <?php
                                        echo $this->Form->create();
                                        echo $this->Form->input('amount');
                                        echo $this->Form->input('comments', array('type' => 'textarea'));
                                        echo $this->Form->submit(__('Submit', true), array('class' => 'btn'));
                                        echo $this->Form->end();
                                        ?>
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
</div>