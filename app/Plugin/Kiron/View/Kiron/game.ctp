<style>
    .iframe-container{ position: relative; overflow: hidden; }
    .iframe-container iframe { position: absolute; top:0; left: 0; height: 100%; border:0; width: 100%; }
</style>

<?php echo $content; ?>


<script type="text/javascript">
    function adjustiframe(contentHeight) {
        var iFrameID = document.getElementById('contentiframe');
        if (iFrameID) {
            iFrameID.height = contentHeight;
            var iFrameDiv = document.getElementById('iframe-container');
            if (iFrameDiv) {
                iFrameDiv.style.height = contentHeight;
            }
        }
    }

    function receiveMessage(event) {

        if (event.origin !== "http://games.staging.playbetman.com")
            return;
        if (event.data.command !== undefined && event.data.command === "adjustiframe")
            adjustiframe(event.data.height);
        else if (event.data.command !== undefined && event.data.command === "refreshbalance")
            refreshbalance();
    }


    window.addEventListener("message", receiveMessage, false);
//
//
//    function refreshBalance() {
//        if (parent !== undefined && parent !== window) {
//            var messageData = {command: 'refreshbalance'};
//            parent.postMessage(messageData, '*');
//        }
//    }
//



</script>