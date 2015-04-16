<?php
/**
 * staticMapLite 0.3.1
 * Copyright 2009 Gerhard Koch
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0
 * @author Gerhard Koch <gerhard.koch AT ymail.com>
 *
 * Simplifié et retouché pour PetiLabo par Philippe GILLES
 */

class openstreetmap {
    protected $tileSize = 256;
    protected $tileSrcUrl = array('mapnik' => 'http://tile.openstreetmap.org/{Z}/{X}/{Y}.png',
        'osmarenderer' => 'http://otile1.mqcdn.com/tiles/1.0.0/osm/{Z}/{X}/{Y}.png',
        'cycle' => 'http://a.tile.opencyclemap.org/cycle/{Z}/{X}/{Y}.png',
    );

	// Propriétés des marqueurs
    protected $tileDefaultSrc = 'mapnik';
    protected $osmLogo = 'osm-logo.png';
	protected $markerFilename = 'osm-marker.png';
	protected $markerShadow = 'osm-marker-shadow.png';
	protected $markerImageOffsetX = -10;protected $markerImageOffsetY = -25;
	protected $markerShadowOffsetX = -1;protected $markerShadowOffsetY = -13;

	// Propriétés pour cartes en mode site (balise <carte>)
	protected $lat = 0;protected $lon = 0;
	protected $width = 400;protected $height = 400;
	protected $image = null;
    protected $centerX, $centerY, $offsetX, $offsetY;
	private $isMapSite = false;
	
	// Propriétés pour cartes en mode admin (PetiLabo Analitix)
	protected $from_x = 0;protected $from_y = 0;
	protected $to_x = 0;protected $to_y = 0;
	private $isMapAdmin = false;
	
	// Propriétés communes aux deux modes
	protected $zoom = 0;
    protected $markerBaseDir = null;
	protected $maptype = null;

	public function __construct() {
        $this->maptype = $this->tileDefaultSrc;
	}
    public function prepareMapSite($root, $lat, $lon, $width, $height, $zoom) {
        $this->lat = $lat;$this->lon = $lon;
        $this->width = $width;$this->height = $height;
        $this->zoom = $zoom;
	    $this->markerBaseDir = $root."images";
		$this->isMapSite = true;
    }
    public function prepareMapAdmin($root, $zoom, $from_x, $from_y, $to_x, $to_y) {
        $this->zoom = $zoom;
        $this->from_x = $from_x;$this->from_y = $from_y;
        $this->to_x = $to_x;$this->to_y = $to_y;
	    $this->markerBaseDir = $root."images";
		$this->isMapAdmin = true;
    }

    private function lonToTile($long, $zoom) {
        return (($long + 180) / 360) * pow(2, $zoom);
    }

    private function latToTile($lat, $zoom) {
        return (1 - log(tan($lat * pi() / 180) + 1 / cos($lat * pi() / 180)) / pi()) / 2 * pow(2, $zoom);
    }

    private function initCoords() {
        $this->centerX = $this->lonToTile($this->lon, $this->zoom);
        $this->centerY = $this->latToTile($this->lat, $this->zoom);
        $this->offsetX = floor((floor($this->centerX) - $this->centerX) * $this->tileSize);
        $this->offsetY = floor((floor($this->centerY) - $this->centerY) * $this->tileSize);
    }

    private function createSiteMap() {
        $this->image = imagecreatetruecolor($this->width, $this->height);
        $startX = floor($this->centerX - ($this->width / $this->tileSize) / 2);
        $startY = floor($this->centerY - ($this->height / $this->tileSize) / 2);
        $endX = ceil($this->centerX + ($this->width / $this->tileSize) / 2);
        $endY = ceil($this->centerY + ($this->height / $this->tileSize) / 2);
        $this->offsetX = -floor(($this->centerX - floor($this->centerX)) * $this->tileSize);
        $this->offsetY = -floor(($this->centerY - floor($this->centerY)) * $this->tileSize);
        $this->offsetX += floor($this->width / 2);
        $this->offsetY += floor($this->height / 2);
        $this->offsetX += floor($startX - floor($this->centerX)) * $this->tileSize;
        $this->offsetY += floor($startY - floor($this->centerY)) * $this->tileSize;

        for ($x = $startX; $x <= $endX; $x++) {
            for ($y = $startY; $y <= $endY; $y++) {
                $url = str_replace(array('{Z}', '{X}', '{Y}'), array($this->zoom, $x, $y), $this->tileSrcUrl[$this->maptype]);
                $tileData = $this->fetchTile($url);
                if ($tileData) {
                    $tileImage = imagecreatefromstring($tileData);
                } else {
                    $tileImage = imagecreate($this->tileSize, $this->tileSize);
                    $color = imagecolorallocate($tileImage, 255, 255, 255);
                    @imagestring($tileImage, 1, 127, 127, 'err', $color);
                }
                $destX = ($x - $startX) * $this->tileSize + $this->offsetX;
                $destY = ($y - $startY) * $this->tileSize + $this->offsetY;
                imagecopy($this->image, $tileImage, $destX, $destY, 0, 0, $this->tileSize, $this->tileSize);
            }
        }
    }
	
