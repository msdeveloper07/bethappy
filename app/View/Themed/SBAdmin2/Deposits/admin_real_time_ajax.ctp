<table  class="table">
    <tr>
        <th><?= __("ID"); ?></th>
        <th><?= __("Deposit ID"); ?></th>
        <th><?= __("User"); ?></th>
        <th><?= __("Amount"); ?></th>
        <th><?= __("Type"); ?></th>
        <th><?= __("Details"); ?></th>
        <th><?= __("Date"); ?></th>
    </tr>    		
    <?php foreach ($lastdeposits as $lastdeposit) { ?>
        <tr>
            <td><?= $lastdeposit['Deposit']['id']; ?></td>
            <td><?= $lastdeposit['Deposit']['deposit_id']; ?></td>
            <td><?= $this->Html->link($lastdeposit['User']['username'], array('controller' =>'users', 'action' => 'view', $lastdeposit['Deposit']['user_id'])); ?></td>
            <td><?= $lastdeposit['Deposit']['amount']; ?><?= Configure::read('Settings.currency'); ?></td>
            <td><?= $lastdeposit['Deposit']['type']; ?></td>
            <td><?= $lastdeposit['Deposit']['details']; ?></td>
            <td><?= $this->Beth->convertDate($lastdeposit['Deposit']['date']); ?></td>
        </tr>
    <?php } ?>
</table>