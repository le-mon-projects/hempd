<?php

function convertSecsToMinsSecs($seconds) {
	date_default_timezone_set('Europe/Berlin');
	$minutes = floor($seconds / 60);
	$seconds = $seconds % 60;
	return $minutes.":".date("s", $seconds);
	return $seconds;
}

function getDataArrayFromString($dataString) {


    $fileArray = explode("file: ", $dataString);

    array_shift($fileArray);

    $fileArrayIndex = -1;

    foreach ($fileArray as $track) {
    
        $trackArrayTemp[++$fileArrayIndex] = explode("\n", $track);
        $trackArray[$fileArrayIndex]['File'] = $trackArrayTemp[$fileArrayIndex][0]; 
        
        foreach ($trackArrayTemp[$fileArrayIndex] as $meta) {
            
            
            if (preg_match('/Title:/', $meta))
                $trackArray[$fileArrayIndex]['title'] = preg_replace("/Title: /", "", $meta);            

            if (preg_match('/Artist:/', $meta) && !preg_match('/AlbumArtist:/', $meta))
                $trackArray[$fileArrayIndex]['artist'] = preg_replace("/Artist: /", "", $meta);            
            
            if (preg_match('/Album:/', $meta))
                $trackArray[$fileArrayIndex]['album'] = preg_replace("/Album: /", "", $meta);            
            
            if (preg_match('/Time:/', $meta))
                $trackArray[$fileArrayIndex]['time'] = preg_replace("/Time: /", "", $meta);            
            
        }
    }
  

  return $trackArray;
 
}


function printArray($array) {
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

function getPlaylistDate($mpd) {
    $playlistInfo = ($mpd->SendCommand("listplaylists"));
    $playlistInfo = explode("\n", $playlistInfo);
    foreach ($playlistInfo as $value) {
        if (preg_match('/playlist:/', $value))
            $listsInfoArray[++$index]['playlist'] = preg_replace("/playlist: /", "", $value);                        
        if (preg_match('/Last-Modified:/', $value))
            $listsInfoArray[$index]['date'] = preg_replace("/Last-Modified: /", "", $value);                        
        
    }
    return $listsInfoArray;
}

function formatTimeStamp($time) {
    $str = substr($time, 8 ,2);
    $str .= ".";    
    $str .= substr($time, 5 ,2);
    $str .= ".";
    $str .= substr($time, 0 ,4);
    return $str;
}
?>
