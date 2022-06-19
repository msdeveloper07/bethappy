
<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
    <li><a href="/admin/Dashboard/administrator"><?= __('Home'); ?></a></li>
    <li><?= __('Games'); ?></li>
    <li><?= __('Provider Games'); ?></li>
    <li class="active"><?= __('Booongo'); ?></li>
</ol>
<!-- end breadcrumb -->
<!-- begin page-header -->
<h1 class="page-header">Booongo <small>Games</small></h1>
<!-- end page-header -->
<?= $this->element('flash_message'); ?>

<?php if (!empty($games)) { ?>
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="ion-arrow-expand"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="ion-ios-loop"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="ion-ios-minus-empty"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="ion-ios-close-empty"></i></a>
            </div>
            <h4 class="panel-title"><?= __('Booongo Games'); ?></h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <?= $this->Html->link(__('Download'), array('plugin' => 'booongo', 'controller' => 'booongo_admin', 'action' => 'getNewGames'), array('class' => 'btn btn-success')); ?>
                            <?= $this->Html->link(__('Synchronize'), array('plugin' => 'booongo', 'controller' => 'booongo_admin', 'action' => 'syncNewGames'), array('class' => 'btn btn-primary')); ?>
                        </li>
                    </ul>        
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="data-table">
                            <thead>
                                <tr>
                                    <th><?= __('ID'); ?></th>
                                    <th><?= __('Game ID'); ?></th>
                                    <th><?= __('Game Key'); ?></th>
                                    <th><?= __('Name'); ?></th>
                                    <th class="filter"><?= __('Category'); ?></th>
                                    <th class="filter"><?= __('Type'); ?></th>
                                    <th class="filter"><?= __('Pay Lines'); ?></th>
                                    <th class="filter"><?= __('Reels'); ?></th>
                                    <th><?= __('Image'); ?></th>
                                    <th class="filter"><?= __('Branded'); ?></th>
                                    <th class="filter"><?= __('Jackpot'); ?></th>
                                    <th class="filter"><?= __('Free Spins'); ?></th>
                                    <th class="filter"><?= __('Fun Play'); ?></th>
                                    <th class="filter"><?= __('Mobile'); ?></th>
                                    <th class="filter"><?= __('Desktop'); ?></th>
                                    <th class="filter"><?= __('New'); ?></th>
                                    <th class="filter"><?= __('Active'); ?></th>
                                    <th><?= __('Actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($games as $row): ?>
                                    <tr data-id="<?= $row['BooongoGames']['id']; ?>">
                                        <td class="table-cell popper" data-popbox="pop1_<?= $row['BooongoGames']['id']; ?>"><?= $row['BooongoGames']['id']; ?></td>
                                        <td class="table-cell"><?= $row['BooongoGames']['game_id']; ?></td>
                                        <td class="table-cell"><?= $row['BooongoGames']['game_key']; ?></td>
                                        <td class="table-cell"><?= $row['BooongoGames']['name'] ?></td><!--<td><input type="text" class="name-value" value="<?= $row['BooongoGames']['name'] ?>"/></td>-->
                                        <td class="table-cell category_id" data-name="category_id" data-type="select" data-pk="<?= $row['BooongoGames']['id']; ?>"><?= $row['BooongoGames']['category_id'] ?></td><!--<td><input type="text" class="category-value" value="<?= $row['BooongoGames']['category_id'] ?>" /></td>-->
                                        <td class="table-cell type" data-name="type" data-type="select" data-pk="<?= $row['BooongoGames']['id']; ?>"><?= $row['BooongoGames']['type']; ?></td>
                                        <td class="table-cell pay-lines" data-name="pay-lines" data-type="number" data-pk="<?= $row['BooongoGames']['id']; ?>"><?= $row['BooongoGames']['pay_lines'] ?></td><!--<td><input type="text" class="paylines-value" value="<?= $row['BooongoGames']['pay_lines'] ?>"/></td>-->
                                        <td class="table-cell"><?= $row['BooongoGames']['reels'] ?></td><!--<td><input type="text" class="reels-value" value="<?= $row['BooongoGames']['reels'] ?>" /></td>-->
                                        <td><img width="100" src="<?= $row['BooongoGames']['image'] ?>"/></td><!--<td><input type="text" class="image-value" value="<?= $row['BooongoGames']['image'] ?>" data-image="<?= $row['BooongoGames']['image'] ?>" /></td>-->
                                        <!--<td><input type="text" class="branded-value" value="<?= $row['BooongoGames']['branded'] ?>" /></td>-->
                                        <td class="table-cell"><input class="checkbranded" type="checkbox" <?= ($row['BooongoGames']['branded'] == 1) ? 'checked' : ""; ?> /></td>
                                        <td class="table-cell"><input class="checkjackpot" type="checkbox" <?= ($row['BooongoGames']['jackpot'] == 1) ? 'checked' : ""; ?> /></td>
                                        <td class="table-cell"><input class="checkfreespins" type="checkbox" <?= ($row['BooongoGames']['free_spins'] == 1) ? 'checked' : ""; ?> /></td>
                                        <td class="table-cell"><input class="checkfunplay" type="checkbox" <?= ($row['BooongoGames']['fun_play'] == 1) ? 'checked' : ""; ?> /></td>
                                        <td class="table-cell"><input class="checkmobile" type="checkbox" <?= ($row['BooongoGames']['mobile'] == 1) ? 'checked' : ""; ?> /></td>
                                        <td class="table-cell"><input class="checkdesktop" type="checkbox" <?= ($row['BooongoGames']['desktop'] == 1) ? 'checked' : ""; ?> /></td>
                                        <td class="table-cell"><input class="checknew" type="checkbox" <?= ($row['BooongoGames']['new'] == 1) ? 'checked' : ""; ?> /></td>
                                        <td class="table-cell"><input class="checkactive" type="checkbox" <?= ($row['BooongoGames']['active'] == 1) ? 'checked' : ""; ?> /></td>
                                        <td class="table-cell" style="text-align: right"><a class="adjust-game btn btn-mini btn-primary"><i class="icon-white icon-upload" style="margin-right:5px;"></i><?= __('Save'); ?></a></td>
                                    </tr>
                                <?php endforeach; ?>
                            <tfoot>
                                <tr>
                                    <th><?= __('ID'); ?></th>
                                    <th><?= __('Game ID'); ?></th>
                                    <th><?= __('Game Key'); ?></th>
                                    <th><?= __('Name'); ?></th>
                                    <th class="filter"><?= __('Category'); ?></th>
                                    <th class="filter"><?= __('Type'); ?></th>
                                    <th class="filter"><?= __('Pay Lines'); ?></th>
                                    <th class="filter"><?= __('Reels'); ?></th>
                                    <th><?= __('Image'); ?></th>
                                    <th class="filter"><?= __('Branded'); ?></th>
                                    <th class="filter"><?= __('Jackpot'); ?></th>
                                    <th class="filter"><?= __('Free Spins'); ?></th>
                                    <th class="filter"><?= __('Fun Play'); ?></th>
                                    <th class="filter"><?= __('Mobile'); ?></th>
                                    <th class="filter"><?= __('Desktop'); ?></th>
                                    <th class="filter"><?= __('New'); ?></th>
                                    <th class="filter"><?= __('Active'); ?></th>
                                    <th><?= __('Actions'); ?></th>
                                </tr>
                            </tfoot>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
} else {
    echo __('<br>No games found.');
}
?>






<!--
<div class="container-fluid">
    <div class="row-fluid"><div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __('Create %s', __($singularName)))))); ?></div></div>
    <div id="page" class="dashboard">
    
       
    </div>
