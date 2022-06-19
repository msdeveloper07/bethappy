<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="expires" content="Thu, 19 Nov 1981 08:52:00 GMT" />
		<meta http-equiv="pragma" content="no-cache" />
		<?php echo $this->Html->script("Games.jquery"); ?>
		
		<script type="text/javascript">
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
			if (vars["technology"] != null) vars["channel"] = vars["technology"] == "html5" ? "mobile" : "flash";
			if (vars["brand"] != null) {
				var brand = vars["brand"].replace(" ", "").replace("%20", "").replace("+", "").toLowerCase();
				if (brand == "netent") {
					if (vars["channel"] != null && vars["channel"] == "mobile") {
						document.location.href = "gameview_netenthtml" + document.location.search + document.location.hash;
					}
					else {
						document.location.href = "gameview_netent" + document.location.search + document.location.hash;
					}
				}
				else if (brand == "egtjackpot" || brand == "egt") {
					if (vars["channel"] != null && vars["channel"] == "mobile") {
						if(vars["mode"] == 'real'){
							var settoken ="";

							var base_url = window.location.origin;
								console.log('data>>>>>>>>>>>>>>>', base_url);
									$.ajax({
										type: 'GET',
										url: base_url+"/games/venum/requestToken",
										accept: 'text/plain',
										success:function(data) {
											settoken = data;
											console.log('settoken>>>>>>>>>>>>>>>', settoken);
											document.location.href = "gameview_egt_mobile" + document.location.search + settoken;
										},
										error: function (error){

										}
									});
							
						}else{
							document.location.href = "gameview_egt_mobile" + document.location.search + document.location.hash;
						}
						
					}
					else {
						document.location.href = "gameview_egt" + document.location.search + document.location.hash;
					}
				}	
				else if (brand == "amatic" && vars["channel"] == "mobile") {
					// document.location.href = "gameview_amatic_mobile" + document.location.search + document.location.hash;
					if(vars["mode"] == 'real'){
							var settoken ="";

							var base_url = window.location.origin;
								console.log('data>>>>>>>>>>>>>>>', base_url);
									$.ajax({
										type: 'GET',
										url: base_url+"/games/venum/requestToken",
										accept: 'text/plain',
										success:function(data) {
											settoken = data;
											console.log('settoken>>>>>>>>>>>>>>>', settoken);
											document.location.href = "gameview_amatic_mobile" + document.location.search + settoken;
										},
										error: function (error){

										}
									});
							
						}else{
							document.location.href = "gameview_amatic_mobile" + document.location.search + document.location.hash;
						}
				}
				else if (brand == "novomatic" && vars["channel"] == "mobile") {
					// document.location.href = "gameview_novomatic" + document.location.search + document.location.hash;
					if(vars["mode"] == 'real'){
							var settoken ="";

							var base_url = window.location.origin;
								console.log('data>>>>>>>>>>>>>>>', base_url);
									$.ajax({
										type: 'GET',
										url: base_url+"/games/venum/requestToken",
										accept: 'text/plain',
										success:function(data) {
											settoken = data;
											console.log('settoken>>>>>>>>>>>>>>>', settoken);
											document.location.href = "gameview_novomatic" + document.location.search + settoken;
										},
										error: function (error){

										}
									});
							
						}else{
							document.location.href = "gameview_novomatic" + document.location.search + document.location.hash;
						}
				}
				else if (brand == "wazdan" && vars["channel"] == "mobile") {
					// document.location.href = "gameview_wazdan" + document.location.search + document.location.hash;
					if(vars["mode"] == 'real'){
							var settoken ="";

							var base_url = window.location.origin;
								console.log('data>>>>>>>>>>>>>>>', base_url);
									$.ajax({
										type: 'GET',
										url: base_url+"/games/venum/requestToken",
										accept: 'text/plain',
										success:function(data) {
											settoken = data;
											console.log('settoken>>>>>>>>>>>>>>>', settoken);
											document.location.href = "gameview_wazdan" + document.location.search + settoken;
										},
										error: function (error){

										}
									});
							
						}else{
							document.location.href = "gameview_wazdan" + document.location.search + document.location.hash;
						}
				}		
				else if (brand == "pragmaticplay" && vars["channel"] == "mobile") {
					// document.location.href = "gameview_pragmaticplay" + document.location.search + document.location.hash;
					if(vars["mode"] == 'real'){
							var settoken ="";

							var base_url = window.location.origin;
								console.log('data>>>>>>>>>>>>>>>', base_url);
									$.ajax({
										type: 'GET',
										url: base_url+"/games/venum/requestToken",
										accept: 'text/plain',
										success:function(data) {
											settoken = data;
											console.log('settoken>>>>>>>>>>>>>>>', settoken);
											document.location.href = "gameview_pragmaticplay" + document.location.search + settoken;
										},
										error: function (error){

										}
									});
							
						}else{
							document.location.href = "gameview_pragmaticplay" + document.location.search + document.location.hash;
						}
				}				
				else if (brand == "quickspin") {
					if (vars["channel"] != null && vars["channel"] == "mobile") {
						// document.location.href = "gameview_quickspin" + document.location.search + document.location.hash;

						if(vars["mode"] == 'real'){
							var settoken ="";

							var base_url = window.location.origin;
								console.log('data>>>>>>>>>>>>>>>', base_url);
									$.ajax({
										type: 'GET',
										url: base_url+"/games/venum/requestToken",
										accept: 'text/plain',
										success:function(data) {
											settoken = data;
											console.log('settoken>>>>>>>>>>>>>>>', settoken);
											document.location.href = "gameview_quickspin" + document.location.search + settoken;
										},
										error: function (error){

										}
									});
							
						}else{
							document.location.href = "gameview_quickspin" + document.location.search + document.location.hash;
						}
					}
					else {
						if(vars["mode"] == 'real'){
							var settoken ="";

							var base_url = window.location.origin;
								console.log('data>>>>>>>>>>>>>>>', base_url);
									$.ajax({
										type: 'GET',
										url: base_url+"/games/venum/requestToken",
										accept: 'text/plain',
										success:function(data) {
											settoken = data;
											console.log('settoken>>>>>>>>>>>>>>>', settoken);
											document.location.href = "gameview_quickspinflash" + document.location.search + settoken;
										},
										error: function (error){

										}
									});
							
						}else{
							document.location.href = "gameview_quickspinflash" + document.location.search + document.location.hash;
						}
						// document.location.href = "gameview_quickspinflash" + document.location.search + document.location.hash;
					}
				}				
			}
		</script>	

		<?php echo $this->Html->script("Games.externalinterface");

  			echo $this->Html->script("Games.swfobject");	?>
		
		<!-- <script language="JavaScript" type="text/javascript" src="js/externalinterface.js<?php echo "?r=".mt_rand(); ?>"></script>
		<script language="JavaScript" type="text/javascript" src="js/swfobject.js<?php echo "?r=".mt_rand(); ?>"></script> -->
		
		<script type="text/javascript">
			var flashvars = {};
			var params = {};
			params.bgcolor = "#000000";
			params.menu = "false";
			params.quality = "high";
			params.scale = "noscale";
			//params.wmode = "gpu";
			params.allowfullscreen = "true";
			params.allowscriptaccess = "always";
			params.base = ".";
			//params.salign = "TL";
			var attributes = {};
			attributes.id = embedID;
			attributes.name = embedID;
			attributes.tabindex = "0";	
			swfobject.embedSWF(swfdirectory + "container.swf?nocache=" + Math.random(), "myAlternativeContent", "100%", "100%", "9.0.0", false, flashvars, params, attributes);		
			SetFocus();
		</script>		
		<style type="text/css" media="screen">
			html, body, div { height:100%; background-color: #000000; leftmargin:0; topmargin:0; marginwidth:0; marginheight:0; }
			body { margin:0; padding:0; overflow:hidden; }
			
			#flashcontent { visibility:hidden; }
		
		</style>		
	
		<title>Loading page...</title>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>

	<body oncontextmenu="return false;" style="top:0px; left:0px;">	
		<div id="myAlternativeContent">
			<a href="https://www.adobe.com/go/getflashplayer" tabindex = 0>
				<img src="https://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
			</a>
			<div id="myAlternativeContent"></div>
		</div>		
	</body>
</html>
