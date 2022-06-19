<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $pluralName, 2 => __('List Categories'))))); ?>
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
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                    <div class="tab-content">
                                        <?php $options = array(
                                            'url' => array('controller' => 'risks'),
                                            'inputDefaults' => array('label' => false,'div' => false)
                                        );
                                        echo $this->Form->create('UserCategory', $options); ?>
                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <th><?php echo __('Category ID'); ?></th>
                                                <th><?php echo __('Category'); ?></th>
                                                <th><?php echo __('Lowest stake');?></th>
                                                <th><?php echo __('Highest stake'); ?></th>
                                            </tr>
                                            <?php foreach ($data as $row): ?>
                                                <tr>
                                                    <td><?php echo $row['UserCategory']['id']; ?></td>
                                                    <td><span style="color:<?php echo $row['UserCategory']['color']; ?>"><?php echo $row['UserCategory']['name'];?></span> (<?php echo $row['UserCategory']['description']; ?>)</td>
                                                    <td><input name="data[Sport][<?php echo $row['Sport']['id']; ?>][min_bet]" type="text" value="<?php if($row['Sport']['min_bet'] != 0): ?><?php echo $row['Sport']['min_bet']; ?><?php endif; ?>" placeholder="<?php echo __('No limits'); ?>" /></td>
                                                    <td><input name="data[Sport][<?php echo $row['Sport']['id']; ?>][max_bet]" type="text" value="<?php if($row['Sport']['max_bet'] != 0): ?><?php echo $row['Sport']['max_bet']; ?><?php endif; ?>" placeholder="<?php echo __('No limits'); ?>" /></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </table>
                                        <?php echo $this->element('paginator'); ?>
                                        <?php echo $this->Form->submit(__('Save', true), array('class' => 'btn')); ?>
                                        <?php echo $this->Form->end(); ?>
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