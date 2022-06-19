<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => __('List %s', $pluralName), 1 => __('View %s Note Revisions', $singularName))))); ?>
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
                                            <thead></thead>
                                            <tbody> 
                                                <tr>
                                                    <td>
                                                        <?php
                                                            echo "ID: ".$data['Couchlog']['id']."<br>";
                                                            echo "Revision: ".$data['Couchlog']['rev']."<br>";
                                                            echo "Userid: ".$data['Couchlog']['userid']."<br>";
                                                            echo "Author: ".$data['Couchlog']['author']."<br>";
                                                            echo "Created: ". date("d-m-Y H:i:s",$data['Couchlog']['timestamp'])."<br><hr>";
                                                            echo "Note:<br>".$data['Couchlog']['transaction']."<br>";                                            
                                                        ?>
                                                    </td>
                                                    <td></td>
                                                </tr>         
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