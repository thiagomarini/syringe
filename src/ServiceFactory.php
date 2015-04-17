<?php

namespace Syringe;

class ServiceFactory
{
    /**
     * @var array
     */
    protected $serviceList;

    /**
     * @var Json Object
     */
    protected $jsonObj;

    /**
     * @var config json file with the services
     */
    protected $jsonConfigFile;

    /**
     * You can imagine to inject your own id list or merge with
     * the default ones...
     */
    public function __construct($configFile)
    {
        $this->jsonConfigFile = $configFile;
        $this->loadServiceList();
    }

    private function loadJsonFile()
    {
        $string        = file_get_contents($this->jsonConfigFile);
        $this->jsonObj = json_decode($string);
    }

    private function loadServiceList()
    {
        $this->loadJsonFile();

        foreach ($this->jsonObj->services as $service) {
            $this->serviceList[$service->id] = $service;
        }
    }

    /**
     * @param array $serviceData
     *
     * @return array
     */
    private function getArgs($serviceData, Container $container)
    {
        $args = [];
        if (isset($serviceData->arguments)) {
            foreach ($serviceData->arguments as $arg) {
                $args[] = $container->get($arg->id);
            }
        }

        return $args;
    }

    /**
     * Use reflection to instantiate new objects
     *
     * @param $id
     *
     * @return object
     */
    private function instantiateService($id, Container $container)
    {
        $serviceData = $this->serviceList[$id];

        $reflector = new \ReflectionClass($serviceData->class);

        return $reflector->newInstanceArgs($this->getArgs($serviceData, $container));
    }

    /**
     * @param string $id a known id key
     *
     * @return a registered service
     *
     * @throws \InvalidArgumentException
     */
    public function create($id, Container $container)
    {

        if (!isset($this->serviceList[$id])) {
            throw new \InvalidArgumentException("'$id' is not a registered service");
        }

        return $this->instantiateService($id, $container);
    }
}
