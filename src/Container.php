<?php

namespace Syringe;

/**
 * The dependency injection container
 *
 * @author Thiago Marini
 */
class Container
{

    /**
     * @var ServiceRepository
     */
    private $repository;

    /**
     * @var ServiceFactory
     */
    private $factory;

    /**
     * Controls circular dependency
     * @var array
     */
    private $servicesCreating = [];

    /**
     * @param string $configFile
     * @param ServiceRepository $repository
     */
    public function __construct($configFile, ServiceRepository $repository = null)
    {
        // if no other repository is provided
        // use the in-memory one
        $this->repository = $repository ?: new InMemoryServiceRepository();

        $this->factory = new ServiceFactory($configFile);
    }

    /**
     * Gets the instance via lazy initialization (created on first usage)
     *
     * @param $id
     *
     * @throws CircularDependencyException
     *
     * @return a registered service
     */
    public function get($id)
    {
        $service = $this->repository->get($id);

        if (is_null($service)) {

            if (isset($this->servicesCreating[$id])) {
                $msg = 'Circular dependency detected: ' . implode(' => ', array_keys($this->servicesCreating)) . " => {$id}";
                throw new CircularDependencyException($msg);
            }
            // remmember ids called
            $this->servicesCreating[$id] = true;
            // pass the container force one instantiation per class
            $service = $this->factory->create($id, $this);

            unset($this->servicesCreating[$id]);

            $this->repository->add($id, $service);
        }

        return $service;
    }
}
