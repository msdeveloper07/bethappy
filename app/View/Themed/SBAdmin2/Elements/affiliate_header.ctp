<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    
    Affiliate code: &nbsp;<button class="btn btn-primary"><?= CakeSession::read('Auth.Affiliate.referral_id'); ?></button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="infoDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-v fa-fw mr-2 text-gray-400"></i>
            </a>
            <!-- Dropdown - Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="infoDropdown">
                <a class="dropdown-item" href="#" title="<?= __('Help'); ?>">
                    <i class="fas fa-question-circle fa-sm fa-fw mr-2 text-gray-400"></i>
                    <?= __('Help'); ?>
                </a>
                <a class="dropdown-item" href="#" title="<?= __('Documentation'); ?>">
                    <i class="fas fa-info-circle fa-sm fa-fw mr-2 text-gray-400"></i>
                    <?= __('Documentation'); ?>
                </a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">                                   
                    <?= CakeSession::read('Auth.Affiliate.affiliate_custom_id'); ?>
                </span>
                <div class="initials-profile rounded-circle">
                    <?= substr(CakeSession::read('Auth.Affiliate.affiliate_custom_id'), 0, 1); ?>
                </div>
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <?= $this->Html->link('<i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>' . __('Profile'), array('plugin'=> NULL, 'controller' => 'staffs', 'action' => 'view', CakeSession::read('Auth.Affiliate.user_id')), array('class' => 'dropdown-item', 'escape' => false)); ?>
                <?= $this->Html->link('<i class="fas fa-clipboard-list fa-sm fa-fw mr-2 text-gray-400"></i>' . __('Audit log'), array('plugin'=> NULL, 'controller' => 'logs', 'action' => 'view', CakeSession::read('Auth.Affiliate.user_id')), array('class' => 'dropdown-item', 'escape' => false)); ?>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/affiliate/users/logout">
                    <i class="fas fa-sign-out-alt fa-fw mr-2 text-gray-400"></i>
                    <?= __('Sign out'); ?>
                </a>
            </div>
        </li>
    </ul>
</nav>
