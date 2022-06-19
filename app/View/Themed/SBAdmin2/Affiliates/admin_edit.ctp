<style>
    #aff_id_code_gen_btn {
        margin-left: 10px;
        margin-top: 5px;
        vertical-align: top;
    }
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __($pluralName), 2 => __('Add/Edit %s', __($singularName)))))); ?></div>
    </div>
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="table table-custom">
                                            <ul class="nav nav-tabs">
                                                <li><a href="<?= $this->Html->URL(array('plugin' => false, 'controller' => 'Affiliates', 'action' => 'view', $this->request->pass[0]),  array('class' => 'btn btn-mini btn-info','style'=>'color:#000')); ?>"><?=__('View');?></a>
                                                <li class="active"><a href="<?= $this->Html->URL(array('plugin' => false, 'controller' => 'Affiliates', 'action' => 'edit', $this->request->pass[0]),  array('class' => 'btn btn-mini btn-info','style'=>'color:#000')); ?>"><?=__('Edit');?></a>
                                            </ul>    
                                            <?=__('System will alert once user deposited same or higher amount of money his/her account.');?>
                                    <div class="tab-content">
                                        <?php //print_r($data);?>
                                <?= $this->MyForm->create('Affiliate');?>        
                                  <table class="table table-bordered table-striped">
                                            <tr>
                                                <th><?= __('Description'); ?></th>
                                                <th><?= __('Value'); ?></th>
                                            </tr>
                                            <tr>
                                                <td><?= __('Parent'); ?></td>
                                                <td>
                                                    <?= $this->Form->input('parent_id', array('options' => $affiliate_array,'label' => false,'default' => $data['Affiliate']['parent_id'])); ?>
                                                    <span style="font-size: x-small; font-style:italic; padding-left: 10px;">
                                                    </span>
                                                </td>
                                                </tr>
                                            <tr>
                                                <td><?= __('Created'); ?></td>
                                                <td><?= $this->Form->input('created', array('label' => false,'value' => $data['Affiliate']['created'])); ?>
                                                    <span style="font-size: x-small; font-style:italic; padding-left: 10px;">
                                                    </span>
                                                </td>
                                                </tr>
                                            <tr>
                                                <td><?= __('Modified'); ?></td>
                                                <td><?= $this->Form->input('modified', array('label' => false,'value' => $data['Affiliate']['modified'])); ?>
                                                    <span style="font-size: x-small; font-style:italic; padding-left: 10px;">
                                                    </span>
                                                </td>
                                                </tr>
                                            <tr>
                                                <td><?= __('Percentage'); ?></td>
                                                <td><?= $this->Form->input('percentage', array('label' => false,'value' => $data['Affiliate']['percentage'])); ?>
                                                    <span style="font-size: x-small; font-style:italic; padding-left: 10px;">
                                                    </span>
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                                <td><?= __('Live Casino Percentage'); ?></td>
                                                <td><?= $this->Form->input('lc_percentage', array('label' => false,'value' => $data['Affiliate']['lc_percentage'])); ?>
                                                    <span style="font-size: x-small; font-style:italic; padding-left: 10px;">
                                                    </span>
                                                </td>
                                            </tr>
                                             <tr>
                                                <td><?= __('Custom Affiliate ID'); ?></td>
                                                <td>
                                                    <?= $this->Form->input('affiliate_custom_id', array('type' => 'text','label' => false,'value' => $data['Affiliate']['affiliate_custom_id'], 'div' => false , 'style' => 'display: inline-block')); ?>
                                                    <a id="aff_id_code_gen_btn" class="btn btn-mini btn-primary"><?= __('Generate Code'); ?></a>
                                                    <span style="display: block;font-size: x-small; font-style:italic; padding-left: 10px;">
                                                    </span>
                                                </td>
                                            </tr>
                                            
                                        </table>    
                                        <?= $this->Form->hidden('id', array('value' => $data['Affiliate']['id'])); ?>
                                        
                                        <?php
                                        if ($data['Affiliate']['user_id']==""){
                                            $data['Affiliate']['user_id']=$this->request->pass[0];
                                        }
                                        echo $this->Form->hidden('user_id', array('value' => $data['Affiliate']['user_id'])); ?>
                                        <?= $this->MyForm->submit(__('Submit', true), array('class' => 'btn', 'style' => 'margin-top: 15px;'));?>
                                        <?= $this->MyForm->end();?>
                                    </div>
                                </div>
                                <!--END TABS-->
                            </div>
                            <div class="space10 visible-phone"></div>
                        </div>
                    </div>
                </div>
                <!-- END INLINE TABS PORTLET-->
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>

<script type="text/javascript">
    (function() {
        // generate code btn event 
        $(document.body).on('click', '#aff_id_code_gen_btn', function() {
            var _val = $('#affiliate_custom_id').val();
            
            if(_val.match(/^(\w)+-/)) {
                var parts = $('#affiliate_custom_id').val().split("-");
                
                if(parts.length > 0 && parts[0] !== "") {
                     $.ajax({
                        url: "/admin/Affiliates/generateaffcode/" + parts[0],
                        success: function(data) {
                            $('#affiliate_custom_id').val(JSON.parse(data));
                        },
                        error: function(err) {
                            console.log(err);
                        }
                    });
                }
            }
            else {
              console.log("pattern doesnt match");  
            }
        });        
    })();
</script>