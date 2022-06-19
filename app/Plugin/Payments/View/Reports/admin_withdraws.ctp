<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Reports'); ?></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Withdraws Reports'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Withdraws Reports'); ?></h1>
            </div>
            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
       
            <div class="col-md-12">
                <?= __('Please choose your timeframe, otherwise the report will show data for the current month.'); ?><br/><br/>
                <?= $this->element('reports_form', array('payment_providers' => $payment_providers, 'status' => true)); ?><br/>
                <div class="table-responsive">
                    <?php if (!empty($data)): ?>
                        <?php ob_start(); ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td colspan="" style="border-right: none;">
                                        <strong><?= Configure::read('Settings.websiteName'); ?></strong>
                                    </td>
                                    <td colspan="<?= empty($selected_provider) ? 5 : 4 ?>" style="text-align:right; border-left: none;" >
                                        <?= __('Report Date:'); ?> <?= date('d-M-Y H:i:s'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="" style="border-right: none;">
                                        <strong><?= $provider; ?> <?= __('Deposits Report'); ?></strong>
                                    </td>
                                    <td  colspan="<?= empty($selected_provider) ? 5 : 4 ?>" style="text-align:right; border-left: none;">
                                        <?= __('Timeframe:'); ?> <?= date("d-m-Y H:i:s", strtotime($from)); ?> - <?= date("d-m-Y H:i:s", strtotime($to)); ?>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($data as $currency => $transactions):
                                    $total[$currency] = 0;
                                    ?>
                                    <tr>
                                        <td colspan="<?= empty($selected_provider) ? 6 : 5 ?>" style="background-color: #fff;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="<?= empty($selected_provider) ? 6 : 5 ?>" style="background: #f9f9f9">
                                            <strong><?= $currency; ?></strong>
                                        </td>
                                    <tr>
                                        <?php if (empty($selected_provider)): ?>
                                            <th><?= __('Provider'); ?></th>  
                                        <?php endif; ?>
                                        <th colspan="2"><?= __('Withdrawer'); ?></th>                     
                                        <th><?= __('Date'); ?></th>
                                        <th><?= __('Amount'); ?></th>
                                        <th><?= __('Status'); ?></th>
                                    </tr>
                                    </tr>
                                    <?php
                                    foreach ($transactions as $key => $transaction):
                                        $total[$currency] += $transaction['amount'];
                                        ?>
                                        <tr class="transaction-body">
                                            <?php if (empty($selected_provider)): ?>
                                                <td colspan="" style="text-align: center "><?= $transaction['provider']; ?></td>
                                            <?php endif; ?>
                                            <td colspan="2" style="text-align: center; ">
                                                <?= ucwords(str_replace("'", "", $transaction['withdrawer_name'])); ?>
                                            </td>
                                            <td colspan="" style="text-align: center "><?= $transaction['created']; ?></td>
                                            <td style="text-align: right;"><?= number_format($transaction['amount'], 2, '.', ','); ?></td>
                                            <td style="text-align: right;"><?= $this->element('status_button', array('status' => $transaction['status'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (!empty($selected_status) || !empty($selected_provider)): ?>
                                        <tr>
                                            <td colspan="3" style="text-align: right; background-color:#f9f9f9;"><b><?= __('TOTAL'); ?> <?= $currency; ?>:</b></td>
                                            <td style="text-align: right; background-color:#f9f9f9;">
                                                <small><?= number_format($total[$currency], 2, '.', ','); ?></small> 
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php
                        $htmldata = ob_get_contents();
                        ob_end_clean();
                        echo $htmldata;
                        $this->assign('htmldata', $htmldata);
                        ?>
                        <div class="print-form text-center mb-4">
                            <?= $this->MyForm->create('Report', array('action' => '/printPDF/report')); ?>
                            <input type='hidden' name='data[type]' value='report'/>
                            <input type='hidden' name='data[htmldata]' value='<?php echo $this->fetch('htmldata'); ?>'/>
                            <input type='hidden' name='data[Report][header]' value='<?= $provider ? $provider . ' ' : '' . __('Withdraws Report'); ?>'/>
                            <input type='hidden' name='data[Report][title]' value='<?= $provider ? $provider . '_' : '' . __('Withdraws_Report'); ?>'/>
                            <input type='hidden' name='data[Report][from]' value='<?= $from; ?>'/>
                            <input type='hidden' name='data[Report][to]' value='<?= $to; ?>'/>
                            <?= $this->MyForm->submit(__('Download PDF', true), array('class' => 'btn btn-success')); ?>
                        </div>
                    <?php elseif (isset($data)): ?>
                        <?= __('No data in this period.'); ?>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

