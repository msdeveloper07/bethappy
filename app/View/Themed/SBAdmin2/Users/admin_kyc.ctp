<div class="container-fluid">
    <div class="row-fluid">
        <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => __('List %s', $pluralName), 1 => $singularName)))); ?>
    </div>
    <?php echo $this->element('flash_message'); ?>
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
                                    <?php echo "<h3>" . __('KYC user') . ' <b>' . $user['User']['username'] . "</b></h3>" ?>
                                    <br />
                                        <?php echo "Previous Value: ".$getkyctypes[$user['User']['kyc_status']];?><br>
                                        <?php echo "Previous Expiration Date: ".date("Y-m-d",strtotime($user['User']['kyc_valid_until']));?><br>
                                    <br />
                                    <div class="tab-content">
                                        <?php
                                        echo $this->Form->create();
                                        echo $this->Form->input('kyc_status',array('label'=>'New KYC Status','options'=>$getkyctypes,'default'=>$user['User']['kyc_status']));
                                        echo $this->Form->input('date',array('label'=>'New Expiration Date','value'=>date("Y-m-d",strtotime("+6 month"))));
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