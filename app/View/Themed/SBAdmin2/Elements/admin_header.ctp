
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Search -->
<!--    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary bg-default" type="button">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>-->

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
<!--        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>-->

        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <?= $this->element('admin_alerts_dropdown'); ?>
        </li>


        <!-- Nav Item - Site Settings -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="settingsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-cogs fa-fw"></i>
            </a>
            <!-- Dropdown - Site Settings -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="settingsDropdown">            
                <?= $this->Html->link('<i class="fas fa-cog fa-sm fa-fw mr-2 text-gray-400"></i>' . __('General Settings'), ['plugin' => false, 'controller' => 'settings', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('General Settings'), 'class' => 'dropdown-item']); ?>
                <?= $this->Html->link('<i class="fas fa-laptop fa-sm fa-fw mr-2 text-gray-400"></i>' . __('SEO Settings'), ['plugin' => false, 'controller' => 'settings', 'action' => 'seo', 'prefix' => 'admin'], ['escape' => false, 'title' => __('SEO Settings'), 'class' => 'dropdown-item']); ?>
                <?= $this->Html->link('<i class="fas fa-network-wired fa-sm fa-fw mr-2 text-gray-400"></i>' . __('IP Settings'), ['plugin' => false, 'controller' => 'settings', 'action' => 'ip', 'prefix' => 'admin'], ['escape' => false, 'title' => __('IP Settings'), 'class' => 'dropdown-item']); ?>
            </div>
        </li>

<!--        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="infoDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-v fa-fw mr-2 text-gray-400"></i>
            </a>-->
            <!-- Dropdown - Information -->
<!--            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="infoDropdown">
                <a class="dropdown-item" href="#" title="<?//= __('Help'); ?>">
                    <i class="fas fa-question-circle fa-sm fa-fw mr-2 text-gray-400"></i>
                    <?//= __('Help'); ?>
                </a>
                <a class="dropdown-item" href="#" title="<?//= __('Documentation'); ?>">
                    <i class="fas fa-info-circle fa-sm fa-fw mr-2 text-gray-400"></i>
                    <?//= __('Documentation'); ?>
                </a>
            </div>-->
        <!--</li>-->

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">                                   
                    <?= CakeSession::read('Auth.User.first_name'); ?>  <?= CakeSession::read('Auth.User.last_name'); ?>
                </span>
                <div class="initials-profile rounded-circle">
                    <?= substr(CakeSession::read('Auth.User.first_name'), 0, 1); ?><?= substr(CakeSession::read('Auth.User.last_name'), 0, 1); ?>
                </div>
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <?= $this->Html->link('<i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>' . __('Profile'), array('controller' => 'staffs', 'action' => 'view', CakeSession::read('Auth.User.id')), array('class' => 'dropdown-item', 'escape' => false)); ?>
                <!--<?//= $this->Html->link('<i class="fas fa-clipboard-list fa-sm fa-fw mr-2 text-gray-400"></i>' . __('Audit log'), array('controller' => 'logs', 'action' => 'view', CakeSession::read('Auth.User.id')), array('class' => 'dropdown-item', 'escape' => false)); ?>-->
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/admin/users/logout">
                    <i class="fas fa-sign-out-alt fa-fw mr-2 text-gray-400"></i>
                    <?= __('Sign out'); ?>
                </a>
            </div>
        </li>
    </ul>
</nav>
