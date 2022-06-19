<div class="container-fluid">
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <?php if (!empty($markets)): ?>
                                    <div class="table table-custom oddskeychange">
                                        <?php echo $this->element('tabs');?>
                                        <div class="tab-content">
                                            <h4><?=__($model[$type]['name']);?></h4>
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th><?=__('Market');?></th>
                                                    <th style="text-align:center"><?=__('Active');?></th>
                                                    <th><?=__('Margin');?></th>
                                                    <th><?=__('Save');?></th>
                                                </tr>
                                                <?php foreach ($markets as $market) { ?>
                                                <tr data-type="<?=$type;?>" data-pid="<?=$model[$type]['import_id'];?>" data-market="<?=$market['BetradarMarket']['type'];?>" class="disabled">
                                                        <td><?= '(' . $market['BetradarMarket']['type'] . ') ' . __($market['BetradarMarket']['name']);?></td>
                                                        
                                                        <?php if (isset($market['active']) && $market['active'] == 0){
                                                            echo '<td style="background:#db0036;text-align:center">';
                                                        } else {
                                                            echo '<td style="text-align:center">';
                                                        } ?>
                                                        <input class="checkactive" type="checkbox" <?= ($market['BetradarMarket']['active'] == 1)?'checked':"";?> />
                                                        <?='</td>';?>
                                                        
                                                        <td><input placeholder="<?=(!empty($market['placeholder'])?$market['placeholder']:"");?>" type="text" class="margin-value" value="<?= $market['BetradarMarket']['margin']; ?>" data-margin="<?= $market['BetradarMarket']['margin']; ?>" /></td>
                                                        <td style="text-align: right"><a class="adjust-oddkey btn btn-mini btn-primary" data-margin='<?=$market['BetradarMarket']['margin'];?>'><i class="icon-white icon-upload" style="margin-right:5px;"></i><?=__('Save');?></a></td>
                                                    </tr>
                                                <?php } ?>
                                            </table>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <?=__('No bets found.');?>
                                <?php endif; ?>
                                <!--END TABS-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>