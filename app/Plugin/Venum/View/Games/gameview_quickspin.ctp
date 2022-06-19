<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
		<meta charset="UTF-8">	
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="expires" content="Thu, 19 Nov 1981 08:52:00 GMT" />
		<meta http-equiv="pragma" content="no-cache" />
		<?php echo $this->Html->script("Venum.externalinterface"); ?>
		<?php echo $this->Html->script("Venum.jquery"); ?>
		
		<!-- <script language="JavaScript" type="text/javascript" src="js/externalinterface.js<?php echo "?r=".mt_rand(); ?>"></script>
		<script language="JavaScript" type="text/javascript" src="js/jquery.js"></script> -->
		
		<script type="text/javascript">
			var closeURL = GetCloseURL().replace("window", "parent.window");
			var lang = confirmLang(getparam("lang"));
			var urlprefix = GetUrlPrefix();
			if (urlprefix.substring(0, 3) == "../") urlprefix = urlprefix.substring(3);
			
			var server = ((GetUrlPrefixDirect().charAt(0) == "." ? "../../../../" : "") + GetUrlPrefixDirect()).replace("direct/game/real/", "").replace("direct/game/demo/", "").replace("//", "/").replace("http:/", "http://").replace("https:/", "https://").replace(/\/$/, "") + "/quickspinsrv/";	

			function confirmLang(LANG) {
				if ( LANG == "it" ) return LANG;
				if ( LANG == "da" ) return LANG;
				if ( LANG == "nl" ) return LANG;
				if ( LANG == "et" ) return LANG;
				if ( LANG == "fi" ) return LANG;
				if ( LANG == "fr" ) return LANG;
				if ( LANG == "ka" ) return LANG;
				if ( LANG == "de" ) return LANG;
				if ( LANG == "el" ) return LANG;
				if ( LANG == "no" ) return LANG;
				if ( LANG == "pl" ) return LANG;
				if ( LANG == "pt" ) return LANG;
				if ( LANG == "ro" ) return LANG;
				if ( LANG == "ru" ) return LANG;
				if ( LANG == "es" ) return LANG;
				if ( LANG == "sv" ) return LANG;
				if ( LANG == "tr" ) return LANG;
				if ( LANG == "cs" ) return LANG;
				
				if ( LANG == "id" ) return LANG;   // Indonesian
				if ( LANG == "ja" ) return LANG;   // Japanese
				if ( LANG == "ko" ) return LANG;   // Korean
				if ( LANG == "lt" ) return LANG;   // Lithuanian
				if ( LANG == "lt" ) return LANG;   // Malay
				if ( LANG == "nb" ) return LANG;   // Norwegian (Bokmal)
				if ( LANG == "th" ) return LANG;   // Thai
				if ( LANG == "vi" ) return LANG;   // Vietnamese
				if ( LANG == "zh" ) return LANG;   // Chinese
				if ( LANG == "ms" ) return LANG;
				
				return "en";
			}
			
			window.isMobile = function() {
				var check = false;
				(function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
				return check;
			};
			
			if (window.addEventListener) window.addEventListener("message", onMessage, false); 
			else if (window.attachEvent) window.attachEvent("onmessage", onMessage, false);	
			function onMessage(event) {
				if (event.data == "close") document.location.href = closeURL.replace("parent.window", "window");
			}
			
			$(document).ready(function() {
			$.ajax({
				 type: 'POST',
				 url: urlprefix + "launchgame",
				 dataType: 'xml',
				 data: { gamename: gametoload, gameversion: GetVersion(), softwaretime: 1, softwarenonce: 'none', softwarehmac: 'none' },
				 success:function(xml) {
					console.log('xml ->>>>>', xml);
					if ($(xml).find("error").text() != "") {
						$("body").append("<strong style='display: inline-block; width: 100%; height: 100%; background-color: black; color: red; font-family: Sans-Serif; font-size: 18px; text-align: center; padding-top: 15px; margin: 0px;'>" + $(xml).find("error").text() + "</strong>");
						return;
					}
					
					server += $(xml).find("gamekey").text();
					
					if (isMobile()) {
						if (getparam("srvOverride") != "") closeURL = "javascript:window.close();";
						window.location.replace(htmldirectory + "00Quickspin/mcasino/quickspin/" + gametoload + "/index.html?partner=a&ticket=11111111-1111-1111-1111-111111111111&jurisdiction=MT&channel=web&partnerid=11&EnableFastplay=true"
													+ "&gameid=" + gametoload
													+ "&moneymode=" + (gamemode == "real" ? "real" : "fun")
													+ "&currencysymbol=" +  encodeURIComponent($(xml).find("currencysymbol").text())
													+ "&currencyisocode=" + ($(xml).find("currencyisocode").text() == "" ? "BLA" : $(xml).find("currencyisocode").text())
													+ "&currencydecimals=" + $(xml).find("currencydecimals").text()
													+ "&currencysymbolcomeslast=" + $(xml).find("currencysymbolcomeslast").text()
													//+ "&mobile=1"
													+ "&lang=" + lang
													+ "&homeurl=" + closeURL
													+ "&server=" + server.replace(/\//g, "|")
													+ "&sid=" + gametoload + "_" + $(xml).find("gamekey").text()
													+ (gamemode == "real" ? "&sync=" + GetUrlPrefixDirect().replace(/\//g, "|") : "")
													//+ ((HideGameExit() == "true" ? "&HideHomeButton=true" : ""))
													//+ "&showreplay=" + (HideReplay() == "true" ? "0" : "1")
													//+ "&showmusic=" + (HideMusic() == "true" ? "0" : "1")
													//+ "&showsound=" + (HideMute() == "true" ? "0" : "1")
													//+ "&showwidescreen=" + (HideWideScreen() == "true" ? "0" : "1")
													//+ "&showroundid=" + (HideTransactionID() == "true" ? "0" : "1")
													//+ "&showtime=" + (HideClock() == "true" ? "0" : "1")
													//+ "&showcursor=" + (HideMousePointer() == "true" ? "0" : "1")
													//+ "&shortspin=" + (ShortSpinning() == "true" ? "1" : "0"))
													+ ((HideFullScreen() == "true" ? "&DisableFullscreen=true" : "")));
													//+ "&DisableFullscreen=" + (HideFullScreen() == "true" ? "true" : "false"));
						return;
					}
					
					$('<iframe>', {
						src: htmldirectory + "00Quickspin/casino/quickspin/" + gametoload + "/index.html?partner=a&ticket=11111111-1111-1111-1111-111111111111&jurisdiction=MT&channel=web&partnerid=11&EnableFastplay=true"
								+ "&gameid=" + gametoload
								+ "&moneymode=" + (gamemode == "real" ? "real" : "fun")
								+ "&currencysymbol=" +  encodeURIComponent($(xml).find("currencysymbol").text())
								+ "&currencyisocode=" + ($(xml).find("currencyisocode").text() == "" ? "BLA" : $(xml).find("currencyisocode").text())
								+ "&currencydecimals=" + $(xml).find("currencydecimals").text()
								+ "&currencysymbolcomeslast=" + $(xml).find("currencysymbolcomeslast").text()								
								//+ "&mobile=0"
								+ "&lang=" + lang
								+ "&homeurl=" + closeURL
								+ "&server=" + server.replace(/\//g, "|")
								+ "&sid=" + gametoload + "_" + $(xml).find("gamekey").text()
								+ (gamemode == "real" ? "&sync=" + GetUrlPrefixDirect().replace(/\//g, "|") : "")
								+ ((HideGameExit() == "true" ? "&HideHomeButton=true" : ""))
								//+ "&showreplay=" + (HideReplay() == "true" ? "0" : "1")
								//+ "&showmusic=" + (HideMusic() == "true" ? "0" : "1")
								//+ "&showsound=" + (HideMute() == "true" ? "0" : "1")
								//+ "&showwidescreen=" + (HideWideScreen() == "true" ? "0" : "1")
								//+ "&showroundid=" + (HideTransactionID() == "true" ? "0" : "1")
								//+ "&showtime=" + (HideClock() == "true" ? "0" : "1")
								//+ "&showcursor=" + (HideMousePointer() == "true" ? "0" : "1")
								//+ "&shortspin=" + (ShortSpinning() == "true" ? "1" : "0")
								+ ((HideFullScreen() == "true" ? "&DisableFullscreen=true" : "")),
								
						id:  'gameframe',
						//width: '100%',
						//height: '100%',
						css: {
							//"width": "1px",
							//"min-width": "100%",
							//"height": "1px",
							//"min-height": "100%"
							"width": "100%",
							"height": "100%"	
						},	
						frameborder: 0,
						//border: 0,
						//scrolling: 'no',
						allowfullscreen: HideFullScreen() == "true" ? undefined : true
					}).appendTo( 'body' );
					
					if (HideGameExit() == "false") {
						$('<img />').attr({
							src:htmldirectory + "00Quickspin/close.png",
							id:'close'
						}).click(Close).appendTo('body');							
					}					
				 },
				 error:function() {
				 }      
			});
			});
			
			function Close() {
				window.location.href = GetCloseURL();
			}
			
		</script>	
		<style type="text/css" media="screen">
			html, body { height:100%; margin:0; padding:0; overflow:hidden; }
			iframe { margin:0; padding:0; overflow:hidden; }
			html, body {
				 background: transparent !important;
				 color: transparent !important;
				 background-color: transparent !important;
				 position: absolute;
				 top: 0;
				 left: 0;
				 right: 0;
				 -webkit-backface-visibility: visible;
			}
			#close {
				position:absolute;
				top:10px;
				right:10px;
				cursor:pointer;
				cursor:hand;
			}			
		</style>
		<title>Loading page...</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<body>
	</body>
</html>
