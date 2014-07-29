<?php include("header.php");

require_once('mpd/mpdconfig.php');
require_once('mpd/mpd.class.php');
require_once('mpd/globalFunctions.php');

    $query = getUrlParam('query');

    $mode = getUrlParam('mode');

    $search_mode = getUrlParam('search_mode');

    $artist = getUrlParam('artist');

    $album = getUrlParam('album');

    if (($mode == 'artists') xor ($search_mode == 'artist')) {
        $search = 'for artist';
        $search_mode = 'artist';
    } elseif (($mode == 'albums') xor ($search_mode == 'album') xor ($album != NULL)) {
        $search = 'for album';
        $search_mode = 'album';
    } else {
        $search = 'any';
        $search_mode = 'any';
    }

?>

<script src="js/mpd-browse.js" type="text/javascript"></script>


    <div class="container starter-template">
        <div class="row">

            <div class="col-md-10 col-xs-12">
                <form action="browse.php" method="get">
                <div class="input-group">
                        <input type="text" name="mode" value="search" hidden></input>
                        <input type="text" name="search_mode" value="<?php print $search_mode; ?>" hidden></input>
                        <input type="text" name="query" class="form-control" placeholder="Search <?php print $search; ?>">
                        <span class="input-group-btn">
                            <button class="btn btn-success" type="submit">Go!</button>
                        </span>

                </div><!-- /input-group -->
                </form>
                <br />

                <?php

                switch ($mode) {
                    case "search":
                        // Suchergebnisse START

                        $mpd = new mpd($host,$mpdPort,$mpdPassword);
                        $searchResults = $mpd->Search($search_mode, $query);

                        ?>
                        <ol class="breadcrumb">
                            <li><a href="browse.php">Bibliothek</a></li>
                            <?php if (strlen($artist)!=0) { ?>
                                <li><a href="browse.php?mode=artists">Artists</a></li>
                                <li><a href="browse.php?mode=albums&query=<?php print urlencode($artist); ?>&artist=<?php print urlencode($artist); ?>"><?php print $artist; ?></a></li>
                            <?php } elseif (strlen($artist)==0 && strlen($album)!=0) { ?>
                                <li>Album</li>
                            <?php }
                            if (strlen($album)!=0) { ?>
                                <li class="active"><?php print $album; ?></li>
                            <?php } else { ?>
                                <li class="active">Suche: <?php print $query; ?></li>
                            <?php } ?>
                        </ol>

                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <span>Tracks:</span>
                                <button class="btn btn-xs btn-warning pull-right" onclick="searchModal('<?php print urlencode($query); ?>')">Alle hinzufügen</button>
                            </div>


                            <table class="table table-hover" id="suchergebnis">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Track</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>


                        <?php

                            if ($searchResults) {
                            // Then print the tracks

                                $number = 0;
                                foreach ($searchResults['files'] as $key => $tracks) {
                                    $track = $tracks['Artist']." - ".$tracks['Title'];
                                    $track = str_replace ('\"', '\\"', $track);
                                    $track = str_replace ("\'", "\\'", $track);
                                    $track = urlencode($track);

                                    if (array_key_exists('Album', $tracks)) {
                                        $result_album = "Album: ".$tracks['Album'];
                                    } else {
                                        $result_album = "no album information";
                                    }

                                    echo "<tr onclick='addTrackToPlayQueue(&#34;" . urlencode($tracks['file']) . "&#34;, &#34;" . $track . "&#34;)'>";
                                    echo "<td>" . ++$number . "</td>";
                                    echo "<td>" . $tracks['Artist'] . " - " . $tracks['Title'] . "<br /><span class='album-grey'>" . $result_album . "</span></td>";
                                    echo "<td>" . convertSecsToMinsSecs($tracks['Time']) . "</td></tr>";

                                }
                            } else echo "<tr><td colspan='2'>keine Ergebnisse für <i>" . $query . "</i> </td></tr>";

                        ?>
                                </tbody>
                            </table>


                        </div>

                        <?php
                        // Suchergebnisse ENDE
                        break;

                    case "artists":

                        // All Artists START

                        $mpd = new mpd($host,$mpdPort,$mpdPassword);
                        $artistsResults = $mpd->GetArtists();

                        // sort alphabetical
                        sort($artistsResults);

                        // delete 1st element because its NULL
                        if ($artistsResults[0] == NULL) unset ($artistsResults[0]);

                        ?>
                        <ol class="breadcrumb">
                          <li><a href="browse.php">Bibliothek</a></li>
                          <li class="active">Artists</li>
                        </ol>
                        <div class="list-group">
                            <a class="list-group-item active">
                                <span>Artists</span>
                            </a>

                        <?php
                            if ($artistsResults) {
                            // Then print the tracks
                                foreach ($artistsResults as $key => $artist) {
                                  echo "<a class='list-group-item' href='browse.php?mode=albums&query=".urlencode($artist)."&artist=".urlencode($artist)."'>";
                                  echo $artist;
                                  echo "</a>";

                                }
                            } else echo "<tr><td colspan='2'>keine Ergebnisse für <i>".$query."</i> </td></tr>";

                        ?>
                            </tbody>
                        </table>


                        <?php

                        // All Artists ENDE
                        break;

                    case "albums":
                        // All Albums START
                        $mpd = new mpd($host,$mpdPort,$mpdPassword);
                        $albumResults = $mpd->GetAlbums(urldecode($query));

                        // sort alphabetical
                        sort($albumResults);

                        // delete 1st element because its NULL
                        if ($albumResults[0] == NULL) unset ($albumResults[0]);

                        ?>
                        <ol class="breadcrumb">
                          <li><a href="browse.php">Bibliothek</a></li>
                          <?php if (strlen($artist)!=0) { ?>
                            <li><a href="browse.php?mode=artists">Artists</a></li>
                          <?php } ?>
                          <?php if (strlen($artist)==0 && strlen($album)==0) { ?>
                            <li>Alle Alben</li>
                          <?php } else {?>
                          <li class="active"><?php print $artist; ?></li>
                          <?php } ?>
                        </ol>
                        <div class="list-group">
                            <a class="list-group-item active">
                                <span>Album</span>
                            </a>


                        <?php

                        if ($albumResults) {
                        // Then print the tracks
                            foreach ($albumResults as $key => $album) {
							  echo "<a class='list-group-item' href='browse.php?mode=search&query=".urlencode($album)."&artist=".urlencode($artist)."&album=".urlencode($album)."'>";
                              //echo "<img src='$cover_art_url' /> ";
                              echo $album;
                              echo "</a>";

                            }
                        } else echo "<tr><td colspan='2'>keine Ergebnisse für <i>".$query."</i> </td></tr>";

                        ?>
                            </tbody>
                        </table>


                        <?php
                        // All Albums ENDE
                        break;
                    case "playlists":
                        // Alle Playlists START
                        ?>
                        <ol class="breadcrumb">
                            <li><a href="browse.php">Bibliothek</a></li>
                            <li class="active">Playlists</li>
                        </ol>
                        <div class="list-group">
                            <a class="list-group-item active">
                                <span>Playlists</span>
                            </a>
                        <?php
                        $mpd = new mpd($host,$mpdPort,$mpdPassword);
                        $playlistsResults = $mpd->GetDir();

                        // sort alphabetical
                        sort($playlistsResults['playlists']);


                        if ($playlistsResults) {
                        // Then print the tracks
                            foreach ($playlistsResults['playlists'] as $key => $playlist) {
                                echo "<a class='list-group-item' href='browse.php?mode=playlist&query=".urlencode($playlist)."'>";
                                echo $playlist;

                                $listsinfo = getPlaylistDate($mpd);
                                foreach ($listsinfo as $value) {
                                    if ($value['playlist']==$playlist)
                                        print " <p class='text-right small text-muted'> erstellt am ".formatTimeStamp($value['date'])."</p>";
                                }

                                echo "</a>";
                            }
                        } else echo "<span class='list-group-item'>Keine Playlists vorhanden</span>";

                        ?>

                        </div>

                        <?php
                        // Alle Playlists ENDE
                        break;
                    case "playlist":
                        // Einzelne Playlist START
                        //   $playlistTrackLiting = getDataArrayFromString($mpd->SendCommand("listplaylistinfo $playlistName"));
                        ?>
                        <ol class="breadcrumb">
                            <li><a href="browse.php">Bibliothek</a></li>
                            <li><a href="browse.php?mode=playlists">Playlists</a></li>
                            <li class="active"><?php print $query; ?></li>
                        </ol>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <span>Playlist: <?php print $query; ?></span>
                                <button class="btn btn-xs btn-warning pull-right" onclick="playlistModal('<?php print $query; ?>')">Alle hinzufügen</button>
                            </div>

                            <table class="table table-hover" id="suchergebnis">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Artist</th>
                                        <th>Track</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php
                            $mpd = new mpd($host,$mpdPort,$mpdPassword);
                            $playlistResults = ($mpd->SendCommand("listplaylistinfo ".$query));

                            $playlistResults = getDataArrayFromString($playlistResults);


                            if ($playlistResults) {
                            // Then print the tracks
                                $nummer = 1;
                                foreach ($playlistResults as $key => $track) {
                                    $song = $track['artist']." - ".$track['title'];
                                    $song = str_replace ('\"', '\\"', $song);
                                    $song = str_replace ("\'", "\\'", $song);
                                    $song = urlencode($song);
                                    echo "<tr onclick='addTrackToPlayQueue(&#34;".urlencode($track['File'])."&#34;, &#34;".$song."&#34;)'>";
                                        echo "<td>".$nummer++."</td>";
                                        echo "<td>".$track['artist']."</td>";
                                        echo "<td>".$track['title']."</td>";
                                        echo "<td>".convertSecsToMinsSecs($track['time'])."</td>";
                                    echo "</tr>";
                                }
                            } else echo "<tr><td colspan='3'>Keine Tracks in dieser Playlist gefunden</td></tr>";

                            echo "</tbody></table";
                        echo "</div>";
                        // einzelne Playlist ENDE
                        break;
                    case "radios":
                        // All Artists START
                        echo "Funktion noch nicht implementiert";
                        // All Artists ENDE
                        break;

                    default:
                        // Standardausgabe START

                    ?>

                    <div class="list-group">
                        <a class="list-group-item active">
                            <span>Bibliothek</span>
                        </a>


                        <a class="list-group-item" href="browse.php?mode=artists">
                                <span class="ArtistIcon image"></span>
                                <span class="name">Artists</span>
                                <span class="arrow"></span>
                        </a>
                        <a class="list-group-item" href="browse.php?mode=albums">
                                <span class="AlbumIcon image"></span>
                                <span class="name">Alben</span>
                                <span class="arrow"></span>
                        </a>
                        <a class="list-group-item" href="browse.php?mode=playlists">
                                <span class="PlaylistIcon image"></span>
                                <span class="name">Playlists</span>
                                <span class="arrow"></span>
                        </a>
                    <?php
                    /*  uncomment when radio-view gets implemented
                        <a class="list-group-item" href="browse.php?mode=radios">
                                <span class="TrackIcon image"></span>
                                <span class="name">Webradios</span>
                                <span class="arrow"></span>
                        </a>
                    */
                    ?>

                    </div>

                    <?php
                    // Standardausgabe ENDE

                }
                // switch ENDE

                ?>

            </div><!-- /.col-md-2 -->
        </div><!-- /.row -->
    </div><!-- /.container -->

    <div class="modal" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
              <p id="addModalTrack" class="h3"></p>
              <p> ..wurde zur Playlist hinzugefügt</p>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div>

    <div class="modal" id="allModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="myModalLabel">Alle hinzufügen?</h4>
            </div>
            <div class="modal-body">
                Sollen alle Lieder von <i><span id="modal-playlist"></span></i> hinzugefügt werden?
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Nein</button>
              <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="addAllSongsFromPlaylist()">OK</button>

            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div>

    <div class="modal" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="myModalLabel">Alle hinzufügen?</h4>
            </div>
            <div class="modal-body">
                <span id="modal-query" style="display:none;"></span>
                Sollen alle angezeigten Lieder hinzugefügt werden?
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Nein</button>
              <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="addAllSongsFromSearch()">OK</button>

            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div>


<?php include("footer.php"); ?>
