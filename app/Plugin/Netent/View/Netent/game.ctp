<script type = 'text/javascript'>
    window.close = function () {
        window.location = '/';
    }
    window.gclose = function () {
        window.location = '/';
    }
</script>
<style>
    body{
        height: 100%;
    }
    #container {
        width: 100%;
        height: 100%;
    }
</style>
<div id="container">
    <?php if (isset($game_embed)): ?>
        <?= $game_embed; ?>
    <?php elseif (isset($game_url)): ?>
        <iframe src="<?= $game_url; ?>" width="100%" height="100%"  marginwidth="0"  hspace="0" scrolling="no" vspace="0" marginheight="0" frameborder="0" allowfullscreen />
    <?php endif; ?>
</div>