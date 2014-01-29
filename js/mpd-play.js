var updatePage = function() {

        getStatus();      
        
        // Update Elemente
        $("#panel-heading").text(MPD.connectedMessage);
        $("#state").text(MPD.status);  
        
	if (MPD.status == "stop") {
		MPD.track = "";
		MPD.artist = "";
		MPD.album = "";
                MPD.position = '0';
                MPD.dauer = "0";                
	}         
        
        $('#track-icon').removeClass("glyphicon-play")
        .removeClass("glyphicon-pause")
        .removeClass("glyphicon-stop");
        $('#track-icon').addClass("glyphicon-" + MPD.status);        
        
        $("#Track").text(MPD.track);
        $("#Artist").text(MPD.artist);
        $("#Album").text(MPD.album);        

        $("#position-sek").text(MPD.position-1);
        $("#position").text(convertSecsToMinsSecs(MPD.position) + " : " + convertSecsToMinsSecs(MPD.dauer));	
        
        var progress = Math.floor(100*MPD.position/MPD.dauer);
        $('#progressbar').slider(progress);    
        
        if (MPD.random == 1) 
            $("#shuffleCheckbox").button('toggle');
        
        if (MPD.repeat == 1)
            $("#randomCheckbox").button('toggle');     

        updateZeit(MPD.position);
	updatePlayQueue();        
}

var spulen = function(zeit) {
    var action = "spulen&zeit=" + zeit;
    mpdAction(action, false);
}

var updatePlayQueue = function() {
    mpdQuery("playqueue", function(listInhalt){
        $("#playlist").html(formatPlayQueue(listInhalt));
    });    
}

var formatPlayQueue = function(trackString) {
  var listOfTracks = trackString.split("\n");
  listOfTracks.pop(); // Get rid of the last element (it's only there because of the newline)
  
  // Format the tracks
  var formatedTracks  = "";
  for (index in listOfTracks) {
    var trackInfo = listOfTracks[index].split("#:#");
    
    var trackNumber = trackInfo[0];
    var isPlaying   = trackInfo[1];
    var title       = trackInfo[2];
    var artist      = trackInfo[3];
    var time        = trackInfo[4];
    var playIcon;
    
    if (isPlaying=="play") {
        playIcon = '<span class="glyphicon glyphicon-play"> </span>';
    } else {
        playIcon = '';
    }
    
    var formatedTrackname = artist + ' - ' + title;
    formatedTrackname = formatedTrackname.replace("'", "&#039;");    
    formatedTracks +=
    '  <tr class="playlistItemWrapper ' + isPlaying + '" onclick="songMenu(' + trackNumber + ', \'' + encodeURIComponent(formatedTrackname) + '\')">' +
    '    <td class="number"><a name="lied' + trackNumber + '"></a>' + trackNumber + '</td>' +
    '    <td class="' + isPlaying + '">' + playIcon + '</td>' +
    '    <td class="name">' + artist + ' - ' + title + '</td>' +
    '    <td class="time">' + convertSecsToMinsSecs(time) + '</td>' +
    '  </tr>';
  }
  return formatedTracks;
}


var songMenu = function(modalTrackid, modalTrack){
    $(function() {
        modalTrack = decodeURIComponent(modalTrack);
        modalTrack = modalTrack.replace("&#039;", "'");
        $( "#modal-trackid" ).text(modalTrackid);
        $( "#modal-track" ).text(modalTrack);        
        $( "#myModal" ).modal('show');        
    });  
}

var savePlayQueue = function() {
    var playListName = $( "#playListName" ).val();        
    if (!playListName) {
        $( "#playListNameForm" ).addClass("has-error");
        $( "#serverResponse").text("Du musst einen Namen eingeben");
    } else {
        $( "#playListNameForm" ).removeClass("has-error");
        
        var action = "PLSave&name=" + playListName;
        mpdAction(action, false);       
        
        $( "#playListNameForm" ).slideUp("slow");
        $( "#modal-saveButton" ).prop( "disabled", true );
        $( "#serverResponse").text("Playlist wurde unter " + playListName + " gespeichert");
    }
}

var removeSong = function(trackNumber) {
    if (!trackNumber) 
        trackNumber = $( "#modal-trackid" ).text();
    
    var action = "PLRemove&track=" + --trackNumber;
    mpdAction(action, true);
}


var playSong = function(trackNumber) {
    if (!trackNumber) 
        trackNumber = $( "#modal-trackid" ).text();
    
    var action = "SkipTo&track=" + --trackNumber;
    mpdAction(action, true); 
}

var clearPlayQueue = function(event) {
    var action = "ClearPlayQueue";
    mpdAction(action, true);
}

var updateZeit = function() {        
    var state = $("#state").text();
    if (state == "play") {    
        var altPos = $("#position-sek").text();
        var neuPos = parseInt(altPos) + 1;

        $("#position-sek").text(neuPos);

        var progress = Math.floor(100*neuPos/MPD.dauer);
        $('#progressbar').slider(progress);

        var Zeit = convertSecsToMinsSecs(neuPos) + " - " + convertSecsToMinsSecs(MPD.dauer)
        $("#position").text(Zeit);
        $("#position-sek").text(neuPos);        
        if (neuPos > MPD.dauer) {
            updatePage();
        }
    }
}

$(document).ready(function(){
    
    setInterval( "updateZeit()", 1000 ); // Auto update the page
    setInterval( "updatePage()", 10000 ); // Auto update the page
    updatePage();
    
    //$('#progressbar').slider(0);
    $("#progressbar").on('slider.newValue', function(evt,data){
            var seekVal = Math.ceil(MPD.dauer*(data.val/100));
            document.getElementById("position-sek").innerHTML = seekVal-1;            
            spulen(seekVal);   
            updateZeit(seekVal,false)
    });
    
    
    $( "#shuffleCheckbox" ).click(function() {

        if(MPD.random == 1){
            mpdAction("RandomOff", false);            
            MPD.random = 0;
        } else {                    
            mpdAction("RandomOn", false);            
            MPD.random = 1;
        }
        
    });

    $( "#repeatCheckbox" ).click(function() {
        if(MPD.repeat == 1){
            mpdAction("RepeatOff". false);
            MPD.repeat = 0;
        } else {        
            mpdAction("RepeatOn", false);
            MPD.repeat = 1;
        }          
        
    });
    
    $( "#clearButton" ).click(function() {
        $(function() {
            $( "#emptyModal" ).modal('show');        
        });  
    });

    $( "#saveButton" ).click(function() {
        $(function() {        
            $( "#playListName" ).val("");
            $( "#playListNameForm" ).show();
            $( "#modal-saveButton" ).prop( "disabled", false );
            $( "#serverResponse").text("");
            $( "#saveModal" ).modal('show');        
        });  
    });        
});        

