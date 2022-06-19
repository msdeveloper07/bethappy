<style>
    .bet-box {
        display: inline-block;
        width: 100%;
        box-sizing: border-box;
    }
    
    .bet-box:nth-of-type(2n) {
        margin-left: 0;
    }
</style>

<div class="container-fluid">
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <?= $this->Form->create('markets');  ?>
                <?php foreach ($sports as $sport) { ?>
                    <div class="widget tradedwidget bet-box">
                        <div class="widget-title" style="font-size:14px;padding:0px 5px">
                            (<?=$sport['Sport']['id'];?>) <b><?=__($sport['Sport']['name']);?></b>
                            <span class="tools" style="float: right"><a href="javascript:;" class="icon-chevron-up" data-id="<?=$key;?>"></a></span>
                        </div>
                        <div class="widget-body bodyevent_<?=$key;?>">
                            <div class="row-fluid">
                                <div class="span12">
                                    <!--BEGIN TABS-->
                                    <div class="table table-custom oddskeychange">
                                        <div class="tab-content">
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th><?=__('Market');?></th>
                                                    <th><?=__('Active');?></th>
                                                    <th><?=__('Margin');?></th>
                                                    <th><?=__('Save');?></th>
                                                </tr>
                                                <?php foreach ($sport['Markets'] as $market) { ?>
                                                    <tr data-type="Sport" data-pid="<?=$sport['Sport']['import_id'];?>" data-market="<?=$market['BetradarMarket']['type'];?>" class="disabled">
                                                        <td><?= '(' . $market['BetradarMarket']['type'] . ') ' . __($market['BetradarMarket']['name']);?></td>
                                                        <td><input class="checkactive" type="checkbox" <?= ($market['BetradarMarket']['active'] == 1)?'checked':"";?> /></td>
                                                        <td><input type="text" class="margin-value" value="<?= $market['BetradarMarket']['margin']; ?>" data-margin="<?= $market['BetradarMarket']['margin']; ?>" /></td>
                                                        <td style="text-align: right"><a class="adjust-oddkey btn btn-mini btn-primary"  data-margin='<?=$market['BetradarMarket']['margin'];?>'><i class="icon-white icon-upload" style="margin-right:5px;"></i><?=__('Save');?></a></td>
                                                    </tr>
                                                <?php } ?>
                                            </table>
                                            <br>
                                        </div>
                                    </div>
                                    <!--END TABS-->
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?= $this->Form->end();?>
            </div>
        </div>
    </div>
</div>