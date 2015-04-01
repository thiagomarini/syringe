<?php

namespace Syringe;

class InMemoryServiceRepository
{
    private $services = [];

    public function get($id)
    {
        if (!isset($this->services[$id])) {
            throw new \Exception('Service not in repository');
        }

        return $this->services[$id];
    }

    public function add($id, $service)
    {
        $this->services[$id] = $service;
    }
}

/**
 * class ConcreteFactory
 */
class ServiceFactory
{
    /**
     * @var array
     */
    protected $serviceList;

    private function loadServiceList()
    {
        $string = file_get_contents('services.json');
        $json   = json_decode($string, true);

        foreach ($json['services'] as $service) {
            $this->serviceList[$service['id']] = $service;
        }
    }

    /**
     * You can imagine to inject your own id list or merge with
     * the default ones...
     */
    public function __construct()
    {

        $this->loadServiceList();

        print_r($this->serviceList);

    }

    /**
     * Creates a vehicle
     *
     * @param string $id a known id key
     *
     * @return a registered service
     * @throws \InvalidArgumentException
     */
    public function create($id)
    {
        if (!array_key_exists($id, $this->serviceList)) {
            throw new \InvalidArgumentException("'$id' is not a registered service");
        }
        $className = $this->serviceList[$id];

        return new $className();
    }
}

class Container
{
    /**
     * @var Singleton reference to singleton instance
     */
    private static $instance;

    /**
     * @var InMemoryServiceRepository
     */
    private $repository;

    private $factory;

    /**
     * is not allowed to call from outside: private!
     */
    private function __construct()
    {
        $this->repository = new InMemoryServiceRepository();
        $this->factory    = new ServiceFactory();
    }

    /**
     * prevent the instance from being cloned
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * prevent from being unserialized
     *
     * @return void
     */
    private function __wakeup()
    {
    }

    /**
     * gets the instance via lazy initialization (created on first usage)
     *
     * @return self
     */
    public static function getInstance()
    {

        if (null === static::$instance) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * @param $id
     *
     * @return a registered service
     */
    public function get($id)
    {
        try {
            return $this->repository->get($id);
        } catch (\Exception $e) {
            return $this->findService($id);
        }
    }

    private function findService($id)
    {
        $this->repository->add($id, $service);
    }
}
