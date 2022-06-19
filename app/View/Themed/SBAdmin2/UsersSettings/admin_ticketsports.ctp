<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => __('User Setting'), 1 => __('User Settings'))))); ?></div>
    </div>
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                    <div class="tab-content">
                                        <?= $this->element('flash_message'); ?>
                                        <h4><?= __('Risk management is crucial for sportsbook. Please be careful in setting all options.'); ?></h4></br>
                                        <p>*<?= __('For limit removal, leave fields blank'); ?></p>
                                        <?php
                                        $options = array(
                                            'url' => array('controller' => 'Userssettings'),
                                            'inputDefaults' => array('label' => false, 'div' => false),
                                            'action'=>'ticketsports/'.$userid
                                        ); ?>
                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <th><?= __('Sport ID'); ?></th>
                                                <th><?= __('Sport'); ?></th>
                                                <th><?= __('Lowest stake');?></th>
                                                <th><?= __('Highest stake'); ?></th>
                                                <th><?= __('Lowest Multi stake');?></th>
                                                <th><?= __('Highest Multi stake'); ?></th>
                                                <th></th>
                                            </tr>

                                            <?php foreach($sports as $row):
                                                if(!empty($settings['limits.sport.' . $row['Sport']['id']])) {
                                                    $vals = unserialize($settings['limits.sport.' . $row['Sport']['id']]);
                                                    
                                                    $row['Sport']['min_bet'] = $vals['min_bet'];                                                                        
                                                    $row['Sport']['max_bet'] = $vals['max_bet'];                                                                        
                                                    $row['Sport']['min_multi_bet'] = $vals['min_multi_bet'];
                                                    $row['Sport']['max_multi_bet'] = $vals['max_multi_bet'];
                                                } ?>
                                            
                                                <tr>
                                                    <td><?= $row['Sport']['id']; ?></td>
                                                    <td><?= $row['Sport']['name'];?></td>
                                                    <td><input name="data[Sport][<?= $row['Sport']['id']; ?>][min_bet]" type="text" value="<?php if($row['Sport']['min_bet'] != 0): ?><?= $row['Sport']['min_bet']; ?><?php endif; ?>" placeholder="<?= __('No limits'); ?>" /></td>
                                                    <td><input name="data[Sport][<?= $row['Sport']['id']; ?>][max_bet]" type="text" value="<?php if($row['Sport']['max_bet'] != 0): ?><?= $row['Sport']['max_bet']; ?><?php endif; ?>" placeholder="<?= __('No limits'); ?>" /></td>
                                                    <td><input name="data[Sport][<?= $row['Sport']['id']; ?>][min_multi_bet]" type="text" value="<?php if($row['Sport']['min_multi_bet'] != 0): ?><?= $row['Sport']['min_multi_bet']; ?><?php endif; ?>" placeholder="<?= __('No limits'); ?>" /></td>
                                                    <td><input name="data[Sport][<?= $row['Sport']['id']; ?>][max_multi_bet]" type="text" value="<?php if($row['Sport']['max_multi_bet'] != 0): ?><?= $row['Sport']['max_multi_bet']; ?><?php endif; ?>" placeholder="<?= __('No limits'); ?>" /></td>
                                                    <td><a class="adjust-limit btn btn-mini btn-primary" data-type="Sport" data-user='<?=$userid?>' data-key='<?= $row['Sport']['id'] ;?>'><i class="icon-save" style="margin-right:5px;"></i><?=__('Save');?></a></td>
                                                </tr>
                                            <?php endforeach;?>
                                        </table>
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