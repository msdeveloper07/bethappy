<style type="text/css">
    a.mnhref:hover {
        text-decoration: none;
    }
    .selecteda {
        background: #333333!important;
    }
</style>

<?php
$menu = array(
    0   =>  array(
        'title'     =>  'Users',
        'class'     =>  'icon-user',
        'sub-menu'  =>  array(
            0   =>  array(
                'title'         =>  'List users',
                'controller'    =>  'users',
                'action'        =>  'index'
            ),
            1   =>  array(
                'title'         =>  'Create user',
                'controller'    =>  'users',
                'action'        =>  'add'
            ),
            2   =>  array(
                'title'         =>  'KYC list',
                'controller'    =>  'KYC',
                'action'        =>  'index'
            ),
            3   =>  array(
                'title'         =>  'User Categories',
                'controller'    =>  'UserCategories',
                'action'        =>  'index'
            ),
            
            4  => array(
                'title'         =>  'User Export',
                'controller'    =>  'Export',
                'action'        =>  'index'
            )
        )
    ),
    1   =>  array(
        'title'     =>  'Affiliates',
        'class'     =>  'icon-briefcase',
        'sub-menu'  =>  array(
            0   =>  array(
                'title'         =>  'List Affiliates',
                'controller'    =>  'Affiliates',
                'action'        =>  'index'
            ),
            1   =>  array(
                'title'         =>  'Media in Use',
                'controller'    =>  'AffiliateMedia',
                'action'        =>  'index'
            ),
            2   =>  array(
                'title'         =>  'Affiliates Media',
                'controller'    =>  'Affiliates',
                'action'        =>  'media'
            )
        )
    ),
   
    2  => array(
        'title'     =>  'Bonus',
        'class'     =>  'icon-gift',
        'sub-menu'  =>  array(
            0   =>  array(
                'title'         =>  'List Bonuses',
                'controller'    =>  'bonus',
                'action'        =>  'index' 
            ),
            1   =>  array(
                'title'         =>  'List Bonus Types',
                'controller'    =>  'BonusType',
                'action'        =>  'index'
            ),
            2   =>  array(
                'title'         =>  'Deposit bonus',
                'controller'    =>  'payment_bonus_groups',
                'action'        =>  'index'
            ),
            3   =>  array(
                'title'         =>  'Bonus Codes',
                'controller'    =>  'bonus_codes',
                'action'        =>  'index'
            ),
            4   =>  array(
                'title'         =>  'Bonus Report',
                'controller'    =>  'bonus',
                'action'        =>  'report'
            )
        )
    ),
    3   =>  array(
        'title'     =>  'Deposits',
        'class'     =>  'icon-plus-sign',
        'sub-menu'  =>  array(
            0   =>  array(
                'title'         =>  'Manual Deposits',
                'controller'    =>  'paymentmanuals',
                'action'        =>  'index',
                'variable'      =>  'deposits'
            ),
            1   =>  array(
                'title'         =>  'Bank Deposits',
                'controller'    =>  'deposits',
                'action'        =>  'index'
            ),
            2   =>  array(
                'title'         =>  'Automatic Payments',
                'plugin'        =>  'payments',
                'controller'    =>  'aretopay',
                'action'        =>  'index'
            ),
            3   =>  array(
                'title'         =>  'Deposit risk management',
                'controller'    =>  'settings',
                'action'        =>  'depositsRisks'
            )
        )
    ),
    4   =>  array(
        'title'     =>  'Withdraws',
        'class'     =>  'icon-minus-sign',
        'sub-menu'  =>  array(
            0   =>  array(
                'title'         =>  'Manual Withdraws',
                'controller'    =>  'paymentmanuals',
                'action'        =>  'index',
                'variable'      =>  'withdraws'
            ),
            1   =>  array(
                'title'         =>  'List requests',
                'controller'    =>  'withdraws',
                'action'        =>  'index'
            ),
            2   =>  array(
                'title'         =>  'Withdraw risks management',
                'controller'    =>  'settings',
                'action'        =>  'withdrawsRisks'
            )
        )
    ),
    5  =>  array(
        'title'     =>  'Marketing',
        'class'     =>  'icon-flag',
        'sub-menu'  =>  array(
            0   =>  array(
                'title'         =>  'Promotion letter',
                'controller'    =>  'mails',
                'action'        =>  'index'
            ),
            1   =>  array(
                'title'         =>  'Media',
                'controller'    =>  'Affiliates',
                'action'        =>  'media'
            )
        )
    ),
    6   =>  array(
        'title'     =>  'Content',
        'class'     =>  'icon-info-sign',
        'sub-menu'  =>  array(
            0   =>  array(
                'title'         =>  'Manage News',
                'controller'    =>  'news',
                'action'        =>  null,
                'acl-allow'     =>  ''
            ),
            1   =>  array(
                'title'         =>  'Manage Pages Content',
                'controller'    =>  'pages',
                'action'        =>  'index'
            ),
            2   =>  array(
                'title'         =>  'Manage Slider',
                'controller'    =>  'slides',
                'action'        =>  'index'
            ),
            3   =>  array(
                'title'         =>  'Manage Scroller',
                'controller'    =>  'scrollers',
                'action'        =>  'index'
            ),
            4   =>  array(
                'title'         =>  'Top Header menu',
                'controller'    =>  'mt_menus',
                'action'        =>  'index'
            ),
            5   =>  array(
                'title'         =>  'Games menu',
                'controller'    =>  'game_menus',
                'action'        =>  'index',
            ),
            6   =>  array(
                'title'         =>  'Bottom menu',
                'controller'    =>  'mb_menus',
                'action'        =>  'index'
            )
        )
    ),
    7 => array(
        'title'     =>  'ACL',
        'class'     =>  'icon-lock',
        'sub-menu'  =>  array(
            0   =>  array(
                'title'         =>  'ACL',
                'plugin'        =>  'acl',
                'controller'    =>  'aros',
                'action'        =>  'users'
            )
        )
    ),
    8  =>  array(
        'title'     =>  'Settings',
        'class'     =>  'icon-wrench',
        'sub-menu'  =>  array(
            0   =>  array(
                'title'         =>  'General settings',
                'controller'    =>  'settings',
                'action'        =>  'index'
            ),
            1   =>  array(
                'title'         =>  'Extra Games settings',
                'controller'    =>  'settings',
                'action'        =>  'extragames'
            ),
            
            2   =>  array(
                'title'         =>  'IP Settings',
                'controller'    =>  'settings',
                'action'        =>  'ip'
            ),
            3   =>  array(
                'title'         =>  'Currencies',
                'controller'    =>  'currencies',
                'action'        =>  'index'
            ),
            4   =>  array(
                'title'         =>  'Payment gateways',
                'controller'    =>  'settings',
                'action'        =>  'deposits'
            ),
            5   =>  array(
                'title'         =>  'SEO settings',
                'controller'    =>  'settings',
                'action'        =>  'seo'
            ),
            6   =>  array(
                'title'         =>  'Email templates',
                'controller'    =>  'templates',
                'action'        =>  'index'
            ),
            7   =>  array(
                'title'         =>  'Jobs',
                'controller'    =>  'jobs',
                'action'        =>  'index'
            )
        )
    ),
    9  =>  array(
        'title'     =>  'Staff',
        'class'     =>  'icon-zoom-in',
        'sub-menu'  =>  array(
            0   =>  array(
                'title'         =>  'List staff',
                'controller'    =>  'staffs',
                'action'        =>  'index'
            ),
            1   =>  array(
                'title'         =>  'Create staff',
                'controller'    =>  'staffs',
                'action'        =>  'add'
            ),
            2   =>  array(
                'title'         =>  'Staff Audit Log',
                'controller'    =>  'Logs',
                'action'        =>  'index'
            )
        )
    ),
);
?>
<div id="sidebar">
    <div class="span2 main-menu-span">
        <div class="well nav-collapse sidebar-nav">
            <div class="panel-group productMenu col-md-3 col-sm-4 hidden-xs" id="accordion">
                <div class="sidemenu" data-spy="affix" data-offset-top="450">
                    <?php  if(isset($menu) AND is_array($menu)): ?>
                        <ul class="nav nav-tabs nav-stacked main-menu headers-li">
                            <li class="nav-header" style="font-size:1em"><?=__('Main');?></li>
                        </ul>
                        
                        <div class="panel panel-default">
                            <?php foreach($menu AS $key => $menuData): ?>
                                <a data-toggle="collapse" data-parent=".sidemenu" href="#<?=$key;?>" class="mnhref">
                                    <div class="pMenuheadingOrange sidebar-parent">
                                        <p class="truncate mntitle"><i class="<?= $menuData['class']?>"></i> <?= __($menuData['title']); ?></p>
                                        <span class="arrow"></span>
                                    </div>
                                </a>
                                
                                <div id="<?=$key;?>" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <?php foreach($menuData['sub-menu'] AS $subMenu): ?>
                                            <?php if($this->MyHtml->checkAcl(array('controller' => $subMenu['controller'], 'action' => $subMenu['action'])) !== false): ?>
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
    $(document).ready(function() {
        $(".pMenuheadingSmall").click(function(e) {
            var $link = $(this).find('a').attr('href');
            window.location = $link;
        });
        
        $('.pMenuheadingSmall').find('a[href="<?php echo $this->request->here;?>"]').parent().parent().parent().addClass('in');
        $('.pMenuheadingSmall').find('a[href="<?php echo $this->request->here;?>"]').parent().addClass('active');
        
        var id = $('.pMenuheadingSmall').find('a[href="<?php echo $this->request->here;?>"]').parent().attr('id');
        $(".pMenuheadingSmall#"+id).addClass('selecteda');
    });
</script>

