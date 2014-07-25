<?php

class wkb extends PHPUnit_Framework_TestCase
{
    private $decoder = null;

    public function setup()
    {
        if (!$this->decoder) {
            $this->decoder = new Symm\Gisconverter\Decoders\WKB();
            $this->encoder = new Symm\Gisconverter\Decoders\WKT();
        }
    }

    /**
     * @expectedException Symm\Gisconverter\Exceptions\InvalidText
     */
    public function testInvalidText1()
    {
        $this->decoder->geomFromBinary('0104000000000000',true);
    }

    /**
     * @expectedException Symm\Gisconverter\Exceptions\InvalidText
     */
    public function testInvalidText2()
    {
        $this->decoder->geomFromBinary('00000000000000000000000000000000000000000',true);
    }

    public function testPoint()
    {
        // POINT(10 10)
        $geom = $this->decoder->geomFromBinary('010100000000000000000024400000000000002440',true);
        $this->assertEquals($geom->toWKB(true), '010100000000000000000024400000000000002440');
        
        // POINT(0 0)
        $geom = $this->decoder->geomFromBinary('010100000000000000000000000000000000000000',true);
        $this->assertEquals($geom->toWKB(true), '010100000000000000000000000000000000000000');
    }

    /*public function testMultiPoint()
    {
        
        $geom = $this->decoder->geomFromBinary('01040000000300000001010000000000000000000c406666666666661640010100000033333333333313400000000000002540010100000000000000000024400000000000002440');
        $this->assertEquals($geom->toWKB(true), '01040000000300000001010000000000000000000c406666666666661640010100000033333333333313400000000000002540010100000000000000000024400000000000002440');

        // MULTIPOINT()
        $geom = $this->decoder->geomFromBinary('010400000000000000');
        $this->assertEquals($geom->toWKB(true), '010400000000000000');
    }

    public function testLineString()
    {
        $binarystring=$this->encoder->geomFromText('LINESTRING(3.5 5.6,4.8 10.5,10 10)');
        var_dump($binarystring->toWKB(true));
        // LINESTRING(3.5 5.6,4.8 10.5,10 10)
        $geom = $this->decoder->geomFromBinary('LINESTRING(3.5 5.6,4.8 10.5,10 10)');
        $this->assertEquals($geom->toWKB(true), 'LINESTRING(3.5 5.6,4.8 10.5,10 10)');
    }

    public function testMultiLineString()
    {
        $binarystring=$this->encoder->geomFromText('MULTILINESTRING((3.5 5.6,4.8 10.5,10 10))');
        var_dump($binarystring->toWKB(true));
        // MULTILINESTRING((3.5 5.6,4.8 10.5,10 10))
        $geom = $this->decoder->geomFromBinary('MULTILINESTRING((3.5 5.6,4.8 10.5,10 10))');
        $this->assertEquals($geom->toWKB(true), 'MULTILINESTRING((3.5 5.6,4.8 10.5,10 10))');

        // MULTILINESTRING((3.5 5.6,4.8 10.5,10 10),(10 10,10 20,20 20,20 15))
        $binarystring=$this->encoder->geomFromText('MULTILINESTRING((3.5 5.6,4.8 10.5,10 10),(10 10,10 20,20 20,20 15))');
        var_dump($binarystring->toWKB(true));
        $geom = $this->decoder->geomFromBinary('MULTILINESTRING((3.5 5.6,4.8 10.5,10 10),(10 10,10 20,20 20,20 15))');
        $this->assertEquals($geom->toWKB(true), 'MULTILINESTRING((3.5 5.6,4.8 10.5,10 10),(10 10,10 20,20 20,20 15))');
    }

    public function testLinearRing()
    {
        $binarystring=$this->encoder->geomFromText('LINEARRING(3.5 5.6,4.8 10.5,10 10,3.5 5.6)');
        var_dump($binarystring->toWKB(true));

        // LINEARRING(3.5 5.6,4.8 10.5,10 10,3.5 5.6)
        $geom = $this->decoder->geomFromBinary('LINEARRING(3.5 5.6,4.8 10.5,10 10,3.5 5.6)');
        $this->assertEquals($geom->toWKB(true), 'LINEARRING(3.5 5.6,4.8 10.5,10 10,3.5 5.6)');
    }

    public function testPolygon()
    {
        $binarystring=$this->encoder->geomFromText('POLYGON((10 10,10 20,20 20,20 15,10 10))');
        var_dump($binarystring->toWKB(true));
        // POLYGON((10 10,10 20,20 20,20 15,10 10))
        $geom = $this->decoder->geomFromBinary('POLYGON((10 10,10 20,20 20,20 15,10 10))');
        $this->assertEquals($geom->toWKB(true), 'POLYGON((10 10,10 20,20 20,20 15,10 10))');


        $binarystring=$this->encoder->geomFromText('POLYGON((0 0,10 0,10 10,0 10,0 0),(1 1,9 1,9 9,1 9,1 1))');
        var_dump($binarystring->toWKB(true));
        // POLYGON((0 0,10 0,10 10,0 10,0 0),(1 1,9 1,9 9,1 9,1 1))
        $geom = $this->decoder->geomFromBinary('POLYGON((0 0,10 0,10 10,0 10,0 0),(1 1,9 1,9 9,1 9,1 1))');
        $this->assertEquals($geom->toWKB(true), 'POLYGON((0 0,10 0,10 10,0 10,0 0),(1 1,9 1,9 9,1 9,1 1))');
    }

    public function testMultiPolygon()
    {
        $binarystring=$this->encoder->geomFromText('MULTIPOLYGON(((10 10,10 20,20 20,20 15,10 10)))');
        var_dump($binarystring->toWKB(true));
        // MULTIPOLYGON(((10 10,10 20,20 20,20 15,10 10)))
        $geom = $this->decoder->geomFromBinary('MULTIPOLYGON(((10 10,10 20,20 20,20 15,10 10)))');
        $this->assertEquals($geom->toWKB(true), 'MULTIPOLYGON(((10 10,10 20,20 20,20 15,10 10)))');

        // MULTIPOLYGON(((10 10,10 20,20 20,20 15,10 10)))
        $binarystring=$this->encoder->geomFromText('MULTIPOLYGON(((10 10,10 20,20 20,20 15,10 10)),((60 60,70 70,80 60,60 60)))');
        var_dump($binarystring->toWKB(true));
        $geom = $this->decoder->geomFromBinary('MULTIPOLYGON(((10 10,10 20,20 20,20 15,10 10)),((60 60,70 70,80 60,60 60)))');
        $this->assertEquals($geom->toWKB(true), 'MULTIPOLYGON(((10 10,10 20,20 20,20 15,10 10)),((60 60,70 70,80 60,60 60)))');
    }
    

    public function testGeometryCollection()
    {

        // GEOMETRYCOLLECTION(POINT(10 10),POINT(30 30),LINESTRING(15 15,20 20))
        $geom = $this->decoder->geomFromBinary('01070000000300000001010000000000000000002440000000000000244001010000000000000000003e400000000000003e400102000000020000000000000000002e400000000000002e4000000000000034400000000000003440',true);
        $this->assertEquals($geom->toWKB(true), '01070000000300000001010000000000000000002440000000000000244001010000000000000000003e400000000000003e400102000000020000000000000000002e400000000000002e4000000000000034400000000000003440');
    }*/

}
