<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><h3 class="page-title"></h3></div>
    </div>
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <!-- BEGIN INLINE TABS PORTLET-->
                <div class="widget">
                    <div class="widget-body">
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                    <div class="tab-content">
									
                                        <h2><?=__('Live Casino Cumulative Report');?></h2><br>
                                        
                                        <?=__('Please select a date range:');?><br><br>
                                        <div style="float:left"><?= $this->element('reports_form'); ?></div>
                                        <br>

                                        <?php if (!empty($bets)): ?>
                                            <h3>Bets</h3>
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th><?= __('Game'); ?></th>
                                                    <th><?= __('Amount'); ?></th>
                                                </tr>
					<?php foreach ($bets as $key=>$bet){ ?>
                                                <tr>
                                                    <td><?= $key; ?></td>
                                                    <td><?= $bet; ?></td>								
                                                </tr>
                                            <?php } ?>
                                            </table>
                                         <?php endif;?>      
                                          
                                              <?php if (!empty($wins)): ?>
                                            <h3>Wins</h3>
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th><?= __('Game'); ?></th>
                                                    <th><?= __('Amount'); ?></th>
                                                </tr>
					<?php foreach ($wins as $key=>$bet){ ?>
                                                <tr>
                                                    <td><?= $key; ?></td>
                                                    <td><?= $bet; ?></td>								
                                                </tr>
                                            <?php } ?>
                                            </table>
                                         <?php endif;?>  
                                            
                                            
                                              <?php if (!empty($tips)): ?>
                                            <h3>Tips</h3>
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th><?= __('Game'); ?></th>
                                                    <th><?= __('Amount'); ?></th>
                                                </tr>
					<?php foreach ($tips as $key=>$bet){ ?>
                                                <tr>
                                                    <td><?= $key; ?></td>
                                                    <td><?= $bet; ?></td>								
                                                </tr>
                                            <?php } ?>
                                            </table>
                                         <?php endif;?>  
                                    </div>
                                </div>
                                <!--END TABS-->
                            </div>
                            <div class="space10 visible-phone"></div>
                        </div>
                    </div>
                </div>
                <!-- END INLINE TABS PORTLET-->
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>