<div class="container-fluid">
    <div class="row-fluid"><div class="span12"><h3 class="page-title"></h3></div></div>
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
                                        <?=__("Report will show only user's accounting information during the selected period:");?>
                                        <br><br>
                                        &bull; <?=__('User ID');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(This is user ID with consists of numbers.)');?></span><br>
                                        &bull; <?=__('Username');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(Username which was entered then registration was made.)');?></span><br>
                                        &bull; <?=__('Balance');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(Current balance of user)');?></span><br>
                                        &bull; <?=__('Tickets Count');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(Tickets played by user.)');?></span><br>
                                        &bull; <?=__('Total');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(Total amount wagered by the user.)');?></span><br>
					&bull; <?=__('Won');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(Total amount of Won tickets.)');?></span><br>
					&bull; <?=__('Lost');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(Total amount of Lost tickets.)');?></span><br>
					&bull; <?=__('Pending');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(Total amount of Pending tickets.)');?></span><br>
					&bull; <?=__('Profit');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__("(User's Profit, calculated according the following formula => Profit = Total - Pending - Won)");?></span><br>
					&bull; <?=__('Liability');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('(Liability.)');?></span><br>
                                        <br>
                                        <?php if (!empty($data)): ?>
                                            <?php foreach ($data as $key=>$currencies){ ?>
                                            <h1><?=$key;?></h1>
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th><?= __('User ID'); ?></th>
                                                    <th><?= __('User name'); ?></th>
                                                    <th><?= __('Balance'); ?></th>
                                                    <th><?= __('Deposits'); ?></th>
                                                    <th><?= __('Bets'); ?></th>
                                                    <th><?= __('Wins'); ?></th>
                                                    <th><?= __('GGR'); ?></th>
                                                    
                                                </tr>
                                                <?php foreach ($currencies as $users){
                                                    $totalbet+=(-$users['Transactions']['Bets']);
                                                    $totalwin+= $users['Transactions']['Wins'];
                                                    $totalbalances+=$users['balance'];
                                                    $totaldeposits+=$users['Transactions']['Deposits'];
                                                    
                                                    if ($users['Transactions']['Bets'] !=0 && $users['Transactions']['Wins'] !=0 && $users['Transactions']['Deposits'] !=0){
                                                    ?>
                                                    <tr>
                                                        <td><?= $users['id']; ?></td>
                                                        <td><?= $users['username']; ?></td>
                                                        <td><?= $users['balance']; ?></td>  
                                                        <td><?= $users['Transactions']['Deposits']; ?></td> 
                                                      	<td><?= round((-$users['Transactions']['Bets']),2); ?></td> 
                                                        <td><?= round($users['Transactions']['Wins'],2); ?></td> 
                                                        <td><?= (-$users['Transactions']['Bets']) - $users['Transactions']['Wins']; ?></td> 
                                                    </tr>
                                                    <?php } } ?>
                                                <tr>
                                                    <td colspan="2"><b><i><?= __('Totals'); ?></i></b></td>
                                                    <td><b><i><?= $totalbalances; ?></i></b></td>
                                                    <td><b><i><?= $totaldeposits; ?></i></b></td>
                                                    <td><b><i><?= $totalbet; ?></i></b></td>
                                                    <td><b><i><?= $totalwin; ?></i></b></td>
                                                    <td><b><i><?= $totalbet - $totalwin; ?></i></b></td>
                                                </tr>
                                            </table>
                                            <?php 
                                                $totalbalances = $totalbet = $totalwin = $totaldeposits = 0;
                                                } ?>
                                            <?= $this->Form->create('Download'); ?>
                                            <?= $this->Form->input('download', array('value' => '1', 'type' => 'hidden')); ?>
                                            <?= $this->Form->input('from', array('type' => 'hidden')); ?>
                                            <?= $this->Form->input('to', array('type' => 'hidden')); ?>
                                            <?= $this->Form->submit(__('Download (Excel file)', true), array('class' => 'btn btn-danger', 'div' => false, 'style' => 'margin-top: 15px;')); ?>
                                            <?= $this->Form->end(); ?>
                                        <?php elseif (isset($data)): ?>
                                            <?= __('No data in this period'); ?>
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