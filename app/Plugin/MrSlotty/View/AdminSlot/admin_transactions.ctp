<div class="container-fluid">
    <div class="row-fluid"><div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __('Create %s', __($singularName)))))); ?></div></div>
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="table table-custom">
                                    <?= $this->element('tabs');?>
                                    <div class="tab-content">
                                        <div class="pull-left"><?= $this->element('slots_form');?></div>
                                        <div class="pull-right">
                                            <?=__('Report will show Slots Analytics History for all players and games. Please set your own filters on the left.');?>
                                            
                                            <?php if ($game): ?>
                                                <div class="tab-content">
                                                    <h4 style="text-align:center"><?=__('Game');?></h4>
                                                    <table class="table table-bordered table-striped">
                                                        <tr>
                                                            <th><?= __('ID'); ?></th>
                                                            <th><?= __('Name'); ?></th>
                                                            <th><?= __('Game ID'); ?></th>
                                                            <th><?= __('Alias'); ?></th>
                                                            <th><?= __('Brand'); ?></th>
                                                            <th><?= __('Active'); ?></th>
                                                        </tr>
                                                        <tr>
                                                            <td><?= $game['SlotGames']['id']; ?></td>
                                                            <td><?= $game['SlotGames']['name']; ?></td>
                                                            <td><?= $game['SlotGames']['gameid']; ?></td>
                                                            <td><?= $game['SlotGames']['alias']; ?></td>
                                                            <td><?= $game['SlotGames']['brand']; ?></td>
                                                            <td><?= $this->Beth->humanizeActive($game['SlotGames']['active']); ?></td>
                                                        </tr>
                                                   </table>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <?php if ($data): ?>
                                        <div class="tab-content">
                                            <?php foreach ($data as $usr => $row) { ?>
                                                <table class="table table-bordered table-striped box">
                                                    <tr><th colspan="5"><?=$usr;?></th></tr>
                                                    <tr>
                                                        <th><?= __('ID'); ?></th>
                                                        <th><?= __('User ID'); ?></th>
                                                        <th><?= __('Username'); ?></th>
                                                        <th><?= __('Transaction Type'); ?></th>
                                                        <th><?= __('Model'); ?></th>
                                                        <th><?= __('Parent ID'); ?></th>
                                                        <th><?= __('Amount'); ?></th>
                                                        <th><?= __('Balance'); ?></th>
                                                        <th><?= __('Date'); ?></th>
                                                    </tr>
                                                    <?php foreach ($row as $log) { ?>
                                                        <tr>
                                                            <td class="tbheader" id="<?=$log['logs']['id'];?>">
                                                                <i class="icon-fullscreen"></i>
                                                                <?= $log['logs']['id']; ?>
                                                            </td>
                                                            <td><?= $log['logs']['user_id']; ?></td>
                                                            <td><?= $log['logs']['username']; ?></td>
                                                            <td><?= $log['logs']['transaction_type']; ?></td>
                                                            <td><?= $log['logs']['Model']; ?></td>
                                                            <td><?= $log['logs']['Parent_id']; ?></td>
                                                            <td><?= $log['logs']['amount'] . ' ' . Configure::read('Settings.currency'); ?></td>
                                                            <td><?= $log['logs']['balance'] . ' ' . Configure::read('Settings.currency'); ?></td>
                                                            <td><?= $this->Beth->convertDateTime($log['logs']['date']); ?></td>
                                                        </tr>
                                                        
                                                        <tr class="box-content tb-<?=$log['logs']['id'];?>" style="display:none">
                                                            <td colspan="9">
                                                                <table class="table table-bordered table-striped box">
                                                                    <tr>
                                                                        <th><?= __('ID'); ?></th>
                                                                        <th><?= __('Action'); ?></th>
                                                                        <th><?= __('Game ID'); ?></th>
                                                                        <th><?= __('Round ID'); ?></th>
                                                                        <th><?= __('Currency'); ?></th>
                                                                        <th><?= __('Bet Amount'); ?></th>
                                                                        <th><?= __('Bet Transaction ID'); ?></th>
                                                                        <th><?= __('Win Amount'); ?></th>
                                                                        <th><?= __('Win Transaction ID'); ?></th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?= $log['slotlogs']['id']; ?></td>
                                                                        <td><?= $log['slotlogs']['action']; ?></td>
                                                                        <td><?= $log['slotlogs']['game_id']; ?></td>
                                                                        <td><?= $log['slotlogs']['round_id']; ?></td>
                                                                        <td><?= $log['slotlogs']['currency']; ?></td>
                                                                        <td><?= $log['slotlogs']['amount'];?></td>
                                                                        <td><?= $log['slotlogs']['bet_transaction_id'];?></td>
                                                                        <td><?= $log['slotlogs']['win'];?></td>
                                                                        <td><?= $log['slotlogs']['win_transaction_id'];?></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                               </table>
                                            <?php } ?>
                                            
                                        </div>
                                    <?php else: ?>
                                        <?=__('No data found.');?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document.body).on('click', '.tbheader', function(ev) { 
        ev.stopPropagation();
        ev.preventDefault();
        
        var id = $(this).attr('id');
        var $box = $(this).parents('.box');

        if($box.find('.box-content.tb-'+id).is(':visible')) {
            $box.find('.box-content.tb-'+id).hide();
            $(this).find('.box-icon .btn-minimize i').removeClass('icon-chevron-up');
            $(this).find('.box-icon .btn-minimize i').addClass('icon-chevron-down');
        } else {
            $box.find('.box-content.tb-'+id).show();
            $(this).find('.box-icon .btn-minimize i').removeClass('icon-chevron-down');
            $(this).find('.box-icon .btn-minimize i').addClass('icon-chevron-up');
        }
    });
</script>