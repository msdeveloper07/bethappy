
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Casino'); ?></li>
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Free Spins'), ['plugin' => 'int_games', 'controller' => 'int_free_spins', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Free Spins')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Create'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Create Free Spins'); ?></h1>
            </div>

            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <div class="col-md-12">
                <?= $this->element('tabs'); ?>
            </div>
            <div class="col-md-12">
                <?php echo $this->Form->create('IntFreeSpin'); ?>

                <ul class="list-group">
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Platform'); ?></p>
                            </div>
                            <div class="col-md-8">
                                <select class="form-control" id="platform" name="data[IntFreeSpin][int_plugin_id]">
                                    <option value=""><?= __('Select platform'); ?></option>
                                    <?php foreach ($platforms as $key => $platform) { ?>
                                        <option value="<?php echo $platform['int_plugins']['games_model']; ?>"><?php echo $platform['int_plugins']['model']; ?></option>
                                    <?php } ?>
                                </select>                            
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Games'); ?></p>
                            </div>
                            <div class="col-md-8">
                                <select class="form-control js-example-basic-single" name="data[IntFreeSpin][games][]" multiple="multiple" id="games">
                                    <?php foreach ($games as $key => $game) { ?>
                                        <option value="<?php echo $game['IntGame']['id']; ?>"><?php echo $game['IntGame']['name']; ?></option>
                                    <?php } ?>
                                </select>                            
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Users'); ?></p>
                            </div>
                            <div class="col-md-8">
                                <select class="form-control js-example-basic-single" name="data[IntFreeSpin][users][]" multiple="multiple">

                                    <?php foreach ($users as $key => $user) { ?>
                                        <option value="<?php echo $user['User']['id']; ?>"><?php echo $user['User']['username']; ?></option>
                                    <?php } ?>
                                </select>                            
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Name'); ?></p>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="data[IntFreeSpin][name]"/>                
                            </div>
                        </div>
                    </li>


                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Bet level'); ?></p>
                            </div>
                            <div class="col-md-8">
                                <select name="data[IntFreeSpin][bet_level]" class="form-control">
                                    <option value="min"><?= __('Min'); ?></option>
                                    <option value="mid" selected><?= __('Mid'); ?></option>
                                    <option value="max"><?= __('Max'); ?></option>
                                </select>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Number of free spins'); ?></p>
                            </div>
                            <div class="col-md-8">
                                <input type="number" class="form-control" name="data[IntFreeSpin][number_of_free_spins]"/>                
                            </div>
                        </div>
                    </li>


                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Valid from'); ?></p>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control datetimepicker-filter" name="data[IntFreeSpin][valid_from]"/>                
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Valid to'); ?></p>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control datetimepicker-filter" name="data[IntFreeSpin][valid_to]"/>                
                            </div>
                        </div>
                    </li>


                </ul>
                <div class="row form-group">
                    <div class="col-sm-12 col-md-3 offset-md-9 col-lg-2 offset-lg-10 my-3">
                        <?php echo $this->Form->submit(__('Save', true), array('class' => 'btn btn-success btn-block')); ?>
                    </div>
                </div>

                <?php echo $this->Form->end(); ?>


            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.js-example-basic-single').select2({
            //placeholder: 'Select users',
            allowClear: true
        });


        $("#platform").on("change", function () {
            $("#games").find('option').remove();
            var platform = $(this).val();
            //console.log(platform);
            if (platform !== "") {
                $.ajax({
                    url: "/int_games/int_games/getGamesBySource/" + platform,
                    type: "POST",
                    cache: false,
                    //data: {source: platform},
                    success: function (data) {
                        //console.log(data);
                        $.each(data, function (key, value) {
                            //console.log(key);
                            //console.log(value);
                            $('<option>').val('').text('select');
                            $('<option>').val(value.IntGame.id).text(value.IntGame.name + ' (' + value.IntBrand.name + ')').appendTo($("#games"));
                        });
                    }
                });
            } else {
                //$("#games").html(" ");
            }
        });


    });
</script>


