<div class="span5">
    <div class="box">
        <?php if((!empty($never_logUsers_active))) { ?>
            <div class="box-header well">
                <h2> 
                    <i class="icon-off"></i>
                    <?php echo __('Never Logged In Users (after email confirmation, status=1)'); ?>
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
                        <?php foreach ($never_logUsers_active as $users): ?>
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
                    <i class="icon-off"></i>
                    <?php echo __('Never Logged In Users (after email confirmation, status=1)'); ?>
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

<div class="span5">
    <div class="box">
        <?php if((!empty($never_logUsers_unconf))) { ?>
            <div class="box-header well">
                <h2> 
                    <i class="icon-off"></i>
                    <?php echo __('Never Logged In Users (never confirmed email, status=0)'); ?>
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
                            <?php foreach ($never_logUsers_unconf as $users): ?>
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
                    <i class="icon-off"></i>
                    <?php echo __('Never Logged In Users (never confirmed email, status=0)'); ?>
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
