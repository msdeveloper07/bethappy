<style type="text/css">
    td.game-edit-name:hover, td.game-edit-active:hover {
        background: #ffffb3!important;
        cursor: pointer;
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
                            <div class="span12">
                                <div class="table table-custom">
                                    <?= $this->element('tabs');?>
                                    <?php if ($data): ?>
                                        <div class="tab-content">
                                            <table class="table table-bordered table-striped box">
                                                <tr>
                                                    <th><?= __('ID'); ?></th>
                                                    <th><?= __('Alias'); ?></th>
                                                    <th><?= __('Brand'); ?></th>
                                                    <th><?= __('Icon'); ?></th>
                                                    <th><?= __('Game ID'); ?></th>
                                                    <th><?= __('Name'); ?></th>
                                                    <th><?= __('Active'); ?></th>
                                                    <th><?= __('Actions');?></th>
                                                </tr>
                                                <?php foreach ($data as $row) { ?>
                                                    <tr id="<?=$row['SlotGames']['gameid'];?>">
                                                        <td><?= $row['SlotGames']['gameid']; ?></td>
                                                        <td><?= $row['SlotGames']['alias']; ?></td>
                                                        <td><?= $row['SlotGames']['brand']; ?></td>
                                                        <td><input type="text" class="image-value" value="<?=$row['SlotGames']['icon']?>" data-image="<?=$row['SlotGames']['icon']?>" /></td>
                                                        <td><?= $row['SlotGames']['gameid']; ?></td>
                                                        <td><input type="text" class="name-value" value="<?=$row['SlotGames']['name']?>" /></td>
                                                        <td><input class="checkactive" type="checkbox" <?= ($row['SlotGames']['active'] == 1)?'checked':"";?> /></td>
                                                        <td style="text-align: right"><a class="adjust-game btn btn-mini btn-primary"><i class="icon-white icon-upload" style="margin-right:5px;"></i><?=__('Save');?></a></td>
                                                    </tr>
                                                <?php } ?>
                                           </table>
                                        </div>
                                    <?php else: ?>
                                        <?=__('No data found.');?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).on('click', '.adjust-game', function(e) {
        var $_this  = $(this),
            _gid        = $_this.closest('tr').attr('id'),
            _active     = $_this.closest('tr').find('td input:checkbox').is(':checked'),
            _name       = $_this.closest('tr').find('td .name-value').val(),
            _img        = $_this.closest('tr').find('td .image-value').val();

            if (!_active) {
                _active = 0;
            } else {
                _active = 1;
            }
            
        $.ajax({
            url: '/admin/mr_slotty/admin/editgame/?id='+_gid+'&active='+_active+'&name='+_name+'&image='+_img,
            success: function(data) {
                var result = JSON.parse(data);
                $_this.html('<i class="icon-check" style="margin-right:5px;"></i>'+result.msg);
                $_this.removeClass('btn-primary');
                if (result.status === "success") {
                    $_this.addClass('btn-success');
                } else {
                    $_this.addClass('btn-danger');
                }
                setTimeout(function(){
                    $_this.addClass('btn-primary');
                    $_this.removeClass('btn-success');
                    $_this.removeClass('btn-danger');
                    $_this.html('<i class="icon-white icon-upload" style="margin-right:5px;"></i>Save');
                }, 3000);
            },
            error: function(data) {
                var result = JSON.parse(data);
                console.log(result);
                $_this.html('<i class="icon-white icon-off" style="margin-right:5px;"></i>'+result.msg);
                $_this.removeClass('btn-primary');
                $_this.addClass('btn-danger');
            }
        });
    });
    
    
    
    
    
    
    
    
    
    $(document.body).on('click', '.tbheader', function(ev) { 
        ev.stopPropagation();
        ev.preventDefault();
        
        var id = $(this).attr('id');
        var $box = $(this).parents('.box');

        if($box.find('.box-content.tb-'+id).is(':visible')) {
            $box.find('.box-content.tb-'+id).hide();
            $(this).find('.box-icon .btn-minimize i').removeClass('icon-chevron-up');
            $(this).find('.box-icon .btn-minimize i').addClass('icon-chevron-down');
        } else {
            $box.find('.box-content.tb-'+id).show();
            $(this).find('.box-icon .btn-minimize i').removeClass('icon-chevron-down');
            $(this).find('.box-icon .btn-minimize i').addClass('icon-chevron-up');
        }
    });
</script>