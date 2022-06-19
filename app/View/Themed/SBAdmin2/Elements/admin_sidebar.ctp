<?php
$menu = array(
    0 => array(
        'title' => __('Players'),
        'icon' => 'users',
        'sub-menu' => array(
            0 => array(
                'title' => __('Players'),
                'controller' => 'users',
                'action' => 'index',
                'icon' => 'users',
                'plugin' => null,
            ),
            1 => array(
                'title' => __('Categories'),
                'controller' => 'userCategories',
                'action' => 'index',
                'icon' => 'user-tag',
                'plugin' => null,
            ),
            2 => array(
                'title' => __('KYC'),
                'controller' => 'KYC',
                'action' => 'index',
                'icon' => 'id-badge',
                'plugin' => null,
            ),
        )
    ),
//    0 => array(
//        'title' => 'Players',
//        'sub-menu' => array(
//            0 => array(
//                'title' => __('Players'),
//                'icon' => 'users',
//                'sub-menu' => array(              
//                    3 => array(
//                        'title' => __('Bulk Fund'),
//                        'controller' => 'Paymentmanuals',
//                        'action' => 'bulkfund'
//                    ),
//                    4 => array(
//                        'title' => __('Export'),
//                        'controller' => 'Export',
//                        'action' => 'index'
//                    ),
//                       5 => array(
////                        'title' => __('Create player'),
////                        'controller' => 'users',
////                        'action' => 'add'
////                    ),
////                    6 => array(
////                        'title' => __('Depositors Export'),
////                        'controller' => 'Export',
////                        'action' => 'index',
////                        'variable' => 'depositors'
////                    )
//                )
//            ),
//            
//        )
//    ), // end users
    1 => array(
        'title' => __('Bonuses'),
        'sub-menu' => array(
            0 => array(
                'title' => __('Bonuses'),
                'controller' => 'bonus',
                'action' => 'index',
                'icon' => 'donate',
                'plugin' => null,
            ),
            1 => array(
                'title' => __('Bonus Types'),
                'controller' => 'BonusTypes',
                'action' => 'index',
                'icon' => 'gifts',
                'plugin' => null,
            ),
//            2 => array(
//                'title' => __('Bonus Codes'),
//                'controller' => 'bonus_codes',
//                'action' => 'index',
//                'icon' => 'barcode'
//            ),
//3 => array(
//                'title' => __('Deposit bonus'),
//                'controller' => 'payment_bonus_groups',
//                'action' => 'index',
//                'icon'=>'gift'
//            ),
        )
    ),
//    1 => array(
//        'title' => __('Affiliates'),
//        'icon' => 'users-friends',
//        'sub-menu' => array(
//            0 => array(
//                'title' => __('Affiliates'),
//                'controller' => 'Affiliates',
//                'action' => 'index',
//                'icon' => 'user-friends',
//            ),
////            1 => array(
////                'title' => __('Media in Use'),
////                'controller' => 'AffiliateMedia',
////                'action' => 'index'
////            ),
////            2 => array(
////                'title' => __('Affiliates Media'),
////                'controller' => 'Affiliates',
////                'action' => 'media'
////            )
//        )
//    ),
//    2 => array(
//        'title' => __('Jackpots'),
//        'icon' => 'users-friends',
//        'sub-menu' => array(
//            0 => array(
//                'title' => __('Grande Atlantic'),
//                'controller' => 'Jackpots',
//                'action' => 'grande_atlantic',
//                'icon' => 'candy-cane',
//            ),
//            1 => array(
//                'title' => __('Atlantic Megapot'),
//                'controller' => 'Jackpots',
//                'action' => 'mega_atlantic',
//                'icon' => 'ice-cream',
//            ),
//            2 => array(
//                'title' => __('Mistery Midi'),
//                'controller' => 'Jackpots',
//                'action' => 'mistery_midi',
//                'icon' => 'apple-alt',
//            ),
//            3 => array(
//                'title' => __('Mistery Mini'),
//                'controller' => 'Jackpots',
//                'action' => 'mistery_mini',
//                'icon' => 'carrot',
//            ),
//            4 => array(
//                'title' => __('Atlantic Promo'),
//                'controller' => 'Jackpots',
//                'action' => 'atlantic_promo',
//                'icon' => 'pepper-hot',
//            ),
//        )
//    ),
    3 => array(
        'title' => __('Payments'),
        'class' => 'ion-ios-loop',
        'sub-menu' => array(
            0 => array(
                'title' => __('Payment Methods'),
                'plugin' => 'payments',
                'controller' => 'PaymentsMethods',
                'action' => 'index',
                'icon' => 'wallet',
            ),
            1 => array(
                'title' => __('Limits'),
                'plugin' => 'payments',
                'controller' => 'Limits',
                'action' => 'index',
                'icon' => 'sliders-h',
            ),
            2 => array(
                'title' => __('Deposits'),
                'icon' => 'user-plus',
                'sub-menu' => array(
                    0 => array(
                        'title' => __('Manual'),
                        'plugin' => 'payments',
                        'controller' => 'Manuals',
                        'action' => 'index',
                        'paramaters' => 'deposit',
                    ),
//                    1 => array(
//                        'title' => __('CASHlib'),
//                        'plugin' => 'payments',
//                        'controller' => 'Cashlib',
//                        'action' => 'index',
//                        'image' => 'cashlib-md.png',
//                        'paramaters' => null
//                    ),
                    2 => array(
                        'title' => __('Forum Pay'),
                        'plugin' => 'payments',
                        'controller' => 'ForumPay',
                        'action' => 'index',
                        'image' => 'forumpay-md.png',
                        'paramaters' => null
                    ),
                    3 => array(
                        'title' => __('Bridger Pay'),
                        'plugin' => 'payments',
                        'controller' => 'BridgerPay',
                        'action' => 'index',
                        'image' => 'bridgerpay-md.png',
                        'paramaters' => null
                    ),
                    4 => array(
                        'title' => __('Aninda'),
                        'plugin' => 'payments',
                        'controller' => 'Aninda',
                        'action' => 'index',
                        'paramaters' => 'deposits',
                        'image' => 'aninda-md.png'
                    ),
                    5 => array(
                        'title' => __('Risk Management'),
                        'controller' => 'settings',
                        'action' => 'depositsRisks',
                        'paramaters' => null,
                        'plugin' => null,
                    ),
//                    1 => array(
//                        'title' => __('RadiantPay'),
//                        'plugin' => 'payments',
//                        'controller' => 'RadiantPay',
//                        'action' => 'index',
//                        'image' => 'radiantpay-md.png',
//                        'paramaters' => null
//                    ),
                )
            ),
            3 => array(
                'title' => __('Withdraws'),
                'icon' => 'user-minus',
                'sub-menu' => array(
                    0 => array(
                        'title' => __('Aninda'),
                        'plugin' => 'payments',
                        'controller' => 'Aninda',
                        'action' => 'index',
                        'paramaters' => 'withdraws',
                        'image' => 'bank-md.png'
                    ),
//                    0 => array(
//                        'title' => __('Bank Transfer'),
//                        'plugin' => 'payments',
//                        'controller' => 'BankTransfer',
//                        'action' => 'index',
//                        'paramaters' => 'withdraws',
//                        'image' => 'bank-md.png'
//                    ),
//                    1 => array(
//                        'title' => __('Card Transfer'),
//                        'plugin' => 'payments',
//                        'controller' => 'CardTransfer',
//                        'action' => 'index',
//                        'paramaters' => 'withdraws',
//                        'image' => 'bank-md.png',
//                        'paramaters' => null
//                    ),
                    2 => array(
                        'title' => __('Risks Management'),
                        'controller' => 'settings',
                        'action' => 'withdrawsRisks',
                        'paramaters' => null,
                        'plugin' => null,
                    )
                )
            ),
        )
    ), //end payments
    4 => array(
        'title' => __('Reports'),
        'class' => 'ion-ios-paper-outline',
        'sub-menu' => array(
            0 => array(
                'title' => __('Deposits Reports'),
                'plugin' => 'payments',
                'controller' => 'reports',
                'action' => 'deposits',
                'icon' => 'file-import',
            ),
            1 => array(
                'title' => __('Withdraws Reports'),
                'plugin' => 'payments',
                'controller' => 'reports',
                'action' => 'withdraws',
                'icon' => 'file-export',
            ),
            2 => array(
                'title' => __('GGR Reports'),
                'plugin' => 'int_games',
                'controller' => 'reports',
                'action' => 'ggr_by_player',
                'icon' => 'file-invoice-dollar',
            ),
//            3 => array(
//                'title' => __('Bonuses Reports'),
//                'controller' => 'reports',
//                'action' => 'bonuses',
//                'icon' => 'file-medical'
//            ),
        )
    ),
//end games
//
    5 => array(
        'title' => __('Casino'),
        'class' => 'ion-ios-game-controller-b-outline',
        'sub-menu' => array(
            0 => array(
                'title' => __('Games'),
                'plugin' => 'int_games',
                'controller' => 'int_games',
                'action' => 'index',
                'icon' => 'dice'
            ),
            1 => array(
                'title' => __('Brands'),
                'plugin' => 'int_games',
                'controller' => 'int_brands',
                'action' => 'index',
                'icon' => 'gamepad'
            ),
            2 => array(
                'title' => __('Categories'),
                'plugin' => 'int_games',
                'controller' => 'int_categories',
                'action' => 'index',
                'icon' => 'grip-horizontal'
            ),
            3 => array(
                'title' => __('Order Games') . " " . __("Desktop"),
                'plugin' => 'int_games',
                'controller' => 'int_games',
                'action' => 'ordered_games_desktop',
                'icon' => 'desktop',
                'paramaters' => null,
            ),
            4 => array(
                'title' => __('Order Games') . " " . __("Mobile"),
                'plugin' => 'int_games',
                'controller' => 'int_games',
                'action' => 'ordered_games_mobile',
                'variable' => true,
                'icon' => 'mobile-alt',
                'paramaters' => null,
            ),
            5 => array(
                'title' => __('Free Spins'),
                'plugin' => 'int_games',
                'controller' => 'int_free_spins',
                'action' => 'index',
                'icon' => 'gift'
            ),
            6 => array(
                'title' => __('Add Game'),
                'plugin' => 'int_games',
                'controller' => 'int_games',
                'action' => 'add_game',
                'icon' => 'puzzle-piece'
            ),
        )
    ),
//    4 => array(
//        'title' => __('Marketing'),
//        'class' => 'ion-ios-briefcase-outline',
//        'sub-menu' => array(
//            0 => array(
//                'title' => __('Promotion letter'),
//                'controller' => 'mails',
//                'action' => 'index',
//                'icon' => 'mail-bulk'
//            ),
//            1 => array(
//                'title' => __('Media'),
//                'controller' => 'Affiliates',
//                'action' => 'media',
//                'icon' => 'photo-video'
//            ),
//            2 => array(
//                'title' => __('Landing Pages'),
//                'controller' => 'LandingPages',
//                'action' => 'index',
//                'icon' => 'external-link-alt'
//            )
//        )
//    ),
    6 => array(
        'title' => __('Content'),
        'class' => 'ion-ios-barcode-outline',
        'sub-menu' => array(
            0 => array(
                'title' => __('Menus'),
                'icon' => 'stream',
                'sub-menu' => array(
                    0 => array(
                        'title' => __('Main Menu'),
                        'controller' => 'mt_menus',
                        'action' => 'index',
                        'plugin' => null,
                        'paramaters' => null,
                    ),
                    1 => array(
                        'title' => __('Footer Menus'),
                        'controller' => 'mb_menus',
                        'action' => 'index',
                        'plugin' => null,
                        'paramaters' => null,
                    ),
                )
            ),
            1 => array(
                'title' => __('Pages'),
                'controller' => 'pages',
                'action' => 'index',
                'icon' => 'file',
                'plugin' => null,
                'paramaters' => null,
            ),
            2 => array(
                'title' => __('Email Templates'),
                'controller' => 'Templates',
                'action' => 'index',
                'icon' => 'envelope-open-text',
                'plugin' => null,
            ),
            3 => array(
                'title' => __('Slider'),
                'controller' => 'slides',
                'action' => 'index',
                'icon' => 'images',
                'plugin' => null,
            ),
            4 => array(
                'title' => __('News'),
                'controller' => 'news',
                'action' => null,
                'acl-allow' => '',
                'icon' => 'newspaper',
                'plugin' => null,
            ),
//            5 => array(
//                'title' => __('Scroller'),
//                'controller' => 'scrollers',
//                'action' => 'index'
//            ),
//            7 => array(
//                'title' => __('Games menu'),
//                'controller' => 'game_menus',
//                'action' => 'index',
//            ),
        )
    ), //end content
    7 => array(
        'title' => __('Settings'),
        'class' => 'ion-ios-settings',
        'sub-menu' => array(
            0 => array(
                'title' => __('Currencies'),
                'controller' => 'currencies',
                'action' => 'index',
                'icon' => 'coins',
                'plugin' => null,
            ),
            1 => array(
                'title' => __('Languages'),
                'controller' => 'languages',
                'action' => 'index',
                'icon' => 'language',
                'plugin' => null,
            ),
            2 => array(
                'title' => __('Countries'),
                'controller' => 'countries',
                'action' => 'index',
                'icon' => 'globe',
                'plugin' => null,
            ),
//            3 => array(
//                'title' => __('Jobs'),
//                'controller' => 'jobs',
//                'action' => 'index',
//                'icon' => 'briefcase'
//            ),
        )
    ),
    8 => array(
        'title' => __('Staff'),
        'icon' => 'user-shield',
        'sub-menu' => array(
            0 => array(
                'title' => __('Staff'),
                'controller' => 'staffs',
                'action' => 'index',
                'icon' => 'user-shield'
            ),
            1 => array(
                'title' => __('Audit Log'),
                'controller' => 'logs',
                'action' => 'index',
                'icon' => 'clipboard-list'
            )
        )
    ),
//    9 => array(
//        'title' => __('ACL'),
//        'class' => 'ion-ios-unlocked-outline',
//        'sub-menu' => array(
//            0 => array(
//                'title' => __('Permissions'),
//                'plugin' => 'acl',
//                'controller' => 'aros',
//                'action' => 'index',
//                'icon' => 'user-lock'
//            ),
//            1 => array(
//                'title' => __('Actions'),
//                'plugin' => 'acl',
//                'controller' => 'acos',
//                'action' => 'index',
//                'icon' => 'tools'
//            ),
//            2 => array(
//                'title' => __('Synchronize'),
//                'plugin' => 'acl',
//                'controller' => 'acos',
//                'action' => 'synchronize',
//                'icon' => 'sync'
//            ),
//        )
//    ),
);
?>
<!-- Sidebar -->
<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/admin/dashboard/administrator">
        <div class="sidebar-brand-icon">
            <img src="/img/casino/bet-happy-logo-lg.png" alt="Bet Happy" height="60"/>
        </div>
    </a>
    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    <!-- Nav Item -->
    <li class="nav-item active">
        <a class="nav-link" href="/admin/dashboard/administrator">
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
            <?php if (isset($menu_item['sub-menu']) && is_array($menu_item['sub-menu'])): ?>
                <?php foreach ($menu_item['sub-menu'] AS $sub_menu_item): ?>
                    <li class="nav-item">
                        <?php if (isset($sub_menu_item['sub-menu']) && is_array($sub_menu_item['sub-menu'])): ?>
                            <!--if there is a sub-menu there is no link-->
                            <a href="javascript:;" class="nav-link" data-toggle="collapse" data-target="#collapse<?= $sub_menu_item['title']; ?>" aria-expanded="true" aria-controls="collapse<?= $sub_menu_item['title']; ?>">
                                <i class="fas fa-fw fa-<?= $sub_menu_item['icon']; ?>"></i>
                                <span><?= __($sub_menu_item['title']); ?></span>
                            </a>
                            <div id="collapse<?= $sub_menu_item['title']; ?>" class="collapse" aria-labelledby="heading<?= $sub_menu_item['title']; ?>" data-parent="#accordionSidebar">
                                <div class="bg-white py-2 collapse-inner rounded">
                                    <?php foreach ($sub_menu_item['sub-menu'] AS $sub_sub_menu_item): ?>
                                        <?= $this->Html->link(__($sub_sub_menu_item['title']), ['plugin' => $sub_sub_menu_item['plugin'], 'controller' => $sub_sub_menu_item['controller'], 'action' => $sub_sub_menu_item['action'], 'prefix' => 'admin', $sub_sub_menu_item['paramaters'] ? $sub_sub_menu_item['paramaters'] : ''], ['escape' => false, 'title' => __($sub_sub_menu_item['title']), 'class' => 'collapse-item']); ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <!--if there isn't a sub-menu there is a link-->
                            <?= $this->Html->link('<i class="fas fa-fw fa-' . $sub_menu_item['icon'] . '"></i> <span>' . __($sub_menu_item['title']) . '</span>', ['plugin' => $sub_menu_item['plugin'], 'controller' => $sub_menu_item['controller'], 'action' => $sub_menu_item['action'], 'prefix' => 'admin', $sub_menu_item['paramaters'] ? $sub_menu_item['paramaters'] : ''], ['escape' => false, 'title' => __($sub_menu_item['title']), 'class' => 'nav-link']); ?>
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





