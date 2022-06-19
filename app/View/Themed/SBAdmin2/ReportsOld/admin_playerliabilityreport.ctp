<style type="text/css">
    .underlined {
        color: #000;
    }
    
    .underlined th,    
    .underlined.both td {
        border-bottom: 1px solid black;    
        vertical-align: top !important;
    }
    
    .underlined td {
        border-top: 1px solid black; 
        vertical-align: top !important;
    }
</style>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __('Player Liability %s', _($singularName)))))); ?></div>
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
                                        <h3><?= __('Player Liability Report'); ?></h3>
                                        
                                        <p><?= __('Please select a month to get the report'); ?></p>
                                        <?= $this->Form->create('Report'); ?>
                                            <select id="select_month" name="data[Report][Month]">
                                                <?php for ($i = 1; $i <= 12; $i++) {
                                                    echo '<option value="'.$i.'"';
                                                    if ($i == $month) echo ' selected="selected"';
                                                    echo '>'.date("F", mktime(0, 0, 0, $i, 10)).'</option>';
                                                }
                                                //echo $this->element('reports_form'); ?>
                                            </select>
                                            <select id="select_year" name="data[Report][Year]">
                                                <?php for($y=date("Y")-2;$y<=date("Y");$y++){ ?>
                                                <option value="<?= $y;?>" <?php if ($year==$y) {echo 'selected="selected"';} ?>><?= $y;?></option>
                                                <?php } ?>
                                            </select>
                                            <?= $this->Form->submit(__('Show', true), array('class' => 'btn')); ?>
                                        <?= $this->Form->end(); ?>
                                        
                                        <?php ob_start();  ?>
                                        
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <?php 
                                                    $total_debit  = 0;
                                                    $total_credit = 0; 
                                                ?>
                                                <tr class="underlined">
                                                    <th style="font-size: 14px;"><?= __('Opening Player liability'); ?></th>
                                                    <th><?= __('Debit'); ?>  <br /><span style="float:right"><?= number_format((float)$total_debit_prev, 2, '.', '');?></span></th>
                                                    <th style="text-align:center">#</th>
                                                    <th><?= __('Credit'); ?>  <br /><span style="float:right"><?= number_format((float)$total_credit_prev, 2, '.', '');?></span></th>
                                                    <th style="text-align:center">#</th>
                                                    <th><?= __('Net'); ?>  <br /><span style="float:right"><?= number_format((float)$total_credit_prev - $total_debit_prev, 2, '.', '');?></span></th>
                                                    <th style="text-align:center;width: 80px"><?= __('Restrictions'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Deposits -->
                                                <tr>
                                                    <td><b><?= __('Deposit (IN)'); ?></b></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="text-align:center">+</td>
                                                </tr>
                                                <?php $sum = 0;
                                                
                                                foreach($deposits as $deposit): 
                                                    $sum += floatval($deposit[0]['total']);
                                                ?>
                                                <tr>
                                                    <td><?= empty($deposit['APCO']['type'])?' - ':$deposit['APCO']['type']; ?></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="text-align:right"><?= $deposit[0]['total']; ?></td>
                                                    <td style="text-align:center"><?= $deposit[0]['count']; ?></td>
                                                    <td style="text-align:right"><?= $deposit[0]['total']; ?></td>
                                                    <td style="text-align:center">+</td>
                                                </tr> 
                                                <?php endforeach; ?>    
                                                
                                                <?php $total_credit += $sum; ?>
                                                <tr>
                                                    <td><b><?=__('Sum:');?></b></td>
                                                    <td style="text-align:right"><b>0.00</b></td>
                                                    <td></td>
                                                    <td style="text-align:right"><b><?= number_format((float)$sum, 2, '.', ''); ?></b></td>
                                                    <td></td>
                                                    <td style="text-align:right"><b><?= number_format((float)$sum, 2, '.', ''); ?></b></td>
                                                    <td style="text-align:center">+</td>
                                                </tr> 
                                                <tr style="height: 35px;">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr> 
                                                
                                                <!-- Withdraws -->                                                
                                                <tr>
                                                    <td><b><?= __('Withdrawal (OUT)'); ?></b></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="text-align:center">-</td>
                                                </tr>
                                                <?php 
                                                $sum = 0;
                                                
                                                foreach($withdraws as $withdraw): 
                                                    $sum += $withdraw[0]['total'];
                                                ?>
                                                <tr>
                                                    <td><?= empty($withdraw['APCO']['type'])?' - ':$withdraw['APCO']['type']; ?></td>
                                                    <td style="text-align:right"><?= $withdraw[0]['total']; ?></td>
                                                    <td style="text-align:center"><?= $withdraw[0]['count']; ?></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="text-align:right">(<?= $withdraw[0]['total']; ?>)</td>
                                                    <td style="text-align:center">-</td>
                                                </tr> 
                                                <?php endforeach; ?> 
                                                <?php $total_debit += $sum; ?>                                               
                                                <tr>
                                                    <td><b><?=__('Sum:');?></b></td>
                                                    <td style="text-align:right"><b><?= number_format((float)$sum, 2, '.', ''); ?></b></td>
                                                    <td></td>
                                                    <td style="text-align:right"><b>0.00</b></td>
                                                    <td></td>
                                                    <td style="text-align:right"><b>(<?= number_format((float)$sum, 2, '.', ''); ?>)</b></td>
                                                    <td style="text-align:center">-</td>
                                                </tr> 
                                                <tr style="height: 35px;">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr> 
                                                
                                                <!-- Stakes -->                                                
                                                <tr>
                                                    <td><b><?= __('Bets (OUT)'); ?></b></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="text-align:center">-</td>
                                                </tr>                                                
                                                <tr>
                                                    <td><?=  __('Bet'); ?></td>
                                                    <td style="text-align:right"><?= $stakes[0][0]['total']; ?></td>
                                                    <td style="text-align:center"><?= $stakes[0][0]['count']; ?></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="text-align:right">(<?= $stakes[0][0]['total']; ?>)</td>
                                                    <td style="text-align:center">-</td>
                                                </tr>    
                                                <?php $total_debit += $stakes[0][0]['total']; ?>                                             
                                                <tr>
                                                    <td><b>Sum:</b></td>
                                                    <td style="text-align:right"><b><?= number_format((float)$stakes[0][0]['total'], 2, '.', ''); ?></b></td>
                                                    <td></td>
                                                    <td style="text-align:right"><b>0.00</b></td>
                                                    <td></td>
                                                    <td style="text-align:right"><b>(<?= number_format((float)$stakes[0][0]['total'], 2, '.', ''); ?>)</b></td>
                                                    <td style="text-align:center">-</td>
                                                </tr> 
                                                <tr style="height: 35px;">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr> 
                                                
                                                <!-- Wins -->                                                
                                                <tr>
                                                    <td><b><?= __('Wins (IN)'); ?></b></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="text-align:center">-</td>
                                                </tr>                                                
                                                <tr>
                                                    <td><?=  __('Win'); ?></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="text-align:right"><?= $wins[0][0]['total']; ?></td>
                                                    <td style="text-align:center"><?= $wins[0][0]['count']; ?></td>
                                                    <td style="text-align:right"><?= $wins[0][0]['total']; ?></td>
                                                    <td style="text-align:center">+</td>
                                                </tr>         
                                                <?php $total_credit += $wins[0][0]['total']; ?>                                        
                                                <tr>
                                                    <td><b><?=__('Sum:');?></b></td>
                                                    <td style="text-align:right"><b>0.00</b></td>
                                                    <td></td>
                                                    <td style="text-align:right"><b><?= number_format((float)$wins[0][0]['total'], 2, '.', ''); ?></b></td>
                                                    <td></td>
                                                    <td style="text-align:right"><b>(<?= number_format((float)$wins[0][0]['total'], 2, '.', ''); ?>)</b></td>
                                                    <td style="text-align:center">+</td>
                                                </tr> 
                                                <tr style="height: 35px;">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr> 
                                                
                                                <!-- Adjustments -->     
                                                <tr>
                                                    <td><b><?= __('Adjustments (IN / OUT)'); ?></b></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="text-align:center">+ / -</td>
                                                </tr>                                                
                                                <tr>
                                                    <td><?=  __('Canceled Bet'); ?></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="text-align:right"><?= $cancel_bet[0][0]['total']; ?></td>
                                                    <td style="text-align:center"><?= $cancel_bet[0][0]['count']; ?></td>
                                                    <td style="text-align:right"><?= $cancel_bet[0][0]['total']; ?></td>
                                                    <td style="text-align:center">-</td>
                                                </tr>                                                
                                                <tr>
                                                    <td><?=  __('Adjustments - Bets and Wins'); ?></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="text-align:right"><?= $adjustments_bet[0][0]['total']; ?></td>
                                                    <td style="text-align:center"><?= $adjustments_bet[0][0]['count']; ?></td>
                                                    <td style="text-align:right"><?= $adjustments_bet[0][0]['total']; ?></td>
                                                    <td style="text-align:center">+/-</td>
                                                </tr>                                               
                                                <tr>
                                                    <td><?=  __('Adjustments - Deposits'); ?></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="text-align:right"><?= $adjustments_deposits[0][0]['total']; ?></td>
                                                    <td style="text-align:center"><?= $adjustments_deposits[0][0]['count']; ?></td>
                                                    <td style="text-align:right"><?= $adjustments_deposits[0][0]['total']; ?></td>
                                                    <td style="text-align:center">+/-</td>
                                                </tr>                                                                                          
                                                <tr>
                                                    <td><?=  __('Canceled Withdrawal'); ?></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="text-align:right"><?= $canceled_withdraws[0][0]['total']; ?></td>
                                                    <td style="text-align:center"><?= $canceled_withdraws[0][0]['count']; ?></td>
                                                    <td style="text-align:right"><?= $canceled_withdraws[0][0]['total']; ?></td>
                                                    <td style="text-align:center">+</td>
                                                </tr>  
                                                <?php 
                                                $adj_sum_credit = $cancel_bet[0][0]['total'] + $canceled_withdraws[0][0]['total']; 
                                                $total_credit += $adj_sum_credit;
                                                ?>
                                                <tr>
                                                    <td><b><?=__('Sum:');?></b></td>
                                                    <td style="text-align:right"><b>0.00</b></td>
                                                    <td></td>
                                                    <td style="text-align:right"><b><?= number_format((float)$adj_sum_credit, 2, '.', ''); ?></b></td>
                                                    <td></td>
                                                    <td style="text-align:right"><b><?= number_format((float)$adj_sum_credit, 2, '.', ''); ?></b></td>
                                                    <td style="text-align:center">+</td>
                                                </tr> 
                                                <tr style="height: 35px;">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr> 
                                                
                                                
                                                <!-- Bonus -->                                                
                                                <tr>
                                                    <td><b><?= __('Bonus turned real'); ?></b></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>                                                
                                                <tr>
                                                    <td><?=  __('Bonus turned real'); ?></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="text-align:right"><?= number_format((float)$bonus_real[0][0]['total'], 2, '.', ''); ?></td>
                                                    <td style="text-align:center"><?= $bonus_real[0][0]['count']; ?></td>
                                                    <td style="text-align:right"><?= number_format((float)$bonus_real[0][0]['total'], 2, '.', ''); ?></td>
                                                    <td style="text-align:center">+</td>
                                                </tr>   
                                                <?php $total_credit += $bonus_real[0][0]['total']; ?>                                             
                                                <tr>
                                                    <td><b><?=__('Sum:');?></b></td>
                                                    <td style="text-align:right"><b>0.00</b></td>
                                                    <td></td>
                                                    <td style="text-align:right"><b><?= number_format((float)$bonus_real[0][0]['total'], 2, '.', ''); ?></b></td>
                                                    <td></td>
                                                    <td style="text-align:right"><b><?= number_format((float)$bonus_real[0][0]['total'], 2, '.', ''); ?></b></td>
                                                    <td></td>
                                                </tr> 
                                                <tr style="height: 35px;">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr> 
                                                
                                                <tr class="underlined both">
                                                    <td><b>Total</b></td>
                                                    <td style="text-align:right"><b><?= number_format((float)$total_debit, 2, '.', ''); ?></b></td>
                                                    <td></td>
                                                    <td style="text-align:right"><b><?= number_format((float)$total_credit, 2, '.', ''); ?></b></td>
                                                    <td></td>
                                                    <td style="text-align:right"><b><?= number_format((float)($total_credit - $total_debit), 2, '.', ''); ?></b></td>
                                                    <td></td>
                                                </tr> 
                                                <tr style="height: 35px;">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr> 
                                                
                                                <tr class="underlined">
                                                    <td><b><?=__('Closing Player liability');?></b></td>
                                                    <td style="text-align:right"><span style="float:left">Debit</span> #<br /><b><?= number_format((float)$total_debit, 2, '.', ''); ?></b></td>
                                                    <td></td>
                                                    <td style="text-align:right"><span style="float:left">Credit</span> #<br /><b><?= number_format((float)$total_credit, 2, '.', ''); ?></b></td>
                                                    <td></td>
                                                    <td style="text-align:right"><span style="float:left">Net</span><br /><b><?= number_format((float)($total_credit - $total_debit), 2, '.', ''); ?></b></td>
                                                    <td></td>
                                                </tr> 
                                            </tbody>
                                        </table>
                                        <?php 
                                        $HTML_DOC  = ob_get_contents();
                                        ob_end_clean();   

                                        echo $HTML_DOC;
                                        ?>
                                        <?= $this->Form->create('Download'); ?>
                                        <?= $this->Form->input('download', array('value' => '1', 'type' => 'hidden')); ?>
                                        <?= $this->Form->input('filename', array('value' => $filename, 'type' => 'hidden')); ?>
                                        <?= $this->Form->input('htmltable', array('value' => $HTML_DOC, 'type' => 'hidden')); ?>
                                        <br>
                                        <?= $this->Form->submit(__('Download', true), array('class' => 'btn btn-danger')); ?>
                                        <?= $this->Form->end(); ?>
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