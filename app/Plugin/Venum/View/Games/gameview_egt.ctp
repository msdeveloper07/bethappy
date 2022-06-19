<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="expires" content="Thu, 19 Nov 1981 08:52:00 GMT" />
		<meta http-equiv="pragma" content="no-cache" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<?php echo $this->Html->script("Venum.externalinterface"); ?>
		<?php echo $this->Html->script("Venum.swfobject"); ?>
		<?php echo $this->Html->script("Venum.jquery"); ?>
		<!-- <script language="JavaScript" type="text/javascript" src="js/externalinterface.js<?php echo "?r=".mt_rand(); ?>"></script>
		<script language="JavaScript" type="text/javascript" src="js/swfobject.js"></script>
		<script language="JavaScript" type="text/javascript" src="js/jquery.js"></script> -->
		
		<title>Loading page...</title>
		
		<style type="text/css" media="screen">
			html, body, body.sidebars { width:100%; height:100%; margin:0; padding:0; background-color: #000000; }
			/* <!--[if !IE]>--> */
			#flashcontent { visibility:hidden; }
			/* <!--<![endif]-->			 */
		</style>
	
		<script type="text/javascript">
	
			var platformDirectory = swfdirectory + "00EGT/";
			var swfMain = platformDirectory + "GamePlatform.swf";
			var ts = new Date().getTime();
			var url = swfMain + "?ts=" + ts;
			
			var base = document.createElement('base');
			base.href = platformDirectory;
			document.getElementsByTagName('head')[0].appendChild(base);				
			
			function confirmLang(LANG){
				if ( LANG == "bg" ) return LANG;
				else if ( LANG == "es" ) return LANG;
				else if ( LANG == "go" ) return LANG;
				else if ( LANG == "it" ) return LANG;
				else if ( LANG == "ru" ) return LANG;
				else if ( LANG == "nl" ) return LANG;
				else if ( LANG == "fr" ) return LANG;
				else if ( LANG == "mk" ) return LANG;
				else if ( LANG == "ro" ) return LANG;
				return "en";
			}			
			
			function closePopup() {
				window.location.href = GetCloseURL();
			}

			function getQueryVariables() {
				var result = {};
				var vars = window.location.search.substring(1).split("&");
				for (var i=0;i<vars.length;i++) {
					var pair = vars[i].split("=");
					result[pair[0]] = pair[1];
				}
				return result;
			}

			var vars = getQueryVariables();
			var paramKey;
			
			var ginParams = ["loadgame", "gameId", "gin", "game", "g"];
			var gin = "-1";
			for(paramKey in ginParams) {
				if (vars[ginParams[paramKey]] != null) {
					gin = vars[ginParams[paramKey]];
					break;
				}
			}

			var ginOverrides = {901:"20202", 902:"20208", 903:"20210"};
			var ginOverride = ginOverrides[gin];
			if(!(ginOverride === undefined)) {
				gin = ginOverride;
			}
			
			var host = window.location.hostname;
			var endpt = GetUrlPrefixDirect();
			if (endpt.charAt(0) != "." && endpt.charAt(0) != "/") {
				var a = document.createElement("a");
				a.href = endpt;
				host = a.hostname;
			}
			
			var flashvars = {
				tcpHost: "eapi3.slotomatic.net",
				tcpPort: "8090",
				lang: confirmLang(getparam("lang")),
				content: platformDirectory + "assets/content.xml",
				gameIdentificationNumber: gin,
				gameId: gin
			};
		
			var params = {
				menu: "false",
				quality: "high",
				allowFullscreen: "true",
				allowScriptAccess: "always",
				bgcolor: "#000000",
				allowFullScreenInteractive: "true",
				base: "."
			};
			
			var attributes = {
				id: embedID,
				name: embedID,
				tabindex: "0"
			};
			
			swfobject.embedSWF(swfdirectory + "gameinit.swf?nocache=" + Math.random(), "myAlternativeContent", "100%", "100%", "11.1", "expressInstall.swf", flashvars, params, attributes);
			SetFocus();
			
			function ChangeContentToLoader(USERKEY){
				flashvars.sessionKey = flashvars.gameId + "_" + ts + "_" + USERKEY;
				var el = document.getElementById(embedID);
				if ( el ) {
					//el.style.display = "none";
					var div = document.createElement("div");
					el.parentNode.insertBefore(div, el);
					swfobject.removeSWF("myAlternativeContent");
					div.setAttribute("id", "myAlternativeContent");
					swfobject.embedSWF(url, "myAlternativeContent", "100%", "100%", "11.1", "expressInstall.swf", flashvars, params, attributes);
					SetFocus();
					if (!$.browser.mozilla) $("#" + embedID).css("box-sizing", "border-box");
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
						console.log('json >>>>>>', json);
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
								swfobject.embedSWF(url, "myAlternativeContent", "100%", "100%", "11.1", "expressInstall.swf", flashvars, params, attributes);
								SetFocus();	
								closeJackpot();							
							}
							return;
						}						 
						if (json.jackpotWon == undefined) return;
						document.getElementById(embedID).QuitFullscreen();
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
		</script>

	</head>
	<body style="margin:0; padding:0; overflow-y:hidden;">
		<audio id="jackpotSound" src="../jackpot.mp3" type="audio/mpeg"></audio> 
		<span id="jackpotWin" onClick="closeJackpot();" style="position: absolute; display: block; width: 100%; height: 49px; overflow: hidden; top: -50px; left: 0px; right: 0px; margin: 0px; padding: 0px; border-bottom: 1px solid #FF6200; background: #ffb347; background: -webkit-linear-gradient(to top, #ffb347, #ffcc33); background: linear-gradient(to top, #ffb347, #ffcc33); color: #701112; font-size: 30px; font-weight: bold; text-align: center; vertical-align: middle; font-family: arial, sans-serif;">You won <span id="jpAmount" style="font-size: 43px;"></span><span id="jpCurrency" style="font-size: 20px;"></span> Jackpot!</span>
		<div id="myAlternativeContent" align="center" style="width:100%; height:100%; overflow:auto;"></div>
	</body>
</html>

