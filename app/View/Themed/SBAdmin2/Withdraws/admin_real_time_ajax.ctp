<table  class="table">
    <tr>
        <th><?= __("ID"); ?></th>
        <th><?= __("Transaction target"); ?></th>
        <th><?= __("User"); ?></th>
        <th><?= __("Amount"); ?></th>
        <th><?= __("Date"); ?></th>
    </tr>    		
    <?php foreach ($lastwithdraws as $lastwithdraw) { ?>
        <tr>
            <td><?= $lastwithdraw['Withdraw']['id']; ?></td>
            <td><?= $lastwithdraw['Withdraw']['transaction_target']; ?></td>
            <td><?= $this->Html->link($lastwithdraw['User']['username'], array('controller' =>'users', 'action' => 'view', $lastwithdraw['Withdraw']['user_id'])); ?></td>
            <td><?= $lastwithdraw['Withdraw']['amount']; ?><?= Configure::read('Settings.currency'); ?></td>
            <td><?= $this->Beth->convertDate($lastwithdraw['Withdraw']['date']); ?></td>
        </tr>
    <?php } ?>
</table>