<style type="text/css">
/*    form#search-form {
        position: relative;
        right: 5px;
        width:100%;
    }

    form#search-form .search-inputs {
        float: left;
        padding-left: 5px;
    }

    #search-form .search-inputs:nth-child(5) {
        margin-left:0px!important;
    }
    form#search-form #search_button {
        float: left;
        margin: 25px 0 0 10px;
    }*/
</style>
<?= $this->Form->create($model, array('type' => 'file', 'id' => 'search-form', 'action' => 'index')); ?>
<div class="form-row align-items-center justify-content-flex-start flex-wrap">

    <?php
    if (!isset($search_fields) || !is_array($search_fields))
        return;



    foreach ($search_fields AS $i => $field) {
        if (!is_array($field))
            $search_fields[$i] = array($field);

//    $class = isset($field['class']) ? $field['class'] : null;

        $search_fields[$i]['div'] = array('class' => 'form-group mr-2');
        $search_fields[$i]['required'] = false;
    }

    echo $this->Form->inputs($search_fields, null, array('fieldset' => false, 'legend' => false));
    ?>
</div>
<?php
echo $this->Form->input('download', array('name' => 'data[Download]', 'value' => '0', 'id' => 'download', 'type' => 'hidden'));
echo $this->Form->button(__('Search', true), array('type' => 'submit', 'id' => 'search_button', 'class' => 'btn btn-primary mr-2'));
echo $this->Form->button(__('Reset'), array('type'=>'reset', 'class' => 'btn btn-danger'));
//echo $this->Form->button(__('Download', true), array('id' => "downloadbtn", 'class' => 'btn btn-danger', 'div' => false));
echo $this->Form->end();
?>
<script>
    $("#downloadbtn").click(function () {
        $("#download").val("1");
        $("#search_button").click();
    });
</script>