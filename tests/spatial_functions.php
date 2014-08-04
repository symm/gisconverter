<?php

class spatial extends PHPUnit_Framework_TestCase
{
    private $decoder = null;

    public function setup()
    {
        if (!$this->decoder) {
            $this->decoder = new Symm\Gisconverter\Decoders\WKT();
        }
    }

    public function testRingContainsRing()
    {

        //Integer rings

        $pA1 = new Symm\Gisconverter\Geometry\Point([0,0]);
        $pA2 = new Symm\Gisconverter\Geometry\Point([10,0]);
        $pA3 = new Symm\Gisconverter\Geometry\Point([10,10]);
        $pA4 = new Symm\Gisconverter\Geometry\Point([0,10]);

        $ringA = new Symm\Gisconverter\Geometry\LinearRing( [$pA1, $pA2, $pA3, $pA4, $pA1] );

            //counterclockwise ring
        $pB1 = new Symm\Gisconverter\Geometry\Point([2,2]);
        $pB2 = new Symm\Gisconverter\Geometry\Point([8,2]);
        $pB3 = new Symm\Gisconverter\Geometry\Point([8,8]);
        $pB4 = new Symm\Gisconverter\Geometry\Point([2,8]);

        $ringB = new Symm\Gisconverter\Geometry\LinearRing( [$pB1, $pB2, $pB3, $pB4, $pB1] );

            //clockwise ring
        $pC1 = new Symm\Gisconverter\Geometry\Point([2,2]);
        $pC2 = new Symm\Gisconverter\Geometry\Point([2,8]);
        $pC3 = new Symm\Gisconverter\Geometry\Point([8,8]);
        $pC4 = new Symm\Gisconverter\Geometry\Point([8,2]);

        $ringC = new Symm\Gisconverter\Geometry\LinearRing( [$pC1, $pC2, $pC3, $pC4, $pC1] );

        $this->assertTrue($ringA->contains($ringB));
        $this->assertTrue($ringA->contains($ringC));
        $this->assertFalse($ringB->contains($ringA));
        $this->assertFalse($ringC->contains($ringA));

        //Floating number rings

        $pA1 = new Symm\Gisconverter\Geometry\Point([12.333327,45.439957]);
        $pA2 = new Symm\Gisconverter\Geometry\Point([12.333410,45.439917]);
        $pA3 = new Symm\Gisconverter\Geometry\Point([12.333293,45.439790]);
        $pA4 = new Symm\Gisconverter\Geometry\Point([12.333132,45.439842]);

        $ringA = new Symm\Gisconverter\Geometry\LinearRing( [$pA1, $pA2, $pA3, $pA4, $pA1] );

        $pB1 = new Symm\Gisconverter\Geometry\Point([12.333306,45.439907]);
        $pB2 = new Symm\Gisconverter\Geometry\Point([12.333338,45.439900]);
        $pB3 = new Symm\Gisconverter\Geometry\Point([12.333335,45.439917]);
        $pB4 = new Symm\Gisconverter\Geometry\Point([12.333321,45.439924]);

        $ringB = new Symm\Gisconverter\Geometry\LinearRing( [$pB1, $pB2, $pB3, $pB4, $pB1] );

        $this->assertTrue($ringA->contains($ringB));
        $this->assertFalse($ringB->contains($ringA));


    }

}
