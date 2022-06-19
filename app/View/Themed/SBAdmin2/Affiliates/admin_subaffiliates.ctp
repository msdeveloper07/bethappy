<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => __('List %s', __($pluralName)), 1 => __('%s Sub-Affiliates', __($singularName)))))); ?></div>
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
                                                    <td><?= $this->html->link($affiliate['User']['username'], array('controller' => 'users', 'action' => 'view', $affiliate['Affiliate']['user_id'])); ?></td>
                                                    <td><?= $affiliate['User']['email']; ?></td>
                                                    <td><?= $affiliate['Affiliate']['percentage'] . ' %'; ?></td>
                                                </tr>
                                            </tbody>        
                                            </table>
                                        <br>
                                        <br>
                                        <h3><?= __('Affiliate Sub-Affiliates'); ?></h3><br>
                                                                       
                                        <?php if (!empty($users['affiliates'])): ?>
                                         <table class="table table-custom" cellpadding="0" cellspacing="0">
                                            <thead> 
                                                <tr>
                                                    <th><?= __('Affiliate Id'); ?></th>
                                                    <th><?= __('Affiliate Custom Id'); ?></th>
                                                    <th><?= __('Parent Id'); ?></th>
                                                    <th><?= __('Username'); ?></th>
                                                    <th><?= __('Country'); ?></th>
                                                    <th><?= __('Percentage'); ?></th>
                                                    <th><?= __('Registration Date'); ?></th>
                                                </tr>
                                            </thead> 
                                            <tbody>
                                                <?php foreach($users['affiliates'] as $user): ?>
                                                    <tr>
                                                        <td><?= $user['Affiliate']['id']; ?></td>
                                                        <td><?= $user['Affiliate']['affiliate_custom_id']; ?></td>
                                                        <td><?= $user['Affiliate']['parent_id']; ?></td>
                                                        <td><?= $this->html->link($user['User']['username'], array('controller' => 'users', 'action' => 'view', $user['User']['id'])); ?></td>
                                                        <td><?= $user['User']['country']; ?></td>
                                                        <td><?= $user['Affiliate']['percentage'] . " %"; ?></td>
                                                        <td><?= $user['User']['registration_date']; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>                                            
                                        </table>
                                    <?php else: ?>
                                        <?= __('No sub-affiliates are registered under this affiliate.');?>
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