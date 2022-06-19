<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => __('List %s', __($pluralName)), 1 => __('%s Users', __($singularName)))))); ?></div>
    </div>
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <?php if(!empty($chartsData)) echo $this->element('charts/pie', array('chartsData' => $chartsData)); ?>
                        
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="table table-custom">     
                                        <?= $this->element('flash_message'); ?>
                                        <table class="table table-custom" cellpadding="0" cellspacing="0">
                                            <thead> 
                                                <tr>
                                                    <th><?= __('Affiliate id'); ?></th>
                                                    <th><?= __('Custom id'); ?></th>
                                                    <th><?= __('Username'); ?></th>
                                                    <th><?= __('E-mail'); ?></th>
                                                    <th><?= __('Percentage'); ?></th>
                                                </tr>
                                            </thead> 
                                            <tbody>
                                                <tr>
                                                    <td><?= $affiliate['Affiliate']['id']; ?></td>
                                                    <td><?= $affiliate['Affiliate']['affiliate_custom_id']; ?></td>
                                                    <td><?= $this->Html->link($affiliate['User']['username'], array('controller' => 'users', 'action' => 'view', $affiliate['Affiliate']['user_id'])); ?></td>
                                                    <td><a href="mailto:<?= $affiliate['User']['email']; ?>?Subject=iSoftGaming Affiliate" target="_top"><?= $affiliate['User']['email']; ?></a></td>
                                                    <td><?= $affiliate['Affiliate']['percentage'] . ' %'; ?></td>
                                                </tr>
                                            </tbody>
                                            </table>
                                        <br><br>
                                        
                                        <h3><?= __('Affiliate Users'); ?></h3><br>
                                                                       
                                        <?php if (!empty($users)): ?>
                                         <table class="table table-custom" cellpadding="0" cellspacing="0">
                                            <thead> 
                                                <tr>
                                                    <th><?= __('User ID'); ?></th>
                                                    <th><?= __('Username'); ?></th>
                                                    <th><?= __('E-mail'); ?></th>
                                                    <th><?= __('Country'); ?></th>
                                                    <th><?= __('Status'); ?></th>
                                                    <th><?= __('Balance'); ?></th>
                                                    <th><?= __('Registration Date'); ?></th>
                                                </tr>
                                            </thead> 
                                            <tbody>
                                                <?php foreach($users as $user): ?>
                                                    <tr>
                                                        <td><?= $user['User']['id']; ?></td>
                                                        <td><?= $this->Html->link($user['User']['username'], array('controller' => 'users', 'action' => 'view', $user['User']['id'])); ?></td>
                                                        <td><a href="mailto:<?= $user['User']['email']; ?>?Subject=iSoftGaming Affiliate" target="_top"><?= $user['User']['email']; ?></a></td>
                                                        <td><?= $user['User']['country']; ?></td>
                                                        <td><?= $this->Beth->getUserStatus($user['User']['status']); ?></td>
                                                        <td><?= $user['User']['balance']; ?></td>
                                                        <td><?= $this->Beth->convertDate($user['User']['registration_date']); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>                                            
                                        </table>
                                    <?php else: ?>
                                        <?= __('No users are registered under this affiliate.');?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="space10 visible-phone"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>