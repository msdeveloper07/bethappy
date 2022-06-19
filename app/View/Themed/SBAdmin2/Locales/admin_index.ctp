<div>
    <h3><?php echo __('Available cultures'); ?></h3>
    <p><?php echo __('Add a culture'); ?></p>
    <?php echo $this->Form->create('Language'); ?>
    <?php echo $this->Form->input('name', array('options' => $localesList)); ?>    
    <?php echo $this->Form->end(__('Add', true)); ?>
    <?php if (isset($locales)): ?>
        <p><?php echo __('Cultures this site supports'); ?></p>
        <ul>
            <?php foreach ($locales as $locale): ?>
                <li>
                    <span><?php echo $locale['name']; ?></span>
                    <?php if ($locale['id'] != 1): ?>
                        <?php echo $this->Html->link(__('X', true), array('action' => 'delete', $locale['id']), NULL, __('Are you sure you want to delete ', true) . $locale['name']); ?>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>