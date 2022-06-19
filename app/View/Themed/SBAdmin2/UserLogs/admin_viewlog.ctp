<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">


            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Players'); ?></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= $this->Html->link(__('Players List'), ['plugin' => false, 'controller' => 'users', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Players List')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= $this->Html->link(__('View Player'), ['plugin' => false, 'controller' => 'users', 'action' => 'view', 'prefix' => 'admin', $user_id], ['escape' => false, 'title' => __('View Player')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Login/Logout History'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Login/Logout History'); ?></h1>
            </div>
            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <div class="col-md-12">
                <?//= $this->element('search'); ?>
                <form action="/admin/UserLogs/viewlog/<?= $user_id ?>" id="search-form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                    <div class="form-row align-items-center justify-content-flex-start flex-wrap">
                        <?php
                        if (!isset($search_fields) || !is_array($search_fields))
                            return;

                        foreach ($search_fields AS $i => $field) {
                            if (!is_array($field))
                                $search_fields[$i] = array($field);

                            $search_fields[$i]['div'] = array('class' => 'form-group mr-2');
                            $search_fields[$i]['required'] = false;
                        }

                        echo $this->Form->inputs($search_fields, null, array('fieldset' => false, 'legend' => false));
                        ?>

                    </div>
                    <?php echo $this->Form->button(__('Search', true), array('type' => 'submit', 'id' => 'search_button', 'class' => 'btn btn-primary'));
                    ?>
                </form>
            </div>
            <div class="col-md-12">

                <div class="table-responsive pt-2">
                    <?php if (!empty($data)): ?>
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th><?= $this->Paginator->sort('id', __('ID')); ?></th>
                                <th><?= $this->Paginator->sort('date'); ?></th>
                                <th><?= $this->Paginator->sort('action'); ?></th>
                                <th><?= $this->Paginator->sort('ip', __('IP')); ?></th>
                                <!--<th><?php //echo __('GeoIP Location');      ?></th>-->
                            </tr>
                            <?php foreach ($data as $row): ?>
                                <tr>
                                    <td><?php echo $row['UserLog']['id']; ?></td>
                                    <td><?php echo date('d-m-Y H:i:s', strtotime($row['UserLog']['date'])); ?></td>
                                    <td><?php echo $row['UserLog']['action']; ?></td>
                                    <td><?php echo $row['UserLog']['ip']; ?></td>

                                    <?php
//                                                            $GetIPINFO=array();
//                                                            $GetIPINFO=geoip_record_by_name($datas['Userlog']['ip']);
//                                                            $datas['Userlog']['ip']="<b>IP INFO:</b>".$GetIPINFO['country_code'].", ".$GetIPINFO['country_code3'].", ".$GetIPINFO['country_name'].", ".$GetIPINFO['region'].", ".$GetIPINFO['city'].", ".$GetIPINFO['postal_code'];
//                                                            
                                    ?>
                                                                            <!--<td><?php //echo $datas['UserLog']['ip'];     ?></td>-->
                                </tr>
                            <?php endforeach; ?>
                        </table>

                        <?= $this->element('paginator'); ?>
                    <?php else: ?>
                        <p><?= __('No records found'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


