<!-- hide script from old browsers

var type = "IE";	//Variable used to hold the browser name
var agt=navigator.userAgent.toLowerCase();
BrowserSniffer();

//detects the capabilities of the browser
function BrowserSniffer() {
	if (navigator.userAgent.indexOf("Opera")!=-1 && document.getElementById) type="OP";		//Opera
	else if (document.all) type="IE";														//Internet Explorer e.g. IE4 upwards
	else if (document.layers) type="NN";													//Netscape Communicator 4
	else if (!document.all && document.getElementById) type="MO";							//Mozila e.g. Netscape 6 upwards
	else if (agt.indexOf("safari") != -1) type="SF";							            //Safari
	else type = "IE";		//I assume it will not get here
}

function ShowLayer(id, action){
	if (type=="IE") eval("document.all." + id + ".style.display='" + action + "'");
	if (type=="NN") eval("document." + id + ".display='" + action + "'");
	if (type=="MO" || type=="OP" || type=="SF") eval("document.getElementById('" + id + "').style.display='" + action + "'");

}

function visiLayer(id, action){
	if (type=="IE") eval("document.all." + id + ".style.visibility='" + action + "'");
	if (type=="NN") eval("document." + id + ".visibility='" + action + "'");
	if (type=="MO" || type=="OP" || type=="SF") eval("document.getElementById('" + id + "').style.visibility='" + action + "'");

}

var cur_lyr;
function swapLayers(id) {
  if (cur_lyr) hideLayer(cur_lyr);
  showLayer(id);
  cur_lyr = id;
}

function showLayer(id) {
  var lyr = getElemRefs(id);
  if (lyr && lyr.css) lyr.css.display = "block";
}

function hideLayer(id) {
  var lyr = getElemRefs(id);
  if (lyr && lyr.css) lyr.css.display = "none";
}

function getElemRefs(id) {
	var el = (document.getElementById)? document.getElementById(id): (document.all)? document.all[id]: (document.layers)? document.layers[id]: null;
	if (el) el.css = (el.style)? el.style: el;
	return el;
}

// end hiding script from old browsers -->

var newWindow;

function subWindow() {
    if (arguments.length < 1) {
        alert("function subWindow called with " + arguments.length +
              " arguments, but it expects at least 1 arguments.");
        return null;
    }
    var heightval = arguments[1] != null ? arguments[1] : 480;
    var widthval = arguments[2] != null ? arguments[2] : 640;
    var filename = arguments[3] != null ? arguments[3] : "";
    var dimensions = "directory=0,height="+heightval+",width="+widthval+
                     ",left=30,top=80,resizable=1,statusbar=0,hotkeys=0,menubar=0,scrollbars=1,status=0,toolbar=0";
    var newWindow = window.open(filename,arguments[0],dimensions);
	{newWindow.focus()}

    if (!filename) {
        newWindow.document.write("<title>B&H Photo-Video Pro Audio, The world's largest dealer of imaging equipment at discount prices</title>")
        newWindow.document.write("<center><font size=4 color='red'>Loading, please wait...</font></center>")
    }
    return;
}

// Resize Reload -->
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->

//Self Close Window
var closeTime = 0;
function closeWindow(closeTime){
setTimeout("self.close()",closeTime);
}
