<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => __('List %s', __($pluralName)), 1 => _($singularName))))); ?></div>
    </div>
    <?= $this->element('flash_message'); ?>
    <div id="page" class="dashboard">
        <div class="row-fluid ">
            <div class="span12">
                <!-- BEGIN INLINE TABS PORTLET-->
                <div class="widget">
                    <div class="widget-body">
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                    <?= "<h3>" . __('Fund user') . ' <b>' . $user['User']['username'] . "</b></h3>" ?>
                                    <?= __('To fund just enter amount into the input field and press submit. For example if you want to fund just credit user with 100 ') . Configure::read('Settings.currency') . __(' just enter that amount into field and press submit'); ?>
                                    
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