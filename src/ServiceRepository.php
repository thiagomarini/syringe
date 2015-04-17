<?php
namespace Syringe;

interface ServiceRepository
{
    public function get($id);

    public function add($id, $service);
}
