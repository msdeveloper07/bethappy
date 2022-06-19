<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __('View %s Revision Notes', $singularName))))); ?>
        </div>
    </div>
    <div id="page" class="dashboard">
        <?php echo $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="table table-custom">
                                      <?php echo $this->element('usertabs'); ?>
                                    <div class="tab-content">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th><?php echo __('Revision');?></th>
                                                    <th><?php echo __('Status');?></th>
                                                    <th><?php echo __('Actions');?></th>
                                                </tr>
                                            </thead>
                                            <tbody> 
                                                <?php 
                                                foreach($data as $notes):
                                                         foreach($notes['Couchlog']['_revs_info'] as $revisions):?>
                                                             <tr>
                                                                 <td><?php echo $revisions['rev'];?></td>
                                                                 <td><?php echo $revisions['status'];?></td>
                                                                 <td><?php echo $this->MyHtml->link(__('View'), array('controller' => 'users', 'action' => 'viewnotesrev', $notes['Couchlog']['id'],$revisions['rev']), array('class' => 'btn btn-mini btn-info'));?></td>
                                                             </tr>
                                                     <?php endforeach;?>
                                                 <?php endforeach;?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="space10 visible-phone"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>