<?php include("header.php"); ?>

<script src="js/mpd-play.js" type="text/javascript"></script>



    <div class="container starter-template" id="startseite">        
        <div class="row">

            <div class="col-md-10 col-xs-12">
                <div class="notifications top-right"></div>

                <div class="panel panel-primary">
                <!-- Default panel contents -->                
                    <div id="panel-heading" class="panel-heading"></div>
                    <div class="panel-body"  id="innerContent">
                        <span id="connectedMessage" class="small grey"></span>
                        <h2>
                            <span id="track-icon" class="glyphicon glyphicon-play"></span>
                            <span id="Track"></span>
                        </h2>
                        <div id="trackinfo">
                             <h4>
                                 <span class="h6">Album:</span></br >
                                 <span id="Album" class="text"></span><br />
                                 <span class="h6">Artist:</span><br />
                                 <span id="Artist" class="text"></span>
                             </h4>
                         </div>

			<span id="position"></span>
			<span id="position-sek" style="display:none">0</span>
                        
                        <div id="progressbar" class="progress progress-striped active"></div>
                   


                    </div><!-- /.panel-body -->

                    <!-- Table -->
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th></th>
                                <th>Track</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody id="playlist">
                        </tbody>
                    </table>
               </div><!-- /.panel -->
           </div><!-- /.col-md-10 -->

            <div class="col-md-2 col-xs-12" >
                <div class="btn-toolbar">
                    <div class="btn-group-vertical btn-block btn-group-lg" data-toggle="buttons">
                        <button id="shuffleCheckbox" type="button" class="btn btn-default">
                            <span class="glyphicon glyphicon-random"></span> Random
                        </button>
                        <button id="singleCheckbox" type="button" class="btn btn-default">
                            <span class="glyphicon glyphicon-star"></span> Single
                        </button>
                        <button id="repeatCheckbox" type="button" class="btn btn-default">
                            <span class="glyphicon glyphicon-repeat"></span> Repeat
                        </button>
                        <button id="consumeCheckbox" type="button" class="btn btn-default">
                            <span class="glyphicon glyphicon-fire"></span> Consume
                        </button>                        
                    </div>

                    <div id="btn-responsive-block" class="btn-group-vertical btn-block btn-group-lg">
                        <button type="button" class="btn btn-default" id="clearButton">
                            <span class="glyphicon glyphicon-trash"></span> Clear
                        </button>
                        <button type="button" class="btn btn-default" id="saveButton">
                            <span class="glyphicon glyphicon-save"></span> Save
                        </button>
                        
                    </div>

                </div>
            </div><!-- /.col-md-2 -->
        </div><!-- /.row -->
    </div><!-- /.container -->

    
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Was tun?</h4>
      </div>
      <div class="modal-body">
          <span id="modal-trackid" class="h4"></span>. <i><span id="modal-track" class="h4"></span></i>      
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal" onclick="playSong()">Abspielen</button>          
        <button type="button" class="btn btn-warning" data-dismiss="modal" onclick="removeSong()">Löschen</button>
        
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
    
<div class="modal" id="emptyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Playlist leeren?</h4>
      </div>
      <div class="modal-body">
          <span>Sicher, dass du die Warteschlange leeren willst?</span>     
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Abbrechen</button>          
        <button type="button" class="btn btn-warning" data-dismiss="modal" onclick="clearPlayQueue()">Leeren</button>
        
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>   
    
<div class="modal" id="saveModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Playlist speichern?</h4>
      </div>
      <div class="modal-body">
          <form role="form">
            <div class="form-group" id="playListNameForm">
              <label class="control-label" for="playListName">Name der neuen Playlist:</label>
              <input type="text" class="form-control" id="playListName" placeholder="...">
            </div>
          </form>
          <span id="serverResponse"></span>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Abbrechen</button>          
        <button type="button" class="btn btn-warning" id="modal-saveButton" onclick="savePlayQueue()">Speichern</button>
        
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>     




<?php include("footer.php"); ?>

