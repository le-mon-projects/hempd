<!DOCTYPE html>
<html lang="de">
<head>
    <!-- meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="hemd - Musikkontrolle fürs Hemp">
    <meta name="author" content="andreas@haueise.net">
    <meta name="hostname"	id="hostname"	content="<?php echo `hostname`;?>" />           

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <script src="js/jquery-1.10.2.min.js"></script>    
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-slider.js"></script>    
    <script src="js/mpd-global.js" type="text/javascript"></script>       
	   
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">


    <!-- Custom styles for this template -->
    <link href="css/mpd.css" rel="stylesheet">
    <link href="assets/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">    
	
    <title>Radio Hemp 1</title>
</head>

<body>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo dirname($_SERVER['PHP_SELF']); ?>"><span class="glyphicon glyphicon-play-circle"></span> Start</a>
                <a class="navbar-brand" href="browse.php"><span class="glyphicon glyphicon-search"></span> Media</a>
            </div>
            <div class="collapse navbar-collapse">

                <div class="btn-toolbar navbar-btn navbar-right" role="toolbar">
                    <span id="state" style="display:none"></span>
                    <div class="btn-group">
                        <button type="button" class="btn btn-default" id="prevButton">
                            <span class="glyphicon glyphicon-backward"></span>
                        </button>
                        <button type="button" class="btn btn-default" id="stopButton">
                            <span id="stop-icon" class="glyphicon glyphicon-stop"></span>
                        </button>
                        <button type="button" class="btn btn-default" id="playPauseButton">
                            <span id="play-icon" class="glyphicon glyphicon-pause"></span>
                        </button>
                        <button type="button" class="btn btn-default" id="nextButton">
                            <span class="glyphicon glyphicon-forward"></span>
                        </button>
                    </div>
                </div>
            </div><!--/.nav-collapse -->
        </div>
    </div><!--/.nav-ende -->