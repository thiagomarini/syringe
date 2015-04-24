<?php
namespace Syringe;

interface ServiceFactory
{
    /**
     * @param string    $id - a known id key
     * @param Container $container
     *
     * @return mixed - a registered service
     *
     * @throws \InvalidArgumentException
     */
    public function create($id, Container $container);
}
