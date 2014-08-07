<?php

class conversions extends PHPUnit_Framework_TestCase
{
    private $default_decoder = null;

    public function setup()
    {
        if (!$this->default_decoder) {
            $this->default_decoder = new Symm\Gisconverter\Decoders\WKT();
        }
    }

    public function testPoint()
    {
        $geom = $this->default_decoder->geomFromText('POINT(10 10)');
        $this->assertEquals($geom->toGeoJSON(), '{"type":"Point","coordinates":[10,10]}');
        $this->assertEquals($geom->toKML(), '<Point><coordinates>10,10</coordinates></Point>');
        $this->assertEquals($geom->toGPX(), '<wpt lon="10" lat="10"></wpt>');
        $this->assertEquals($geom->toWKB(true), '010100000000000000000024400000000000002440');

        $geom = $this->default_decoder->geomFromText('POINT(0 0)');
        $this->assertEquals($geom->toGeoJSON(), '{"type":"Point","coordinates":[0,0]}');
        $this->assertEquals($geom->toKML(), '<Point><coordinates>0,0</coordinates></Point>');
        $this->assertEquals($geom->toGPX(), '<wpt lon="0" lat="0"></wpt>');
        $this->assertEquals($geom->toWKB(true), '010100000000000000000000000000000000000000');
    }

    public function testMultiPoint()
    {
        $geom = $this->default_decoder->geomFromText('MULTIPOINT(3.5 5.6,4.8 10.5,10 10)');
        $this->assertEquals($geom->toGeoJSON(), '{"type":"MultiPoint","coordinates":[[3.5,5.6],[4.8,10.5],[10,10]]}');
        $this->assertEquals($geom->toKML(), '<MultiGeometry><Point><coordinates>3.5,5.6</coordinates></Point><Point><coordinates>4.8,10.5</coordinates></Point><Point><coordinates>10,10</coordinates></Point></MultiGeometry>');
        $this->assertEquals($geom->toWKB(true), '01040000000300000001010000000000000000000c406666666666661640010100000033333333333313400000000000002540010100000000000000000024400000000000002440');

        $geom = $this->default_decoder->geomFromText('MULTIPOINT()');
        $this->assertEquals($geom->toGeoJSON(), '{"type":"MultiPoint","coordinates":[]}');
        $this->assertEquals($geom->toKML(), '<MultiGeometry></MultiGeometry>');
        $this->assertEquals($geom->toWKB(true), '010400000000000000');
    }

    public function testLineString()
    {
        $geom = $this->default_decoder->geomFromText('LINESTRING(3.5 5.6,4.8 10.5,10 10)');
        $this->assertEquals($geom->toGeoJSON(), '{"type":"LineString","coordinates":[[3.5,5.6],[4.8,10.5],[10,10]]}');
        $this->assertEquals($geom->toKML(), '<LineString><coordinates>3.5,5.6 4.8,10.5 10,10</coordinates></LineString>');
        $this->assertEquals($geom->toGPX(), '<trkseg><trkpt lon="3.5" lat="5.6"></trkpt><trkpt lon="4.8" lat="10.5"></trkpt><trkpt lon="10" lat="10"></trkpt></trkseg>');
        $this->assertEquals($geom->toGPX('trkseg'), '<trkseg><trkpt lon="3.5" lat="5.6"></trkpt><trkpt lon="4.8" lat="10.5"></trkpt><trkpt lon="10" lat="10"></trkpt></trkseg>');
        $this->assertEquals($geom->toGPX('rte'), '<rte><rtept lon="3.5" lat="5.6"></rtept><rtept lon="4.8" lat="10.5"></rtept><rtept lon="10" lat="10"></rtept></rte>');
        $this->assertEquals($geom->toWKB(true), '0102000000030000000000000000000c4066666666666616403333333333331340000000000000254000000000000024400000000000002440');
    }

    public function testMultiLineString()
    {
        $geom = $this->default_decoder->geomFromText('MULTILINESTRING((3.5 5.6,4.8 10.5,10 10))');
        $this->assertEquals($geom->toGeoJSON(), '{"type":"MultiLineString","coordinates":[[[3.5,5.6],[4.8,10.5],[10,10]]]}');
        $this->assertEquals($geom->toKML(), '<MultiGeometry><LineString><coordinates>3.5,5.6 4.8,10.5 10,10</coordinates></LineString></MultiGeometry>');
        $this->assertEquals($geom->toWKB(true), '0105000000010000000102000000030000000000000000000c4066666666666616403333333333331340000000000000254000000000000024400000000000002440');

        $geom = $this->default_decoder->geomFromText('MULTILINESTRING((3.5 5.6,4.8 10.5,10 10),(10 10,10 20,20 20,20 15))');
        $this->assertEquals($geom->toGeoJSON(), '{"type":"MultiLineString","coordinates":[[[3.5,5.6],[4.8,10.5],[10,10]],[[10,10],[10,20],[20,20],[20,15]]]}');
        $this->assertEquals($geom->toKML(), '<MultiGeometry><LineString><coordinates>3.5,5.6 4.8,10.5 10,10</coordinates></LineString><LineString><coordinates>10,10 10,20 20,20 20,15</coordinates></LineString></MultiGeometry>');
        $this->assertEquals($geom->toWKB(true), '0105000000020000000102000000030000000000000000000c406666666666661640333333333333134000000000000025400000000000002440000000000000244001020000000400000000000000000024400000000000002440000000000000244000000000000034400000000000003440000000000000344000000000000034400000000000002e40');
    }

