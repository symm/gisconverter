<?php

namespace Symm\Gisconverter\Geometry;

use Symm\Gisconverter\Exceptions\UnimplementedMethod;
use Symm\Gisconverter\Interfaces\GeometryInterface;

abstract class Geometry implements GeometryInterface
{
    const name = "";

    private $attributes;

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    public function getGeomType() {
        return static::name;
    }


    public function toGeoJSON()
    {
        throw new UnimplementedMethod(__FUNCTION__, get_called_class());
    }

    public function toKML()
    {
        throw new UnimplementedMethod(__FUNCTION__, get_called_class());
    }

    public function toGPX($mode = null)
    {
        throw new UnimplementedMethod(__FUNCTION__, get_called_class());
    }

    public function toWKT()
    {
        throw new UnimplementedMethod(__FUNCTION__, get_called_class());
    }

    public function equals(Geometry $geom)
    {
        throw new UnimplementedMethod(__FUNCTION__, get_called_class());
    }

    public function __toString()
    {
        return $this->toWKT();
    }


    /**
     * generates a binary string with the components of a WKB geometry
     * @return string binary representation of the components of a WKB geometry
     */
    public function writeWKB()
    {
        throw new UnimplementedMethod(__FUNCTION__, get_called_class());
    }

    /**
     * generates a binary string with the whole WKB representation of a geometry
     * @param  boolean $write_as_hex if true, return the hex representation of the data
     * @return string  WKB representation of a geometry
     */
    public function toWKB($write_as_hex = false)
    {
        throw new UnimplementedMethod(__FUNCTION__, get_called_class());
    }
}
