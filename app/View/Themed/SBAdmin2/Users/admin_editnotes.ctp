<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => __('List %s', $pluralName), 1 => __('Edit %s Notes', $singularName))))); ?>
        </div>
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
                                       <?php echo $this->element('usertabs'); ?>
                                    <div class="tab-content">
                                            <?php
                                            echo $this->MyForm->create();
                                            echo $this->Form->hidden('id',array('value'=>$data['Couchlog']['id']));
                                            echo $this->Form->hidden('rev',array('value'=>$data['Couchlog']['rev']));
                                            echo $this->Form->hidden('userid',array('value'=>$data['Couchlog']['userid']));
                                            echo $this->Form->hidden('timestamp',array('value'=>$data['Couchlog']['timestamp']));
                                            echo $this->Form->input(__('note'),array('label' => 'Note','type'=>'textarea','value'=>$data['Couchlog']['transaction']));
                                            echo $this->MyForm->submit(__('Submit', true), array('class' => 'btn', 'style' => 'margin-top: 15px;'));
                                            echo $this->MyForm->end();
                                            ?>
                                        <?php echo $this->MyHtml->link(__('Previous Revisions'), array('controller' => 'users', 'action' => 'viewnotesrev', $data['Couchlog']['id']), array('class' => 'btn btn-mini btn-success'));?>
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