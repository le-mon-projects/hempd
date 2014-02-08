var mpdControlFile = "mpd/mpdcontrol.php";
var MPD = new Object();

var convertSecsToMinsSecs = function(timeInSeconds) {
  var trackTime = new Date(0, 0, 0, 0, 0, timeInSeconds, 0);  
  
  return trackTime.getMinutes() + ":" +
          (trackTime.getSeconds() < 10 ? '0' : '') + trackTime.getSeconds();
}

var getStatus = function() {
    
    /* Ruft den Status des Servers ab */
    
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", mpdControlFile, false);
    xmlhttp.send();
    var serverResponse = xmlhttp.responseText;

    /* Verbindungscheck */

    if (serverResponse.search("Could not connect to the MPD server") != -1) {
        MPD.connectedMessage = "Connection to host failed";
    } else {
        var hostname = document.getElementById("hostname").content;
        MPD.connectedMessage = "Connected to " + hostname;
    }
    
    var serverResponseLine = serverResponse.split("\n");

    MPD.status = serverResponseLine[0];
    MPD.random = serverResponseLine[1];
    MPD.repeat = serverResponseLine[2];
    MPD.single = serverResponseLine[3];
    MPD.consume = serverResponseLine[4];
    MPD.playqueueCount = serverResponseLine[5];
    MPD.track = serverResponseLine[6];
    MPD.artist = serverResponseLine[7];
    MPD.album = serverResponseLine[8];
    MPD.position = serverResponseLine[9];
    MPD.dauer = serverResponseLine[10];   

}

var mpdAction = function(action, reload) {
    
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", mpdControlFile + "?action=" + action, false);
    xmlhttp.send(); 
    if ($("#startseite").length && reload == true){ 
        updatePage();
    }    
}

var mpdQuery = function(query, cb) {
    
  var xmlhttp = new XMLHttpRequest();
  
  xmlhttp.onreadystatechange=function(Warteschlange) {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            Warteschlange = xmlhttp.responseText;
    }
  }
  
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
            if( typeof cb === 'function' )
                cb(xmlhttp.responseText);
        }
    }  
  
  
  xmlhttp.open("GET", "mpd/mpdquery.php?request=" + query, true);
  xmlhttp.send();  

}

$( document ).ready(function() {
    
    getStatus();    

    if (MPD.status == "play") {
        $("#play-icon").removeClass("glyphicon-play");
        $("#play-icon").addClass("glyphicon-pause");
    }  

    if (MPD.status == "pause" || MPD.status == "stop") {
        $("#play-icon").removeClass("glyphicon-pause");
        $("#play-icon").addClass("glyphicon-play");
    }   
    
    /* Button event handler */
    
    $( "#nextButton" ).click(function() {        
	mpdAction("Next", true);        
    });
    
    $( "#prevButton" ).click(function() {        
	mpdAction("Previous", true);
    });    
    
    $( "#stopButton" ).click(function() {
        mpdAction("Stop", true);
        $("#play-icon").removeClass("glyphicon-pause");
        $("#play-icon").addClass("glyphicon-play");
        
    });    
    
    $( "#playPauseButton" ).click(function() {
        
	getStatus();                
        
        if (MPD.status == "play") {
            stateAction = "Pause";
            $("#play-icon").removeClass("glyphicon-pause");
            $("#play-icon").addClass("glyphicon-play");
        }  
        
        if (MPD.status == "pause" || MPD.status == "stop") {
            stateAction = "Play";                
            $("#play-icon").removeClass("glyphicon-play");
            $("#play-icon").addClass("glyphicon-pause");
        }
        
	mpdAction(stateAction, true);    
    });
    
    
});    
