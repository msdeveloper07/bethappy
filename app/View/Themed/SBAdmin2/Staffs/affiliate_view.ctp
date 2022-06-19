
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Profile'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Profile'); ?></h1>

            </div>
            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <div class="col-md-12 pt-2">
                <ul class="list-group">


                    <?php $i = 1; //var_dump($fields);?>
                    <?php foreach ($fields['Staff'] as $key => $value): //var_dump($key); ?>
                        <?php
                        $class = '';
                        if ($i++ % 2 == 0)
                            $class = 'alt';

                        if ($key == 'mobile_number')
                            $key = 'Phone';
                        
                        if($key =='date_of_birth' || $key == 'registration_date')
                            $value = date('d-m-Y', strtotime($value));
                        
                        
                        ?>
                        <li class="list-group-item form-group">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <?php echo Inflector::humanize($key); ?>
                                </div>
                                <div class="col-md-8">
                                    <?php echo $value; ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>