</div>-->

<script>




//    $(document).ready(function () {
//            function get_editable() {
//
//        $.fn.editable.defaults.mode = 'inline';
////                                                $.fn.editable.defaults.true = false;
//        $.fn.editable.defaults.url = '/post';
//        $.fn.editable.defaults.type = 'text';
//
//        $('.pay-lines').editable({
//            type: 'number',
//        });
//        //$('td').editable();
//        $('.category_id').editable({
//            prepend: "Select category",
//            source: [{value: 1, text: 'ahaa'}, {value: 2, text: 'ohooo'}]
//        }).on('shown', function (e, editable) {
//            editable.input.$input.select2({
//                width: 100,
//                minimumResultsForSearch: Infinity
//            });
//            editable.input.$input.select2('val', editable.input.$input.val());
//        });
//        $('.type').editable({
//            prepend: "Select type",
//            source: [{value: 1, text: 'HTML5'}, {value: 2, text: 'Flash'}]
//        }).on('shown', function (e, editable) {
//            editable.input.$input.select2({
//                width: 80,
//                minimumResultsForSearch: Infinity
//            });
//            editable.input.$input.select2('val', editable.input.$input.val());
//        });
//
//
//
//
//    }
//    $("#data-table").DataTable({
//        drawCallback: function (settings) {
//            get_editable();
//        }
//    });
//        






