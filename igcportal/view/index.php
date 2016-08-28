<!DOCTYPE html>
<html lang="pl">
<head>
    <title>IGCPortal</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="lib/jquery-3.1.0.min.js"></script>
    <style>
        /* Remove the navbar's default margin-bottom and rounded borders */
        .navbar {
            margin-bottom: 0;
            border-radius: 0;
        }

        /* Add a gray background color and some padding to the footer */
        footer {
            background-color: #f2f2f2;
            padding: 25px;
        }

        body {
            background-image: url('img/bg.png');
            background-repeat: repeat-y;
            background-size: 100% 100%;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">IGCPortal</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">STRONA GŁÓWNA</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="jumbotron" style="">
    <div class="container text-center">
        <h1>IGC PORTAL</h1>
        <p>Obsługa plików IGC dla paralotniarstwa</p>
    </div>
</div>


<div id="container1" class="container-fluid bg-1 text-center"
     style="background-color: rgba(94, 188, 153, 0.71); margin-top: -30px; padding: 40px; display: none">
    <h3><b>LINK DO PLIKU .IGC:</b></h3> <input type="text" id="url" class="form-control"
                                               style="width: 20%; margin-left: auto; margin-right: auto">
    <button type="button" id="seeDetails" class="btn btn-primary" onclick="getDetails()" style="margin-top: 15px;">
        SZCZEGÓŁY
    </button>
</div>

<br/><br/>

<div id="details-container" style="min-height: 700px">
    <div class="container-fluid bg-1 text-center" style="">
        <div id="details"></div>
    </div>
    <div class="container-fluid bg-1 text-center" style="">
        <div id="mapDetails"></div>
    </div>
</div>


<br/><br/>

<script>
    $(document).ready(function () {
        $('#container1').fadeIn(2500);
    })
</script>

<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyCzFMnn9r2mSNLdYx9e8fXgBRUpNrVBFxI"
        type="text/javascript"></script>
<script src="scripts.js"></script>


<footer class="container-fluid text-center">
    <p>(C) Michał Wójcik - VuYeK ™</p>
</footer>

</body>
</html>