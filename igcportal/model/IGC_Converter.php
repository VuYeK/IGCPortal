<?php

/**
 * Created by PhpStorm.
 * User: VuYeK
 * Date: 26.08.2016
 * Time: 12:03
 */
class IGC_Converter
{
    //Tablica rekordów pliku
    public $lines = array();

    /**
     * IGC_Converter constructor.
     * @param $url - link do pliku IGC
     */
    public function __construct($url)
    {
        $igcUrl = @fopen($url, "r");
        if ($igcUrl) {
            while (($buffer = fgets($igcUrl)) !== FALSE) {
                array_push($this->lines, $this->getLine($buffer));
            }
        }
    }


    /**
     * Funkcja generująca tablicę rekordu
     * @param $string - linia z IGC
     * @return array - tablica z typem oraz całą linią
     */
    public function getLine($string)
    {
        return array(
            "type" => strtoupper(substr($string, 0, 1)),
            "line" => $string,
        );
    }


    /**
     * Funkcja pobierająca szczegóły lotu oraz generująca skrpyt do rysowania mapy z GoogleMapsAPI
     * @param Flight $flight - lot do uzupełnienia
     */
    public function getDetails(Flight $flight)
    {
        if (is_array($this->lines)) {
            $start_flag = false;

            //Inicjalizacja mapy
            $mapCode = '
<br /><br />
<div id="map" style="width: 40%; height: 460px; border: 3px solid #434343; text-align: center; margin-left: auto; margin-right: auto"></div>

<script type="text/javascript">


        var map = new GMap2(document.getElementById("map"));
        ';

            foreach ($this->lines as $each) {

                //Obsługa rekorków typu H
                if ($each['type'] == 'H') {
                    $record = $this->H_Type($each);
                    switch ($record['type']) {
                        case 'DTE': {
                            $flight->flightDateTime->setDate('20' . substr($record['value'], 4, 2),
                                substr($record['value'], 2, 2),
                                substr($record['value'], 0, 2));
                            break;
                        }

                        case 'FXA': {
                            $flight->fixAccurancy = (int)$record['value'];
                            break;
                        }

                        case 'PLT': {
                            $tmp = strstr($record['value'], ':');
                            $flight->pilot = substr($tmp, 1);
                            break;
                        }

                        case 'GTY': {
                            $tmp = strstr($record['value'], ':');
                            $flight->gliderType = substr($tmp, 1);
                            break;
                        }

                        case 'GID': {
                            $tmp = strstr($record['value'], ':');
                            $flight->gliderId = substr($tmp, 1);
                            break;
                        }

                        case 'DTM': {
                            $tmp = strstr($record['value'], ':');
                            $flight->GPSdatum = substr($tmp, 1);
                            break;
                        }

                        case 'RFW': {
                            $tmp = strstr($record['value'], ':');
                            $flight->loggerFirmware = substr($tmp, 1);
                            break;
                        }

                        case 'RHW': {
                            $tmp = strstr($record['value'], ':');
                            $flight->loggerHardware = substr($tmp, 1);
                            break;
                        }

                        case 'FTY': {
                            $tmp = strstr($record['value'], ':');
                            $flight->loggerType = substr($tmp, 1);
                            break;
                        }

                        case 'CCL': {
                            $tmp = strstr($record['value'], ':');
                            $flight->glideClass = substr($tmp, 1);
                            break;
                        }
                    }
                } //Obsługa rekordów typu B
                elseif ($each['type'] == 'B') {
                    $record = $this->B_Type($each);

                    $record_time = clone $flight->flightDateTime;
                    $record_time->setTime($record['timeH'],
                        $record['timeM'],
                        $record['timeS']);
                    if (!$start_flag) {
                        $start_flag = true;
                        $flight->flightDateTime = $record_time;
                        //Punkt początkowy
                        $flight->startPoint = $record['latitude']['decimal_degrees'] . ',' . $record['longtitude']['decimal_degrees'];

                        //Początek mapy
                        $mapCode .= "map.setCenter(new GLatLng(" . $record['latitude']['decimal_degrees'] . ", " . $record['longtitude']['decimal_degrees'] . "), 13, G_SATELLITE_MAP);\n";
                        $mapCode .= "var polyline = new GPolyline([\n";
                    }

                    //Punkt końcowy
                    $flight->finishPoint = $record['latitude']['decimal_degrees'] . ',' . $record['longtitude']['decimal_degrees'];


                    //Obliczenie czasu lotu
                    $flight->flightDuration = $record_time->getTimestamp() - $flight->flightDateTime->getTimestamp();
                    $flight->flightDuration = (string)($flight->flightDuration / 3600 % 24) . ':' . (string)($flight->flightDuration / 60 % 60) . ':' . (string)($flight->flightDuration % 60);

                    //Min i max wysokość
                    if ($record['pressure_altitude'] > $flight->maxHeight) {
                        $flight->maxHeight = $record['pressure_altitude'];
                    } elseif ($record['pressure_altitude'] < $flight->minHeight) {
                        $flight->minHeight = $record['pressure_altitude'];
                    }


                    //Kolejne punkty mapy
                    $mapCode .= "new GLatLng(" . $record['latitude']['decimal_degrees'] . ", " . $record['longtitude']['decimal_degrees'] . "),\n";
                }
            }

            //Pobranie lokalizacji początkowej i końcowej z google api
            $dataArray = json_decode(@file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $flight->startPoint), true);
            $flight->startPoint = $flight->startPoint . ' --- ' . $dataArray['results'][0]['formatted_address'];

            $dataArray = json_decode(@file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $flight->finishPoint), true);
            $flight->finishPoint = $flight->finishPoint . ' --- ' . $dataArray['results'][0]['formatted_address'];

