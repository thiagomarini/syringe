<?php

namespace Syringe;

class JsonServiceFactory implements ServiceFactory
{
    /**
     * @var array
     */
    private $serviceList;

    /**
     * @var Json Object
     */
    private $jsonObj;

    /**
     * @var config json file with the services
     */
    private $jsonConfigFile;

    /**
     * @param string $configFile
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

    /**
     * @param \stdClass $serviceData
     * @param Container $container
     *
     * @return array
     */
    private function getArgs(\stdClass $serviceData, Container $container)
    {
        $args = [];
        if (isset($serviceData->arguments)) {
            foreach ($serviceData->arguments as $arg) {
                // recurse back to the container to find dependencies
                $args[] = $container->get($arg->id);
            }
        }

        return $args;
    }

    /**
     * Use reflection to instantiate new objects
     *
     * @param           $id
     * @param Container $container
     *
     * @return mixed - any object
     */
    private function instantiateService($id, Container $container)
    {
        $serviceData = $this->serviceList[$id];

        $reflector = new \ReflectionClass($serviceData->class);

        return $reflector->newInstanceArgs($this->getArgs($serviceData, $container));
    }

    private function loadServiceList()
    {
        $this->loadJsonFile();

        foreach ($this->jsonObj->services as $service) {
            $this->serviceList[$service->id] = $service;
        }
    }

    /**
     * @param string    $id - a known id key
     * @param Container $container
     *
     * @return mixed - a registered service
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
