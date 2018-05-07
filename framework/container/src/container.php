<?php

declare(strict_types=1);

namespace framework\container;

class container implements containerInterface {

    private $alias      = [];

    private $services   = [];

    private $factories  = [];

    public function __construct(array $config)
    {
        if(isset($config['factory']))
            $this->setFactories($config['factory']);

        if(isset($config['service']))
            $this->setServices($config['service']);

        if(isset($config['alias']))
            $this->setAlias(($config['alias']));
    }

    public function get(String $service)
    {
        $requestedName = $service;
        if (isset($this->services[$requestedName]))
            return $this->services[$requestedName];


        if(isset($this->alias[$service]))
            $service = $this->alias[$service];

        if($requestedName !== $service && isset($this->services[$service])){
            $this->services[$requestedName] = $this->services[$service];
            return $this->services[$service];
        }

        $object = $this->doCreate($service);
        $this->services[$service] = $object;
        if($requestedName !== $service)
            $this->services[$requestedName] = $object;


        return $object;
    }

    public function has(String $service) : boolean
    {
        $service    = isset($this->alias[$service]) ? $this->alias[$service] : $service;
        $found      =  isset($this->services[$service]) || isset($this->factories[$service]);

        return $found;

    }

    public function getAlias() : array
    {
        return $this->alias;
    }

    public function getServices() : array
    {
        return $this->services;
    }

    public function getFactories() : array
    {
        return $this->factories;
    }


    public function setAlias(array $alias, bool $overwrite = false)
    {

        if($overwrite)
            $this->alias = array_merge($this->alias, $alias);
        else
            $this->alias = array_merge($alias, $this->alias);
        return $this;
    }

    public function setServices(array $services, bool $overwrite = false)
    {
        if($overwrite)
            $this->services = array_merge($this->services, $services);
        else
            $this->services = array_merge($services, $this->services);
        return $this;
    }

    public function setFactories(array $factories, bool $overwrite = false)
    {
        if($overwrite)
            $this->factories = array_merge($this->factories, $factories);
        else
            $this->factories = array_merge($factories, $this->factories);
        return $this;
    }

    private function doCreate($resolvedName)
    {

        if(isset($this->factories[$resolvedName]) && class_exists($this->factories[$resolvedName])){
            $factory    = new $this->factories[$resolvedName];
            $object     = $factory($this, $resolvedName);

            return $object;
        }

        trigger_error(sprintf(
            'Service with name "%s" could not be created.',
            $resolvedName), E_USER_ERROR);
    }

}