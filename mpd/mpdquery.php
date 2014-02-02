<?php

require_once('mpdconfig.php');
require_once('mpd.class.php');
require_once('globalFunctions.php');

$mpd = new mpd($host,$mpdPort,$mpdPassword);


  $request = getUrlParam('request');

switch ($request) {
  case "playqueue": printPlayQueue($mpd);                       break;
  case "totalTrackCount": printTotalTrackCount($mpd);           break;
  default:                                                      break;
}

$mpd->Disconnect();

/******************************************************************************/

function printPlayQueue($mpd) {

    $trackNumber = 1;
    foreach ($mpd->playlist as $song) {
        echo $trackNumber."#:#";
    
        // isPlaying
        if ($mpd->current_track_id == ($trackNumber - 1))
            echo "play";
        else
            echo "stop";
    
        echo "#:#";
    
        echo $song['Title']."#:#";  // title
        echo $song['Artist']."#:#"; // artist
        echo $song['Time']."#:#\n"; // time
    
        $trackNumber++;
  }
}


function printTotalTrackCount($mpd) {
  echo $mpd->num_songs;
}


?>
