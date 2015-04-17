<?php

namespace Syringe;

class InMemoryServiceRepository implements ServiceRepository
{
    private $services = [];

    public function get($id)
    {
        if (!isset($this->services[$id])) {
            return null;
        }

        return $this->services[$id];
    }

    public function add($id, $service)
    {
        $this->services[$id] = $service;
    }
}
