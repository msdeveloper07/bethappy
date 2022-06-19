<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="expires" content="Thu, 19 Nov 1981 08:52:00 GMT" />
		<meta http-equiv="pragma" content="no-cache" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<?php echo $this->Html->script("Games.externalinterface"); ?>
		<?php echo $this->Html->script("Games.swfobject"); ?>
		<?php echo $this->Html->script("Games.jquery"); ?>
		
		<!-- <script language="JavaScript" type="text/javascript" src="js/externalinterface.js<?php echo "?r=".mt_rand(); ?>"></script>
		<script language="JavaScript" type="text/javascript" src="js/swfobject.js<?php echo "?r=".mt_rand(); ?>"></script>
		<script language="JavaScript" type="text/javascript" src="js/jquery.js"></script> -->
		
		<script type="text/javascript">
			var flashvars = {};
			flashvars.closeURL = GetCloseURL().replace( /[;]$/, '' );
			flashvars.helpURL = "javascript:help()";
			flashvars.lang = confirmLang( getparam( "lang" ) );
			flashvars.gameId = gametoload;
			flashvars.gameurl = gametoload + ".swf";
			flashvars.currencyChar = "#";
			flashvars.server = ( getparam('mode') == "real" ) ? ( ( GetUrlPrefixDirect().charAt(0) == "." ? "../../" : "" ) + GetUrlPrefixDirect() ).replace( "direct/game/real/", "" ) : ( ( GetUrlPrefixDirect().charAt(0) == "." ? "../../" : "" ) + GetUrlPrefixDirect() ).replace( "direct/game/demo/", "fun/" ).replace( "//", "/" ).replace("http:/", "http://").replace("https:/", "https://");
			flashvars.disableAudio = "false";
			if ( HideGameExit() != "true" ) flashvars.fullscreen = "true";
			flashvars.sessid = "none";
			flashvars.doDebug = "false";
			
			var params = {};
			params.bgcolor = "#000000";
			params.menu = "false";
			params.quality = "high";
			params.scale = "exactfit";
			params.wmode = "direct";
			params.allowfullscreen = "true";
			params.allowscriptaccess = "always";
			params.base = ".";
			var attributes = {};
			attributes.id = embedID;
			attributes.name = embedID;
			attributes.tabindex="0";
			swfobject.embedSWF(netentdirectory + "gameinit.swf?nocache=" + Math.random(), "myAlternativeContent", "100%", "100%", "9.0.0", false, flashvars, params, attributes);

			function ChangeContentToLoader( USERKEY ){
				flashvars.sessid = flashvars.gameId + "_" + USERKEY;
				var el = document.getElementById( embedID );
				if ( el ) {
					//el.style.display = "none";
					var div = document.createElement( "div" );
					el.parentNode.insertBefore( div, el );
					swfobject.removeSWF( "myAlternativeContent" );
					div.setAttribute( "id", "myAlternativeContent" );
					swfobject.embedSWF(netentdirectory + gametoload + "/" + flashvars.lang + "/loader.swf", "myAlternativeContent", "100%", "100%", "9.0.0", false, flashvars, params, attributes);
					SetFocus();
					if (!$.browser.mozilla) $("#" + embedID).css("box-sizing", "border-box");
					$("<audio></audio>").attr({
						"id": "jackpotSound",
						"src": swfdirectory + "jackpot.mp3",
						"type": "audio/mpeg"
					}).appendTo("body");						
					if (gamemode == "real") setTimeout(startSync, 3000, USERKEY, GetUrlPrefixDirect());				
				}
			}
			
			var refreshIntervalId;
			function startSync(USERKEY, URL) {
				$.ajax({
					 type: 'POST',
					 url: URL,
					 dataType: 'jsonp',
					 data: { sid: USERKEY },
					 success:function(json) {
						 console.log('json ->>>>>', json);
						setTimeout(startSync, 3000, USERKEY, URL);
						if (json.balance != undefined) {
							//document.location.reload();
							var el = document.getElementById( embedID );
							if ( el ) {
								el.style.display = "none";
								var div = document.createElement( "div" );
								el.parentNode.insertBefore( div, el );
								swfobject.removeSWF( "myAlternativeContent" );
								div.setAttribute( "id", "myAlternativeContent" );
								swfobject.embedSWF(netentdirectory + gametoload + "/" + flashvars.lang + "/loader.swf", "myAlternativeContent", "100%", "100%", "9.0.0", false, flashvars, params, attributes);
								SetFocus();	
								closeJackpot();							
							}
							return;
						}
						if (json.jackpotWon == undefined) return;
						$("#jpAmount").text(json.jackpotWon);
						$("#jpCurrency").text(json.jackpotCurrency);
						$("#jackpotSound").get(0).play();
						if (parseInt($("#jackpotWin").css("top"), 10) == 0) return;
						$("#jackpotWin").stop().animate({top: "0px"}, 800, animDownComplete);
						refreshIntervalId = setInterval(animUpdate, 100);
					 },
					 error:function() {
						setTimeout(startSync, 3000, USERKEY, URL);	
					 }      
				});
			}
			function animUpdate() {
				if (parseInt($("#jackpotWin").css("top"), 10) == 0 || parseInt($("#jackpotWin").css("top"), 10) == -50) return;
				$("#" + embedID).css("padding-top", parseInt($("#jackpotWin").css("top"), 10) + 50);
			}			
			function animDownComplete() {
				clearInterval(refreshIntervalId);
				$("#jackpotWin").css("top", "0px");
				$("#jackpotWin").css("cursor", "pointer");
				if (typeof window.chrome === "object") {
					$("#" + embedID).css("padding-top", "0px");
					$("#" + embedID).css("margin-top", "50px");
					$("#" + embedID).css("padding-bottom", "50px");
				}
				else {
					$("#" + embedID).css("padding-top", "50px");
				}
			}			
			function closeJackpot() {
				if (parseInt($("#jackpotWin").css("top"), 10) != 0) return;
				$("#jackpotWin").css("cursor", "default");
				if (typeof window.chrome === "object") {
					$("#" + embedID).css("padding-top", "50px");
					$("#" + embedID).css("margin-top", "0px");
					$("#" + embedID).css("padding-bottom", "0px");		
				}				
				$("#jackpotWin").stop().animate({top: "-50px"}, 800, animUpComplete);
				refreshIntervalId = setInterval(animUpdate, 100);
			}
			function animUpComplete() {
				clearInterval(refreshIntervalId);
				$("#jackpotWin").css("top", "-50px");
				$("#" + embedID).css("padding-top", "0px");		
			}			
			function confirmLang( LANG ){
				if ( LANG == "de" ) return LANG;
				else if ( LANG == "es" ) return LANG;
				else if ( LANG == "fr" ) return LANG;
				else if ( LANG == "it" ) return LANG;
				else if ( LANG == "pl" ) return LANG;
				else if ( LANG == "ru" ) return LANG;
				else if ( LANG == "el" ) return LANG;	
				else if ( LANG == "cn" ) return LANG;	
				else return "en";
			}			
			function help(){
				//var wh = window.open( netentdirectory + gametoload + "/rules/rules.html?gameid=" + gametoload + "&langid=" + flashvars.lang, "rules", "directories=no, location=no, menubar=no, resizable=no, scrollbars=yes, status=no, toolbar=no, width=440, height=420" );
				//wh.focus();
			}
			/*
			function gameEventHandler( e ){
				//alert(JSON.stringify(document.getElementById( embedID ).getWin()));
				//alert(JSON.stringify(e));
			}	
			function extendedGameEventHandler( e ){
				//alert(JSON.stringify(document.getElementById( embedID ).getWin()));
				alert(JSON.stringify(e));
			}	
			function action( e ){
				//alert(JSON.stringify(document.getElementById( embedID ).getWin()));
				alert(e);
			}	
			function error( e ){
				//alert(JSON.stringify(document.getElementById( embedID ).getWin()));
				alert(e);
			}*/
		</script>	
		<style type="text/css" media="screen">
			html, body { height:100%; background-color: #000000;}
			body { margin:0; padding:0; overflow:hidden; }
			
			#flashcontent { visibility:hidden; }
			div { visibility:hidden; }
		
		</style>		
	
		<title>Loading page...</title>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>

	<body oncontextmenu="return false;">
		<span id="jackpotWin" onClick="closeJackpot();" style="position: absolute; display: block; width: 100%; height: 49px; overflow: hidden; top: -50px; left: 0px; right: 0px; margin: 0px; padding: 0px; border-bottom: 1px solid #FF6200; background: #ffb347; background: -webkit-linear-gradient(to top, #ffb347, #ffcc33); background: linear-gradient(to top, #ffb347, #ffcc33); color: #701112; font-size: 30px; font-weight: bold; text-align: center; vertical-align: middle; font-family: arial, sans-serif;">You won <span id="jpAmount" style="font-size: 43px;"></span><span id="jpCurrency" style="font-size: 20px;"></span> Jackpot!</span>	
		<div id="myAlternativeContent">
			<a href="https://www.adobe.com/go/getflashplayer">
				<img src="https://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
			</a>
		</div>		
	</body>
</html>