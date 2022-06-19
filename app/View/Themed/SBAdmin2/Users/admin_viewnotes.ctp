<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => __('List %s', $pluralName), 1 => __('View %s Notes', $singularName))))); ?></div>
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
                                     <?= $this->element('usertabs'); ?>
                                    <div class="tab-content">
                                        <?= $this->element('dialog',array('userid'=>$userid)); ?>	
                                        <a data-toggle="modal" href="#UserNotedialog" class="btn btn-primary btn-mini"><?=__('Add Note');?></a>
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th width="250" style="text-align: center"><?= __('ID');?></th>
                                                    <th><?= __('Date');?></th>
                                                    <th><?= __('Note');?></th>
                                                    <th><?= __('Author');?></th>
                                                    <th style="text-align: center"><?= __('Author Id');?></th>
                                                    <th style="text-align: center"><?= __('Actions');?></th>
                                                </tr>
                                                
                                            </thead>
                                            <tbody> 
                                                <?php foreach($data as $notes):?>
                                                    <tr>
                                                       <td style="text-align: center; border-top-color: #000"><?= $notes['value']['_id'];?></td>
                                                       <td style="border-top-color: #000"><?= $this->Beth->convertDate($notes['key'][1]);?></td>
                                                       <td style="border-top-color: #000;max-width:400px;word-wrap: break-word;"><?= $notes['value']['transaction'];?></td>
                                                       <td style="border-top-color: #000"><?= $notes['value']['author_name'];?></td>
                                                       <td style="text-align: center;border-top-color: #000"><?= $notes['value']['author'];?></td>
                                                       <td style="text-align: center;border-top-color: #000">
                                                            <?= $this->MyHtml->link(__('Edit'), array('controller' => 'users', 'action' => 'editnotes', $notes['id']), array('class' => 'btn btn-mini btn-info'));?> 
                                                            <?= $this->MyHtml->link(__('Revisions'), array('controller' => 'users', 'action' => 'viewnotesrev', $notes['id']), array('class' => 'btn btn-mini btn-success'));?> 
                                                            <?= $this->MyHtml->link(__('Delete'), array('controller' => 'users', 'action' => 'deletenotes', $notes['id']), array('class' => 'btn btn-mini btn-danger'), __('Are you sure?'));?>
                                                       </td>
                                                    </tr>
                                                    <?php foreach($notes['revisions'] as $rev):?>
                                                        <tr>
                                                            <td style="text-align: center">revision</td>
                                                            <td><?= $this->Beth->convertDate($rev['timestamp']);?></td>
                                                            <td style="max-width:400px;word-wrap: break-word;"><?= $rev['transaction'];?></td>
                                                            <td><?= $rev['author_name'];?></td>
                                                            <td style="text-align: center"><?= $rev['author'];?></td>
                                                            <td></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                 <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                        
                                    </div>
                                </div>
                                <!--END TABS-->
                            </div>
                            <div class="space10 visible-phone"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>