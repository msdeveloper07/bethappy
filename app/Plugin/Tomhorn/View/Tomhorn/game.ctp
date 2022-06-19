
<script type = 'text/javascript'>
    window.close = function () {
    window.location = '/';
    }
    window.gclose = function () {
    window.location = '/';
    }
</script>
<div style="text-align: center">
    <script type="text/javascript" src="<?= $game['params']['param:base']; ?>/ClientUtils.js"></script>
    <script type="text/javascript" src="/js/swfobject.js"></script>
    <div id="gamecontainer">
        <h2><?php echo __('Game Client starting procedure failed!'); ?></h2>
    </div>
    <script type="text/javascript">
    var params = {
<?php foreach ($game['params'] as $key => $value) { ?>

        '<?= $key; ?>': '<?= $value; ?>',
                'var:exitUrl': '<?= Router::fullbaseUrl(); ?>',
                'width': '100%',
                'height': '95%',
<?php } ?>
    };
    renderClient(params, 'gamecontainer');
    </script>
    <script>
        var iframe = document.getElementById ("casinoClient");
        iframe.setAttribute('frameBorder', '0');
    </script>
</div>