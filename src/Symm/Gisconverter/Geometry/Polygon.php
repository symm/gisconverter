<?php

namespace Symm\Gisconverter\Geometry;

use Symm\Gisconverter\Exceptions\InvalidFeature;

class Polygon extends Collection
{
    const name = "Polygon";

    public function __construct($components)
    {
        $outer = $components[0];

        foreach (array_slice($components, 1) as $inner) {
            if (!$outer->contains($inner)) {
                throw new InvalidFeature(__CLASS__, "Polygon inner rings must be enclosed in outer ring");
            }
        }

        foreach ($components as $comp) {
            if (!($comp instanceof LinearRing)) {
                throw new InvalidFeature(__CLASS__, "Polygon can only contain LinearRing elements");
            }
        }

        $this->components = $components;
    }

    public function toKML()
    {
        $str = '<outerBoundaryIs>' .
            $this->components[0]->toKML() .
            '</outerBoundaryIs>';

        $str .= implode(
            "",
            array_map(
                function ($comp) {
                    return '<innerBoundaryIs>' . $comp->toKML() . '</innerBoundaryIs>';
                },
                array_slice($this->components, 1)
            )
        );

        return
            '<' . static::name . '>' .
                $str .
            '</' . static::name . '>';
    }

    public function writeWKB()
    {
        $wkb = pack('L', $this->numGeometries());
        foreach ($this->components as $component) {
            $wkb.= $component->writeWKB();
        }
        
        return $wkb;
    }

    public function toWKB($write_as_hex = false)
    {
        $wkb = pack('c', 1);
        $wkb.= pack('L', 3);
        $wkb.= $this->writeWKB();
        
        if ($write_as_hex) {
            $unpacked = unpack('H*', $wkb);
            return $unpacked[1];
        } else {
            return $wkb;
        }
    }
}
