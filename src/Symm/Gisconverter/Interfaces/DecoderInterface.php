<?php

namespace Symm\Gisconverter\Interfaces;

interface DecoderInterface {
    /*
     * @param string $text
     * @return Geometry
     */
    static public function geomFromText($text);
}
