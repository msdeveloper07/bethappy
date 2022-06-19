/*** GAME INFORMATION: ***/

var gametoload = getparam('loadgame');
// Insert here the name of the game which is meant to be played. The game names are identical to the folder names in which they are provided.
// By default the value is retrieved from the query string.
 
var gamemode = getparam('mode');
// Insert here the mode in which the game is meant to run ("real" or "demo"). The "demo" mode (play for fun) does not require any player identifier
// and the credits played do not have a real value.
// By default the value is retrieved from the query string.



/*** FUNCTIONAL (MANDATORY) CONFIGURATION OF THE EMBEDDED GAME: ***/

var embedID = "flashcontent";
// Insert here the identifier that you wish to assign to your embedded flash object. It is used to give keyboard focus to the game as soon as it is loaded.

var swfdirectory = "https://static.slotomatic.net/games/";
// Insert here the path to the folder which includes the container and game SWF files.

var netentdirectory = "https://static.slotomatic.net/flash/";
// Insert here the path to the folder which includes the container and game SWF files.

var htmldirectory = "https://static.slotomatic.net/games/";
// Insert here the path to the folder which includes html5 game files.

var communicationdirectory = "https://venum-dev.com/venum/games/";
// Insert here the path to the folder which includes the API connection files.
// OR:
// var communicationdirectory = "../../api/userbytoken/" + requestedToken + "/";
// Insert the path to the game server (api) followed by endpoint "userbytoken/requestedToken/" (where "requestedToken" is the access token you need to request by player/request_token api call) if you wish the games to authenticate the player through access token during the game initialization.
if (getparam('token') != '') communicationdirectory = "https://api3.slotomatic.net/api/userbytoken/" + getparam('token') + "/";

var directcommunicationdirectory = "https://api3.slotomatic.net/api/";
// Insert here the path to the game server (api) if you wish the games to communicate directly to the game server. Leaving it as an empty string disables direct communication. 

var closeurl = "javascript:window.close();";
// Specify what to do when the player quits the game (hits the exit button inside the game). Insert an URL to load a new page or take javascript actions. 
// By default the browser window will be closed.
//**********************************************************


/*** DISPLAY CONFIGURATION OPTIONS OF THE EMBEDDED GAME: ***/

var exactfitmode = "false";
// Whether or not the game should be stretched to take the full available space ("true" or "false").
// In case of "true", automatically disables wide screen mode option as well.

var startonwidescreen = "false";
// Whether or not the game should be stretched into wide screen mode when opened ("true" or "false").
// Takes effect only in case of games which support wide screen mode.
	
var hideclock = "false";
// Whether or not to hide computer time on the game screen ("true" or "false").

var hidetransactionID = "false";
// Whether or not to hide transaction ID on the game screen ("true" or "false").

var hidereplaybutton = "false";
// Whether or not to hide replay button on the game screen ("true" or "false").

var hidegameexitbutton = "false";
// Whether or not to hide (disable) exit button on the game screen ("true" or "false").

var hidefullscreenbutton = "false";
// Whether or not to hide (disable) full screen button on the game screen ("true" or "false").

var hidewidescreenbutton = "false";
// Whether or not to hide (disable) wide screen button on the game screen ("true" or "false").
// Takes effect only in case of games which support wide screen mode.

var hidemutebutton = "false";
// Whether or not to hide (disable) sound on/off button on the game screen ("true" or "false").

var hidemusicbutton = "false";
// Whether or not to hide (disable) music on/off button on the game screen ("true" or "false").
// Takes effect only in case of games which support music on/off toggle.

var hidemousepointer = "false";
// Whether or not to hide the mouse pointer on the game screen ("true" or "false").

var shortspinning = "false";
// Whether or not to shorten the duration of spinning animation in slot games ("true" or "false").

/***************************************************************************************************************************/










