<?php

class tostring extends PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $decoder = new Symm\Gisconverter\Decoders\WKT();
        $geom = $decoder->geomFromText('POINT(10 10)');
        $this->assertEquals($geom->toWKT(), (string) $geom);

        $geom = $decoder->geomFromText(' POINT  ( 10  10 ) ');
        $this->assertEquals($geom->toWKT(), (string) $geom);
    }

}
