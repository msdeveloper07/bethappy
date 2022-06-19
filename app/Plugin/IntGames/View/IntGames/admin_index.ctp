<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Casino'); ?></li>

                    <li class="breadcrumb-item active" aria-current="page"><?= __('Games'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Games'); ?></h1>
            </div>

            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <?= $this->Form->create(); ?>
        <div class="row">


            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="IntGameName"><?php echo __('Name'); ?></label>
                    <input name="data[IntGame][name]" class="form-control" maxlength="50" type="text" id="IntGameName"></div>
            </div>

            <div class="col-md-3 col-sm-12">
                <?php if ($categories): ?>
                    <label><?= __('Categories'); ?></label>
                    <select type="select" name="data[IntGame][category_id]" class="form-control" id="IntGameCategory">
                        <option selected disabled><?php echo __('Select category'); ?></option>
                        <?php foreach ($categories as $key => $category) { ?>
                            <option value="<?php echo $category['IntCategory']['id']; ?>"><?php echo $category['IntCategory']['name']; ?></option>
                        <?php } ?>
                    </select>
                <?php endif;
                ?>
            </div>

            <div class="col-md-3 col-sm-12">
                <?php if ($brands): ?>
                    <label><?= __('Brands'); ?></label>
                    <select type="select" name="data[IntGame][brand_id]" class="form-control" id="IntGameBrand">
                        <option selected disabled><?php echo __('Select brand'); ?></option>
                        <?php foreach ($brands as $key => $brand) { ?>
                            <option value="<?php echo $brand['IntBrand']['id']; ?>"><?php echo $brand['IntBrand']['name']; ?></option>
                        <?php } ?>
                    </select>
                <?php endif;
                ?>
            </div>

            <div class="col-md-12 mb-4">
                <div class="d-flex justify-content-flex-start">

                    <div class="form-check mr-4">
                        <input class="form-check-input" type="checkbox" value="1" id="IntGameNew" name="data[IntGame][new]">
                        <label class="form-check-label" for="IntGameNew">
                            <?= __('New', true); ?>  
                        </label>
                    </div>

                    <div class="form-check mr-4">
                        <input class="form-check-input" type="checkbox" value="1" id="IntGameMobile" name="data[IntGame][mobile]">
                        <label class="form-check-label" for="IntGameMobile">
                            <?= __('Mobile', true); ?>  
                        </label>
                    </div>

                    <div class="form-check mr-4">
                        <input class="form-check-input" type="checkbox" value="1" id="IntGameDesktop" name="data[IntGame][desktop]">
                        <label class="form-check-label" for="IntGameDesktop">
                            <?= __('Desktop', true); ?>  
                        </label>
                    </div>

                    <div class="form-check mr-4">
                        <input class="form-check-input" type="checkbox" value="1" id="IntGameFetured" name="data[IntGame]['featured']">
                        <label class="form-check-label" for="IntGameFetured">
                            <?= __('Featured', true); ?>  
                        </label>
                    </div>

                    <div class="form-check mr-4">
                        <input class="form-check-input" type="checkbox" value="1" id="IntGameJackpot" name="data[IntGame][jackpot]">
                        <label class="form-check-label" for="IntGameJackpot">
                            <?= __('Jackpot', true); ?>  
                        </label>
                    </div>
                    <div class="form-check mr-4">
                        <input class="form-check-input" type="checkbox" value="1" id="IntGameFunPlay" name="data[IntGame][fun_play]">
                        <label class="form-check-label" for="IntGameFunPlay">
                            <?= __('Fun Play', true); ?>  
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="IntGameFreeSpins" name="data[IntGame][free_spins]">
                        <label class="form-check-label" for="IntGameFreeSpins">
                            <?= __('Free Spins', true); ?>  
                        </label>
                    </div>

                </div>     

            </div>
            <div class="col-md-12">
                <?= $this->Form->submit(__('Search', true), array('class' => 'btn btn-primary')); ?>
            </div>
        </div>
        <?= $this->Form->end(); ?>
        <div class="row">
            <div class="col-md-12">
                <?= $this->element('tabs'); ?>
            </div>
            <div class="col-md-12 pt-2">
                <?= $this->element('list'); ?>
            </div>
        </div>
    </div>
</div>