            //Zakończenie mapy
            $mapCode .= '
        ], "#FF0000", 2);
        map.addOverlay(polyline);
    </script>';
            $flight->mapCode = $mapCode;
        }
    }


    /**
     * Obsługa rekordów typu H
     * @param $record - rekord typu H
     * @return mixed - tablica z rozłożonym rekordem
     */
    public function H_Type($record)
    {
        $source = substr($record['line'], 1, 1);
        $code = substr($record['line'], 2, 3);

        $H_result['type'] = $code;
        $H_result['source'] = $source;
        $H_result['value'] = substr($record['line'], 5);

        return $H_result;
    }


    /**
     * Obsługa rekordów typu B
     * @param $record - rekord typu B
     * @return mixed - tablica z danymi z rekordu
     */
    public function B_Type($record)
    {
        // Czas
        $B_result['timeH'] = substr($record['line'], 1, 2);
        $B_result['timeM'] = substr($record['line'], 3, 2);
        $B_result['timeS'] = substr($record['line'], 5, 2);

        // Szerokość geograficzna
        $latitude = array();
        $latitude['degrees'] = substr($record['line'], 7, 2);
        $latitude['minutes'] = substr($record['line'], 9, 2);
        $latitude['decimal_minutes'] = substr($record['line'], 11, 3);
        $latitude['direction'] = substr($record['line'], 14, 1);

        $pm = $latitude['direction'] == "S" ? "-" : "";
        $dd = round((((int)$latitude['minutes'] . "." . (int)$latitude['decimal_minutes']) / 60) + (int)$latitude['degrees'], 6);
        $latitude['decimal_degrees'] = $pm . $dd;

        //Zapisanie danych szzerokości do tablicy wynikowej
        $B_result['latitude'] = $latitude;


        // Długość geograficzna
        $longitude = array();
        $longitude['degrees'] = substr($record['line'], 15, 3);
        $longitude['minutes'] = substr($record['line'], 18, 2);
        $longitude['decimal_minutes'] = substr($record['line'], 20, 3);
        $longitude['direction'] = substr($record['line'], 23, 1);

        $pm = $longitude['direction'] == "W" ? "-" : "";
        $dd = round((((int)$longitude['minutes'] . "." . (int)$longitude['decimal_minutes']) / 60) + (int)$longitude['degrees'], 6);
        $longitude['decimal_degrees'] = $pm . $dd;

        //Zapisanie danych długości do tablicy wynikowej
        $B_result['longtitude'] = $longitude;

        // Wysokość ciśnieniowa
        $B_result['pressure_altitude'] = intval(substr($record['line'], 25, 5));

        // Wysokość GPS
        $B_result['gps_altitude'] = intval(substr($record['line'], 30, 5));


        return $B_result;
    }
}