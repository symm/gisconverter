<?php

namespace Symm\Gisconverter;

class Gisconverter
{
    public function __construct()
    {

    }

    public static function wktToGeojson($text)
    {
        $className = __NAMESPACE__ . '\\Decoders\\WKT';
        $decoder = new $className;

        return $decoder->geomFromText($text)->toGeoJSON();
    }

    public static function wktToKml($text)
    {
        $className = __NAMESPACE__ . '\\Decoders\\WKT';
        $decoder = new $className;

        return $decoder->geomFromText($text)->toKML();
    }

    public static function wktToGpx($text)
    {
        $className = __NAMESPACE__ . '\\Decoders\\WKT';
        $decoder = new $className;

        return $decoder->geomFromText($text)->toGPX();
    }

    public static function wktToWkb($text, $is_hex_string = false)
    {
        $className = __NAMESPACE__ . '\\Decoders\\WKB';
        $decoder = new $className;

        return $decoder->geomFromText($text)->toWKB($is_hex_string);
    }

    public static function geojsonToWkt($text)
    {
        $className = __NAMESPACE__ . '\\Decoders\\GeoJSON';
        $decoder = new $className;

        return $decoder->geomFromText($text)->toWKT();
    }

    public static function geojsonToKml($text)
    {
        $className = __NAMESPACE__ . '\\Decoders\\GeoJSON';
        $decoder = new $className;

        return $decoder->geomFromText($text)->toKML();
    }

    public static function geojsonToGpx($text)
    {
        $className = __NAMESPACE__ . '\\Decoders\\GeoJSON';
        $decoder = new $className;

        return $decoder->geomFromText($text)->toGPX();
    }

    public static function geojsonToWkb($text, $is_hex_string = false)
    {
        $className = __NAMESPACE__ . '\\Decoders\\GeoJSON';
        $decoder = new $className;

        return $decoder->geomFromText($text)->toWKB($is_hex_string);
    }

    public static function kmlToWkt($text)
    {
        $className = __NAMESPACE__ . '\\Decoders\\KML';
        $decoder = new $className;

        return $decoder->geomFromText($text)->toWKT();
    }

    public static function kmlToGeojson($text)
    {
        $className = __NAMESPACE__ . '\\Decoders\\KML';
        $decoder = new $className;

        return $decoder->geomFromText($text)->toGeoJSON();
    }

    public static function kmlToGpx($text)
    {
        $className = __NAMESPACE__ . '\\Decoders\\KML';
        $decoder = new $className;

        return $decoder->geomFromText($text)->toGPX();
    }

    public static function kmlToWkb($text, $is_hex_string = false)
    {
        $className = __NAMESPACE__ . '\\Decoders\\KML';
        $decoder = new $className;

        return $decoder->geomFromText($text)->toWKB($is_hex_string);
    }

    public static function gpxToWkt($text)
    {
        $className = __NAMESPACE__ . '\\Decoders\\GPX';
        $decoder = new $className;

        return $decoder->geomFromText($text)->toWKT();
    }

    public static function gpxToGeojson($text)
    {
        $className = __NAMESPACE__ . '\\Decoders\\GPX';
        $decoder = new $className;

        return $decoder->geomFromText($text)->toGeoJSON();
    }

    public static function gpxToKml($text)
    {
        $className = __NAMESPACE__ . '\\Decoders\\GPX';
        $decoder = new $className;

        return $decoder->geomFromText($text)->toKML();
    }

    public static function gpxToWkb($text, $is_hex_string = false)
    {
        $className = __NAMESPACE__ . '\\Decoders\\GPX';
        $decoder = new $className;

        return $decoder->geomFromText($text)->toWKB($is_hex_string);
    }

    public static function wkbToWkt($text, $is_hex_string = false)
    {
        $className = __NAMESPACE__ . '\\Decoders\\WKB';
        $decoder = new $className;

        return $decoder->geomFromBinary($text, $is_hex_string)->toWKT();
    }

    public static function wkbToGeojson($text, $is_hex_string = false)
    {
        $className = __NAMESPACE__ . '\\Decoders\\WKB';
        $decoder = new $className;

        return $decoder->geomFromBinary($text, $is_hex_string)->toGeoJSON();
    }

    public static function wkbToKml($text, $is_hex_string = false)
    {
        $className = __NAMESPACE__ . '\\Decoders\\WKB';
        $decoder = new $className;

        return $decoder->geomFromBinary($text, $is_hex_string)->toKML();
    }

    public static function wkbToGpx($text, $is_hex_string = false)
    {
        $className = __NAMESPACE__ . '\\Decoders\\WKB';
        $decoder = new $className;

        return $decoder->geomFromBinary($text, $is_hex_string)->toGPX();
    }
}
