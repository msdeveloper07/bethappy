<?php
$menu = array(
    0 => array(
        'title' => 'All Games',
        'class' => 'icon-heart',
        'image' => 'casino-sm',
        'sub-menu' => array(
            0 => array(
                'title' => __('Games List'),
                'plugin' => 'int_games',
                'controller' => 'int_games',
                'action' => 'index'
            ),
            1 => array(
                'title' => __('Add Game'),
                'plugin' => 'int_games',
                'controller' => 'int_games',
                'action' => 'add_game'
            ),
            2 => array(
                'title' => __('Order Games') . " " . __("Desktop"),
                'plugin' => 'int_games',
                'controller' => 'int_games',
                'action' => 'ordered_games'
            ),
            3 => array(
                'title' => __('Order Games') . " " . __("Mobile"),
                'plugin' => 'int_games',
                'controller' => 'int_games',
                'action' => 'ordered_games',
                'variable' => true
            ),
            4 => array(
                'title' => __('Games Categories'),
                'plugin' => 'int_games',
                'controller' => 'int_categories',
                'action' => 'index'
            ),
            5 => array(
                'title' => __('Games Brands'),
                'plugin' => 'int_games',
                'controller' => 'int_brands',
                'action' => 'index'
            ),
            6 => array(
                'title' => __('Transactions'),
                'plugin' => 'int_games',
                'controller' => 'int_games',
                'action' => 'transactions'
            )
        )
    ),
    1 => array(
        'title' => 'Habanero',
        'class' => 'icon-fire',
        'image' => 'habanero-sm',
        'sub-menu' => array(
            0 => array(
                'title' => __('Games'),
                'plugin' => 'habanero',
                'controller' => 'habanero_admin',
                'action' => 'games'
            ),
            1 => array(
                'title' => __('GGR by Player'),
                'plugin' => 'habanero',
                'controller' => 'habanero_admin',
                'action' => 'ggr_by_player'
            ),
            2 => array(
                'title' => __('GGR by Game'),
                'plugin' => 'habanero',
                'controller' => 'habanero_admin',
                'action' => 'ggr_by_game'
            ),
        )
    ),
    2 => array(
        'title' => 'Playson',
        'class' => 'icon-play',
        'image' => 'playson-sm',
        'sub-menu' => array(
            0 => array(
                'title' => __('Games'),
                'plugin' => 'playson',
                'controller' => 'playson_admin',
                'action' => 'games'
            ),
            1 => array(
                'title' => __('GGR by Player'),
                'plugin' => 'playson',
                'controller' => 'playson_admin',
                'action' => 'ggr_by_player'
            ),
            2 => array(
                'title' => __('GGR by Game'),
                'plugin' => 'playson',
                'controller' => 'playson_admin',
                'action' => 'ggr_by_game'
            ),
        )
    ),
    3 => array(
        'title' => 'Tomhorn',
        'class' => 'icon-refresh',
        'image' => 'tomhorn-sm',
        'sub-menu' => array(
            0 => array(
                'title' => __('Games'),
                'plugin' => 'tomhorn',
                'controller' => 'tomhorn_admin',
                'action' => 'games'
            ),
            1 => array(
                'title' => __('GGR by Player'),
                'plugin' => 'tomhorn',
                'controller' => 'tomhorn_admin',
                'action' => 'ggr_by_player'
            ),
            2 => array(
                'title' => __('GGR by Game'),
                'plugin' => 'tomhorn',
                'controller' => 'tomhorn_admin',
                'action' => 'ggr_by_game'
            ),
        )
    ),
    4 => array(
        'title' => 'Vivo',
        'class' => 'icon-dice',
        'image' => 'vivo-sm',
        'sub-menu' => array(
            0 => array(
                'title' => __('Games'),
                'plugin' => 'vivo',
                'controller' => 'vivo_admin',
                'action' => 'games'
            ),
            1 => array(
                'title' => __('GGR by Player'),
                'plugin' => 'vivo',
                'controller' => 'vivo_admin',
                'action' => 'ggr_by_player'
            ),
            2 => array(
                'title' => __('GGR by Game'),
                'plugin' => 'vivo',
                'controller' => 'vivo_admin',
                'action' => 'ggr_by_game'
            ),
        )
    ),
    5 => array(
        'title' => 'Kiron',
        'class' => 'icon-dice',
        'image' => 'kiron-sm',
        'sub-menu' => array(
            0 => array(
                'title' => __('Games'),
                'plugin' => 'kiron',
                'controller' => 'kiron_admin',
                'action' => 'games'
            ),
            1 => array(
                'title' => __('GGR by Player'),
                'plugin' => 'kiron',
                'controller' => 'kiron_admin',
                'action' => 'ggr_by_player'
            ),
            2 => array(
                'title' => __('GGR by Game'),
                'plugin' => 'kiron',
                'controller' => 'kiron_admin',
                'action' => 'ggr_by_game'
            ),
        )
    ),
    6 => array(
        'title' => 'Netent',
        'class' => 'icon-pause',
        'image' => 'netent-sm',
        'sub-menu' => array(
            0 => array(
                'title' => __('Games'),
                'plugin' => 'netent',
                'controller' => 'netent_admin',
                'action' => 'games'
            ),
            1 => array(
                'title' => __('GGR by Player'),
                'plugin' => 'netent',
                'controller' => 'netent_admin',
                'action' => 'ggr_by_player'
            ),
            2 => array(
                'title' => __('GGR by Game'),
                'plugin' => 'netent',
                'controller' => 'netent_admin',
                'action' => 'ggr_by_game'
            ),
        )
    ),
    7 => array(
        'title' => 'Microgaming',
        'class' => 'icon-pause',
        'image' => 'microgaming-sm',
        'sub-menu' => array(
            0 => array(
                'title' => __('Games'),
                'plugin' => 'microgaming',
                'controller' => 'microgaming_admin',
                'action' => 'games'
            ),
            1 => array(
                'title' => __('GGR by Player'),
                'plugin' => 'microgaming',
                'controller' => 'microgaming_admin',
                'action' => 'ggr_by_player'
            ),
            2 => array(
                'title' => __('GGR by Game'),
                'plugin' => 'microgaming',
                'controller' => 'microgaming_admin',
                'action' => 'ggr_by_game'
            ),
        )
    ),
    8 => array(
        'title' => 'Booongo',
        'class' => 'icon-pause',
        'image' => 'booongo-sm',
        'sub-menu' => array(
            0 => array(
                'title' => __('Games'),
                'plugin' => 'booongo',
                'controller' => 'booongo_admin',
                'action' => 'games'
            ),
            1 => array(
                'title' => __('GGR by Player'),
                'plugin' => 'booongo',
                'controller' => 'booongo_admin',
                'action' => 'ggr_by_player'
            ),
            2 => array(
                'title' => __('GGR by Game'),
                'plugin' => 'booongo',
                'controller' => 'booongo_admin',
                'action' => 'ggr_by_game'
            ),
        )
    ),
    9 => array(
        'title' => 'Spinomenal',
        'class' => 'icon-pause',
        'image' => 'spinomenal-sm',
        'sub-menu' => array(
            0 => array(
                'title' => __('Games'),
                'plugin' => 'spinomenal',
                'controller' => 'spinomenal_admin',
                'action' => 'games'
            ),
            1 => array(
                'title' => __('GGR by Player'),
                'plugin' => 'spinomenal',
                'controller' => 'spinomenal_admin',
                'action' => 'ggr_by_player'
            ),
            2 => array(
                'title' => __('GGR by Game'),
                'plugin' => 'spinomenal',
                'controller' => 'spinomenal_admin',
                'action' => 'ggr_by_game'
            ),
        )
    ),
    10 => array(
        'title' => 'Betsoft',
        'class' => 'icon-pause',
        'image' => 'betsoft-sm',
        'sub-menu' => array(
            0 => array(
                'title' => __('Games'),
                'plugin' => 'betsoft',
                'controller' => 'betsoft_admin',
                'action' => 'games'
            ),
            1 => array(
                'title' => __('GGR by Player'),
                'plugin' => 'betsoft',
                'controller' => 'betsoft_admin',
                'action' => 'ggr_by_player'
            ),
            2 => array(
                'title' => __('GGR by Game'),
                'plugin' => 'betsoft',
                'controller' => 'betsoft_admin',
                'action' => 'ggr_by_game'
            ),
        )
    ),
    11 => array(
        'title' => 'Ezugi',
        'class' => 'icon-pause',
        'image' => 'ezugi-sm',
        'sub-menu' => array(
            0 => array(
                'title' => __('Games'),
                'plugin' => 'ezugi',
                'controller' => 'ezugi_admin',
                'action' => 'games'
            ),
            1 => array(
                'title' => __('GGR by Player'),
                'plugin' => 'ezugi',
                'controller' => 'ezugi_admin',
                'action' => 'ggr_by_player'
            ),
            2 => array(
                'title' => __('GGR by Game'),
                'plugin' => 'ezugi',
                'controller' => 'ezugi_admin',
                'action' => 'ggr_by_game'
            ),
        )
    ),
    12 => array(
        'title' => 'Platipus',
        'class' => 'icon-pause',
        'image' => 'platipus-sm',
        'sub-menu' => array(
            0 => array(
                'title' => __('Games'),
                'plugin' => 'platipus',
                'controller' => 'platipus_admin',
                'action' => 'games'
            ),
            1 => array(
                'title' => __('GGR by Player'),
                'plugin' => 'platipus',
                'controller' => 'platipus_admin',
                'action' => 'ggr_by_player'
            ),
            2 => array(
                'title' => __('GGR by Game'),
                'plugin' => 'platipus',
                'controller' => 'platipus_admin',
                'action' => 'ggr_by_game'
            ),
        )
    ),
    13 => array(
        'title' => 'Igromat',
        'class' => 'icon-pause',
        'image' => 'igromat-sm',
        'sub-menu' => array(
            0 => array(
                'title' => __('Games'),
                'plugin' => 'igromat',
                'controller' => 'igromat_admin',
                'action' => 'games'
            ),
            1 => array(
                'title' => __('GGR by Player'),
                'plugin' => 'igromat',
                'controller' => 'igromat_admin',
                'action' => 'ggr_by_player'
            ),
            2 => array(
                'title' => __('GGR by Game'),
                'plugin' => 'igromat',
                'controller' => 'igromat_admin',
                'action' => 'ggr_by_game'
            ),
        )
    ),
    14 => array(
        'title' => 'Reports',
        'class' => 'icon-retweet',
        'image' => 'report-sm',
        'sub-menu' => array(
            0 => array(
                'title' => __('Inactivity Users Report'),
                'controller' => 'reports',
                'action' => 'inactivity_users'
            ),
            1 => array(
                'title' => __('Users report'),
                'controller' => 'reports',
                'action' => 'users'
            ),
            3 => array(
                'title' => __('Deposits report'),
                'controller' => 'reports',
                'action' => 'deposits'
            ),
            4 => array(
                'title' => __('Witdraw report'),
                'controller' => 'reports',
                'action' => 'withdraws'
            ),
            5 => array(
                'title' => __('Liability report'),
                'controller' => 'reports',
                'action' => 'usersliabilityReport'
            ),
            6 => array(
                'title' => __('Player Liability report'),
                'controller' => 'reports',
                'action' => 'playerindex'
            ),
            7 => array(
                'title' => __('Transaction report'),
                'controller' => 'reports',
                'action' => 'transactionreport'
            ),
            8 => array(
                'title' => __('APCO report'),
                'controller' => 'reports',
                'action' => 'apco'
            ),
            9 => array(
                'title' => __('Statistics Report'),
                'controller' => 'reports',
                'action' => 'statistic_reports'
            ),
            9 => array(
                'title' => __('Custom Report'),
                'controller' => 'reports',
                'action' => 'customizable'
            )
        )
    )
);
?>
<div id="sidebar">
    <div class="span2 main-menu-span">
        <div class="well nav-collapse sidebar-nav">
            <div class="panel-group productMenu col-md-3 col-sm-4 hidden-xs" id="accordion">
                <div class="sidemenu" data-spy="affix" data-offset-top="450">
                    <?php if (isset($menu) AND is_array($menu)): ?>
                        <ul class="nav nav-tabs nav-stacked main-menu headers-li">
                            <li class="nav-header" style="font-size:1em"><?= __('Main'); ?></li>
                        </ul>

                        <div class="panel panel-default">
                            <?php foreach ($menu AS $key => $menuData): ?>
                                <a data-toggle="collapse" data-parent=".sidemenu" href="#<?= $key; ?>" class="mnhref">
                                    <div class="pMenuheadingOrange sidebar-parent">
                                        <p class="truncate mntitle">
                                            <!--<i class="<?= $menuData['class'] ?>"></i>--> 
                                            <img src="/Layout/images/providers/<?= $menuData['image'] ?>.png" width="20" height="20"/>
                                            <?= __($menuData['title']); ?>
                                        </p>
                                        <span class="arrow"></span>
                                    </div>
                                </a>
                                <div id="<?= $key; ?>" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <?php foreach ($menuData['sub-menu'] AS $subMenu): ?>
                                            <?php if ($this->MyHtml->checkAcl(array('controller' => $subMenu['controller'], 'action' => $subMenu['action'])) !== false): ?>
                                                <div class="pMenuheadingSmall"><?= $this->MyHtml->link(__($subMenu['title']), array('plugin' => $subMenu['plugin'], 'controller' => $subMenu['controller'], 'action' => $subMenu['action'], $subMenu['variable']), array('class' => 'ajax-link truncate')); ?></div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(".pMenuheadingSmall").click(function (e) {
            var $link = $(this).find('a').attr('href');
            window.location = $link;
        });

        $('.pMenuheadingSmall').find('a[href="<?php echo $this->request->here; ?>"]').parent().parent().parent().addClass('in');
        $('.pMenuheadingSmall').find('a[href="<?php echo $this->request->here; ?>"]').parent().addClass('active');

        var id = $('.pMenuheadingSmall').find('a[href="<?php echo $this->request->here; ?>"]').parent().attr('id');
        $(".pMenuheadingSmall#" + id).addClass('selecteda');
    });
</script>