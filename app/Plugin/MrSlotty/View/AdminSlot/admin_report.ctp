<div class="container-fluid">
    <div class="row-fluid"><div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __('Create %s', __($singularName)))))); ?></div></div>
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="table table-custom">
                                    <?= $this->element('tabs');?>
                                    <div class="tab-content">
                                        <?= $this->element('slots_form', array('model' => 'Report'));?>
                                            <?=__('Report will show Slots Analytics History for all players and games. Please set your own filters on the left.');?>
                                             <?=$datefrom;?> - <?=$dateto;?>
                                            <?php if (!empty($data)): ?>
                                            <?php foreach ($data as $currency=>$users){ ?>
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th colspan="8"><?=$currency;?></th>
                                                    <th><button type="button" class="btncol" data-toggle="collapse" data-target=".collapseme<?=$currency;?>">Click Hide</button></th>
                                                </tr>
                                                <tr>
                                                    <th><?= __('User ID'); ?></th>
                                                    <th><?= __('User name'); ?></th>
                                                    <th><?= __('Balance'); ?></th>
                                                    <th><?= __('Bets'); ?></th>
                                                    <th><?= __('Wins'); ?></th>
                                                    <th><?= __('Refunds'); ?></th>
                                                    <th><?= __('GGR'); ?></th>
                                                    <th><?= __('Bonus'); ?> <?= __('Bets'); ?></th>
                                                    <th><?= __('Bonus'); ?> <?= __('Wins'); ?></th>
                                                    <th><?= __('Bonus'); ?> <?= __('Refunds'); ?></th>
                                                    <th><?= __('Bonus'); ?> <?= __('GGR'); ?></th>
                                                    <th><?= __('Player'); ?> <?= __('GGR'); ?></th>
                                                </tr>
                                                <?php foreach ($users as $user){
                                                    if ($user['RealTransactions']['Bets']!=0 || $user['BonusTransactions']['Bets']!=0){
                                                        
                                                        $totalbet+=abs($user['RealTransactions']['Bets']);
                                                        $totalwin+= $user['RealTransactions']['Wins'];
                                                        $totalrefund+= $user['RealTransactions']['Refund'];
                                                        $totalbonusbet+=abs($user['BonusTransactions']['Bets']);
                                                        $totalbonuswin+= $user['BonusTransactions']['Wins'];
                                                        $totalbonusrefund+= $user['BonusTransactions']['Refund'];
							$totalPLAYERGGR +=round(abs($user['RealTransactions']['Bets']) - $user['RealTransactions']['Wins'] + abs($user['BonusTransactions']['Bets']) - $user['BonusTransactions']['Wins'],2); 
                                                    ?>
                                                <tr class="collapseme<?=$currency;?>">
                                                    <td><?= $user['id']; ?></td>
                                                    <td><?= $user['username']; ?></td>
                                                    <td><?= $user['balance']; ?></td>
                                                    <td><?= round(abs($user['RealTransactions']['Bets']),2); ?></td> 
                                                    <td><?= round($user['RealTransactions']['Wins'],2); ?></td> 
                                                    <td><?= round($user['RealTransactions']['Refund'],2); ?></td> 
                                                    <td><?= round(abs($user['RealTransactions']['Bets']) - $user['RealTransactions']['Wins'],2); ?></td> 
                                                    <td><?= round(abs($user['BonusTransactions']['Bets']),2); ?></td> 
                                                    <td><?= round($user['BonusTransactions']['Wins'],2); ?></td> 
                                                    <td><?= round($user['BonusTransactions']['Refund'],2); ?></td> 
                                                    <td><?= round(abs($user['BonusTransactions']['Bets']) - $user['BonusTransactions']['Wins'],2); ?></td> 
                                                    <td><?= round(abs($user['RealTransactions']['Bets']) - $user['RealTransactions']['Wins'] + ($user['BonusTransactions']['Bets']) - $user['BonusTransactions']['Wins'],2); ?></td> 
                                                </tr>
                                                    <?php } } ?>
                                                 
                                                 <tr>
                                                    <td colspan="3"><b><i><?= __('Totals'); ?></i></b></td>
                                                    <td><b><i><?= $totalbet; ?></i></b></td>
                                                    <td><b><i><?= $totalwin; ?></i></b></td>
                                                    <td><b><i><?= $totalrefund; ?></i></b></td>
                                                    <td><b><i><?= $totalbet - $totalwin; ?></i></b></td>
                                                    <td><b><i><?= $totalbonusbet; ?></i></b></td>
                                                    <td><b><i><?= $totalbonuswin ?></i></b></td>
                                                    <td><b><i><?= $totalbonusrefund; ?></i></b></td>
                                                    <td><b><i><?= $totalbonusbet - $totalbonuswin; ?></i></b></td>
                                                    <td> </td>
                                                </tr>
                                                 <tr>
                                                    <td colspan="8"><b><i><?= __('Super'); ?> <?= __('Totals'); ?></i></b></td>
                                                    <td><b><i><?= __('Bets'); ?>: <?= $totalbet+$totalbonusbet; ?></i></b></td>
                                                    <td><b><i><?= __('Wins'); ?>: <?= $totalwin+$totalbonuswin; ?></i></b></td>
                                                    <td><b><i><?=$currency;?>: <?= ($totalbet - $totalwin) + ($totalbonusbet - $totalbonuswin); ?></i></b></td>
                                                    <td><?=$totalPLAYERGGR;?></td>
                                                </tr>
                                            </table>
                                            <?php 
                                                $totalPLAYERGGR = $totalrefund = $totalbonusrefund = $totalbet = $totalwin = $totalbonusbet = $totalbonuswin = 0;
                                                } 
                                            ?>
                                            <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
