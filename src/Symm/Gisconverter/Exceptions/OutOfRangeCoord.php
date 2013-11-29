<?php
/**
 * Created by PhpStorm.
 * User: gaz
 * Date: 29/11/2013
 * Time: 12:31
 */

namespace Symm\Gisconverter\Exceptions;

abstract class OutOfRangeCoord extends CustomException
{
    private $coord;
    public $type;

    public function __construct($coord)
    {
        $this->message = "invalid {$this->type}: $coord";
    }
}
