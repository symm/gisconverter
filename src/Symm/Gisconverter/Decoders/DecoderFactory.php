<?php

namespace Symm\Gisconverter\Decoders;

use Symm\Gisconverter\Exceptions\DecoderNotFoundException;
use Symm\Gisconverter\Interfaces\DecoderInterface;

class DecoderFactory
{
    const GEOJSON = 'GeoJSON';
    const GPX = 'GPX';
    const KML = 'KML';
    const WKT = 'WKT';

    /**
     * Facotry method.
     *
     * Instantiate a given decoder class.
     *
     * @param string $type Decoder type
     *
     * @return DecoderInterface
     * @throws DecoderNotFoundException
     */
    public static function getDecoder($type)
    {
        $decoderClass = __NAMESPACE__ . "\\$type";
        if (class_exists($decoderClass)) {
            return new $decoderClass;
        }

        throw new DecoderNotFoundException("Unable to find decoder class for $type type");
    }
}
 