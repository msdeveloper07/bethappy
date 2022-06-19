<?php echo $this->Html->script('/assets/ace-builds-master/src-noconflict/ace.js'); ?>

<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $pluralName, 2 => __('IP %s', $pluralName))))); ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?php echo $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <!-- BEGIN INLINE TABS PORTLET-->
                <div class="widget">
                    <div class="widget-body">
                        <?php echo $this->element('search');?>
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                    <div class="tab-content">
                                        <?php echo $this->element('flash_message'); ?>
                                        <h3>IP List</h3>
                                        <p style="margin-top:10px;">Type the IP address you want to ban and the word deny next to it in the text area below (example 192.168.1.256 deny)</p>
                                        <br />
                                        <div class="row-fluid">
                                            <div id="editor" style="width:100%;height:600px;"><?php echo $contents; ?></div>
                                        </div>
                                        <br />
                                        <br />
                                        <div class="row-fluid">
                                            <?php 
                                                echo $this->Form->create('Setting'); 
                                                echo $this->Form->input('ips', array('type' => 'hidden')); 
                                                echo $this->Form->submit(__('Save', true), array('class' => 'btn', 'style' => 'float:right;'));
                                                echo $this->Form->end(); 
                                            ?>
                                        </div>
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

<script>
    var editor = ace.edit("editor");
    //editor.getSession().setMode("ace/mode/javascript");
    
    $('.submit').click(function(ev) {
        $('#SettingIps').val(editor.getValue());
    });
</script>