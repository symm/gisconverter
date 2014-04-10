<?php

use Symm\Gisconverter\Decoders\DecoderFactory;

class DecoderFactoryTest extends PHPUnit_Framework_TestCase {

    public function testFactoryShouldReturnGeoJsonDecoder()
    {
        $decoder = DecoderFactory::getDecoder(DecoderFactory::GEOJSON);
        $this->assertInstanceOf('Symm\Gisconverter\Decoders\GeoJSON', $decoder);
    }

    public function testFactoryShouldReturnGPXDecoder()
    {
        $decoder = DecoderFactory::getDecoder(DecoderFactory::GPX);
        $this->assertInstanceOf('Symm\Gisconverter\Decoders\GPX', $decoder);
    }

    public function testFactoryShouldReturnWKTDecoder()
    {
        $decoder = DecoderFactory::getDecoder(DecoderFactory::WKT);
        $this->assertInstanceOf('Symm\Gisconverter\Decoders\WKT', $decoder);
    }

    public function testFactoryShouldReturnKMLDecoder()
    {
        $decoder = DecoderFactory::getDecoder(DecoderFactory::KML);
        $this->assertInstanceOf('Symm\Gisconverter\Decoders\KML', $decoder);
    }

    public function testFacotryShouldThrowAnExceptionForUnknownDecoder()
    {
        $this->setExpectedException('Symm\Gisconverter\Exceptions\DecoderNotFoundException', 'Unable to find decoder class for FooBar type');
        DecoderFactory::getDecoder('FooBar');
    }
}
 