    public function testLinearRing()
    {
        $geom = $this->default_decoder->geomFromText('LINEARRING(3.5 5.6,4.8 10.5,10 10,3.5 5.6)');
        $this->assertEquals($geom->toGeoJSON(), '{"type":"LinearRing","coordinates":[[3.5,5.6],[4.8,10.5],[10,10],[3.5,5.6]]}');
        $this->assertEquals($geom->toKML(), '<LinearRing><coordinates>3.5,5.6 4.8,10.5 10,10 3.5,5.6</coordinates></LinearRing>');
        $this->assertEquals($geom->toWKB(true), '0102000000040000000000000000000c40666666666666164033333333333313400000000000002540000000000000244000000000000024400000000000000c406666666666661640');
    }

    public function testPolygon()
    {
        $geom = $this->default_decoder->geomFromText('POLYGON((10 10,10 20,20 20,20 15,10 10))');
        $this->assertEquals($geom->toGeoJSON(), '{"type":"Polygon","coordinates":[[[10,10],[10,20],[20,20],[20,15],[10,10]]]}');
        $this->assertEquals($geom->toKML(), '<Polygon><outerBoundaryIs><LinearRing><coordinates>10,10 10,20 20,20 20,15 10,10</coordinates></LinearRing></outerBoundaryIs></Polygon>');
        $this->assertEquals($geom->toWKB(true), '0103000000010000000500000000000000000024400000000000002440000000000000244000000000000034400000000000003440000000000000344000000000000034400000000000002e4000000000000024400000000000002440');

        $geom = $this->default_decoder->geomFromText('POLYGON((0 0,10 0,10 10,0 10,0 0),(1 1,9 1,9 9,1 9,1 1))');
        $this->assertEquals($geom->toGeoJSON(), '{"type":"Polygon","coordinates":[[[0,0],[10,0],[10,10],[0,10],[0,0]],[[1,1],[9,1],[9,9],[1,9],[1,1]]]}');
        $this->assertEquals($geom->toKML(), '<Polygon><outerBoundaryIs><LinearRing><coordinates>0,0 10,0 10,10 0,10 0,0</coordinates></LinearRing></outerBoundaryIs><innerBoundaryIs><LinearRing><coordinates>1,1 9,1 9,9 1,9 1,1</coordinates></LinearRing></innerBoundaryIs></Polygon>');
        $this->assertEquals($geom->toWKB(true), '01030000000200000005000000000000000000000000000000000000000000000000002440000000000000000000000000000024400000000000002440000000000000000000000000000024400000000000000000000000000000000005000000000000000000f03f000000000000f03f0000000000002240000000000000f03f00000000000022400000000000002240000000000000f03f0000000000002240000000000000f03f000000000000f03f');
    }

    public function testMultiPolygon()
    {
        $geom = $this->default_decoder->geomFromText('MULTIPOLYGON(((10 10,10 20,20 20,20 15,10 10)))');
        $this->assertEquals($geom->toGeoJSON(), '{"type":"MultiPolygon","coordinates":[[[[10,10],[10,20],[20,20],[20,15],[10,10]]]]}');
        $this->assertEquals($geom->toKML(), '<MultiGeometry><Polygon><outerBoundaryIs><LinearRing><coordinates>10,10 10,20 20,20 20,15 10,10</coordinates></LinearRing></outerBoundaryIs></Polygon></MultiGeometry>');
        $this->assertEquals($geom->toWKB(true), '0106000000010000000103000000010000000500000000000000000024400000000000002440000000000000244000000000000034400000000000003440000000000000344000000000000034400000000000002e4000000000000024400000000000002440');

        $geom = $this->default_decoder->geomFromText('MULTIPOLYGON(((10 10,10 20,20 20,20 15,10 10)),((60 60,70 70,80 60,60 60)))');
        $this->assertEquals($geom->toGeoJSON(), '{"type":"MultiPolygon","coordinates":[[[[10,10],[10,20],[20,20],[20,15],[10,10]]],[[[60,60],[70,70],[80,60],[60,60]]]]}');
        $this->assertEquals($geom->toKML(), '<MultiGeometry><Polygon><outerBoundaryIs><LinearRing><coordinates>10,10 10,20 20,20 20,15 10,10</coordinates></LinearRing></outerBoundaryIs></Polygon><Polygon><outerBoundaryIs><LinearRing><coordinates>60,60 70,70 80,60 60,60</coordinates></LinearRing></outerBoundaryIs></Polygon></MultiGeometry>');
        $this->assertEquals($geom->toWKB(true), '0106000000020000000103000000010000000500000000000000000024400000000000002440000000000000244000000000000034400000000000003440000000000000344000000000000034400000000000002e4000000000000024400000000000002440010300000001000000040000000000000000004e400000000000004e400000000000805140000000000080514000000000000054400000000000004e400000000000004e400000000000004e40');
    }

    public function testGeometryCollection()
    {
        $geom = $this->default_decoder->geomFromText('GEOMETRYCOLLECTION(POINT(10 10),POINT(30 30),LINESTRING(15 15,20 20))');
        $this->assertEquals($geom->toGeoJSON(), '{"type":"GeometryCollection","geometries":[{"type":"Point","coordinates":[10,10]},{"type":"Point","coordinates":[30,30]},{"type":"LineString","coordinates":[[15,15],[20,20]]}]}');
        $this->assertEquals($geom->toKML(), '<MultiGeometry><Point><coordinates>10,10</coordinates></Point><Point><coordinates>30,30</coordinates></Point><LineString><coordinates>15,15 20,20</coordinates></LineString></MultiGeometry>');
        $this->assertEquals($geom->toWKB(true), '01070000000300000001010000000000000000002440000000000000244001010000000000000000003e400000000000003e400102000000020000000000000000002e400000000000002e4000000000000034400000000000003440');
    }
}
