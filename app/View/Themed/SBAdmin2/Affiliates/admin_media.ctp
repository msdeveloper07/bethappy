<style>
.tab-content {
    overflow:auto;
}
    
.fa {
    display: inline-block;
    font-family: FontAwesome;
    font-style: normal;
    font-weight: normal;
    line-height: 1;
}

.list-group-item {
    position: relative;
    display: block;
    padding: 10px 15px;
    margin-bottom: -1px;
    background-color: #fff;
    border: 1px solid #ddd;
}

.row:before,
.row:after {
    display: table;
    content: " ";
}
.col-leftright {
    position: relative;
    min-height: 1px;
    padding-right: 15px;
    padding-left: 30px;
}

.list-group {
    padding-left: 0;
    margin-bottom: 20px;
}

.badge {
    display: inline-block;
    min-width: 10px;
    padding: 3px 7px;
    font-size: 12px;
    font-weight: bold;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    background-color: #999;
    border-radius: 10px;
}

.list-group-item:last-child {
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
    margin-bottom: 0;
}
    
.list-group-item > .badge {
    float: right;
}
.list-group-item > .badge + .badge {
    margin-right: 5px;
}
</style>

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
                        <?php //echo $this->element('search');?>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="table table-custom">                                    
                                    <div class="tab-content">
                                        <div class="box" style="overflow:auto">
                                            <div class="box-header well">
                                                <h2><i class="icon-list-alt"></i><?= __('Media Upload'); ?></h2>
                                                <div class="box-icon"><a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-down"></i></a></div>
                                            </div>
                                        
                                            <?= $this->MyForm->create('affiliatemedia', array('type' => 'file')); ?>
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th><?= __('Description'); ?></th>
                                                    <th><?= __('Value'); ?></th>
                                                </tr>
                                                <tr>
                                                    <td><?= __('File'); ?></td>
                                                    <td>                                                    
                                                        <?= $this->Form->input('file', array('type' => 'file','label' => false)); ?>
                                                        <span style="font-size: x-small; font-style:italic; padding-left: 10px;">
                                                          <?= __('Permitted Files: JPEG, GIF, SWF'); ?>  
                                                        </span>
                                                    </td>
                                                    </tr> 
                                            </table>    
                                            <?php 
                                                echo $this->MyForm->submit(__('Submit', true), array('class' => 'btn', 'style' => 'margin-top: 15px;'));
                                                echo $this->MyForm->end();
                                            ?>
                                        </div>
                                        
                                        <div class="box" style="overflow:auto">
                                            <div class="box-header well">
                                                <h2><i class="icon-list-alt"></i><?= __('Folders'); ?></h2>
                                                <div class="box-icon"><a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-down"></i></a></div>
                                            </div>

                                            <div class="row">
                                                <?php if(empty($folderlist)): ?> 
                                                    <div class="col-lg-2 col-leftright">      
                                                        <h3><?= __('Folder List'); ?></h3>
                                                        <ul class="list-group">               
                                                            <li class="list-group-item"><a style="font-size: 14px;" href="<?= $this->Html->URL(array('controller' => 'Affiliates', 'action' => 'media')); ?>"><i  style="font-size: 16px;margin-right: 7px;" class="icon-step-backward"></i> <?= __('Back'); ?></a></li>
                                                        </ul>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="col-lg-4 col-leftright">
                                                        <h2><?= __('Folder List'); ?></h2>
                                                        <ul class="list-group">
                                                        <?php foreach($folderlist as $folder):?>
                                                            <li class="list-group-item"><a style="font-size: 13px;" href="<?= $this->Html->URL(array('controller' => 'Affiliates', 'action' => 'media', $folder, 'view')); ?>"><i  style="font-size: 16px;margin-right: 7px;" class="fa icon-folder-open"></i> <?= $folder;?></a><span class="badge"><?= $folder['counter']; ?></span></li>
                                                        <?php endforeach ;?>
                                                        </ul>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6 col-leftright">
                                                  <h3><?= __('File List'); ?></h3>
                                                    <br/>
                                                    <?php if(!empty($filelist)): ?>  
                                                        <form>
                                                            <table class="table table-bordered table-striped">
                                                                <tr>
                                                                    <th><?= __('URL'); ?></th>
                                                                    <th><?= __('Image'); ?></th>
                                                                    <th><?= __('Action'); ?></th>
                                                                </tr>
                                                                <?php foreach($filelist as $file):?>
                                                                <tr>
                                                                    <td>                                                    
                                                                        /img/banners/<?= $current_directory;?>/<?= $file ?>
                                                                    </td>                                                
                                                                    <td>
                                                                        <?php if (strtoupper(pathinfo($file, PATHINFO_EXTENSION))=="JPG" || strtoupper(pathinfo($file, PATHINFO_EXTENSION))=="GIF"){ ?>
                                                                            <img style="max-width:400px" src="/img/banners/<?= $current_directory;?>/<?= $file ?>" />
                                                                        <?php }else{ ?>                                                
                                                                            //swf
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td><?php 
                                                                        echo $this->MyHtml->link('Delete', array('controller' => Affiliates, 'action' => 'media', $current_directory, 'delete', $file), array('class' => isset($action['class']) ? $action['class'] : 'btn btn-mini btn-danger'), 'Are you sure?');
                                                                        echo $this->MyHtml->link('Access', array('controller' => AffiliateMedia, 'action' => 'access', $current_directory, $file), array('class' => isset($action['class']) ? $action['class'] : 'btn btn-mini btn-primary', 'style' => 'margin-left: 5px;'));
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                                <?php endforeach ;?>
                                                            </table> 
                                                        </form>
                                                    <?php else: ?>
                                                        <p><?= __('No media files available in this directory.'); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>



                                         <!--   
                                            <hr>
                                           <h2><?=__('Media List');?></h2>
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th><?= __('URL'); ?></th>
                                                    <th><?= __('Image'); ?></th>
                                                    <th><?= __('Action'); ?></th>
                                                </tr>
                                                <?php foreach($filelist as $file):?>
                                                <tr>
                                                    <td>                                                    
                                                       /img/banners/<?= $current_directory;?>/<?= $file ?>
                                                    </td>                                                
                                                    <td>
                                                        <?php if (strtoupper(pathinfo($file, PATHINFO_EXTENSION))=="JPG" || strtoupper(pathinfo($file, PATHINFO_EXTENSION))=="GIF"){ ?>
                                                            <img style="max-width:400px" src="/img/banners/<?= $current_directory;?>/<?= $file ?>" />
                                                        <?php }else{ ?>                                                
                                                            //swf
                                                        <?php } ?>
                                                    </td>
                                                    <td><?php 
                                                        $delete = __('Are you sure?');
                                                        echo $this->MyHtml->link('x', array('controller' => 'Affiliates', 'action' => 'media',$current_directory,'delete',$file), array('class' => isset($action['class']) ? $action['class'] : ''), $delete);?>
                                                    </td>
                                                </tr>
                                                <?php endforeach ;?>
                                            </table> 
                                           -->
                                        </div>
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

