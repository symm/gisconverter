<?php

namespace Symm\Gisconverter\Decoders;

use Symm\Gisconverter\Exceptions\InvalidText;
use Symm\Gisconverter\Geometry\Point;

class GPX extends XML {
    static protected function _extractCoordinates($xml) {
        $attributes = $xml->attributes();
        $lon = (string) $attributes['lon'];
        $lat = (string) $attributes['lat'];
        if (!$lon or !$lat) {
            throw new InvalidText(__CLASS__);
        }
        return array($lon, $lat);
    }

    static protected function parseTrkseg($xml) {
        $res = array();
        foreach ($xml->children() as $elem) {
            if (strtolower($elem->getName()) == "trkpt") {
                $res[] = new Point(static::_extractCoordinates($elem));
            }
        }
        return $res;
    }

    static protected function parseRte($xml) {
        $res = array();
        foreach ($xml->children() as $elem) {
            if (strtolower($elem->getName()) == "rtept") {
                $res[] = new Point(static::_extractCoordinates($elem));
            }
        }
        return $res;
    }

    static protected function parseWpt($xml) {
        return static::_extractCoordinates($xml);
    }

    static protected function _geomFromXML($xml) {
        $nodename = strtolower($xml->getName());
        if ($nodename == "gpx" or $nodename == "trk") {
            return static::_childsCollect($xml);
        }
        foreach (array("Trkseg", "Rte", "Wpt") as $kml_type) {
            if (strtolower($kml_type) == $xml->getName()) {
                $type = $kml_type;
                break;
            }
        }

        if (!isset($type)) {
            throw new InvalidText(__CLASS__);
        }

        try {
            $components = call_user_func(array('static', 'parse'.$type), $xml);
        } catch(InvalidText $e) {
            throw new InvalidText(__CLASS__);
        } catch(\Exception $e) {
            throw $e;
        }

        if ($type == "Trkseg" or $type == "Rte") {
            $constructor = 'Symm\\Gisconverter\\Geometry\\' . 'LineString';
        } else if ($type == "Wpt") {
            $constructor = 'Symm\\Gisconverter\\Geometry\\' . 'Point';
        }
        return new $constructor($components);
    }
}
