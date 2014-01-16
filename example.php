#!/usr/bin/php
<?php

require_once 'vendor/autoload.php';

use Symm\Gisconverter\Gisconverter;

$decoder = new Symm\Gisconverter\Decoders\WKT();
$geometry = $decoder->geomFromText('MULTIPOLYGON(((10 10,10 20,20 20,20 15,10 10)))'); # create a geometry from a given string input

print $geometry->toGeoJSON();      # output geometry in GeoJSON format
print PHP_EOL . PHP_EOL;

print $geometry->toKML();       # output geometry in KML format
print PHP_EOL . PHP_EOL;

#ok, you get the idea. Now, let's use helper functions

print Gisconverter::geojsonToKml('{"type":"LinearRing","coordinates":[[3.5,5.6],[4.8,10.5],[10,10],[3.5,5.6]]}');
print PHP_EOL . PHP_EOL;

print Gisconverter::geojsonToWkt('{"type":"LinearRing","coordinates":[[3.5,5.6],[4.8,10.5],[10,10],[3.5,5.6]]}');
print PHP_EOL . PHP_EOL;

print Gisconverter::kmlToWkt('<Polygon><outerBoundaryIs><LinearRing><coordinates>10,10 10,20 20,20 20,15 10,10</coordinates></LinearRing></outerBoundaryIs></Polygon>');
print PHP_EOL . PHP_EOL;

print Gisconverter::kmlToGeojson('<Polygon><outerBoundaryIs><LinearRing><coordinates>10,10 10,20 20,20 20,15 10,10</coordinates></LinearRing></outerBoundaryIs></Polygon>');
print PHP_EOL . PHP_EOL;

print Gisconverter::kmlToGpx('<LineString><coordinates>3.5,5.6 4.8,10.5 10,10</coordinates></LineString>');
print PHP_EOL . PHP_EOL;
