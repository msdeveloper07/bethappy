<style type="text/css">
    .popbox {
        display: none;
        background: -moz-linear-gradient(top,  rgba(0,0,0,0.65) 0%, rgba(0,0,0,0.65) 100%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(0,0,0,0.65)), color-stop(100%,rgba(0,0,0,0.65))); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(top,  rgba(0,0,0,0.65) 0%,rgba(0,0,0,0.65) 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top,  rgba(0,0,0,0.65) 0%,rgba(0,0,0,0.65) 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(top,  rgba(0,0,0,0.65) 0%,rgba(0,0,0,0.65) 100%); /* IE10+ */
        background: linear-gradient(to bottom,  rgba(0,0,0,0.65) 0%,rgba(0,0,0,0.65) 100%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#a6000000', endColorstr='#a6000000',GradientType=0 ); /* IE6-9 */
        border: 1px solid #4D4F53;
        border-radius:7px;
        opacity:-1px;
        z-index: 99999;
        position: absolute;
        padding-top:7px;
        padding-bottom:3px;
        width: 300px;
        box-shadow: 0px 0px 5px 0px rgba(164, 164, 164, 1);
    }

    .litable {
        list-style:none;
        color:#fff;
    }
    .popper{
        text-decoration: underline;
        cursor: pointer;
    }

    .paylines-value, .reels-value, .freespins-value, .category-value{
        max-width:50px;
    }
</style>

<div class="container-fluid">
    <div class="row-fluid"><div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __('Create %s', __($singularName)))))); ?></div></div>
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <div class="row-fluid">

                            <div class="row-fluid">
                                <div class="pull-left">
                                    <?= $this->Html->link(__('Get New'), array('plugin' => 'kiron', 'controller' => 'kiron_admin', 'action' => 'getNewGames'), array('class' => 'btn btn-primary')); ?>
                                    <?= $this->Html->link(__('Sync New'), array('plugin' => 'kiron', 'controller' => 'kiron_admin', 'action' => 'syncNewGames'), array('class' => 'btn btn-primary')); ?>
                                </div>
                                <div class="pull-right">
                                    <?= $this->Html->link(__('Update All'), array('plugin' => 'kiron', 'controller' => 'kiron_admin', 'action' => 'updateGames'), array('class' => 'btn btn-primary')); ?>
                                    <?= $this->Html->link(__('Sync All'), array('plugin' => 'kiron', 'controller' => 'kiron_admin', 'action' => 'syncGames'), array('class' => 'btn btn-primary')); ?>

                                </div>
                            </div>
                            <br>
                            <div class="span12">

                                <div class="table table-custom">

                                    <?php if (!empty($games)) { ?>
                                        <div class="pull-right"><?= __('Total games: ' . count($games)); ?></div>
                                        <div class="clearfix"></div>

                                        <div class="tab-content">
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th><?= __('ID'); ?></th>
                                                    <th><?= __('Game ID'); ?></th>
                                                    <th><?= __('Name'); ?></th>
                                                    <th><?= __('Category'); ?></th>
                                                    <th><?= __('Type'); ?></th>
                                                    <th><?= __('Paylines'); ?></th>
                                                    <th><?= __('Reels'); ?></th>
                                                    <th><?= __('Image'); ?></th>
                                                    <th><?= __('Branded'); ?></th>
                                                    <th><?= __('Free Spins'); ?></th>
                                                    <th><?= __('Mobile'); ?></th>
                                                    <th><?= __('Desktop'); ?></th>
                                                    <th><?= __('Fun Play'); ?></th>
                                                    <th><?= __('New'); ?></th>
                                                    <th><?= __('Active'); ?></th>
                                                    <th><?= __('Actions'); ?></th>
                                                </tr>
                                                <?php foreach ($games as $row): ?>
                                                    <tr data-id="<?= $row['KironGames']['id']; ?>">
                                                        <td class="popper" data-popbox="pop1_<?= $row['KironGames']['id']; ?>"><?= $row['KironGames']['id']; ?></td>
                                                        <td><?= $row['KironGames']['game_id']; ?></td>
                                                        <td><input type="text" class="name-value" value="<?= $row['KironGames']['name'] ?>"/></td>
                                                        <td><input type="text" class="category-value" value="<?= $row['KironGames']['category'] ?>" /></td>
                                                        <td><?= $row['KironGames']['type']; ?></td>
                                                        <td><input type="text" class="paylines-value" value="<?= $row['KironGames']['paylines'] ?>"/></td>
                                                        <td><input type="text" class="reels-value" value="<?= $row['KironGames']['reels'] ?>" /></td>
                                                        <td><input type="text" class="image-value" value="<?= $row['KironGames']['image'] ?>" data-image="<?= $row['KironGames']['image'] ?>" /></td>
                                                        <!--<td><input type="text" class="branded-value" value="<?= $row['KironGames']['branded'] ?>" /></td>-->
                                                        <td><input class="checkbranded" type="checkbox" <?= ($row['KironGames']['branded'] == 1) ? 'checked' : ""; ?> /></td>
                                                        <td><input class="checkfreespins" type="checkbox" <?= ($row['KironGames']['freespins'] == 1) ? 'checked' : ""; ?> /></td>
                                                        <td><input class="checkmobile" type="checkbox" <?= ($row['KironGames']['mobile'] == 1) ? 'checked' : ""; ?> /></td>
                                                        <td><input class="checkdesktop" type="checkbox" <?= ($row['KironGames']['desktop'] == 1) ? 'checked' : ""; ?> /></td>
                                                        <td><input class="checkfunplay" type="checkbox" <?= ($row['KironGames']['funplay'] == 1) ? 'checked' : ""; ?> /></td>
                                                        <td><input class="checknew" type="checkbox" <?= ($row['KironGames']['new'] == 1) ? 'checked' : ""; ?> /></td>
                                                        <td><input class="checkactive" type="checkbox" <?= ($row['KironGames']['active'] == 1) ? 'checked' : ""; ?> /></td>
                                                        <td style="text-align: right"><a class="adjust-game btn btn-mini btn-primary"><i class="icon-white icon-upload" style="margin-right:5px;"></i><?= __('Save'); ?></a></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                        </div>
                                        <?php
                                    } else {
                                        echo __('<br>No games found.');
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $(document).on('click', '.adjust-game', function (e) {
            var $_this = $(this),
                    _gid = $_this.closest('tr').data('id'),
                    _name = $_this.closest('tr').find('td .name-value').val(),
                    _category = $_this.closest('tr').find('td .category-value').val(),
                    _paylines = $_this.closest('tr').find('td .paylines-value').val(),
                    _reels = $_this.closest('tr').find('td .reels-value').val(),
                    _freespins = $_this.closest('tr').find('td input.checkfreespins:checkbox').is(':checked') ? 1 : 0,
                    _img = $_this.closest('tr').find('td .image-value').val(),
                    _branded = $_this.closest('tr').find('td input.checkbranded:checkbox').is(':checked') ? 1 : 0,
                    _mobile = $_this.closest('tr').find('td input.checkmobile:checkbox').is(':checked') ? 1 : 0,
                    _desktop = $_this.closest('tr').find('td input.checkdesktop:checkbox').is(':checked') ? 1 : 0,
                    _funplay = $_this.closest('tr').find('td input.checkfunplay:checkbox').is(':checked') ? 1 : 0,
                    _new = $_this.closest('tr').find('td input.checknew:checkbox').is(':checked') ? 1 : 0,
                    _active = $_this.closest('tr').find('td input.checkactive:checkbox').is(':checked') ? 1 : 0;

            $.ajax({
                url: '/admin/kiron/kiron_admin/editGame/?id=' + _gid + '&name=' + _name + '&category=' + _category + '&paylines=' + _paylines +
                        '&reels=' + _reels + '&freespins=' + _freespins + '&branded=' + _branded + '&mobile=' + _mobile + '&desktop=' + _desktop + '&image=' + _img +
                        '&funplay=' + _funplay + '&new=' + _new + '&active=' + _active,
                success: function (data) {
                    var result = JSON.parse(data);
                    $_this.html('<i class="icon-check" style="margin-right:5px;"></i>' + result.msg);
                    $_this.removeClass('btn-primary');
                    if (result.status === "success") {
                        $_this.addClass('btn-success');
                    } else {
                        $_this.addClass('btn-danger');
                    }
                    setTimeout(function () {
                        $_this.addClass('btn-primary');
                        $_this.removeClass('btn-success');
                        $_this.removeClass('btn-danger');
                        $_this.html('<i class="icon-white icon-upload" style="margin-right:5px;"></i>Save');
                    }, 3000);
                },
                error: function (data) {
                    console.log(result);
                    var result = JSON.parse(data);

                    $_this.html('<i class="icon-white icon-off" style="margin-right:5px;"></i>' + result.msg);
                    $_this.removeClass('btn-primary');
                    $_this.addClass('btn-danger');
                }
            });
        });
    });
</script>



