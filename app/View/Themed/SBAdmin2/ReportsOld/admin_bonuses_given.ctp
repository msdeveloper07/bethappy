<div class="container-fluid">

    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => __('Reports'), 1 => __('Given Bonuses Report'))))); ?></div>
    </div>

    <div class="row-fluid">
        <div class="span12"><h3 class="page-title"><?= __('Given Bonuses'); ?> <?= __('Report'); ?></h3></div>
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
                                        <div>
                                            <?= __('Report will show bonuses received by players. Please see report structure below:'); ?>
                                            <br><br>
                                            &bull; <?= __('Bonus Name'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the name of the bonus that the player received - from List Bonuses.)'); ?></span><br>
                                            &bull; <?= __('Player'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is player\'s full name. Player\'s id and username are in the brackets.)'); ?></span><br>
                                            &bull; <?= __('Bonus Created'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the date the bonus was created.)'); ?></span><br>
                                            &bull; <?= __('Bonus Amount'); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('(This is the initial amount player has, after he receives the bonus.)'); ?></span><br>
                                            <br>
                                            <?= __('You can generate a report by entering date range below.'); ?><br/>
                                            <?= __('Please choose your timeframe, otherwise the report will show data for the current month.'); ?><br/><br/>
                                            <?= __('Timeframe is not considering the time!'); ?><br><br>
                                            <div>
                                                <?= $this->element('reports_form'); ?>
                                            </div>
                                        </div>

                                        <?php if (!empty($data)): ?>
                                            <?php ob_start(); ?>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <td colspan="2" style="border-right: none;">
                                                            <strong><?= Configure::read('Settings.websiteTitle'); ?></strong>
                                                        </td>
                                                        <td  colspan="2" style="text-align:right; border-left: none;">
                                                            <?= __('Report Date:'); ?> <?= date('d-M-Y H:i:s'); ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="border-right: none;">
                                                            <strong><?= $provider; ?> <?= __('Bonuses Given Report'); ?></strong>
                                                        </td>
                                                        <td colspan="2" style="text-align:right; border-left: none;"">
                                                            <?= __('Timeframe:'); ?> <?= $from; ?> - <?= $to; ?>
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <?php foreach ($data as $currency => $users): ?>
                                                    <!--for format-->
                                                    <tr>
                                                        <td colspan="4" style="background-color: #fff;">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="4" style="background-color: #f9f9f9;"><?= $currency; ?></th>
                                                        <!--<th style="border-left: none; text-align: right; background-color: #f9f9f9;"><button type="button" class="btncol btn btn-info" data-toggle="collapse" data-target=".collapseme<?= $currency; ?>"><?= __('Click to Hide'); ?></button></th>-->
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align: center; background-color: #E1F5F2;"><?= __('Bonus'); ?> <?= __('Name'); ?></th>
                                                        <th style="text-align: center; background-color: #E1F5F2;"><?= __('Player'); ?></th>
                                                        <th style="text-align: center; background-color: #E1F5F2;"><?= __('Bonus'); ?> <?= __('Created'); ?></th>
                                                        <th style="text-align: center; background-color: #E1F5F2;"><?= __('Bonus'); ?> <?= __('Amount'); ?></th>

                                                    </tr>
                                                    <?php
                                                    foreach ($users as $rows):
                                                        $totalbonus += $rows['Bonus']['initial_amount'];
                                                        ?>
                                                        <tr class="collapseme<?= $currency; ?>">
                                                            <td style="text-align: center;"><?= $rows['BonusType']['name']; ?></td>
                                                            <td style="text-align: center;">
                                                                <?= ucwords(str_replace("'", "", $rows['User']['first_name'])) . ' ' . ucwords(str_replace("'", "", $rows['User']['last_name'])); ?><br/>
                                                                <small>(<?= $rows['User']['id'] . ': ' . $rows['User']['username']; ?>)</small>
                                                            </td>
                                                            <!--<td style="text-align: center;"><?= $rows['User']['username']; ?></td>-->
                                                            <td style="text-align: center;"><?= $rows['Bonus']['created']; ?></td>
                                                            <td style="text-align: right;"><?= number_format($rows['Bonus']['initial_amount'], 2, '.', ','); ?></td>

                                                        </tr>
                                                    <?php endforeach; ?>
                                                    <tr>
                                                        <td colspan="3" style="text-align: right; background-color: #f9f9f9; "><strong><?= __('TOTAL'); ?> <?= $currency; ?>:</strong></td>
                                                        <td style="text-align: right; background-color: #f9f9f9;"><?= number_format($totalbonus, 2, '.', ','); ?></td>
                                                    </tr>

                                                    <?php
                                                    $totalbonus = 0;

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
                                                <input type='hidden' name='data[Report][header]' value='<?= $provider . ' ' . __('Bonuses Given Report'); ?>'/>
                                                <input type='hidden' name='data[Report][title]' value='<?= $provider . '_' . __('Bonuses_Given_Report'); ?>'/>
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