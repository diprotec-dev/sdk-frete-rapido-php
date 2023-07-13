<?php

declare(strict_types=1);

namespace FreteRapido\Endpoints\Collections;

use FreteRapido\Endpoints\Abstracts\Endpoint;
use FreteRapido\Exceptions\EndpointNotFound;
use InvalidArgumentException;

class Endpoints
{

    private array $endpoints = [];
    

    public function __construct(array $endpoints = [])
    {
        if (empty($endpoints)) return;
        
        foreach ($endpoints as $name => $endpoint) {
            $this->add($name, $endpoint);
        }
    }
    
 
    public function add(string $name, string $endpointClass): self
    {
        if (! class_exists($endpointClass)) {
            throw new EndpointNotFound("The endpoint class {$endpointClass} doesn't exist.");
        }
        
        if (! is_subclass_of($endpointClass, Endpoint::class)) {
            throw new InvalidArgumentException(
                "Class {$endpointClass} doesn't extends Endpoints\\Abstracts\\Endpoint.
            ");
        }
        
        $this->endpoints[$name] = $endpointClass;
        
        return $this;
    }
    

    public function has(string $key): bool
    {
        return isset($this->endpoints[$key]);
    }
    
  
    public function get(string $key): ?string
    {
        if ($this->has($key)) {
            return $this->endpoints[$key];
        }
        
        return null;
    }
    
 
    public function all(): array
    {
        return $this->endpoints;
    }
}