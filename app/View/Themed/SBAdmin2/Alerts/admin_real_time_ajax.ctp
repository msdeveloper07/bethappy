<table  class="table">
    <tr>
        <th><?= __("Alert ID"); ?></th>
        <th><?= __("User"); ?></th>
        <th><?= __("Alert Title"); ?></th>
        <th><?= __("Alert Description"); ?></th>
        <th><?= __("Date"); ?></th>
    </tr>    		
    <?php foreach ($lastalerts as $alerts) { ?>
        <tr>
            <td><?= $alerts['Alert']['id']; ?></td>
            <td><?= $this->Html->link($alerts['User']['username'], array('controller' =>'users', 'action' => 'view', $alerts['Alert']['user_id'])); ?></td>
            <td><?= $alerts['Alert']['alert_source']; ?></td>
            <td><?= $alerts['Alert']['alert_text']; ?></td>
            <td><?= $this->Beth->convertDate($alerts['Alert']['date']); ?></td>
        </tr>
    <?php } ?>
</table>