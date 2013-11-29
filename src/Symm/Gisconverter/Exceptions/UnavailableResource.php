<?php

namespace Symm\Gisconverter\Exceptions;

class UnavailableResource extends CustomException {
    public function __construct($ressource) {
        $this->message = "unavailable ressource: $ressource";
    }
}
