/********** GAME WINDOW CONFIGURATION: **********/

var UrlToOpen = "gameview.ctp";
// SPECIFY THE DEFAULT CONTENT PAGE OF THE POPUP WINDOW (THE PAGE WHICH EMBEDS THE FLASH GAME)

var PageTitlePrefix = "Playing ***";
// SPECIFY THE PAGE TITLE - THE PART OF THE TITLE APPEARING BEFORE THE NAME OF THE GAME

var PageTitleSuffix = "*** at Casino";
// SPECIFY THE PAGE TITLE - THE PART OF THE TITLE APPEARING AFTER THE NAME OF THE GAME

/************************************************/

console.log('in gamewindow');








var GameWindow;
var GameName;
var DefWidth;
var DefHeight;
var AlreadyResized;
function opengame( GAME, GAMENAME, MODE, DEFAULTWIDTH, DEFAULTHEIGHT, ALTPAGE, LANGUAGE ) {
	if ( typeof ALTPAGE === "undefined" ) ALTPAGE = "";
	if ( typeof LANGUAGE === "undefined" ) LANGUAGE = "";
	if ( GameWindow == null || GameWindow.closed ) {
		GameName = GAMENAME;
		DefWidth = DEFAULTWIDTH;
		DefHeight = DEFAULTHEIGHT;
		AlreadyResized = false;
		var URL = ( ALTPAGE != "" ? ALTPAGE : UrlToOpen ) + "?loadgame=" + GAME + "&mode=" + MODE;
		if ( LANGUAGE != "" ) URL += "&lang=" + LANGUAGE;
		GameWindow = window.open( URL, "_blank", "left=0,top=0,width="+screen.availWidth+",height="+screen.availHeight+",toolbar=no,titlebar=no,menubar=no,scrollbars=no,resizable=yes" );
		GameWindow.resizeTo( screen.availWidth, screen.availHeight );
		var innerw = GameWindow.innerWidth || GameWindow.document.documentElement.clientWidth || GameWindow.document.body.clientWidth;
		var innerh = GameWindow.innerHeight || GameWindow.document.documentElement.clientHeight || GameWindow.document.body.clientHeight;	
		if ( innerw > GameWindow.innerHeight * DefWidth / DefHeight ) {
			GameWindow.resizeTo( innerh * DefWidth / DefHeight + ( GameWindow.outerWidth - innerw ), screen.availHeight );
			GameWindow.moveTo( ( screen.availWidth - GameWindow.outerWidth ) / 2, ( screen.availHeight - GameWindow.outerHeight ) / 2 );
			AlreadyResized = true;
		}
		else if ( GameWindow.innerHeight > innerw * DefHeight / DefWidth ) {
			GameWindow.resizeTo( screen.availWidth, innerw * DefHeight / DefWidth + ( GameWindow.outerHeight - innerh ) );
			GameWindow.moveTo( ( screen.availWidth - GameWindow.outerWidth ) / 2, ( screen.availHeight - GameWindow.outerHeight ) / 2 );
			AlreadyResized = true;
		}
		settitle();
	}
	if ( GameWindow.focus ) GameWindow.focus();
}
function settitle() {
	if ( GameWindow.document.title && GameWindow.document.body ) {
		GameWindow.document.title = PageTitlePrefix + GameName + PageTitleSuffix;
		if ( !AlreadyResized ) {
			var innerw = GameWindow.innerWidth || GameWindow.document.documentElement.clientWidth || GameWindow.document.body.clientWidth;
			var innerh = GameWindow.innerHeight || GameWindow.document.documentElement.clientHeight || GameWindow.document.body.clientHeight;	
			if ( innerw > GameWindow.innerHeight * DefWidth / DefHeight ) {
				GameWindow.resizeTo( innerh * DefWidth / DefHeight + ( GameWindow.outerWidth - innerw ), screen.availHeight );
				GameWindow.moveTo( ( screen.availWidth - GameWindow.outerWidth ) / 2, ( screen.availHeight - GameWindow.outerHeight ) / 2 );
				AlreadyResized = true;
			}
			else if ( GameWindow.innerHeight > innerw * DefHeight / DefWidth ) {
				GameWindow.resizeTo( screen.availWidth, innerw * DefHeight / DefWidth + ( GameWindow.outerHeight - innerh ) );
				GameWindow.moveTo( ( screen.availWidth - GameWindow.outerWidth ) / 2, ( screen.availHeight - GameWindow.outerHeight ) / 2 );
				AlreadyResized = true;
			}
		}
	}
	else setTimeout( settitle, 10 );
}