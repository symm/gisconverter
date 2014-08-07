<?php

namespace Symm\Gisconverter\Geometry;

abstract class Collection extends Geometry
{
    protected $components;

    public function __get($property)
    {
        if ($property == "components") {
            return $this->components;
        } else {
            throw new \Exception("Undefined property");
        }
    }

    public function getComponents() {
        return $this->components;
    }

    public function numGeometries() {
        return count($this->components);
    }    

    public function toWKT()
    {
        $recursiveWKT = function ($geom) use (&$recursiveWKT) {
            if ($geom instanceof Point) {
                return "{$geom->lon} {$geom->lat}";
            } else {
                return "(" . implode(',', array_map($recursiveWKT, $geom->components)). ")";
            }
        };

        return strtoupper(static::name) . call_user_func($recursiveWKT, $this);
    }

    public function toGeoArray()
    {
        $recursiveJSON = function ($geom) use (&$recursiveJSON) {

            if ($geom instanceof Point) {
                return array($geom->lon, $geom->lat);
            } else {
                return array_map($recursiveJSON, $geom->components);
            }
        };

        return array('type' => static::name, 'coordinates' => call_user_func($recursiveJSON, $this));
    }
    
    public function toGeoJSON()
    {
        return json_encode((object) $this->toGeoArray());
    }

    public function toKML()
    {
        return '<MultiGeometry>' .
        implode(
            "",
            array_map(
                function ($comp) {
                    return $comp->toKML();
                },
                $this->components
            )
        )
        . '</MultiGeometry>';
    }

    public function writeWKB()
    {
        $wkb = pack('L',$this->numGeometries());
            foreach ($this->components as $component) {
              $wkb .=  $component->toWKB();
            }

        return $wkb;
    }

    public function toWKB($write_as_hex = false)
    {
        $wkb = pack('c', 1);

        switch($this->getGeomType()) {

          case 'MultiPoint';
            $wkb .= pack('L',4);
            $wkb .= $this->writeWKB();
            break;
          case 'MultiLineString';
            $wkb .= pack('L',5);
            $wkb .= $this->writeWKB();
            break;

          case 'MultiPolygon';
            $wkb .= pack('L',6);
            $wkb .= $this->writeWKB();
            break;

          case 'GeometryCollection';
            $wkb .= pack('L',7);
            $wkb .= $this->writeWKB();
            break;
   
        }

        if ($write_as_hex) {
            $unpacked = unpack('H*', $wkb);
            return $unpacked[1];
        } else {
            return $wkb;
        }
    }
}
