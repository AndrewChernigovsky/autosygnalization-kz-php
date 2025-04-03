<?php

namespace HELPERS;

class Services
{
    private $services;
    public function __construct($services = null)
    {
        $this->services = $services;
    }

    public function getServices()
    {
        return $this->services;
    }
}
