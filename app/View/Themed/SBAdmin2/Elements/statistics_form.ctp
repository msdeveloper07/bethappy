<?php echo $this->Form->create('Report'); ?>

    <div class="control-group" style="float:left; padding-right:15px">
        <label class="control-label">Country</label>
        <div class="controls">
            <div class="input-append">
                <?php  echo $this->MyForm->input('country', array('label' => false, 'options' => $country, 'type' => 'select', 'class' => 'inp11', 'style' => 'width: 260px; margin-top:-6px')); ?>
            </div>
        </div>
    </div>
    
    <div class="control-group" style="float:left; padding-right:15px">
        <label class="control-label">From</label>
        <div class="controls">
            <div class="input-append date">
                <?php  echo $this->MyForm->input("date_from", array('label' => false, 'data-date-format' => "yyyy-mm-dd", 'type' => 'text', 'value' => $date_from, 'class' => 'm-ctrl-medium datepicker', 'size' => '16', 'style' => 'width: 250px; margin-top:-5px')); ?>
            </div>
            
        </div>
    </div>


    <div class="control-group" style="float:left; padding-right:15px">
        <label class="control-label">To</label>
        <div class="controls">
            <div class="input-append date">
                <?php  echo $this->MyForm->input("date_to", array('label' => false, 'data-date-format' => "yyyy-mm-dd", 'type' => 'text', "value" => $date_to, 'class' => 'm-ctrl-medium datepicker', 'size' => '16', 'style' => 'width: 250px; margin-top:-5px')); ?>
            </div>
            
        </div>
    </div>

<?php echo $this->Form->submit(__('Show', true), array('class' => 'btn', 'style' => 'float:left; margin-top:17px')); ?>
<?php echo $this->Form->end(); ?>