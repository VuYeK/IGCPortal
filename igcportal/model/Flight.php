<?php

/**
 * Created by PhpStorm.
 * User: VuYeK
 * Date: 26.08.2016
 * Time: 11:54
 */
class Flight implements JsonSerializable
{
    public $flightDateTime;
    public $fixAccurancy;
    public $pilot;
    public $gliderType;
    public $gliderId;
    public $GPSdatum;
    public $loggerFirmware;
    public $loggerHardware;
    public $loggerType;
    public $glideClass;
    public $maxHeight;
    public $minHeight;
    public $flightDuration;
    public $startPoint;
    public $finishPoint;
    public $mapCode;

    /**
     * Flight constructor.
     */
    public function __construct()
    {
        $this->flightDateTime = new DateTime("1994-04-04");
        $this->fixAccurancy = 0;
        $this->pilot = '';
        $this->gliderType = '';
        $this->gliderId = '';
        $this->GPSdatum = '';
        $this->loggerFirmware = '';
        $this->loggerHardware = '';
        $this->loggerType = '';
        $this->glideClass = '';
        $this->maxHeight = 0;
        $this->minHeight = 80000;
        $this->flightDuration = 0;
        $this->startPoint = '';
        $this->finishPoint = '';
        $this->mapCode = '';
    }

    /**
     * Serializacja obiektu
     * @return array - tablica asocjacyjna z polami obiektu
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}