<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('GGR'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('GGR'); ?></h1>
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
                <?= $this->element('reports_form'); ?><br/><br/>

                <div class="table-responsive">
                    <?php if (!empty($data)): //var_dump($data);?>
                        <?php ob_start(); ?>
                        <table class="table table-bordered" style="font-size:12px;">
                            <thead>
                                <tr>
                                    <td colspan="2" style="border-right: none;">
                                        <strong><?= $user['first_name'] . ' ' . $user['last_name']; ?> (<?= $user['username']; ?>)</strong>
                                    </td>
                                    <td colspan="6" style="text-align:right; border-left: none;">
                                        <?= __('Report Date:'); ?> <?= date('d-M-Y H:i:s'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border-right: none;">
                                        <strong><?= __('GGR Report'); ?></strong>
                                    </td>
                                    <td colspan="6" style="text-align:right; border-left: none;">
                                        <?= __('Timeframe:'); ?> <?= $from; ?> - <?= $to; ?>
                                    </td>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td colspan="8" style="background-color: #fff; ">&nbsp;</td>
                                </tr>

                                <tr class="sub-heading">
                                    <th colspan="2"  style="text-align:center;"></th>
                                    <th style="text-align:center;"><?= __('Bets'); ?></th>
                                    <th style="text-align:center;"><?= __('Wins'); ?></th>
                                    <th style="text-align:center;"><?= __('Refunds'); ?></th>
                                    <th style="text-align:center;"><?= __('Rollbacks'); ?></th>
    <!--                                            <th style="text-align:center;"><?= __('Real'); ?><br/> <?= __('GGR'); ?></th>
                                    <th style="text-align:center;"><?= __('Bonus'); ?><br/> <?= __('Bets'); ?></th>
                                    <th style="text-align:center;"><?= __('Bonus'); ?><br/> <?= __('Wins'); ?></th>
                                    <th style="text-align:center;"><?= __('Bonus'); ?><br/> <?= __('Refunds'); ?></th>
                                    <th style="text-align:center;"><?= __('Bonus'); ?><br/> <?= __('Rollbacks'); ?></th>
                                    <th style="text-align:center;"><?= __('Bonus'); ?><br/> <?= __('GGR'); ?></th>-->
                                    <th style="width:5px;">&nbsp;</th>
                                    <th style="text-align:center;"><?= __('GGR'); ?></th>
                                </tr>
                                <?php
                                foreach ($data as $user_id => $user):

                                    if (!empty($user['RealTransactions']) || !empty($user['BonusTransactions'])):
                                        ?>

                                        <?php
                                        //real for all players
                                        $total_real_bets += $user['RealTransactions']['real_bets'];
                                        $total_real_wins += $user['RealTransactions']['real_wins'];
                                        $total_real_refunds += $user['RealTransactions']['real_refunds'];
                                        $total_real_rollbacks += $user['RealTransactions']['real_rollbacks'];

                                        //bonus for all players
                                        $total_bonus_bets += $user['BonusTransactions']['bonus_bets'] ? $user['BonusTransactions']['bonus_bets'] : 0;
                                        $total_bonus_wins += $user['BonusTransactions']['bonus_wins'] ? $user['BonusTransactions']['bonus_wins'] : 0;
                                        $total_bonus_refunds += $user['BonusTransactions']['bonus_refunds'] ? $user['BonusTransactions']['bonus_refunds'] : 0;
                                        $total_bonus_rollbacks += $user['BonusTransactions']['bonus_rollbacks'] ? $user['BonusTransactions']['bonus_rollbacks'] : 0;

                                        //total player
                                        $total_player_real_GGR = round($user['RealTransactions']['real_bets'] - $user['RealTransactions']['real_wins'] + $user['RealTransactions']['real_refunds'] - $user['RealTransactions']['real_rollbcks'], 2);
                                        $total_player_bonus_GGR = round($user['BonusTransactions']['bonus_bets'] - $user['BonusTransactions']['bonus_wins'] + $user['BonusTransactions']['bonus_refunds'] - $user['BonusTransactions']['bonus_rollbacks'], 2);
                                        $total_player_GGR = round(($user['RealTransactions']['real_bets'] - $user['RealTransactions']['real_wins'] + $user['RealTransactions']['real_refunds'] - $user['RealTransactions']['real_rollbcks']) + ($user['BonusTransactions']['bonus_bets'] - $user['BonusTransactions']['bonus_wins'] + $user['BonusTransactions']['bonus_refunds'] - $user['BonusTransactions']['bonus_rollbacks']), 2);


                                        $total_real_GGR += $total_player_real_GGR;
                                        $total_bonus_GGR += $total_player_bonus_GGR;

                                        $total_GGR = round($total_real_GGR + $total_bonus_GGR, 2);


                                        if ($total_player_GGR > 0) {
                                            $player_status = "#CCFF90";
                                            $ggr_status = "#CCFF90";
                                            $total_status = "#CCFF90";
                                        } elseif ($total_player_GGR < 0) {
                                            $player_status = "#ffab91";
                                            $ggr_status = "#ffab91";
                                            $total_status = "#ffab91";
                                        } else {
                                            $player_status = "#FFEE58";
                                            $ggr_status = "#FFEE58";
                                            $total_status = "#FFEE58";
                                        }

                                        if ($total_real_GGR > 0) {
                                            $real_status = "#CCFF90";
                                        } elseif ($total_real_GGR < 0) {
                                            $real_status = "#ffab91";
                                        } else {
                                            $real_status = "#FFEE58";
                                        }


                                        if ($total_bonus_GGR > 0) {
                                            $bonus_status = "#CCFF90";
                                        } elseif ($total_bonus_GGR < 0) {
                                            $bonus_status = "#ffab91";
                                        } else {
                                            $bonus_status = "#FFEE58";
                                        }

//                 
                                        ?>
                                        <tr>
                                            <td colspan = "2"  style="text-align:center; " >
                                                <?= ucwords(str_replace("'", "", $user['User']['first_name'])) . ' ' . ucwords(str_replace("'", "", $user['User']['last_name'])); ?><br/>
                                                <small>(<?= $user['User']['id'] . ': ' . $user['User']['username']; ?>)</small>
                                            </td>
                                            <td style="text-align:right;"><?= number_format($user['RealTransactions']['real_bets'], 2, '.', ','); ?></td> 
                                            <td style="text-align:right;"><?= number_format($user['RealTransactions']['real_wins'], 2, '.', ','); ?></td> 
                                            <td style="text-align:right;"><?= number_format($user['RealTransactions']['real_refunds'], 2, '.', ','); ?></td> 
                                            <td style="text-align:right;"><?= number_format($user['RealTransactions']['real_rollbacks'], 2, '.', ','); ?></td> 
            <!--                                                    <td style="text-align:right; background-color: #eceff1;"><?= number_format($total_player_real_GGR, 2, '.', ','); ?></td> 
                                            <td style="text-align:right;"><?= number_format($user['BonusTransactions']['bonus_bets'], 2, '.', ','); ?></td> 
                                            <td style="text-align:right;"><?= number_format($user['BonusTransactions']['bonus_wins'], 2, '.', ','); ?></td> 
                                            <td style="text-align:right;"><?= number_format($user['BonusTransactions']['bonus_refunds'], 2, '.', ','); ?></td> 
                                            <td style="text-align:right;"><?= number_format($user['BonusTransactions']['bonus_rollbacks'], 2, '.', ','); ?></td> 
                                            <td style="text-align:right; background-color: #eceff1;"><?= number_format($total_player_bonus_GGR, 2, '.', ','); ?></td> -->
                                            <td style="width:5px;">&nbsp;</td>
                                            <td style="text-align:right; width: 100px; background-color:<?= $player_status; ?>"><?= number_format($total_player_GGR, 2, '.', ','); ?></td> 

                                        </tr>
                                        <?php
                                    endif;
                                    ?>      

                                    <?php
                                    $total_player_GGR = $total_real_bets = $total_real_wins = $total_real_refunds = $total_real_rollbacks = $total_real_GGR = $total_bonus_bets = $total_bonus_wins = $total_bonus_refunds = $total_bonus_rollbacks = $total_bonus_GGR = $total_GGR = 0;

                                endforeach;
                                ?> 



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
                            <input type='hidden' name='data[Report][header]' value='<?= $provider . ' ' . __('GGR by Player Report'); ?>'/>
                            <input type='hidden' name='data[Report][title]' value='<?= $provider . '_' . __('GGR_Report'); ?>'/>
                            <input type='hidden' name='data[Report][from]' value='<?= $from; ?>'/>
                            <input type='hidden' name='data[Report][to]' value='<?= $to; ?>'/>
                            <!--<?//= $this->MyForm->submit(__('Download PDF', true), array('class' => 'btn btn-success')); ?>-->
                        </div>
                    <?php elseif (isset($data)): ?>
                        <?= __('No data in this period.'); ?>
                    <?php endif; ?>  

                </div>
            </div>
        </div>
    </div>
</div>

<!--<script>
    $(".btncol").click(function () {
        $($(this).data("target")).toggle();
    });
</script>-->