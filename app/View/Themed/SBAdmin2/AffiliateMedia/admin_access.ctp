<style>
    .checker {
        display:inline-block;
    }
    
    .checker ~ label {
        display: inline-block;
        top: 4px;
        left: -14px;
        position: relative;
    }
</style>

<div id="affiliate-add-modal" class="modal fade" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><?=__("Add Affiliate to Media's Access Control");?></h4>
            </div>
            <div class="modal-body form">
                <div class="form-horizontal">                                                        
                    <div class="form-group">
                        <label class="control-label col-md-4"><?=__('Affiliate Custom ID:');?></label>
                        <div class="col-md-8"><input id="aff-cid" style="margin-left: 10px;" type="text" class="form-control" /></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="save-acl"><?=__('Save');?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=__('Cancel');?></button>
            </div>
        </div>
    </div>
</div>

<div class="space10 visible-phone"></div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => __('List %s', __($pluralName)), 1 => __($singularName))))); ?></div>
    </div>
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                    <div class="tab-content"> 
                                        <div class="cent-txt txt-pad">                                            
                                            <h3><?= __('Affiliate Media Access Control'); ?></h3>
                                            <br />
                                            <?= $this->MyForm->input('is_global', array('label' => __('Global Access', true), 'type' => 'checkbox', 'name' => 'global', 'id' => 'global-access', 'checked' => $is_global)); ?>                                        
                                        </div>
                                        
                                        <br />
                                        <br />
                                        
                                        <div id="affiliate-section">                                        
                                            <h3><?= __('Affiliate List'); ?> <?= $this->MyHtml->link('Add Affiliate', '#affiliate-add-modal', array('class' => 'btn btn-primary pull-right', 'data-toggle' => 'modal', 'role' => 'button')); ?></h3>
                                        
                                            <br />

                                            <div class="cent-txt txt-pad">
                                                <table id="aff-table" class="table table-bordered table-striped">
                                                    <tr>
                                                        <th><?= __('ID'); ?></th>
                                                        <th><?= __('CustomID'); ?></th>
                                                        <th><?= __('ParentID'); ?></th>
                                                        <th><?= __('UserID'); ?></th>
                                                        <th><?= __('Username'); ?></th>
                                                        <th><?= __('Actions'); ?></th>
                                                    </tr>
                                                    <?php foreach($affiliates as $affiliate) : ?>
                                                    <tr>
                                                        <td><?= $affiliate['id']; ?></td>
                                                        <td><?= $this->Html->link($affiliate['affiliate_custom_id'], array('controller' => 'affiliates', 'action' => 'view', $affiliate['id'])); ?></td>
                                                        <td><?= $affiliate['parent_id']; ?></td>
                                                        <td><?= $affiliate['user_id']; ?></td>
                                                        <td><?= $this->Html->link($affiliate['username'], array('controller' => 'users', 'action' => 'view', $affiliate['user_id'])); ?></td>
                                                        <td><?= $this->MyHtml->link('Remove', 'javascript:;', array('class' => 'btn btn-mini btn-danger remove-aff', 'data-id' => $affiliate['acl_id'])); ?></td>
                                                     </tr>
                                                    <?php endforeach; ?>
                                                </table>
                                            </div>                                        
                                        </div>
                                    </div> 
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
    $(document).ready(function() {
        var filePath = '<?= $path; ?>';
        
        function showAff() {
            $('#global-access').is(':checked') ? $('#affiliate-section').hide() : $('#affiliate-section').show();
        };
    
        $('#global-access').change(function() {
            if(confirm('Are you sure you want to change media\'s global access status?')) {
                $.ajax({
                    url: '/admin/AffiliateMedia/acl_global',
                    method: 'POST',
                    data: { 
                        'path' : filePath,
                        'is_global' : $('#global-access').is(':checked')
                    },
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function (data) {
                        showAff();
                        console.log(data);
                    },
                    error: function (error) {
                    }
                });
            }
        });
                
        $(document).on('click', '.remove-aff', function(e) {
            var _this = this;
            
            if(confirm('Are you sure you want to remove affiliate from media\'s access control?')) {  
                $.ajax({
                    url: '/admin/AffiliateMedia/acl_remove',
                    method: 'POST',
                    data: { 'id' : $(_this).data('id') },
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function (data) {
                        if(data.error) {
                            alert(data.error);
                        } else {
                            $(_this).parents('tr').remove();
                        }
                    },
                    error: function (error) {
                        alert('Action failed');
                    }
                });
            }
        });
        
        $('#save-acl').click(function(e) {
            $.ajax({
                url: '/admin/AffiliateMedia/acl_add',
                method: 'POST',
                data: { 
                    'path' : filePath,
                    'aff_cid' : $('#aff-cid').val()
                },
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (data) {
                    $('#affiliate-add-modal').modal('hide');
                    
                    if(data.error) {
                        alert(data.error);
                    } else {
                        $('#aff-table').append('<tr><td>' + data['id'] + '</td>' +
                                                '<td>' + data['affiliate_custom_id'] + '</td>' +
                                                '<td>' + data['user_id'] + '</td>' +
                                                '<td>' + data['parent_id'] + '</td>' +
                                                '<td>' + data['username'] + '</td>' +
                                                '<td><a href="javascript:;" class="btn btn-mini btn-danger remove-aff" data-id="' + data['acl_id'] + '">Remove</a></td></tr>');
                    }
                },
                error: function (error) {
                    $('#affiliate-add-modal').modal('hide');
                }
            });
        });
        
        showAff();
    });
</script>