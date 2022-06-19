<div class="box span12">
    <div class="box-header well">
        <h2><i class="icon-info-sign"></i>&nbsp;&nbsp;&nbsp;Daily Statistics - Monthly on color box</h2>
        <div class="box-icon"><a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-down"></i></a></div>
    </div>
    <div class="box-content">
        <div class="sortable row-fluid ui-sortable">
            <div class="well span3 statistics">
                <a data-original-title="<?php echo __('Daily Users'); ?>" data-rel="tooltip" href="/admin/users/?dashboard=1" style="text-decoration:none">
                    <span class="green numberst"><?php echo isset($activeUsers_daily) ? $activeUsers_daily : 0; ?></span>
                    <div class="title"><span class="icon-user turquoise-color"></span><?php echo __('Visited Users'); ?></div>
                </a>
                <a data-original-title="<?php echo __('Monthly Users'); ?>" data-rel="tooltip" class="notification green" href="/admin/users/?dashboard=2">
                    <?php echo isset($activeUsers_monthly) ? $activeUsers_monthly : 0; ?>
                </a>
            </div>

            <div class="well span3 statistics">
                <a data-original-title="<?php echo __('Daily Registered'); ?>" data-rel="tooltip" href="/admin/users/?dashboard=3" style="text-decoration:none;">
                    <span class="red numberst"><?php echo isset($registeredCount_daily) ? $registeredCount_daily : 0; ?></span>
                    <div class="title"><span class="icon-user turquoise-color"></span><?php echo __('Registered Users'); ?></div>
                </a>
                <a data-original-title="<?php echo __('Monthly Registered'); ?>" data-rel="tooltip" class="notification green" href="/admin/users/?dashboard=4">
                    <?php echo isset($registeredCount_monthly) ? $registeredCount_monthly : 0; ?>
                </a>
            </div>

            <div class="well span3 statistics">
                <a data-original-title="<?php echo __('Daily Deposits'); ?>" data-rel="tooltip" href="/admin/deposits/?dashboard=1" style="text-decoration:none;">
                    <span class="red numberst"><?php echo isset($depositsCount_daily) ? $depositsCount_daily : 0; ?></span>
                    <div class="title"><span class="icon-plus-sign green-color"></span><?php echo __('Deposits'); ?></div>
                </a>
                <a class="notification yellow" data-original-title="<?php echo __('Monthly Deposits'); ?>" data-rel="tooltip" href="/admin/deposits/?dashboard=2">
                    <?php echo isset($depositsCount_monthly) ? $depositsCount_monthly : 0; ?>
                </a>
            </div>

            <div class="well span3 statistics">
                <a data-original-title="<?php echo __('Daily Withdraws'); ?>" data-rel="tooltip" href="/admin/withdraws/?dashboard=1" style="text-decoration:none;">
                    <span class="numberst"><?php echo isset($withdrawsCount_daily) ? $withdrawsCount_daily : 0; ?></span>
                    <div class="title" style="position:relative; top:7px; padding-bottom:4px">
                        <span class="icon-minus-sign gray-color"></span><?php echo __('Withdraws'); ?>
                    </div>
                </a>
                <a data-original-title="<?php echo __('Monthly Withdraws'); ?>" data-rel="tooltip" class="notification yellow" href="/admin/withdraws/?dashboard=2">
                    <?php echo isset($withdrawsCount_monthly) ? $withdrawsCount_monthly : 0; ?>
                </a>
            </div>
        </div>
    </div>
</div>