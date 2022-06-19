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
                                            <table class="table table-bordered table-striped box">
                                                <tr>
                                                    <th colspan="5">
                                                        <?php 
                                                            if ($from) echo __('From') . ' ' . $this->Beth->convertDateTime($from);
                                                            if ($from && $to) echo ' - ';
                                                            if ($to) echo __('To') . ' ' . $this->Beth->convertDateTime($to);
                                                        ?>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th><?= __('ID'); ?></th>
                                                    <th><?= __('Action'); ?></th>
                                                    <th><?= __('Player ID'); ?></th>
                                                    <th><?= __('Player Username'); ?></th>
                                                    <th><?= __('Game ID'); ?></th>
                                                    <th><?= __('Round ID'); ?></th>
                                                    <th><?= __('Currency'); ?></th>
                                                    <th><?= __('Bet Amount'); ?></th>
                                                    <th><?= __('Bet Transaction ID'); ?></th>
                                                    <th><?= __('Win Amount'); ?></th>
                                                    <th><?= __('Win Transaction ID'); ?></th>
                                                    <th><?= __('Date'); ?></th>
                                                    
                                                </tr>
                                                <?php foreach ($data as $row) { ?>
                                                    <tr>
                                                        <td><?= $row['logs']['id']; ?></td>
                                                        <td><?= $row['logs']['action']; ?></td>
                                                        <td><?= $row['logs']['player_id']; ?></td>
                                                        <td><?= $row['logs']['player_name']; ?></td>
                                                        <td><?= $row['logs']['game_id']; ?></td>
                                                        <td><?= $row['logs']['round_id']; ?></td>
                                                        <td><?= $row['logs']['currency']; ?></td>
                                                        <td><?= $row['logs']['amount'] . ' ' . Configure::read('Settings.currency'); ?></td>
                                                        <td><?= $row['logs']['bet_transaction_id']; ?></td>
                                                        <td><?= $row['logs']['win'] . ' ' . Configure::read('Settings.currency'); ?></td>
                                                        <td><?= $row['logs']['win_transaction_id']; ?></td>
                                                        <td><?= $this->Beth->convertDateTime($row['logs']['datetime']); ?></td>
                                                    </tr>
                                                <?php } ?>
                                           </table>
                                            
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