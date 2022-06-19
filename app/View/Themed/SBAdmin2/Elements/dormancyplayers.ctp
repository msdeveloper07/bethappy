
<div class="span5">
    <div class="box">
        <?php if((!empty($dormancyUsers))) { ?>
            <div class="box-header well">
                <h2> 
                    <i class="icon-reorder"></i>
                    <?php echo __('900 Days (30 Months) Dormancy users') ; ?>
                </h2>
                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <div class="table table-custom">
                        <table class="table table-custom" cellpadding="0" cellspacing="0">
                            <tr>
                                <th><?php echo __('User ID'); ?></th>
                                <th><?php echo __('Username'); ?></th>
                                <th><?php echo __('First Name'); ?></th>
                                <th><?php echo __('Last Name'); ?></th>
                                <th><?php echo __('Email'); ?></th>
                            </tr>
                            <?php foreach ($dormancyUsers as $users): ?>
                                <tr>
                                    <td><?php echo $users['User']['id'];?></td>
                                    <td><?php echo $this->html->link($users['User']['username'], array('controller' => 'users', 'action' => 'view',$users['User']['id']));?></td>
                                    <td><?php echo $users['User']['first_name'];?></td>
                                    <td><?php echo $users['User']['last_name'];?></td>
                                    <td><?php echo $users['User']['email'];?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                </div>
            </div>
        <?php } else { ?>
            <div class="box-header well">
                <h2> 
                    <i class="icon-user"></i>
                    <?php echo __('900 Days (30 Months) Dormancy users') ; ?>
                </h2>
                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-down"></i></a>
                </div>
            </div>
            <div class="box-content" style="display:none">
                <div class="table table-custom">
                    <div class="tab-content" style="text-align:center; font-weight:bold;">
                        <?php echo __('No users found'); ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

	 