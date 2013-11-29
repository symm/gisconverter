<?php

namespace Symm\Gisconverter;

class Gisconverter {

    public function __construct()
    {

    }

    public static function wkt_to_geojson ($text) {
        $className = __NAMESPACE__ . '\\Decoders\\WKT';
        $decoder = new $className;
        return $decoder->geomFromText($text)->toGeoJSON();
    }
    public static function wkt_to_kml ($text) {
        $className = __NAMESPACE__ . '\\Decoders\\WKT';
        $decoder = new $className;
        return $decoder->geomFromText($text)->toKML();
    }
    public static function wkt_to_gpx($text) {
        $className = __NAMESPACE__ . '\\Decoders\\WKT';
        $decoder = new $className;
        return $decoder->geomFromText($text)->toGPX();
    }
    public static function geojson_to_wkt ($text) {
        $className = __NAMESPACE__ . '\\Decoders\\GeoJSON';
        $decoder = new $className;
        return $decoder->geomFromText($text)->toWKT();
    }
    public static function geojson_to_kml ($text) {
        $className = __NAMESPACE__ . '\\Decoders\\GeoJSON';
        $decoder = new $className;
        return $decoder->geomFromText($text)->toKML();
    }
    public static function geojson_to_gpx ($text) {
        $className = __NAMESPACE__ . '\\Decoders\\GeoJSON';
        $decoder = new $className;
        return $decoder->geomFromText($text)->toGPX();
    }
    public static function kml_to_wkt ($text) {
        $className = __NAMESPACE__ . '\\Decoders\\KML';
        $decoder = new $className;
        return $decoder->geomFromText($text)->toWKT();
    }
    public static function kml_to_geojson ($text) {
        $className = __NAMESPACE__ . '\\Decoders\\KML';
        $decoder = new $className;
        return $decoder->geomFromText($text)->toGeoJSON();
    }
    public static function kml_to_gpx ($text) {
        $className = __NAMESPACE__ . '\\Decoders\\KML';
        $decoder = new $className;
        return $decoder->geomFromText($text)->toGPX();
    }
    public static function gpx_to_wkt ($text) {
        $className = __NAMESPACE__ . '\\Decoders\\GPX';
        $decoder = new $className;
        return $decoder->geomFromText($text)->toWKT();
    }
    public static function gpx_to_geojson ($text) {
        $className = __NAMESPACE__ . '\\Decoders\\GPX';
        $decoder = new $className;
        return $decoder->geomFromText($text)->toGeoJSON();
    }
    public static function gpx_to_kml ($text) {
        $className = __NAMESPACE__ . '\\Decoders\\GPX';
        $decoder = new $className;
        return $decoder->geomFromText($text)->toGPX();
    }
}