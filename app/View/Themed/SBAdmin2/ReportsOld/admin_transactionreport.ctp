<style>
    .control-label,
    .controls,
    .submit {
        float:left;
        padding-left:5px;
    }
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __('Transaction %s', __($singularName)))))); ?></div>
    </div>
    
    <!-- BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <!-- BEGIN INLINE TABS PORTLET-->
                <div class="widget">
                    <div class="widget-body">
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                    <div class="tab-content">
					<h3><?= __("Report accounting audit rail activity:"); ?><br></h3><br>	
                                        
                                        <?= $this->Form->create('Download'); ?>
                                        <?= $this->Form->input('download', array('value' => '1', 'type' => 'hidden')); ?>
                                        <?= $this->Form->input('from', array('type' => 'hidden')); ?>
                                        <?= $this->Form->input('to', array('type' => 'hidden')); ?>
                                        <?= $this->Form->submit(__('Download (CSV file)', true), array('class' => 'btn btn-danger', 'div' => false, 'style' => 'margin-top: 15px;')); ?>
                                        <?= $this->Form->end(); ?>
                                        <div style="float:left">
                                            <?= $this->element('reports_form'); ?>
                                        </div>
                                        <br>
                                        <?php if (!empty($data)): ?>
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th style="text-align: center"><?= __('Transaction Id'); ?></th>
                                                    <th><?= __('Type'); ?></th>
                                                    <th style="text-align: center"><?= __('Player Id'); ?></th>
                                                    <th><?= __('Player Name'); ?></th>
                                                    <th><?= __('Player e-Mail'); ?></th>
                                                    <th><?= __('Date'); ?></th>
                                                    <th style="text-align: center"><?= __('Currency'); ?></th>
                                                    <th style="text-align: right"><?= __('Amount'); ?></th>
                                                    <th style="text-align: right"><?= __('User Balance'); ?></th>
                                                    
                                                </tr>
                                            <?php foreach ($data as $row){ ?>
                                                <tr>
                                                    <td style="text-align: center"><?= $row['value']['_id']; ?></td>     
                                                    <td><?= __($row['value']['transaction_type']); ?></td>     
                                                    <td style="text-align: center"><?= $row['value']['user_id']; ?></td>      
                                                    <td><?= $row['value']['user_data']['username']; ?></td>       
                                                    <td><?= $row['value']['user_data']['email']; ?></td>                                           
                                                    <td><?= $this->Beth->convertDate($row['value']['date']);?></td>
                                                    <td style="text-align: center"><?= 'EUR'; ?></td>
                                                    <td style="text-align: right"><?= abs($row['value']['amount']); ?></td>
                                                    <td style="text-align: right"><?= $row['value']['balance']; ?></td>
                                                </tr>
                                            <?php } ?>
                                            </table>
                                        <?php endif; ?>
                                        
                                        <?php if(isset($paging['pages'])) echo $paging['pages']; ?>
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