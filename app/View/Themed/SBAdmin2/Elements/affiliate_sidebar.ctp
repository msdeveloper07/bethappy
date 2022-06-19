<?php
$menu = array(
    0 => array(
        'title' => __('Menu'),
        'icon' => 'users',
        'sub-menu' => array(
            0 => array(
                'title' => __('Players'),
                'controller' => 'users',
                'action' => 'index',
                'icon' => 'users'
            ),
            1 => array(
                'title' => __('Deposits'),
                'plugin' => 'payments',
                'controller' => 'reports',
                'action' => 'players_deposits',
                'paramaters' => CakeSession::read('Auth.Affiliate.id'),
                'icon' => 'user-plus'
            ),
            2 => array(
                'title' => __('GGR'),
                'plugin' => 'int_games',
                'controller' => 'reports',
                'action' => 'players_ggr',
                'paramaters' => CakeSession::read('Auth.Affiliate.id'),
                'icon' => 'file-invoice-dollar'
            ),
        )
    ),
);
?>
<!-- Sidebar -->
<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/affiliate/dashboard">
        <div class="sidebar-brand-icon">
            <img src="/img/casino/atlantic_slot_logo.png" alt="Atlantic Slot Casino" height="60"/>
        </div>
    </a>
    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    <!-- Nav Item -->
    <li class="nav-item active">
        <a class="nav-link" href="/affiliate/dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span><?= __('Dashboard'); ?></span>
        </a>
    </li>
    <?php if (isset($menu) && is_array($menu)): ?>
        <?php foreach ($menu AS $key => $menu_item): ?>
            <!-- Divider -->
            <hr class="sidebar-divider">
            <!-- Heading -->
            <div class="sidebar-heading">
                <?= $menu_item['title']; ?>
            </div>
            <?php if (is_array($menu_item['sub-menu'])): ?>
                <?php foreach ($menu_item['sub-menu'] AS $sub_menu_item): ?>
                    <li class="nav-item">
                        <?php if (is_array($sub_menu_item['sub-menu'])): ?>
                            <!--if there is a sub-menu there is no link-->
                            <a href="javascript:;" class="nav-link" data-toggle="collapse" data-target="#collapse<?= $sub_menu_item['title']; ?>" aria-expanded="true" aria-controls="collapse<?= $sub_menu_item['title']; ?>">
                                <i class="fas fa-fw fa-<?= $sub_menu_item['icon']; ?>"></i>
                                <span><?= __($sub_menu_item['title']); ?></span>
                            </a>
                            <div id="collapse<?= $sub_menu_item['title']; ?>" class="collapse" aria-labelledby="heading<?= $sub_menu_item['title']; ?>" data-parent="#accordionSidebar">
                                <div class="bg-white py-2 collapse-inner rounded">
                                    <?php foreach ($sub_menu_item['sub-menu'] AS $sub_sub_menu_item): ?>
                                        <?= $this->Html->link(__($sub_sub_menu_item['title']), ['plugin' => $sub_sub_menu_item['plugin'], 'controller' => $sub_sub_menu_item['controller'], 'action' => $sub_sub_menu_item['action'], 'prefix' => 'affiliate', $sub_sub_menu_item['paramaters'] ? $sub_sub_menu_item['paramaters'] : ''], ['escape' => false, 'title' => __($sub_sub_menu_item['title']), 'class' => 'collapse-item']); ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <!--if there isn't a sub-menu there is a link-->
                            <?= $this->Html->link('<i class="fas fa-fw fa-' . $sub_menu_item['icon'] . '"></i> <span>' . __($sub_menu_item['title']) . '</span>', ['plugin' => $sub_menu_item['plugin'], 'controller' => $sub_menu_item['controller'], 'action' => $sub_menu_item['action'], 'prefix' => 'affiliate', $sub_menu_item['paramaters'] ? $sub_menu_item['paramaters'] : ''], ['escape' => false, 'title' => __($sub_menu_item['title']), 'class' => 'nav-link']); ?>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>       
        <?php endforeach; ?>
    <?php endif; ?>
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->