    private function createAdminMap() {
		$width = ((int) (1 + $this->to_x - $this->from_x)) * $this->tileSize;
		$height = ((int) (1 + $this->to_y - $this->from_y)) * $this->tileSize;
        $this->image = imagecreatetruecolor($width, $height);
        for ($x = $this->from_x; $x <= $this->to_x; $x++) {
            for ($y = $this->from_y; $y <= $this->to_y; $y++) {
                $url = str_replace(array('{Z}', '{X}', '{Y}'), array($this->zoom, $x, $y), $this->tileSrcUrl[$this->maptype]);
                $tileData = $this->fetchTile($url);
                if ($tileData) {
                    $tileImage = imagecreatefromstring($tileData);
                } else {
                    $tileImage = imagecreate($this->tileSize, $this->tileSize);
                    $color = imagecolorallocate($tileImage, 255, 255, 255);
                    @imagestring($tileImage, 1, 127, 127, 'err', $color);
                }
                $destX = ((int) ($x - $this->from_x)) * $this->tileSize;
                $destY = ((int) ($y - $this->from_y)) * $this->tileSize;
                imagecopy($this->image, $tileImage, $destX, $destY, 0, 0, $this->tileSize, $this->tileSize);
            }
        }
    }

    private function placeMarker() {	
		// set some local variables
		$markerLat = $this->lat;
		$markerLon = $this->lon;
		$markerImg = imagecreatefrompng($this->markerBaseDir . '/' . $this->markerFilename);
		$markerShadowImg = imagecreatefrompng($this->markerBaseDir . '/' . $this->markerShadow);

		// calc position
		$destX = floor(($this->width / 2) - $this->tileSize * ($this->centerX - $this->lonToTile($markerLon, $this->zoom)));
		$destY = floor(($this->height / 2) - $this->tileSize * ($this->centerY - $this->latToTile($markerLat, $this->zoom)));

		// copy shadow on basemap
		if ($markerShadowImg) {
			imagecopy($this->image, $markerShadowImg, $destX + intval($this->markerShadowOffsetX), $destY + intval($this->markerShadowOffsetY), 0, 0, imagesx($markerShadowImg), imagesy($markerShadowImg));
		}

		// copy marker on basemap above shadow
		imagecopy($this->image, $markerImg, $destX + intval($this->markerImageOffsetX), $destY + intval($this->markerImageOffsetY), 0, 0, imagesx($markerImg), imagesy($markerImg));
    }

    private function fetchTile($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0");
        curl_setopt($ch, CURLOPT_URL, $url);
        $tile = curl_exec($ch);
        curl_close($ch);
        return $tile;
    }

    private function copyrightNotice() {
        $logoImg = imagecreatefrompng($this->markerBaseDir . '/' . $this->osmLogo);
        imagecopy($this->image, $logoImg, imagesx($this->image) - imagesx($logoImg), imagesy($this->image) - imagesy($logoImg), 0, 0, imagesx($logoImg), imagesy($logoImg));
    }

    public function makeMap($src) {
		if ($this->isMapSite) {
			$this->initCoords();
			$this->createSiteMap();
			$this->placeMarker();
			$this->copyrightNotice();
		}
		elseif ($this->isMapAdmin) {
			$this->createAdminMap();
			$this->copyrightNotice();
		}
		if ($this->image) {
			@imagejpeg($this->image, $src, 90);
			@imagedestroy($this->image);
		}
    }
}