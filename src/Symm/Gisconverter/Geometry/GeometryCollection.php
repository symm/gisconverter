<?php

namespace Symm\Gisconverter\Geometry;

class GeometryCollection extends Collection
{
    const name = "GeometryCollection";

    public function __construct($components)
    {
        foreach ($components as $comp) {
            if (!($comp instanceof Geometry)) {
                throw new InvalidFeature(__CLASS__, "GeometryCollection can only contain Geometry elements");
            }
        }
        $this->components = $components;
    }

    public function toWKT()
    {
        return strtoupper(static::name) .
        "(" .
        implode(
            ',',
            array_map(
                function ($comp) {
                    return $comp->toWKT();
                },
                $this->components
            )
        ) .
        ')';
    }

    public function toGeoJSON()
    {
        $value = (object) array (
            'type' => static::name,
            'geometries' => array_map(
                function ($comp) {
                    // XXX: quite ugly
                    return json_decode($comp->toGeoJSON());
                },
                $this->components
            )
        );

        return json_encode($value);
    }
}
