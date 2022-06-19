<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __('User Setting'), 2 => __('User Settings'))))); ?></div>
    </div>
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                         <div class="row-fluid">
                            <div class="span12">
                                <?= $this->Form->create('Search');  ?>
                                <select id="SearchSportId" name="data[Search][sport_id]">
                                    <?php foreach($sports as $key => $sport): ?>
                                        <option value="<?=$key?>"><?=$sport?></option>
                                    <?php endforeach;?>
                                </select>
                                <?= $this->Form->submit(__('Select Sport', true), array('class' => 'btn'));?>                                
                                <?= $this->Form->end();?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if(!empty($sportname)): ?>
                    <em><?=__('Results for') . ': ' . __($sportname);?></em>
                <?php endif; ?>

                <?php foreach ($data as $key => $country): ?>
                    <div class="widget tradedwidget">
                        <div class="widget-title" style="font-size:14px;padding:1px 4px;border:1px solid #ccc">
                            <b><?=__($country['country_name']);?></b>
                            <span class="tools" style="float: right"><a href="javascript:;" class="icon-chevron-up" data-id="<?=$key;?>"></a></span>
                        </div>
                        <div class="widget-body bodyevent_<?=$key;?>" style="display: none">
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="table table-custom">
                                        <div class="tab-content">
                                            <?php if (!empty($country['Leagues'])): ?>
                                            <?php $options = array(
                                                'url' => array('controller' => 'risks'),
                                                'inputDefaults' => array('label' => false, 'div' => true)
                                            ); ?>
                                                <table class="table table-bordered table-striped">
                                                    <tr>
                                                        <th><?= __('League ID'); ?></th> 
                                                        <th><?= __('League'); ?></th>
                                                        <th><?= __('Lowest stake');?></th>
                                                        <th><?= __('Highest stake'); ?></th>
                                                        <th><?= __('Lowest Multi stake');?></th>
                                                        <th><?= __('Highest Multi stake'); ?></th>
                                                        <th></th>
                                                    </tr>
                                                    <tbody>
                                                        <?php foreach ($country['Leagues'] as $row): 
                                                            if(!empty($settings['limits.league.' . $row['League']['id']])) {
                                                                $vals = unserialize($settings['limits.league.' . $row['League']['id']]);
                                                                $row['League']['min_bet'] = $vals['min_bet'];
                                                                $row['League']['max_bet'] = $vals['max_bet'];
                                                                $row['League']['min_multi_bet'] = $vals['min_multi_bet'];
                                                                $row['League']['max_multi_bet'] = $vals['max_multi_bet'];
                                                            } ?>
                                                            <tr>
                                                                <td><?= $row['League']['id']; ?></td>
                                                                <td><?= $row['League']['name']; ?></td>
                                                                <td><input name="data[League][<?= $row['League']['id']; ?>][min_bet]" type="text" value="<?php if($row['League']['min_bet'] != 0): ?><?= $row['League']['min_bet']; ?><?php endif; ?>" placeholder="<?= __('No limits'); ?>" /></td>
                                                                <td><input name="data[League][<?= $row['League']['id']; ?>][max_bet]" type="text" value="<?php if($row['League']['max_bet'] != 0): ?><?= $row['League']['max_bet']; ?><?php endif; ?>" placeholder="<?= __('No limits'); ?>" /></td>
                                                                <td><input name="data[League][<?= $row['League']['id']; ?>][min_multi_bet]" type="text" value="<?php if($row['League']['min_multi_bet'] != 0): ?><?= $row['League']['min_multi_bet']; ?><?php endif; ?>" placeholder="<?= __('No limits'); ?>" /></td>
                                                                <td><input name="data[League][<?= $row['League']['id']; ?>][max_multi_bet]" type="text" value="<?php if($row['League']['max_multi_bet'] != 0): ?><?= $row['League']['max_multi_bet']; ?><?php endif; ?>" placeholder="<?= __('No limits'); ?>" /></td>
                                                                <td><a class="adjust-limit btn btn-mini btn-primary" data-type="League" data-user='<?=$userid?>' data-key='<?= $row['League']['id'] ;?>'><i class="icon-save" style="margin-right:5px;"></i><?=__('Save');?></a></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <!--END TABS-->
                                </div>
                                <div class="space10 visible-phone"></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>