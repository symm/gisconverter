<?php

namespace Symm\Gisconverter;

use Symm\Gisconverter\Decoders\DecoderFactory;

class Gisconverter
{
    public function __construct()
    {

    }

    private static function getDecoder($type)
    {
        return DecoderFactory::getDecoder($type);
    }

    protected static function getWktDecoder()
    {
        return self::getDecoder(DecoderFactory::WKT);
    }

    protected static function getGeoJsonDecoder()
    {
        return self::getDecoder(DecoderFactory::GEOJSON);
    }

    protected static function getKmlDecoder()
    {
        return self::getDecoder(DecoderFactory::KML);
    }

    protected static function getGpxDecoder()
    {
        return self::getDecoder(DecoderFactory::GPX);
    }

    public static function wktToGeojson($text)
    {
        return self::getWktDecoder()->geomFromText($text)->toGeoJSON();
    }

    public static function wktToKml($text)
    {
        return self::getWktDecoder()->geomFromText($text)->toKML();
    }

    public static function wktToGpx($text)
    {
        return self::getWktDecoder()->geomFromText($text)->toGPX();
    }

    public static function geojsonToWkt($text)
    {
        return self::getGeoJsonDecoder()->geomFromText($text)->toWKT();
    }

    public static function geojsonToKml($text)
    {
        return self::getGeoJsonDecoder()->geomFromText($text)->toKML();
    }

    public static function geojsonToGpx($text)
    {
        return self::getGeoJsonDecoder()->geomFromText($text)->toGPX();
    }

    public static function kmlToWkt($text)
    {
        return self::getKmlDecoder()->geomFromText($text)->toWKT();
    }

    public static function kmlToGeojson($text)
    {
        return self::getKmlDecoder()->toGeoJSON();
    }

    public static function kmlToGpx($text)
    {
        return self::getKmlDecoder()->geomFromText($text)->toGPX();
    }

    public static function gpxToWkt($text)
    {
        return self::getGpxDecoder()->geomFromText($text)->toWKT();
    }

    public static function gpxToGeojson($text)
    {
        return self::getGpxDecoder()->geomFromText($text)->toGeoJSON();
    }

    public static function gpxToKml($text)
    {
        return self::getGpxDecoder()->geomFromText($text)->toKML();
    }
}
