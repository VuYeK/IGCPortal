<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="utf-8">

    <title>IGCPortal</title>
    <meta name="description" content="Obsługa plików IGC">
    <meta name="author" content="VuYeK">
    <meta name="keywords" content="igc, paragliding"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

    <link rel="shortcut icon" href="favicon.png"/>

    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!--<link rel="stylesheet" href="style.css">
    <script src="script.js"></script>-->
</head>

<body>
<h1>IGCPortal</h1>

<br/>

Link do pliku IGC: <input type="text" id="url">

<br/><br/>

<button type="button" id="seeDetails" onclick="getDetails()">SZCZEGÓŁY</button>

<br/><br/>

<div id="details"></div>

<script src="scripts.js"></script>
</body>
</html>