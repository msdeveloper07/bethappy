<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" class="payments-layout">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <meta http-equiv="Content-Type" charset="text/html; <?= Configure::read('Settings.charset'); ?>"/>
        <base href="/" />

        <title><?= Configure::read('Settings.websiteName'); ?></title>
        <?= $this->Html->meta('keywords', (isset($keywords) ? $keywords : Configure::read('Settings.metaKeywords'))); ?>
        <?= $this->Html->meta('description', (isset($description) ? $description : Configure::read('Settings.metaDescription'))); ?>
        <?= $this->Html->meta(array('name' => 'author', 'content' => Configure::read('Settings.metaAuthor'))); ?>
        <?= $this->Html->meta(array('name' => 'reply-to', 'content' => Configure::read('Settings.metaReplayTo'))); ?>
        <?= $this->Html->meta(array('name' => 'copyright', 'content' => Configure::read('Settings.metaCopyright'))); ?>
        <?= $this->Html->meta(array('name' => 'revisit-after', 'content' => Configure::read('Settings.metaRevisitTime'))); ?>
        <?= $this->Html->meta(array('name' => 'identifier-url', 'content' => Configure::read('Settings.metaIdentifierUrl'))); ?>
        <?= $this->Html->meta('favicon.ico', 'img/casino/favicons/favicon.ico', array('type' => 'icon')); ?>

        <?php
        echo $this->Html->script('jquery-3.3.1.min.js');
        echo $this->Html->script('jquery-ui-1.12.1/jquery-ui.min.js');
        echo $this->Html->script('bootstrap-4.3.1/bootstrap.min.js');
        echo $this->Html->script('parsley-2.8.1/parsley.js');

        echo $this->Html->css('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;600;800&display=swap');
        echo $this->Html->css('fontawesome-free-5.11.2-web/css/all.min.css');
        echo $this->Html->css('ion-icons-2.0.0/css/ion-icons.min.css');
        echo $this->Html->css('jquery-ui-1.12.1/jquery-ui.min.css');
        echo $this->Html->css('bootstrap-4.3.1/bootstrap.min.css');
        echo $this->Html->css('casino/payments.css');
        ?>
    </head>

    <?php echo $content_for_layout; ?>
  
    <!--script type="text/javascript" src="/Layout/js/custom.js"></script>-->

</html>