<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Casino'); ?></li>

                    <li class="breadcrumb-item active" aria-current="page"><?= __('Add Game'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Add Game'); ?></h1>
            </div>

            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <div class="col-md-12">
                <ul class="list-group">
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Platform'); ?></p>
                            </div>
                            <div class="col-md-8">
                                <select type="select" name="data[platform]" class="form-control">
                                    <option selected disabled><?php echo __('Select platform'); ?></option>
                                    <?php foreach ($platforms as $key => $platform) { ?>
                                        <option value="<?php echo $platform; ?>"><?php echo $platform; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Provider'); ?></p>
                            </div>
                            <div class="col-md-8">
                                <select type="select" name="data[provider]" class="form-control">
                                    <option selected disabled><?php echo __('Select provider'); ?></option>
                                    <?php foreach ($game_providers as $key => $provider) { ?>
                                        <option value="<?php echo $provider; ?>"><?php echo $provider; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Category'); ?></p>
                            </div>
                            <div class="col-md-8">
                                <select type="select" name="data[category_id]" class="form-control">
                                    <option selected disabled><?php echo __('Select category'); ?></option>
                                    <?php
                                    foreach ($categories as $category) {
                                        ?>
                                        <option value="<?= $category['IntCategory']['id']; ?>"><?= $category['IntCategory']['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </li>


                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Game name'); ?></p>
                            </div>
                            <div class="col-md-8">
                                <input name="data[name]" class="form-control"  type="text"/>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Game ID'); ?></p>
                            </div>
                            <div class="col-md-8">
                                <input name="data[game_id]" class="form-control"  type="text"/>

                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Game key'); ?> <i>(<?= __('optional'); ?>)</i></p>
                            </div>
                            <div class="col-md-8">
                                <input name="data[game_key]"  class="form-control"  type="text"/>

                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('RTP'); ?></p>
                            </div>
                            <div class="col-md-8">
                                <input name="data[rtp]" class="form-control"  type="text"/>
                            </div>
                        </div>
                    </li>


                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Volatility'); ?></p>
                            </div>
                            <div class="col-md-8">
                                <select name="data[volatility]" class="form-control">
                                    <option value="N/A"><?= __('N/A'); ?></option>
                                    <option value="N/A"><?= __('Low'); ?></option>
                                    <option value="N/A"><?= __('Medium'); ?></option>
                                    <option value="N/A"><?= __('High'); ?></option>
                                </select>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Pay lines'); ?></p>
                            </div>
                            <div class="col-md-8">
                                <input  name="data[pay_lines]" class="form-control"  type="text"/>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Reels'); ?></p>
                            </div>
                            <div class="col-md-8">
                                <input name="data[reels]" class="form-control"  type="text"/>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Free spins'); ?></p>
                            </div>
                            <div class="col-md-8">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="FreeSpins" name="data[free_spins]"/>
                                    <label class="custom-control-label" for="FreeSpins"></label>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Fun play'); ?></p>
                            </div>
                            <div class="col-md-8">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="Funplay" name="data[fun_play]"/>
                                    <label class="custom-control-label" for="Funplay"></label>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Branded'); ?></p>
                            </div>
                            <div class="col-md-8">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="Branded" name="data[branded]"/>
                                    <label class="custom-control-label" for="Branded"></label>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Jackpot'); ?></p>
                            </div>
                            <div class="col-md-8">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="Jackpot" name="data[jackpot]"/>
                                    <label class="custom-control-label" for="Jackpot"></label>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Desktop'); ?></p>
                            </div>
                            <div class="col-md-8">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="Desktop" name="data[desktop]"/>
                                    <label class="custom-control-label" for="Desktop"></label>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Mobile'); ?></p>
                            </div>
                            <div class="col-md-8">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="Mobile" name="data[mobile]"/>
                                    <label class="custom-control-label" for="Mobile"></label>
                                </div>
                            </div>
                        </div>
                    </li>

                </ul>

                <div class="row form-group">
                    <div class="col-sm-12 col-md-3 offset-md-9 col-lg-2 offset-lg-10 my-3">
                        <?php echo $this->Form->submit(__('Save', true), array('class' => 'btn btn-success btn-block')); ?>
                    </div>
                </div>
            </div>   


        </div>
    </div>
</div>
