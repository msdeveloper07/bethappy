<style type="text/css">
    h1 { 
        padding: 10px 0 10px 0;
    }
</style>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __($pluralName), 2 => __('List %s', __($pluralName)))))); ?></div>
    </div>
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <?= $this->element('search');?>
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                    <?= $this->element('tabs');?>
                                    <div class="tab-content">
                                        <?php if (!empty($bigOddTickets)): ?>
                                            <h1><?= __('Warning Withdraws'); ?></h1>
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th><?= __('Id'); ?></th>
                                                    <th><?= __('Date'); ?></th>
                                                    <th><?= __('User'); ?></th>
                                                    <th><?= __('Stake'); ?></th>
                                                    <th><?= __('odd'); ?></th>
                                                    <th><?= __('Winning'); ?></th>
                                                </tr>
                                                <?php foreach ($bigOddTickets as $ticket): ?>
                                                    <tr>
                                                        <td><?= $ticket['Ticket']['id']; ?></td>
                                                        <td><?= $this->Beth->convertDate($ticket['Ticket']['date']); ?></td>
                                                        <td>
                                                            <?php if($ticket['User']['username'] == null):?>
                                                                <?= __('User Terminated'); ?>
                                                            <?php else: ?>
                                                                <?= $ticket['User']['username']; ?>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?= $ticket['Ticket']['amount'] . ' ' .Configure::read('Settings.currency'); ?></td>
                                                        <td><?= $ticket['Ticket']['odd']; ?></td>
                                                        <td><?= $ticket['Ticket']['return'] . ' ' . Configure::read('Settings.currency'); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                        <?php else: ?>
                                        <?php endif; ?>

                                        <?php if (!empty($bigStakeTickets)): ?>
                                            <h1><?= __('Warning Stakes'); ?></h1>
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th><?= __('Id'); ?></th>
                                                    <th><?= __('Date'); ?></th>
                                                    <th><?= __('User'); ?></th>
                                                    <th><?= __('Stake'); ?></th>
                                                    <th><?= __('odd'); ?></th>
                                                    <th><?= __('Winning'); ?></th>
                                                </tr>
                                                <?php foreach ($bigStakeTickets as $ticket): ?>
                                                    <tr>
                                                        <td><?= $ticket['Ticket']['id']; ?></td>
                                                        <td><?= $this->Beth->convertDate($ticket['Ticket']['date']); ?></td>
                                                        <td>
                                                            <?php if($ticket['User']['username'] == null):?>
                                                                <?= __('User Terminated'); ?>
                                                            <?php else: ?>
                                                                <?= $ticket['User']['username']; ?>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?= $ticket['Ticket']['amount']  . ' ' . Configure::read('Settings.currency');; ?></td>
                                                        <td><?= $ticket['Ticket']['odd']; ?></td>
                                                        <td><?= $ticket['Ticket']['return']  . ' ' . Configure::read('Settings.currency');; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                        <?php else: ?>
                                        <?php endif; ?>

                                        <?php if (!empty($bigWinningTickets)): ?>
                                            <h1><?= __('Warning Winnings'); ?></h1>
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th><?= __('Id'); ?></th>
                                                    <th><?= __('Date'); ?></th>
                                                    <th><?= __('User'); ?></th>
                                                    <th><?= __('Stake'); ?></th>
                                                    <th><?= __('odd'); ?></th>
                                                    <th><?= __('Winning'); ?></th>
                                                </tr>
                                                <?php foreach ($bigWinningTickets as $ticket): ?>
                                                    <tr>
                                                        <td><?= $ticket['Ticket']['id']; ?></td>
                                                        <td><?= $this->Beth->convertDate($ticket['Ticket']['date']); ?></td>
                                                        <td>
                                                            <?php if($ticket['User']['username'] == null):?>
                                                                <?= __('User Terminated'); ?>
                                                            <?php else: ?>
                                                                <?= $ticket['User']['username']; ?>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?= $ticket['Ticket']['amount']  . ' ' . Configure::read('Settings.currency');; ?></td>
                                                        <td><?= $ticket['Ticket']['odd']; ?></td>
                                                        <td><?= $ticket['Ticket']['return']  . ' ' . Configure::read('Settings.currency');; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                        <?php else: ?>
                                        <?php endif; ?>

                                        <?php if (!empty($bigDeposits)): ?>
                                            <h1><?= __('Warning Deposits'); ?></h1>
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th><?= __('Id'); ?></th>
                                                    <th><?= __('Date'); ?></th>
                                                    <th><?= __('User'); ?></th>
                                                    <th><?= __('Amount'); ?></th>
                                                </tr>
                                                <?php foreach ($bigDeposits as $deposit): ?>
                                                    <tr>
                                                        <td><?= $deposit['Deposit']['id']; ?></td>
                                                        <td><?= $this->Beth->convertDate($deposit['Deposit']['date']); ?></td>
                                                        <td>
                                                            <?php if($deposit['User']['username'] == null):?>
                                                                <?= __('User Terminated'); ?>
                                                            <?php else: ?>
                                                                <?= $deposit['User']['username']; ?>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?= $deposit['Deposit']['amount']  . ' ' . Configure::read('Settings.currency');; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                        <?php else: ?>
                                        <?php endif; ?>

                                        <?php if (!empty($bigWithdraws)): ?>
                                            <h1><?= __('Warning Withdraws'); ?></h1>
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th><?= __('Id'); ?></th>
                                                    <th><?= __('Date'); ?></th>
                                                    <th><?= __('User'); ?></th>
                                                    <th><?= __('amount'); ?></th>
                                                </tr>
                                                <?php foreach ($bigWithdraws as $deposit): ?>
                                                    <tr>
                                                        <td><?= $deposit['Withdraw']['id']; ?></td>
                                                        <td><?= $this->Beth->convertDate($deposit['Withdraw']['date']); ?></td>
                                                        <td><?= $deposit['User']['username']; ?></td>
                                                        <td><?= $deposit['Withdraw']['amount']  . ' ' . Configure::read('Settings.currency');; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                        <?php else: ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <!--END TABS-->
                            </div>
                            <div class="space10 visible-phone"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>