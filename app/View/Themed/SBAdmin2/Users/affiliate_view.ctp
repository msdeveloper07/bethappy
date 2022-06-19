<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= $this->Html->link(__('Players'), ['plugin' => false, 'controller' => 'users', 'action' => 'index', 'prefix' => 'affiliate'], ['escape' => false, 'title' => __('Players')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('View'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('View'); ?></h1>
            </div>
            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <div class="col-md-12 pt-2">
                <div class="row">
                    <div class="col-sm-12 col-md-12">


                        <ul class="list-group">
                            <?php
                            $i = 1;
                            $curency = $fields['User']['currency'];
                            ?>
                            <?php
                            foreach ($fields['User'] as $key => $value):
                                if ($key == 'username' || $key == 'first_name' || $key == 'last_name' ||
                                        $key == 'address1' || $key == 'city' || $key == 'zip_code' ||
                                        $key == 'country' || $key == 'mobile_number' || $key == 'email' ||
                                        $key == 'date_of_birth' || $key == 'last_visit' ||
                                        $key == 'registration_date' || $key == 'gender' || $key == 'balance'):
                                    ?>
                                    <?php
                                    $class = '';
                                    if ($i++ % 2 == 0)
                                        $class = 'alt';
                                    if ($key == 'mobile_number')
                                        $key = 'Phone';


                                    if ($key == 'last_visit' || $key == 'registration_date')
                                        $value = date('d-m-Y H:i:s', strtotime($value));

                                    if ($key == 'balance')
                                        $value = $curency['Currency']['code'] . $value;
                                    ?>

                                    <li class="list-group-item form-group small">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <p class="mb-0">
                                                    <?= Inflector::humanize($key); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-8">
                                                <?= $value; ?>
                                            </div>
                                        </div>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>

                    </div>
                
         
                </div>


            </div>
        </div>
    </div>
</div>





<style>
    .table-overflow tbody {
        display:block;
        height:400px;
        overflow:auto;
    }
    .table-overflow thead,   .table-overflow tbody tr {
        display:table;
        width:100%;
        table-layout:fixed;
    }
</style>
