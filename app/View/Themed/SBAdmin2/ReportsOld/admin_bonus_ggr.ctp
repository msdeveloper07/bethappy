<div class="container-fluid">

    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => __('Reports'), 1 => __('Bonus GGR'))))); ?></div>
    </div>

    <div class="row-fluid">
        <div class="span12"><h3 class="page-title"><?= __('Bonus'); ?> <?= __('GGR'); ?></h3></div>
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
                                        <?= __('Report will show bonuses GGR for selected provider, groupped by currency. Please see report structure below:'); ?>
                                        <br><br>
                                        &bull; <?= __('Player'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is player\'s full name. Player\'s id and username are in the brackets.)'); ?></span><br>
                                        &bull; <?= __('Balance'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is player\'s real balance.)'); ?></span><br>
                                        &bull; <?= __('Bets'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the sum of bonus bets made by player.)'); ?></span><br>
                                        &bull; <?= __('Wins'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the sum of bonus wins received by player.)'); ?></span><br>
                                        &bull; <?= __('Refunds'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the sum of bonus refunds made to player.)'); ?></span><br>
                                        &bull; <?= __('Bonus GGR'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the bonus GGR.)'); ?></span><br>
                                        &bull; <?= __('Player GGR'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is player\'s bonus GGR.)'); ?></span><br>
                                        <br>
                                        <?= __('You can generate a report by selecting a provider.'); ?><br/><br/>
                                            <?= __('Please choose your timeframe and currency, otherwise the report will show data for all currencies, for the current month.'); ?><br/><br/>
                                            <div>
                                            <?= $this->element('reports_form', array('model' => 'Report', 'game_providers' => $game_providers)); ?>
                                        </div>


                                        <?php if (!empty($data)): ?>
                                            <?php ob_start(); ?>
                                            <table class="table table-bordered">

                                                <thead>
                                                    <tr>
                                                        <td colspan="3" style="border-right: none;">
                                                            <strong><?= Configure::read('Settings.websiteTitle'); ?></strong>
                                                        </td>
                                                        <td  colspan="4" style="text-align:right; border-left: none;">
                                                            <?= __('Report Date:'); ?> <?= date('d-M-Y H:i:s'); ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" style="border-right: none;">
                                                            <strong><?= $provider; ?> <?= __('Bonus GGR Report'); ?></strong>
                                                        </td>
                                                        <td colspan="4" style="text-align:right; border-left: none;">
                                                            <?= __('Timeframe:'); ?> <?= $from; ?> - <?= $to; ?>
                                                        </td>
                                                    </tr>


                                                </thead>

                                                <?php foreach ($data as $currency => $users) : ?>
                                                    <tr>
                                                        <td colspan="7" style="background-color: #fff;">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="7" style="background-color: #f9f9f9; text-align: left;"><?= $currency; ?></th>

                                                    </tr>

                                                    <tr>
                                                        <th colspan="2"  style="text-align:center; background-color: #E1F5F2; "><?= __('Player'); ?></th>
                                                        <th style="text-align:center; background-color: #E1F5F2; "><?= __('Balance'); ?></th>
                                                        <th style="text-align:center; background-color: #E1F5F2; "><?= __('Bets'); ?></th>
                                                        <th style="text-align:center; background-color: #E1F5F2; "><?= __('Wins'); ?></th>
                                                        <th style="text-align:center; background-color: #E1F5F2; "><?= __('Refunds'); ?></th>
                                                        <th style="text-align:center; background-color: #E1F5F2; "><?= __('Bonus'); ?> <?= __('GGR'); ?></th>

                                                    </tr>
                                                    <?php
                                                    foreach ($users as $user) :
                                                        if ($user['RealTransactions']['Bets'] || $user['BonusTransactions']['Bets']) :

                                                            $totalbonusbets += $user['BonusTransactions']['Bets'];
                                                            $totalbonuswins += $user['BonusTransactions']['Wins'];
                                                            $totalbonusrefunds += $user['BonusTransactions']['Refunds'];
                                                            $totalPLAYERGGR += round(($user['BonusTransactions']['Bets']) - $user['BonusTransactions']['Wins'], 2);
                                                            
                                                            
                                                               //total bonus ggr done
                                                                    if ((($user['BonusTransactions']['Bets']) - $user['BonusTransactions']['Wins']) > 0) {
                                                                        $bonus_status = "#CCFF90";
                                                                    } elseif ((($user['BonusTransactions']['Bets']) - $user['BonusTransactions']['Wins'])< 0) {
                                                                        $bonus_status = "#ffab91";
                                                                    } else {
                                                                        $bonus_status = "#FFEE58";
                                                                    }
                                                                   
                                                                    //currency ggr 
                                                                    if (($totalbonusbets - $totalbonuswins) > 0) {
                                                                        $total_status = "#CCFF90";
                                                                    } elseif (($totalbonusbets - $totalbonuswins) < 0) {
                                                                        $total_status = "#ffab91";
                                                                    } else {
                                                                        $total_status = "#FFEE58";
                                                                    }
                                                            ?>

                                                            <tr class = "collapseme<?= $currency; ?>">
                                                                <td colspan = "2"  style="text-align:center; ">
                                                                    <?= ucwords(str_replace("'", "", $user['first_name'])) . ' ' . ucwords(str_replace("'", "", $user['last_name'])); ?><br/>
                                                                    <small>(<?= $user['id'] . ': ' . $user['username']; ?>)</small>
                                                                </td>

                                                                <td style="text-align:right; "><?= number_format($user['balance'], 2, '.', ','); ?></td>
                                                                <td style="text-align:right; "><?= number_format($user['BonusTransactions']['Bets'], 2, '.', ','); ?></td> 
                                                                <td style="text-align:right; "><?= number_format($user['BonusTransactions']['Wins'], 2, '.', ','); ?></td> 
                                                                <td style="text-align:right; "><?= number_format($user['BonusTransactions']['Refunds'], 2, '.', ','); ?></td> 
                                                                <td style="text-align:right; background-color: <?= $bonus_status; ?>"><?= number_format(($user['BonusTransactions']['Bets']) - $user['BonusTransactions']['Wins'], 2, '.', ','); ?></td> 
                                                            </tr>
                                                            <?php
                                                        endif;
                                                    endforeach;
                                                    ?>
                                                    <tr>
                                                        <td colspan="3" style="background-color: #f9f9f9; text-align: right;"><b><i><?= __('TOTALS'); ?></i></b></td>
                                                        <td style="text-align:right; background-color: #f9f9f9; "><b><i><?= number_format($totalbonusbets, 2, '.', ','); ?></i></b></td>
                                                        <td style="text-align:right; background-color: #f9f9f9; "><b><i><?= number_format($totalbonuswins, 2, '.', ','); ?></i></b></td>
                                                        <td style="text-align:right; background-color: #f9f9f9; "><b><i><?= number_format($totalbonusrefunds, 2, '.', ','); ?></i></b></td>
                                                        <td style="text-align:right; background-color: #f9f9f9; "><b><i><?= number_format($totalPLAYERGGR, 2, '.', ','); ?></i></b></td>
                                                   
                                                    </tr>
                                                    <tr>
                                                        <td colspan="7" style="background-color: #fff; line-height: 1; padding: 0;">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4" style="background-color: #f9f9f9; text-align: right; "><b><i><?= $currency; ?> <?= __('TOTALS'); ?></i></b></td>
                                                        <td style="text-align:right; background-color: #f9f9f9; "><b><i><?= __('Bets'); ?>: <?= number_format($totalbonusbets, 2, '.', ','); ?></i></b></td>
                                                        <td style="text-align:right; background-color: #f9f9f9; "><b><i><?= __('Wins'); ?>: <?= number_format($totalbonuswins, 2, '.', ','); ?></i></b></td>
                                                        <td style="text-align:right; background-color: <?= $total_status; ?>; "><b><i><?= __('GGR'); ?>: <?= number_format(($totalbonusbets - $totalbonuswins), 2, '.', ','); ?></i></b></td>
                                                    </tr>

                                                    <?php
                                                    $totalPLAYERGGR = $totalbonusrefunds = $totalbonusbets = $totalbonuswins = 0;
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
                                                <input type='hidden' name='data[Report][header]' value='<?= $provider . ' ' . __('Bonus GGR Report'); ?>'/>
                                                <input type='hidden' name='data[Report][title]' value='<?= $provider . '_' . __('Bonus_GGR_Report'); ?>'/>
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
                        <div class="space10 visible-phone"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    $(".btncol").click(function () {
        $($(this).data("target")).toggle();
    })
</script>