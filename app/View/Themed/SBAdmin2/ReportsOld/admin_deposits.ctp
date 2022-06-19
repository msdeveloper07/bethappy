<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => __('Reports'), 1 => __('Deposits Report'))))); ?></div>
    </div>

    <div class="row-fluid">
        <div class="span12"><h2><?= __('Deposits Report'); ?></h2></div>
    </div>
    <!-- BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <!-- BEGIN INLINE TABS PORTLET-->
                <div class="widget">
                    <div class="widget-body">
                        <?= $this->element('search'); ?>
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                    <?= $this->element('tabs'); ?>
                                    <div class="tab-content">
                                        <?= __('Report will show deposits by players and manual deposits by staff. Please see report structure below:'); ?>
                                        <br><br>
                                        &bull; <?= __('Date'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the date and time when deposit was made.)'); ?></span><br>
                                        &bull; <?= __('Depositor Name'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is depositor\'s full name.)'); ?></span><br>
                                        &bull; <?= __('Amount'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the amount of money deposited.)'); ?></span><br>
                                        <br>
                                        <?= __('You can generate a report by choosing a provider.'); ?><br><br>
                                        <?= __('Please choose your timeframe, otherwise the report will show data for the current month.'); ?><br/><br/>
                                        <div>
                                            <?= $this->element('reports_form', array('pay_providers' => $pay_providers)); ?>
                                        </div>
                                        <?php if (!empty($data)): ?>
                                            <?php ob_start(); ?>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <td colspan="1" style="border-right: none;">
                                                            <strong><?= Configure::read('Settings.websiteTitle'); ?></strong>
                                                        </td>
                                                        <td colspan="2" style="text-align:right; border-left: none;" >
                                                            <?= __('Report Date:'); ?> <?= date('d-M-Y H:i:s'); ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="1" style="border-right: none;">
                                                            <strong><?= $provider; ?> <?= __('Deposits Report'); ?></strong>
                                                        </td>
                                                        <td colspan="2" style="text-align:right; border-left: none;">
                                                            <?= __('Timeframe:'); ?> <?= $from; ?> - <?= $to; ?>
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($data as $currency => $transactions):
                                                        $total[$currency] = 0;
                                                        ?>

                                                        <tr>
                                                            <td colspan="3" style="background-color: #fff;">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" style="border-bottom: 1px solid #dddddd; background-color: #f9f9f9; ">
                                                                <strong><?= $currency; ?></strong>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th style="text-align:center; background-color: #E1F5F2; "><?= __('Date'); ?></th>
                                                            <th style="text-align: center;  background-color: #E1F5F2;"><?= __('Depositor'); ?></th>
                                                            <th style="text-align:center; background-color: #E1F5F2; width:100px;"><?= __('Amount'); ?></th>
                                                        </tr>
                                                        <?php
                                                        foreach ($transactions as $key => $transaction):
                                                            $total[$currency] += $transaction['amount'];
                                                            ?>
                                                            <tr>
                                                                <td style="text-align: center; "><?= $transaction['created']; ?></td>
                                                                <td style="text-align: center; "><?= ucwords(str_replace("'", "", $transaction['depositor_name'])); ?></td>
                                                                <td style="text-align: right;"><?= number_format($transaction['amount'], 2, '.', ','); ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                        <tr>
                                                            <td colspan="2" style="text-align: right; background-color: #f9f9f9;"><b><?= __('TOTAL'); ?> <?= $currency; ?>:</b></td>
                                                            <td style=" text-align: right; background-color: #f9f9f9;">
                                                                <small><?= number_format($total[$currency], 2, '.', ','); ?></small> 
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                            <?php
                                            $htmldata = ob_get_contents();
                                            ob_end_clean();
                                            echo $htmldata;
                                            $this->assign('htmldata', $htmldata);
                                            ?>
                                            <div class="print-form text-center">
                                                <?= $this->MyForm->create('Report', array('action' => '/printPDF/report')); ?>
                                                <input type='hidden' name='data[type]' value='report'/>
                                                <input type='hidden' name='data[htmldata]' value='<?php echo $this->fetch('htmldata'); ?>'/>
                                                <input type='hidden' name='data[Report][header]' value='<?= $provider . ' ' . __('Deposits Report'); ?>'/>
                                                <input type='hidden' name='data[Report][title]' value='<?= $provider . '_' . __('Deposits_Report'); ?>'/>
                                                <input type='hidden' name='data[Report][from]' value='<?= $from; ?>'/>
                                                <input type='hidden' name='data[Report][to]' value='<?= $to; ?>'/>
                                                <?= $this->MyForm->submit(__('Download PDF', true), array('class' => 'btn btn-success')); ?>
                                            </div>
                                        <?php elseif (isset($data)): ?>
                                            <?= __('No data in this period.'); ?>
                                        <?php endif; ?>

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