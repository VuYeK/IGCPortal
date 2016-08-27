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
     * Funkcja pobierająca szczegóły lotu
     * @param Flight $flight - lot do uzupełnienia
     */
    public function getDetails(Flight $flight)
    {
        if (is_array($this->lines)) {

            foreach ($this->lines as $each) {
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
                }
            }
        }
    }


    /**
     * Obsługa rekordów typu H
     * @param $record
     * @return mixed
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
}