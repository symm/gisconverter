<?php

namespace Symm\Gisconverter\Decoders;

use Symm\Gisconverter\Geometry\Point;
use Symm\Gisconverter\Geometry\LinearRing;
use Symm\Gisconverter\Geometry\LineString;
use Symm\Gisconverter\Exceptions\InvalidText;

/**
 * WKB Decoder
 * Partially inspired by https://github.com/phayes/geoPHP
 **/
class WKB extends Decoder
{
  
  private $dimension = 2;
  private $z = false;
  private $m = false;
  
  /**
   * Read WKB into geometry objects
   * @param  string $wkb    Well-known-binary string
   * @param  boolean $is_hex_string if true, I need to pack it
   * @return Geometry
   */
  public function geomFromBinary($wkb, $is_hex_string = false)
  {
    if ($is_hex_string) {
      $wkb = pack('H*', $wkb);
    }
    
    if (empty($wkb)) {
      throw new InvalidText('Empty or incomplete WKB geometry. Not enough to parse a valid point');
    }
    
    $mem = fopen('php://memory', 'r+');
    fwrite($mem, $wkb);
    fseek($mem, 0);
    
    $geometry = $this->getGeometry($mem);
    fclose($mem);
    return $geometry;
  }
  
  public function getGeometry(&$mem)
  {
    
    $first5bytes = fread($mem, 5);
    if (empty($first5bytes)) {
      throw new InvalidText('Not enough input to determine base info');
    }
    
    $base_info = unpack("corder/ctype/cz/cm/cs", $first5bytes);
    
    //@TODO support XDR too
    if ($base_info['order'] !== 1) {
      throw new InvalidText('Only NDR (little endian) SKB format is supported at the moment. Order was ' . $base_info['order']);
    }
    
    if ($base_info['type'] === 0 || $base_info['type'] > 7) {
      throw new InvalidText('Not a valid WKB Geometry type');
    }
    
    if ($base_info['z']) {
      $this->dimension++;
      $this->z = true;
    }
    
    if ($base_info['m']) {
      $this->dimension++;
      $this->m = true;
    }
    
    if ($base_info['s']) {
      fread($mem, 4);
    }
    
    // WKB types start from 1. The following array allows us to translate numeric type to its name
    $wkb_types = array(null, 'Point', 'LineString', 'Polygon', 'MultiPoint', 'MultiLineString', 'MultiPolygon', 'GeometryCollection');
    
    $type = $wkb_types[$base_info['type']];
    
    $components = call_user_func(array($this, 'parse' . $type), $mem);
    
    $constructor = '\\Symm\\Gisconverter\\Geometry\\' . $type;
    
    return new $constructor($components);
  }
  
  protected function parsePoint(&$mem)
  {
    $point_coords = unpack("d*", fread($mem, $this->dimension * 8));
    
    if (count($point_coords) < 2) {
      throw new InvalidText('A valid point requires at least two coordinates');
    }
    $components = array($point_coords[1], $point_coords[2]);
    return $components;
  }
  
  protected function parseLineString(&$mem)
  {
    
    // Get the number of points expected in this string out of the first 4 bytes
    $binlength = fread($mem, 4);
    
    if (strlen($binlength) < 4) {
      throw new InvalidText('Not enough input for a valid length');
    }
    
    $line_length = unpack('L', $binlength);
    
    if (!$line_length[1]) {
      throw new InvalidText('Not enough points for a valid linestring');
    }
    
    // Read the number of points x2 (each point is two coords) into decimal-floats
      $line_coords = unpack('d*', fread($mem, $line_length[1] * $this->dimension * 8));
    
    // We have our coords, build up the linestring
    $components = array();
    $i = 1;
    $num_coords = count($line_coords);
    while ($i <= $num_coords) {
      $components[] = new Point(array($line_coords[$i], $line_coords[$i + 1]));
      $i+= 2;
    }
    return $components;
  }
  
  protected function parseLinearRing(&$mem)
  {
    return $this->parseLineString($mem);
  }
  
  protected function parsePolygon(&$mem)
  {
    $binlength = fread($mem, 4);
    
    if (strlen($binlength) < 4) {
      throw new InvalidText('Not enough input for a valid length');
    }
    
    $poly_length = unpack('L', $binlength);
    
    $components = array();
    $i = 1;
    while ($i <= $poly_length[1]) {
      $childs = $this->parseLinearRing($mem);
      $components[] = new LinearRing($childs);
      $i++;
    }
    return $components;
  }
  
  protected function parseCollection(&$mem)
  {
    $binlength = fread($mem, 4);
    
    if (strlen($binlength) < 4) {
      throw new InvalidText('Not enough input for a valid length');
    }
    $multi_length = unpack('L', $binlength);
    
    $components = array();
    $i = 1;
    while ($i <= $multi_length[1]) {
      $components[] = $this->getGeometry($mem);
      $i++;
    }
    return $components;
  }
  
  protected function parseMultiPoint(&$mem)
  {
    return $this->parseCollection($mem);
  }
  
  protected function parseMultiLineString(&$mem)
  {
    return $this->parseCollection($mem);
  }
  
  protected function parseMultiPolygon(&$mem)
  {
    return $this->parseCollection($mem);
  }
  
  protected function parseGeometryCollection(&$mem)
  {
    return $this->parseCollection($mem);
  }
}