//        $(document).on('click', '.adjust-game', function (e) {
//            var $_this = $(this),
//                    _gid = $_this.closest('tr').data('id'),
//                    _name = $_this.closest('tr').find('td .name-value').val(),
//                    _category = $_this.closest('tr').find('td .category-value').val(),
//                    _paylines = $_this.closest('tr').find('td .paylines-value').val(),
//                    _reels = $_this.closest('tr').find('td .reels-value').val(),
//                    _freespins = $_this.closest('tr').find('td input.checkfreespins:checkbox').is(':checked') ? 1 : 0,
//                    _img = $_this.closest('tr').find('td .image-value').val(),
//                    _branded = $_this.closest('tr').find('td input.checkbranded:checkbox').is(':checked') ? 1 : 0,
//                    _mobile = $_this.closest('tr').find('td input.checkmobile:checkbox').is(':checked') ? 1 : 0,
//                    _desktop = $_this.closest('tr').find('td input.checkdesktop:checkbox').is(':checked') ? 1 : 0,
//                    _funplay = $_this.closest('tr').find('td input.checkfunplay:checkbox').is(':checked') ? 1 : 0,
//                    _new = $_this.closest('tr').find('td input.checknew:checkbox').is(':checked') ? 1 : 0,
//                    _active = $_this.closest('tr').find('td input.checkactive:checkbox').is(':checked') ? 1 : 0;
//
//            $.ajax({
//                url: '/admin/booongo/booongo_admin/editGame/?id=' + _gid + '&name=' + _name + '&category=' + _category + '&paylines=' + _paylines +
//                        '&reels=' + _reels + '&freespins=' + _freespins + '&branded=' + _branded + '&mobile=' + _mobile + '&desktop=' + _desktop + '&image=' + _img +
//                        '&funplay=' + _funplay + '&new=' + _new + '&active=' + _active,
//                success: function (data) {
//                    var result = JSON.parse(data);
//                    $_this.html('<i class="icon-check" style="margin-right:5px;"></i>' + result.msg);
//                    $_this.removeClass('btn-primary');
//                    if (result.status === "success") {
//                        $_this.addClass('btn-success');
//                    } else {
//                        $_this.addClass('btn-danger');
//                    }
//                    setTimeout(function () {
//                        $_this.addClass('btn-primary');
//                        $_this.removeClass('btn-success');
//                        $_this.removeClass('btn-danger');
//                        $_this.html('<i class="icon-white icon-upload" style="margin-right:5px;"></i>Save');
//                    }, 3000);
//                },
//                error: function (data) {
//                    console.log(result);
//                    var result = JSON.parse(data);
//
//                    $_this.html('<i class="icon-white icon-off" style="margin-right:5px;"></i>' + result.msg);
//                    $_this.removeClass('btn-primary');
//                    $_this.addClass('btn-danger');
//                }
//            });
//        });
//    });


</script>



