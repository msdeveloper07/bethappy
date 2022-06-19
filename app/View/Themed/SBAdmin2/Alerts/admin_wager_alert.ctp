<div class="container-fluid">
    <div class="row-fluid"><div class="span12"><h3 class="page-title"></h3></div></div>
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="table table-custom">
                                    <div class="tab-content">	                                        
                                        <div class="span6">
                                            <h5><?=__('You can generate an alert report by entering date range below:');?></h5>
                                            <div style="float:left"><?= $this->element('reports_form');?></div>
                                            <br />
                                        </div>
                                        <div class="span6">
                                            <h4><?=__('Alerts:');?></h4>
                                            &bull; <?=__('Suspicious Wager');?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__("(LIVE Wager: Amount placed higher than 500 %s)", Configure::read('Settings.currency'));?></span><br>
					</div>                                        
                                        <?php if (!empty($data)): ?>
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th><?= __('Alert ID'); ?></th>
                                                    <th><?= __('User id'); ?></th>
                                                    <th><?= __('Alert Title'); ?></th>
                                                    <th><?= __('Alert'); ?></th>
                                                    <th><?= __('Date'); ?></th>
                                                </tr>
                                                <?php foreach ($data as $datas){
                                                    if ($datas['Alert']['alert_source'] == 'Wager higher than normal') { ?>
                                                        <tr>
                                                            <td><?= $datas['Alert']['id']; ?></td>
                                                            <td><?= $datas['User']['username']; ?></td>
                                                            <td><?= $datas['Alert']['alert_source']; ?></td>
                                                            <td><?= $datas['Alert']['alert_text']; ?></td>
                                                            <td><?= $this->Beth->convertDate($datas['Alert']['date']);?></td>
                                                        </tr>
                                                    <?php }
                                                } ?>
                                            </table>
                                        <?php endif; ?>
                                    </div>
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