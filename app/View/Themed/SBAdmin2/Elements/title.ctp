<?php $title = ''; ?>
<?php if (isset($title)): ?>
    <h1><?php echo $title; ?></h1>
<?php else: ?>
    <h1>
        <?php        
        $controller = $this->params['controller'];
        $action = str_replace('admin_', '', $this->params['action']);
        if ($action == 'index') {
            echo Inflector::humanize($controller);
        } else {
            echo $this->Html->link(__(Inflector::humanize($controller)), array('action' => 'index'));
            echo ' / ';
            echo Inflector::humanize($action);
        }
        ?>
    </h1>
<?php endif; ?>

