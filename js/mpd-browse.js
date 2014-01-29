var addTrackToPlayQueue = function(trackFileName, trackInfo) {
    var action = "AddToPlayQueue&track=" + trackFileName;
    mpdAction(action);
    trackInfo = decodeURIComponent(trackInfo);
    trackInfo = trackInfo.replace(/\+/g,' ');
    $( "#addModalTrack" ).text(trackInfo);          
    $( "#addModal" ).modal('show');          
}

var addAllSongsFromSearch = function() {
    var query = $( "#modal-query" ).text();    
    var action = "addAllSongsFromSearch&query=" + query;
    mpdAction(action);  
}

var addAllSongsFromPlaylist = function(playList) {
    var playList = $( "#modal-playlist" ).text();    
    var action = "addAllSongsFromPlaylist&playlist=" + playList
    mpdAction(action);     
}

var searchModal = function(query){
    $(function() {
        $( "#modal-query" ).text(query);
        $( "#searchModal" ).modal('show');        
    });  
}

var playlistModal = function(playList){
    $(function() {
        $( "#modal-playlist" ).text(playList);
        $( "#allModal" ).modal('show');        
    });  
}

