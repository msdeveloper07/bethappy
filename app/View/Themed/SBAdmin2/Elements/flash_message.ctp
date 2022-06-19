<?php if(($success = $this->Session->flash('success')) != null): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <button class="close" data-dismiss="alert">×</button>
        <strong><?php echo __('Success!'); ?></strong> <?php echo $success; ?>
    </div>
<?php endif; ?>

<?php if(($error = $this->Session->flash('error')) != null): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <button class="close" data-dismiss="alert">×</button>
        <strong><?php echo __('Error!'); ?></strong> <?php echo $error; ?>
    </div>
<?php endif; ?>