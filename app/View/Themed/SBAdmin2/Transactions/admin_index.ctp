<style type="text/css">
    .chart-title {
        float:left;
    }
    
    .deposits-charts {
        float: left;
        width: 300px;
    }

    form#search-form {
        float: left;
    }
    
    form#search-form,
    .search-inputs,
    .input,
    .search-inputsm-ctrl-medium,
    .date-picker{
        float:left;
        padding:5px;
    }
    
    #search-form button[type="submit"] {
        float: left;
        margin: 25px 0 0 10px;
        clear: both;
        position: relative;
        background: #2fade7;
        color: #FFFFFF;
        left: 185px;
    }
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __($singularName), 2 => __('List %s', __($pluralName)))))); ?></div>
    </div>
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget"><div class="widget-body"><?= $this->element('charts/pie', array('placeholderClass' => 'transactions-charts', 'chartsData' => $chartsData));?></div></div>
            </div>
        </div>
        <hr>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <!--search begin-->
                        <?php
                            if(!isset($search_fields) || !is_array($search_fields)) return;

                            echo $this->Form->create('transactions', array('type' => 'file', 'id' => 'search-form', 'action' => '/'));

                            foreach($search_fields AS $i => $field) {
                                if(!is_array($field)) $search_fields[$i] = array($field);

                                $class = isset($field['class']) ? $field['class'] : null;

                                $search_fields[$i]['div'] = array('class' => 'search-inputs'. $class .'' );
                                $search_fields[$i]['required'] = false;
                            }

                            echo $this->Form->inputs($search_fields, null, array('fieldset' => false, 'legend' => false));
                            echo $this->Form->button(__('Search', true), array('type' => 'submit', 'id' => 'search_button', 'class' => 'btn'));
                            echo $this->Form->end();
                        ?>
                        <!--search end-->

                        <div class="row-fluid">
                            <div class="span12">
                                <div class="table table-custom">
                                        <?= $this->element('tabs');?>
                                    <div class="tab-content"> 
                                        <?= $this->element('list', array('title' => 'Payment Transactions'));?>
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