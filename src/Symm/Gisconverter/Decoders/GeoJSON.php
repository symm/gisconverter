<?php

namespace Symm\Gisconverter\Decoders;

use Symm\Gisconverter\Exceptions\InvalidText;
use Symm\Gisconverter\Geometry\Linestring;
use Symm\Gisconverter\Geometry\Point;
use Symm\Gisconverter\Geometry\LinearRing;
use Symm\Gisconverter\Geometry\Polygon;

class GeoJSON extends Decoder {

    static public function geomFromText($text) {
        $ltext = strtolower($text);
        $obj = json_decode($ltext);
        if (is_null ($obj)) {
            throw new InvalidText(__CLASS__, $text);
        }

        try {
            $geom = static::_geomFromJson($obj);
        } catch(InvalidText $e) {
            throw new InvalidText(__CLASS__, $text);
        } catch(\Exception $e) {
            throw $e;
        }

        return $geom;
    }

    static protected function _geomFromJson($json) {
        if (property_exists ($json, "geometry") and is_object($json->geometry)) {
            return static::_geomFromJson($json->geometry);
        }

        if (!property_exists ($json, "type") or !is_string($json->type)) {
            throw new InvalidText(__CLASS__);
        }

        foreach (array("Point", "MultiPoint", "LineString", "MultiLinestring", "LinearRing",
                     "Polygon", "MultiPolygon", "GeometryCollection") as $json_type) {
            if (strtolower($json_type) == $json->type) {
                $type = $json_type;
                break;
            }
        }

        if (!isset($type)) {
            throw new InvalidText(__CLASS__);
        }

        try {
            $components = call_user_func(array('static', 'parse'.$type), $json);
        } catch(InvalidText $e) {
            throw new InvalidText(__CLASS__);
        } catch(\Exception $e) {
            throw $e;
        }

        $constructor = 'Symm\\Gisconverter\\Geometry\\' . $type;
        return new $constructor($components);
    }

    static protected function parsePoint($json) {
        if (!property_exists ($json, "coordinates") or !is_array($json->coordinates)) {
            throw new InvalidText(__CLASS__);
        }
        return $json->coordinates;
    }

    static protected function parseMultiPoint($json) {
        if (!property_exists ($json, "coordinates") or !is_array($json->coordinates)) {
            throw new InvalidText(__CLASS__);
        }
        return array_map(function($coords) {
            return new Point($coords);
        }, $json->coordinates);
    }

    static protected function parseLineString($json) {
        return static::parseMultiPoint($json);
    }

    static protected function parseMultiLineString($json) {
        $components = array();
        if (!property_exists ($json, "coordinates") or !is_array($json->coordinates)) {
            throw new InvalidText(__CLASS__);
        }
        foreach ($json->coordinates as $coordinates) {
            $linecomp = array();
            foreach ($coordinates as $coordinates) {
                $linecomp[] = new Point($coordinates);
            }
            $components[] = new LineString($linecomp);
        }
        return $components;
    }

    static protected function parseLinearRing($json) {
        return static::parseMultiPoint($json);
    }

    static protected function parsePolygon($json) {
        $components = array();
        if (!property_exists ($json, "coordinates") or !is_array($json->coordinates)) {
            throw new InvalidText(__CLASS__);
        }
        foreach ($json->coordinates as $coordinates) {
            $ringcomp = array();
            foreach ($coordinates as $coordinates) {
                $ringcomp[] = new Point($coordinates);
            }
            $components[] = new LinearRing($ringcomp);
        }
        return $components;
    }

    static protected function parseMultiPolygon($json) {
        $components = array();
        if (!property_exists ($json, "coordinates") or !is_array($json->coordinates)) {
            throw new InvalidText(__CLASS__);
        }
        foreach ($json->coordinates as $coordinates) {
            $polycomp = array();
            foreach ($coordinates as $coordinates) {
                $ringcomp = array();
                foreach ($coordinates as $coordinates) {
                    $ringcomp[] = new Point($coordinates);
                }
                $polycomp[] = new LinearRing($ringcomp);
            }
            $components[] = new Polygon($polycomp);
        }
        return $components;
    }

    static protected function parseGeometryCollection($json) {
        if (!property_exists ($json, "geometries") or !is_array($json->geometries)) {
            throw new InvalidText(__CLASS__);
        }
        $components = array();
        foreach ($json->geometries as $geometry) {
            $components[] = static::_geomFromJson($geometry);
        }

        return $components;
    }

}
