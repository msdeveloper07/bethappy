<table  class="table">
    <tr>
        <th><?php echo __("ID"); ?></th>
        <th><?php echo __("Username"); ?></th>
        <th><?php echo __("Email"); ?></th>
        <th><?php echo __("Country"); ?></th>
        <th><?php echo __("Balance"); ?></th>
    </tr>    		
    <?php foreach ($lastactive as $active) { ?>
        <tr>
            <td><?php echo $active['User']['id']; ?></td>
            <td><?php echo $this->Html->link($active['User']['username'], array('controller' =>'users', 'action' => 'view', $active['User']['id'])); ?></td>
            <td><?php echo $active['User']['email']; ?></td>
            <td><?php echo $active['User']['country']; ?></td>
            <td><?php echo $active['User']['balance']; ?></td>
        </tr>
    <?php } ?>
</table>