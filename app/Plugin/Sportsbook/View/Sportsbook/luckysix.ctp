<style>
    iframe {
        border: none;
        height: 100vh;
        width: 100%
    }    
</style>

<div>
    <iframe src="https://staging-bethappy.web.7platform.net/luckysix?auth=b2b&id=<?=$id?>&token=<?=$sessionId?>&authStrategy=token&currency=<?=$currency?>" frameborder="0"
        scrolling="yes"
        style="width: 100%;">
    </iframe>
</div>
<script src="https://assets.nsoft-cdn.com/public/cdn/iframe-resizer/3.4.0/script.js"></script>
<script>
iFrameResize({ 
    checkOrigin: [ 'https://staging-bethappy.web.7platform.net' ],
    // heightCalculationMethod: 'lowestElement',
    autoResize: true,
    initCallback: function (iframe) {
        iframe.style.height = window.innerHeight + "px";
        // window.parent.document.querySelector("iframe").style.height = window.innerHeight + "px";

        window.addEventListener("resize", function(e) {
            iframe.style.height = window.innerHeight + "px";
            // window.parent.document.querySelector("iframe").style.height = window.innerHeight + "px";
        })
    },
    messageCallback: function(data) {
        console.log(data);
        if (data.message && data.message.type) {
            switch (data.message.type) {
            case 'loginRequired':
                break;
            case 'contentLoaded':
                // window.parent.document.querySelector("iframe").style.height = window.parent.document.querySelector("iframe").contentWindow.document.documentElement.scrollHeight + 'px';
                break;
            }
        }
    }

});

// let count = 0;
// function resizeParentIframe() {
//     window.parent.document.querySelector("iframe").style.height = window.parent.document.querySelector("iframe").contentWindow.document.body.scrollHeight + "px";
//     console.log(window.parent.document.querySelector("iframe").contentWindow.document.body.scrollHeight);

//     count++;
//     if (count < 500) {
//         setTimeout(() => {
//             resizeParentIframe();
//         }, 50);
//     }
// }

// setTimeout(() => {
//     resizeParentIframe();
// }, 50);
</script>