function getparam( name ) {
	console.log('getPram', name);
	
	
		
	
	
	name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	var regexS = "[\\?&]"+name+"=([^&#]*)";
	var regex = new RegExp( regexS );
	var results = regex.exec( window.location.href );
	console.log('results in ', results); 
	
	
	if( results == null ) return "";
	else return results[1];
}
if (getparam("srvOverride") != "") {
	closeurl = "javascript:window.closeByParent();";
}
function closeByParent() {
	window.parent.postMessage({"action": "closeGame"}, "*");
}
if (getparam("exactFitMode") != "") exactfitmode = getparam("exactFitMode");
if (getparam("startOnWideScreen") != "") startonwidescreen = getparam("startOnWideScreen");
if (getparam("hideClock") != "") hideclock = getparam("hideClock");
if (getparam("hideTransactionID") != "") hidetransactionID = getparam("hideTransactionID");
if (getparam("hideReplayButton") != "") hidereplaybutton = getparam("hideReplayButton");
if (getparam("hideGameExitButton") != "") hidegameexitbutton = getparam("hideGameExitButton");
if (getparam("hideFullScreenButton") != "") hidefullscreenbutton = getparam("hideFullScreenButton");
if (getparam("hideWideScreenButton") != "") hidewidescreenbutton = getparam("hideWideScreenButton");
if (getparam("hideMuteButton") != "") hidemutebutton = getparam("hideMuteButton");
if (getparam("hideMusicButton") != "") hidemusicbutton = getparam("hideMusicButton");
if (getparam("hideMousePointer") != "") hidemousepointer = getparam("hideMousePointer");
if (getparam("shortSpinning") != "") shortspinning = getparam("shortSpinning");

function GetGameToLoad() {
	return gametoload;
}
// function GetGameToken() {

// 	var base_url = window.location.origin;
// 	console.log('data>>>>>>>>>>>>>>>', base_url);
// 		$.ajax({
// 			type: 'GET',
// 			url: base_url+"/venum/games/requestToken",
// 			accept: 'text/plain',
// 			success:function(data) {
// 				console.log('data>>>>>>>>>>>>>>>', data);
// 				alert(data);
// 				return data;
	
// 			},
// 			error: function (error){

// 			}
// 		});
		
	
// }

function ExactFitMode() {
	return exactfitmode;
}			
function StartOnWideScreen() {
	return startonwidescreen;
}
function HideClock() {
	return hideclock;
}
function HideTransactionID() {
	return hidetransactionID;
}
function HideReplay() {
	return hidereplaybutton;
}
function HideGameExit() {
	return hidegameexitbutton;
}
function HideFullScreen() {
	return hidefullscreenbutton;
}
function HideWideScreen() {
	return hidewidescreenbutton;
}			
function HideMute() {
	return hidemutebutton;
}
function HideMusic() {
	return hidemusicbutton;
}
function DisableESC() {
	return "false";
}			
function HideMousePointer() {
	return hidemousepointer;
}	
function UseCustomPreloader() {
	return "true";
}	
function ShortSpinning() {
	return shortspinning;
}		

function PreloaderInited() {
	document.getElementById( embedID ).style.visibility = "visible";
}			
function SetFocus() {
        var el = document.getElementById(embedID);
        if (el) {
                el.tabindex = 0;
                el.focus();
        }
}
			
function GetSWFDirectory() {
	return swfdirectory;
}
function GetCloseURL() {
	return closeurl;
}			
function GetGameMode() {
	SetFocus();
	return "real";
}	
function GetVersion() {
	return "201501200000-RBX04sOyqI";
}
function GetUrlPrefix() {
	if (getparam("srvOverride") != "") return getparam("srvOverride");
	var spl = communicationdirectory.split( "/" );
	console.log('communicationdirectory',communicationdirectory);
	console.log('spl', spl);
	
	if ( spl[ spl.length - 3 ] == "userbytoken" ) return "/venum/games/gameplay?reqfile=game/" + gamemode + "/";
	return "/venum/games/gameplay?reqfile=game/" + gamemode + "/";
}
function GetUrlPrefixDirect() {
	if (getparam("srvOverride") != "") return getparam("srvOverride");
	if ( directcommunicationdirectory == "" ) return GetUrlPrefix();
	return directcommunicationdirectory + "direct/game/" + gamemode + "/";
}



