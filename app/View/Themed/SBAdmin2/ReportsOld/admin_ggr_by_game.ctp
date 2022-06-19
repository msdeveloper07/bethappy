<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => __('Reports'), 1 => __('GGR by Game Report'))))); ?></div>
    </div>

    <div class="row-fluid">
        <div class="span12"><h2><?= __('GGR by Game Report'); ?></h2></div>
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
                                        <?= __('Report will show GGR by players, for selected provider and selected currency. Please see report structure below:'); ?>
                                        <br><br>
                                        &bull; <?= __('Game'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the game\'s name.)'); ?></span><br>
                                        &bull; <?= __('Real'); ?> <?= __('Bets'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the total betting amount made on this game, with real money.)'); ?></span><br>
                                        &bull; <?= __('Real'); ?> <?= __('Wins'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the total winning amount won on this game, in real money.)'); ?></span><br>
                                        &bull; <?= __('Real'); ?> <?= __('Refunds'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the total refunded amount to this game, in real money.)'); ?></span><br>
                                        &bull; <?= __('Real'); ?> <?= __('GGR'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the total GGR of the game, made with real money.)'); ?></span><br>
                                        &bull; <?= __('Bonus'); ?> <?= __('Bets'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the total betting amount made on this game, with bonus money.)'); ?></span><br>
                                        &bull; <?= __('Bonus'); ?> <?= __('Wins'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the total winning amount won on this game in bonus money.)'); ?></span><br>
                                        &bull; <?= __('Bonus'); ?> <?= __('Refunds'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the total refunded amount to this game, in bonus money.)'); ?></span><br>
                                        &bull; <?= __('Bonus'); ?> <?= __('GGR'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the total GGR of the game made with bonus money.)'); ?></span><br>
                                        &bull; <?= __('Game'); ?> <?= __('GGR'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the total GGR of the game, made with real plus bonus money.)'); ?></span><br>

                                        <br>
                                        <?= __('You can generate a report by selecting a provider:'); ?><br><br>
                                        <?= __('Please choose your timeframe and currency, otherwise the report will show data for all currencies, for the current month.'); ?><br><br>
                                        <?= __('Please run Netent and Microgaming reports by currency, due to their extensiveness.'); ?><br><br>

                                        <div>
                                            <?= $this->element('reports_form', array('game_providers' => $game_providers, 'currencies' => $currencies)); ?>
                                        </div>
                                        <?php if (!empty($data)): ?>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <td colspan="5" style="border-right: none;">
                                                            <strong><?= Configure::read('Settings.websiteTitle'); ?></strong>
                                                        </td>
                                                        <td colspan="6" style="text-align:right; border-left: none;" >
                                                            <?= __('Report Date:'); ?> <?= date('d-M-Y H:i:s'); ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="5" style="border-right: none;">
                                                            <strong><?= $provider; ?> <?= __('GGR by Player Report'); ?></strong>
                                                        </td>
                                                        <td colspan="6" style="text-align:right; border-left: none;">
                                                            <?= __('Timeframe:'); ?> <?= $from; ?> - <?= $to; ?>
                                                        </td>
                                                    </tr>
                                                </thead>

                                                <?php
                                                foreach ($data as $currency => $users) :
                                                    if (!empty($currency)):
                                                        ?>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="11" style="background-color: #fff;">&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="5" style="background-color: #f9f9f9;"><?= $currency; ?></th>
                                                                <th colspan="6" style="background-color: #f9f9f9; text-align: right; border-left: none;">
                                                                    <button type="button" class="btncol btn btn-info" data-toggle="collapse" data-target=".collapseme<?= $currency; ?>"><?= __('Show/Hide'); ?></button>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="2"  style="text-align:center; background-color: #e1f5f285;"><?= __('Game'); ?></th>
                                                                <th style="text-align:center; background-color: #e1f5f285; "><?= __('Real'); ?> <?= __('Bets'); ?></th>
                                                                <th style="text-align:center; background-color: #e1f5f285; "><?= __('Real'); ?> <?= __('Wins'); ?></th>
                                                                <th style="text-align:center; background-color: #e1f5f285; "><?= __('Real'); ?> <?= __('Refunds'); ?></th>
                                                                <th style="text-align:center; background-color: #e1f5f285; "><?= __('Real'); ?> <?= __('GGR'); ?></th>
                                                                <th style="text-align:center; background-color: #e1f5f285; "><?= __('Bonus'); ?> <?= __('Bets'); ?></th>
                                                                <th style="text-align:center; background-color: #e1f5f285; "><?= __('Bonus'); ?> <?= __('Wins'); ?></th>
                                                                <th style="text-align:center; background-color: #e1f5f285; "><?= __('Bonus'); ?> <?= __('Refunds'); ?></th>
                                                                <th style="text-align:center; background-color: #e1f5f285; "><?= __('Bonus'); ?> <?= __('GGR'); ?></th>
                                                                <th style="text-align:center; background-color: #e1f5f285; min-width: 100px"><?= __('Game'); ?> <?= __('GGR'); ?></th>
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
                                                                    ?>
                                                                    <tr class="collapseme<?= $currency; ?>">
                                                                        <td colspan = "2"  style="text-align:center; " >
                                                                            <?= ucfirst($user['User']['first_name']) . ' ' . ucfirst($user['User']['last_name']); ?><br/>
                                                                            <small>(<?= $user['User']['id'] . ': ' . $user['User']['username']; ?>)</small>
                                                                        </td>
                                                                        <td style="text-align:right; "><?= number_format($user['RealTransactions']['Bets'], 2, '.', ','); ?></td> 
                                                                        <td style="text-align:right; "><?= number_format($user['RealTransactions']['Wins'], 2, '.', ','); ?></td> 
                                                                        <td style="text-align:right; "><?= number_format($user['RealTransactions']['Refunds'], 2, '.', ','); ?></td> 
                                                                        <td style="text-align:right; "><?= number_format($user['RealTransactions']['Bets'] - $user['RealTransactions']['Wins'], 2, '.', ','); ?></td> 
                                                                        <td style="text-align:right; "><?= number_format($user['BonusTransactions']['Bets'], 2, '.', ','); ?></td> 
                                                                        <td style="text-align:right; "><?= number_format($user['BonusTransactions']['Wins'], 2, '.', ','); ?></td> 
                                                                        <td style="text-align:right; "><?= number_format($user['BonusTransactions']['Refunds'], 2, '.', ','); ?></td> 
                                                                        <td style="text-align:right; "><?= number_format($user['BonusTransactions']['Bets'] - $user['BonusTransactions']['Wins'], 2, '.', ','); ?></td> 
                                                                        <td style="text-align:right; "><?= number_format(($user['RealTransactions']['Bets']) - $user['RealTransactions']['Wins'] + ($user['BonusTransactions']['Bets']) - $user['BonusTransactions']['Wins'], 2, '.', ','); ?></td> 
                                                                    </tr>
                                                                    <?php
                                                                endif;
                                                            endforeach;
                                                            ?>      
                                                        </tbody>

                                                        <tr>
                                                            <td colspan="2" style="background-color: #f9f9f9; "><b><i><?= __('TOTALS'); ?></i></b></td>
                                                            <td style="text-align:right; background-color: #f9f9f9; "><b><i><?= __('Real'); ?> <?= __('Bets'); ?>: <?= number_format($totalbets, 2, '.', ','); ?></i></b></td>
                                                            <td style="text-align:right; background-color: #f9f9f9; "><b><i><?= __('Real'); ?> <?= __('Wins'); ?>: <?= number_format($totalwins, 2, '.', ','); ?></i></b></td>
                                                            <td style="text-align:right; background-color: #f9f9f9; "><b><i><?= __('Real'); ?> <?= __('Refunds'); ?>: <?= number_format($totalrefunds, 2, '.', ','); ?></i></b></td>
                                                            <td style="text-align:right; background-color: #f9f9f9; "><b><i><?= __('Real'); ?> <?= __('GGR'); ?>:<?= number_format($totalbets - $totalwins, 2, '.', ','); ?></i></b></td>
                                                            <td style="text-align:right; background-color: #f9f9f9; "><b><i><?= __('Bonus'); ?> <?= __('Bets'); ?>: <?= number_format($totalbonusbets, 2, '.', ','); ?></i></b></td>
                                                            <td style="text-align:right; background-color: #f9f9f9; "><b><i><?= __('Bonus'); ?> <?= __('Wins'); ?>: <?= number_format($totalbonuswins, 2, '.', ','); ?></i></b></td>
                                                            <td style="text-align:right; background-color: #f9f9f9; "><b><i><?= __('Bonus'); ?> <?= __('Refunds'); ?>: <?= number_format($totalbonusrefunds, 2, '.', ','); ?></i></b></td>
                                                            <td style="text-align:right; background-color: #f9f9f9; "><b><i><?= __('Bonus'); ?> <?= __('GGR'); ?>: <?= number_format($totalbonusbets - $totalbonuswins, 2, '.', ','); ?></i></b></td>
                                                            <td style="text-align:right; background-color: #f9f9f9; "><?= __('GGR'); ?>: <?= $totalPLAYERGGR; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="11" style="background-color: #fff; padding: 0; line-height: 1;">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="8" style="background-color: #f9f9f9; "><b><i><?= $currency; ?> <?= __('TOTALS'); ?></i></b></td>
                                                            <td style="text-align:right; background-color: #f9f9f9; "><b><i><?= __('Bets'); ?>: <?= number_format($totalbets + $totalbonusbets, 2, '.', ','); ?></i></b></td>
                                                            <td style="text-align:right; background-color: #f9f9f9; "><b><i><?= __('Wins'); ?>: <?= number_format($totalwins + $totalbonuswins, 2, '.', ','); ?></i></b></td>
                                                            <td style="text-align:right; background-color: #f9f9f9; "><b><i><?= __('GGR'); ?>: <?= number_format(($totalbets - $totalwins) + ($totalbonusbets - $totalbonuswins), 2, '.', ','); ?></i></b></td>
                                                        </tr>

                                                        <?php
                                                        $totalPLAYERGGR = $totalbets = $totalwins = $totalrefunds = $totalbonusbets = $totalbonuswins = $totalbonusrefunds = 0;
                                                    endif;
                                                    ?>
                                                    <?php
                                                endforeach;
                                                ?>  
                                            </table>

                                            <div class="print-form text-center">
                                                <?= $this->Form->create('Download'); ?>
                                                <?= $this->Form->input('download', array('value' => '1', 'type' => 'hidden')); ?>
                                                <?= $this->Form->input('from', array('type' => 'hidden')); ?>
                                                <?= $this->Form->input('to', array('type' => 'hidden')); ?>
                                                <br>
                                                <?= $this->Form->submit(__('Download PDF', true), array('class' => 'btn btn-success')); ?>
                                                <?= $this->Form->end(); ?>
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
    })
</script>