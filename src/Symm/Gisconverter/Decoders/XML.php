<?php

namespace Symm\Gisconverter\Decoders;

use Symm\Gisconverter\Exceptions\UnavailableResource;
use Symm\Gisconverter\Exceptions\InvalidText;
use Symm\Gisconverter\Geometry\GeometryCollection;

abstract class XML extends Decoder
{
    public static function geomFromText($text)
    {
        if (!function_exists("simplexml_load_string") || !function_exists("libxml_use_internal_errors")) {
            throw new UnavailableResource("simpleXML");
        }

        libxml_use_internal_errors(true);

        $xmlobj = simplexml_load_string($text);
        if ($xmlobj === false) {
            throw new InvalidText(__CLASS__, $text);
        }

        try {
            $geom = static::geomFromXML($xmlobj);
        } catch (InvalidText $e) {
            throw new InvalidText(__CLASS__, $text);
        } catch (\Exception $e) {
            throw $e;
        }

        return $geom;
    }

    protected static function childElements($xml, $nodename = "")
    {
        $nodename = strtolower($nodename);
        $res = array();

        foreach ($xml->children() as $child) {
            if ($nodename) {
                if (strtolower($child->getName()) == $nodename) {
                    array_push($res, $child);
                }
            } else {
                array_push($res, $child);
            }
        }

        return $res;
    }

    protected static function childsCollect($xml)
    {
        $components = array();

        foreach (static::childElements($xml) as $child) {
            try {
                $geom = static::geomFromXML($child);
                $components[] = $geom;
            } catch (InvalidText $e) {

            }
        }

        $ncomp = count($components);
        if ($ncomp == 0) {
            throw new InvalidText(__CLASS__);
        } elseif ($ncomp == 1) {
            return $components[0];
        } else {
            return new GeometryCollection($components);
        }
    }

    protected static function geomFromXML($xml)
    {

    }
}
