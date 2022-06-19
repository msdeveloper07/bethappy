<?php
echo $this->Paginator->counter(array(
    'format' => __('Page {:page} / {:pages}, showing {:start} - {:end} of {:count} total records')
));

if ($this->Paginator->hasPage(2)):
    ?>
    <div class="paging">
        <?php echo $this->Paginator->first('<i class="icon ion-ios-arrow-left"></i>', array('class' => 'disabled', 'escape' => false)); ?>

        <?php if ($this->Paginator->hasPrev()): ?>
            <?php echo $this->Paginator->prev('<i class="icon ion-ios-arrow-thin-left"></i>', array('escape' => false), null, array('class' => 'disabled')); ?>
        <?php endif; ?>

        <?php echo $this->Paginator->numbers(array('before'=> null, 'after'=>null, 'separator' => ' ')); ?>

        <?php if ($this->Paginator->hasNext()): ?>
            <?php echo $this->Paginator->next('<i class="icon ion-ios-arrow-thin-right"></i>', array('escape' => false), null, array('class' => 'disabled')) . "\n"; ?>
        <?php endif; ?>

        <?php echo $this->Paginator->last('<i class="icon ion-ios-arrow-right"></i>', array('escape' => false, 'class' => 'disabled')); ?>
    </div>
<?php endif; ?>