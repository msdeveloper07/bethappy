<table  class="table">
    <tr>
        <th><?php echo __("ID"); ?></th>
        <th><?php echo __("User"); ?></th>
        <th><?php echo __("Data URL"); ?></th>
        <th><?php echo __("Date"); ?></th>
    </tr>    		
    <?php foreach ($lastkyc as $kyc) { ?>
        <tr>
            <td><?php echo $kyc['KYC']['id']; ?></td>
            <td><?php echo $this->Html->link($kyc['User']['username'], array('controller' =>'users', 'action' => 'view', $kyc['KYC']['user_id'])); ?></td>
            <td><?php echo $kyc['KYC']['kyc_data_url']; ?></td>
            <td><?php echo $kyc['KYC']['date']; ?></td>
        </tr>
    <?php } ?>
</table>
