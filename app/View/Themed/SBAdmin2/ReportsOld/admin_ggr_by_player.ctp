<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => __('Reports'), 1 => __('GGR by Player Report'))))); ?></div>
    </div>

    <div class="row-fluid">
        <div class="span12"><h2><?= __('GGR by Player Reports'); ?></h2></div>
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
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#total" data-toggle="tab">GGR</a></li>
                                    <li><a href="#real" data-toggle="tab">Real GGR</a></li>
                                    <li><a href="#bonus" data-toggle="tab">Bonus GGR</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="total">GGR</div>
                                    <div class="tab-pane" id="real">Real</div>
                                    <div class="tab-pane" id="bonus">Bonus</div>
                                </div>





                                <div class="table table-custom">
                                    <?= $this->element('tabs'); ?>
                                    <div class="tab-content">
                                        <div>
                                            <?= __('Report will show GGR by players, for selected provider and selected currency. Please see report structure below:'); ?>
                                            <br/><br/>
                                            &bull; <?= __('Player'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is player\'s full name. Players\' id and username are in the brackets.)'); ?></span><br/>
                                            &bull; <?= __('Real'); ?> <?= __('Bets'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the total betting amount of the player, made with his real money.)'); ?></span><br/>
                                            &bull; <?= __('Real'); ?> <?= __('Wins'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the total winning amount of the player, made with his real money.)'); ?></span><br/>
                                            &bull; <?= __('Real'); ?> <?= __('Refunds'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the total refunded amount to the player, to his real money.)'); ?></span><br/>
                                            &bull; <?= __('Real'); ?> <?= __('GGR'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the total GGR player made with his real money.)'); ?></span><br/>
                                            &bull; <?= __('Bonus'); ?> <?= __('Bets'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the total betting amount of the player, made with his bonus money.)'); ?></span><br/>
                                            &bull; <?= __('Bonus'); ?> <?= __('Wins'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the total winning amount of the player, made with his bonus money.)'); ?></span><br/>
                                            &bull; <?= __('Bonus'); ?> <?= __('Refunds'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the total refunded amount to the player, to his bonus money.)'); ?></span><br/>
                                            &bull; <?= __('Bonus'); ?> <?= __('GGR'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the total GGR player made with his bonus money.)'); ?></span><br>
                                            &bull; <?= __('Player'); ?> <?= __('GGR'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the total GGR player made with his real plus his bonus money.)'); ?></span><br/>

                                            <br/>
                                            <?= __('You can generate a report by selecting a provider.'); ?><br/><br/>
                                            <?= __('Please choose your timeframe and currency, otherwise the report will show data for all currencies, for the current month.'); ?><br/><br/>
                                            <div>
                                                <?= $this->element('reports_form', array('game_providers' => $game_providers, 'currencies' => $currencies)); ?>
                                            </div>
                                        </div>

                                        <?php if (!empty($data)): ?>
                                            <?php ob_start(); ?>
                                            <table class="table table-bordered" style="font-size:12px;">
                                                <thead>
                                                    <tr>
                                                        <td colspan="6" style="border-right: none;">
                                                            <strong><?= Configure::read('Settings.websiteTitle'); ?></strong>
                                                        </td>
                                                        <td colspan="6" style="text-align:right; border-left: none;">
                                                            <?= __('Report Date:'); ?> <?= date('d-M-Y H:i:s'); ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6" style="border-right: none;">
                                                            <strong><?= $provider; ?> <?= __('GGR by Player Report'); ?></strong>
                                                        </td>
                                                        <td colspan="6" style="text-align:right; border-left: none;">
                                                            <?= __('Timeframe:'); ?> <?= $from; ?> - <?= $to; ?>
                                                        </td>
                                                    </tr>
                                                </thead>

                                                <?php
                                                foreach ($data as $currency => $users):
                                                    if (!empty($currency)):
                                                        ?>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="12" style="background-color: #fff; ">&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="12" style="background-color: #f9f9f9; text-align:left;"><?= $currency; ?></th>
                                                            </tr>
                                                            <tr class="sub-heading">
                                                                <th colspan="2"  style="text-align:center; background-color: #E1F5F2;"><?= __('Player'); ?></th>
                                                                <th style="text-align:center; background-color: #E1F5F2;"><?= __('Real'); ?><br/> <?= __('Bets'); ?></th>
                                                                <th style="text-align:center; background-color: #E1F5F2;"><?= __('Real'); ?><br/> <?= __('Wins'); ?></th>
                                                                <th style="text-align:center; background-color: #E1F5F2;"><?= __('Real'); ?><br/> <?= __('Refunds'); ?></th>
                                                                <th style="text-align:center; background-color: #E1F5F2;"><?= __('Real'); ?><br/> <?= __('GGR'); ?></th>
                                                                <th style="text-align:center; background-color: #E1F5F2;"><?= __('Bonus'); ?><br/> <?= __('Bets'); ?></th>
                                                                <th style="text-align:center; background-color: #E1F5F2;"><?= __('Bonus'); ?><br/> <?= __('Wins'); ?></th>
                                                                <th style="text-align:center; background-color: #E1F5F2;"><?= __('Bonus'); ?><br/> <?= __('Refunds'); ?></th>
                                                                <th style="text-align:center; background-color: #E1F5F2;"><?= __('Bonus'); ?><br/> <?= __('GGR'); ?></th>
                                                                <th style="width:5px;">&nbsp;</th>
                                                                <th style="text-align:center; background-color: #E1F5F2;"><?= __('Player'); ?> <?= __('GGR'); ?></th>
                                                            </tr>
                                                            <?php
                                                            foreach ($users as $key => $user) :
                                                                if ($user['RealTransactions']['Bets'] || $user['BonusTransactions']['Bets']) :
                                                                    $totalbets += $user['RealTransactions']['Bets'];
                                                                    $totalwins += $user['RealTransactions']['Wins'];
                                                                    $totalrefunds += $user['RealTransactions']['Refunds'];
                                                                    $totalbonusbets += $user['BonusTransactions']['Bets'];
                                                                    $totalbonuswins += $user['BonusTransactions']['Wins'];
                                                                    $totalbonusrefunds += $user['BonusTransactions']['Refunds'];
                                                                    $totalPLAYERGGR += round(($user['RealTransactions']['Bets']) - $user['RealTransactions']['Wins'] + ($user['BonusTransactions']['Bets']) - $user['BonusTransactions']['Wins'], 2);


                                                                    if ((($user['RealTransactions']['Bets']) - $user['RealTransactions']['Wins'] + ($user['BonusTransactions']['Bets']) - $user['BonusTransactions']['Wins']) > 0) {
                                                                        $player_status = "#CCFF90";
                                                                    } elseif ((($user['RealTransactions']['Bets']) - $user['RealTransactions']['Wins'] + ($user['BonusTransactions']['Bets']) - $user['BonusTransactions']['Wins']) < 0) {
                                                                        $player_status = "#ffab91";
                                                                    } else {
                                                                        $player_status = "#FFEE58";
                                                                    }

                                                                    if ($totalPLAYERGGR > 0) {
                                                                        $ggr_status = "#CCFF90";
                                                                    } elseif ($totalPLAYERGGR < 0) {
                                                                        $ggr_status = "#ffab91";
                                                                    } else {
                                                                        $ggr_status = "#FFEE58";
                                                                    }

                                                                    //currency ggr 
                                                                    if (($totalbets - $totalwins) + ($totalbonusbets - $totalbonuswins) > 0) {
                                                                        $total_status = "#CCFF90";
                                                                    } elseif (($totalbets - $totalwins) + ($totalbonusbets - $totalbonuswins) < 0) {
                                                                        $total_status = "#ffab91";
                                                                    } else {
                                                                        $total_status = "#FFEE58";
                                                                    }
                                                                    //total real ggr done
                                                                    if (($totalbets - $totalwins) > 0) {
                                                                        $real_status = "#CCFF90";
                                                                    } elseif (($totalbets - $totalwins) < 0) {
                                                                        $real_status = "#ffab91";
                                                                    } else {
                                                                        $real_status = "#FFEE58";
                                                                    }
                                                                    //total bonus ggr done
                                                                    if (($totalbonusbets - $totalbonuswins) > 0) {
                                                                        $bonus_status = "#CCFF90";
                                                                    } elseif (($totalbonusbets - $totalbonuswins) < 0) {
                                                                        $bonus_status = "#ffab91";
                                                                    } else {
                                                                        $bonus_status = "#FFEE58";
                                                                    }
                                                                    ?>
                                                                    <tr class="collapseme<?= $currency; ?>">
                                                                        <td colspan = "2"  style="text-align:center; " >
                                                                            <?= ucwords(str_replace("'", "", $user['User']['first_name'])) . ' ' . ucwords(str_replace("'", "", $user['User']['last_name'])); ?><br/>
                                                                            <small>(<?= $user['User']['id'] . ': ' . $user['User']['username']; ?>)</small>
                                                                        </td>
                                                                        <td style="text-align:right;"><?= number_format($user['RealTransactions']['Bets'], 2, '.', ','); ?></td> 
                                                                        <td style="text-align:right;"><?= number_format($user['RealTransactions']['Wins'], 2, '.', ','); ?></td> 
                                                                        <td style="text-align:right;"><?= number_format($user['RealTransactions']['Refunds'], 2, '.', ','); ?></td> 
                                                                        <td style="text-align:right; background-color: #eceff1;"><?= number_format($user['RealTransactions']['Bets'] - $user['RealTransactions']['Wins'], 2, '.', ','); ?></td> 
                                                                        <td style="text-align:right;"><?= number_format($user['BonusTransactions']['Bets'], 2, '.', ','); ?></td> 
                                                                        <td style="text-align:right;"><?= number_format($user['BonusTransactions']['Wins'], 2, '.', ','); ?></td> 
                                                                        <td style="text-align:right;"><?= number_format($user['BonusTransactions']['Refunds'], 2, '.', ','); ?></td> 
                                                                        <td style="text-align:right; background-color: #eceff1;"><?= number_format($user['BonusTransactions']['Bets'] - $user['BonusTransactions']['Wins'], 2, '.', ','); ?></td> 
                                                                        <td style="width:5px;">&nbsp;</td>
                                                                        <td style="text-align:right; width: 100px; background-color:<?= $player_status; ?>"><?= number_format(($user['RealTransactions']['Bets']) - $user['RealTransactions']['Wins'] + ($user['BonusTransactions']['Bets']) - $user['BonusTransactions']['Wins'], 2, '.', ','); ?></td> 
                                                                    </tr>
                                                                    <?php
                                                                endif;
                                                            endforeach;
                                                            ?>      
                                                        </tbody>

                                                        <tr>
                                                            <td colspan="12" style="background-color: #fff; padding: 0; line-height: 1;">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" style="background-color: #f9f9f9; text-align: right; "><b><i><?= __('TOTALS'); ?>:</i></b></td>
                                                            <td style="text-align:right; background-color: #f9f9f9; "><b><i><?= number_format($totalbets, 2, '.', ','); ?></i></b></td>
                                                            <td style="text-align:right; background-color: #f9f9f9; "><b><i><?= number_format($totalwins, 2, '.', ','); ?></i></b></td>
                                                            <td style="text-align:right; background-color: #f9f9f9; "><b><i><?= number_format($totalrefunds, 2, '.', ','); ?></i></b></td>
                                                            <td style="text-align:right; background-color: <?= $real_status; ?>; "><b><i><?= number_format($totalbets - $totalwins, 2, '.', ','); ?></i></b></td>
                                                            <td style="text-align:right; background-color: #f9f9f9; "><b><i><?= number_format($totalbonusbets, 2, '.', ','); ?></i></b></td>
                                                            <td style="text-align:right; background-color: #f9f9f9; "><b><i> <?= number_format($totalbonuswins, 2, '.', ','); ?></i></b></td>
                                                            <td style="text-align:right; background-color: #f9f9f9; "><b><i><?= number_format($totalbonusrefunds, 2, '.', ','); ?></i></b></td>
                                                            <td style="text-align:right; background-color: <?= $bonus_status; ?>; "><b><i><?= number_format($totalbonusbets - $totalbonuswins, 2, '.', ','); ?></i></b></td>
                                                            <td style="width:5px;">&nbsp;</td>
                                                            <td style="text-align:right; background-color: <?= $ggr_status; ?>; "><?= number_format($totalPLAYERGGR, 2, '.', ','); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="12" style="background-color: #fff; padding: 0; line-height: 1;">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="8" style="background-color: #f9f9f9; text-align: right; "><b><i><?= $currency; ?> <?= __('TOTALS'); ?>:</i></b></td>
                                                            <td style="text-align:right; background-color: #f9f9f9; "><b><i><?= __('Bets'); ?>: <?= number_format($totalbets + $totalbonusbets, 2, '.', ','); ?></i></b></td>
                                                            <td style="text-align:right; background-color: #f9f9f9; "><b><i><?= __('Wins'); ?>: <?= number_format($totalwins + $totalbonuswins, 2, '.', ','); ?></i></b></td>
                                                            <td style="width:5px;">&nbsp;</td>
                                                            <td style="text-align:right; background-color: <?= $total_status; ?>; "><b><i><?= __('GGR'); ?>: <?= number_format(($totalbets - $totalwins) + ($totalbonusbets - $totalbonuswins), 2, '.', ','); ?></i></b></td>
                                                        </tr>

                                                        <?php
                                                        $totalPLAYERGGR = $totalbets = $totalwins = $totalrefunds = $totalbonusbets = $totalbonuswins = $totalbonusrefunds = 0;
                                                    endif;
                                                endforeach;
                                                ?>  
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
<script>
    $(".btncol").click(function () {
        $($(this).data("target")).toggle();
    });
</script>