<div>
    <a class="nav-link dropdown-toggle" title="<?= __('Alerts center'); ?>" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bell fa-fw"></i>
        <!--<?//php echo $alerts_count ? $alerts_count : 0; ?>-->
        <span class="badge badge-danger badge-counter" id="alerts_count"></span>
    </a>

    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown" >
        <h6 class="dropdown-header bg-default">
            <?= __('Alerts center'); ?>
        </h6>
        <span id="alerts_list"></span>
        <!--in admin.ctp call an ajax function that loads last 5 alerts-->
        <a class="dropdown-item text-center small text-gray-500" href="<?php echo $this->Html->url(array('controller' => 'alerts', 'action' => 'admin_index')); ?>"><?= __('Go to alerts'); ?></a>
    </div>
</div>


