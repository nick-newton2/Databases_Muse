<?php

$servername = "localhost";
$username = "hjeon";
$password = "na0103Yeh|";
$conn = NULL;

try {
    $conn = new PDO("mysql:host=$servername;dbname=hjeon", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="bkgs/M_logo.png" type="image/icon type">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Muse Home</title>
    <link rel="stylesheet" href="css/sty.css">
	  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
	  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">

	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="app.js"></script>
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
</head>

<body onload="onPageLoad()" class="bkg_im">
  <header>
      <div class="container_h">
      <h3 class="logo">MUSE Home</h3>

        <nav>
          <ul>
            <li><a style="font-weight:bold" href="http://db.cse.nd.edu/cse30246/muse/">Home</a></li>
            <li><a href="http://db.cse.nd.edu/cse30246/muse/devplan.html">DevPlan</a></li>
            <li><a href="http://db.cse.nd.edu/cse30246/muse/project.html">About</a></li>
            <li><a href="http://db.cse.nd.edu/cse30246/muse/login.php">Admin</a></li>
            <li><a href="http://db.cse.nd.edu/cse30246/muse/oauth.php">Login</a></li>

          </ul>
        </nav>
      </div>
    </header>

    <h1></h1>

  <div>
    <div class="content-container-full">
        <div class="row">
            <h2>Song Search:</h2>
        </div>
        <div class="row">
            <div class="col">
                <input id="searchText" class="form-control me-2" type="text" name="searched_song_name" placeholder="Enter the song name ..." aria-label="Search">
            </div>
            <div class="col">
                <button id="searchButton" class="btn btn-outline-success" type="button" onclick="getTable();">Search</button>
            </div>
        </div>

        <div class="row">
	        <table id="songTable" class="display"></table>
	    </div>

    </div>



    <script type="text/javascript">

        $('#searchText').keyup(function(event) {
            event.preventDefault();
            if (event.keyCode === 13) {
                $("#searchButton").click();
            }
        });

        function msToMinutesSeconds(data){
            var minutes = Math.floor(data / 60000);
            var seconds = ((data % 60000) / 1000).toFixed(0);
            return minutes + ":" + (seconds < 10 ? '0' : '') + seconds;
        }

        function artistTrim(data){
            if(data.match(/'([^']+)'/) !== null){
                return data.match(/'([^']+)'/)[1]
            } else{
                return ""
            };
        }

        function getTable(){
            console.log($('#searchText').val());
            var searchText = $('#searchText').val();
            var $response;
            $.ajax({
                type: 'POST',
                data: {'searched_song_name': searchText},
                url: 'http://db.cse.nd.edu/cse30246/muse/requestHandler.php',
                success:function(response){
                    var data = JSON.parse(response);
                    console.log(data);
                    if($.fn.DataTable.isDataTable("#songTable")) {
                        $('#songTable').DataTable().clear().destroy();
                    }
                    $('#songTable').DataTable({
			            data: data,
                        language: {
                            "search": "Filter : "
                        },
			            columns: [
                            {"title": "Song Name", "data": "name",
                                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                                    $(nTd).html("<a href='song.php?song_id=" + oData.id + "'>" + oData.name + "</a>");
                                }
                            },
                            {"title": "Song ID", "data": "id"},
                            {"title": "Artist", "data": "artists", "render": artistTrim},
                            {"title": "Album", "data": "album"},
                            {"title": "Duration", "data": "duration_ms", "render": msToMinutesSeconds},
                            {"title": "Year", "data": "year"}
                        ]
		            });
                }
            });
        }
	</script>

    <div class="footer">
        <p>MUSE Inc.</p>
    </div>
  </div>

</body>

</html>
