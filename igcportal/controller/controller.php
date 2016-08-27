<?php
header('Content-type: application/json');
$url = $_GET['url'];

//Importy
function modelLoader($class)
{
    require_once '../model/' . $class . '.php';
}

spl_autoload_register('modelLoader');

//Utworzenie obiektów
$flight = new Flight();
$igcConverter = new IGC_Converter($url);

spl_autoload_unregister('modelLoader');

//Pobranie szczegółoów
$igcConverter->getDetails($flight);

//Generowanie JSONa
$jsonArray = array('flight' => $flight);
echo json_encode($jsonArray);
