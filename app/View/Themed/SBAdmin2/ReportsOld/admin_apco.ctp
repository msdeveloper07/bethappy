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
        <div class="span12">
            <?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __('Transactions %s', __($singularName)))))); ?>
            <h3 class="page-title"></h3>
        </div>
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
                                        <h2><?=__('APCO Transactions Report');?></h2>
                                        <hr>
                                        <div><?=__('Please select a date range:');?></div>
                                        <div><?=__('(Without selecting any date the following list will display the last 7 days)');?></div>
                                        <br>
                                        <div style="float:left">
                                            <?= $this->element('reports_form'); ?>
                                        </div>
                                        <?php if (!empty($data)):?>
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th><?= __('ID'); ?></th>
                                                    <th><?= __('User ID'); ?></th>
                                                    <th><?= __('Amount'); ?></th>
                                                    <th><?= __('State'); ?></th>
                                                    <th><?= __('Transaction ID'); ?></th>
                                                    <th><?= __('CardType'); ?></th>
                                                    <th><?= __('CardCountry'); ?></th>
                                                    <th><?= __('Source'); ?></th>
                                                    <th><?= __('Acq'); ?></th>
                                                    <th><?= __('CardHName'); ?></th>
                                                    <th><?= __('Card Expiry'); ?></th>
                                                    <th><?= __('Card No'); ?></th>
                                                    <th><?= __('Time'); ?></th>
                                                </tr>
                                                <?php foreach ($data as $apco) { ?>
                                                    <tr>
                                                        <td><?= $apco['Apcos']['id']; ?></td>
                                                        <td><?= $apco['Apcos']['userId']; ?></td>
                                                        <td><?= $apco['Apcos']['amount']; ?></td>
                                                        <td><?= $apco['Apcos']['state']; ?></td>
                                                        <td><?= $apco['Apcos']['transaction_id']; ?></td>
                                                        <td><?= $apco['Apcos']['CardType']; ?></td>
                                                        <td><?= $apco['Apcos']['CardCountry']; ?></td>
                                                        <td><?= $apco['Apcos']['Source']; ?></td>
                                                        <td><?= $apco['Apcos']['Acq']; ?></td>
                                                        <td><?= $apco['Apcos']['CardHName']; ?></td>
                                                        <td><?= $apco['Apcos']['CardExpiry']; ?></td>
                                                        <td><?= $apco['Apcos']['cardNo']; ?></td>
                                                        <td><?= date("d-m-Y H:i:s",$apco['Apcos']['time']); ?></td>
                                                    </tr>
                                            <?php } ?>
                                            </table>
                                         <?php endif;?>      

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