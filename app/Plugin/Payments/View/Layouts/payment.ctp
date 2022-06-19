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


        <link rel="apple-touch-icon" sizes="180x180" href="img/casino/favicons/bet-happy/apple-touch-icon.png"/>
        <link rel="icon" type="image/png" sizes="32x32" href="img/casino/favicons/bet-happy/favicon-32x32.png"/>
        <link rel="icon" type="image/png" sizes="16x16" href="img/casino/favicons/bet-happy/favicon-16x16.png"/>
        <link rel="manifest" href="img/casino/favicons/bet-happy/site.webmanifest"/>
        <meta name="msapplication-TileColor" content="#da532c"/>
        <meta name="theme-color" content="#ffffff"/>

        <?php
        echo $this->Html->css('https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;700&display=swap');
        echo $this->Html->css('fontawesome-free-5.11.2-web/css/all.min.css');
        echo $this->Html->css('ion-icons-2.0.0/css/ion-icons.min.css');

        echo $this->Html->script('https://code.jquery.com/jquery-3.5.1.js');
        echo $this->Html->script('jquery-ui-1.12.1/jquery-ui.min.js');
        echo $this->Html->css('jquery-ui-1.12.1/jquery-ui.min.css');

        echo $this->Html->script('https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js');
        echo $this->Html->script('https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js');
        echo $this->Html->css('https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css');

        echo $this->Html->script('parsley-2.8.1/parsley.js');
        echo $this->Html->css('bet-happy/payments.css');
        ?>
    </head>

        <?php echo $content_for_layout; ?>

</html>