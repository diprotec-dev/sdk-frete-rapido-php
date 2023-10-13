<?php

declare(strict_types=1);

namespace Unit;

use PHPUnit\Framework\TestCase;

abstract class FreteRapidoTestCase extends TestCase
{
   
    protected function arrayMock(string $mockName): array
    {
        $content = $this->jsonMock($mockName);
        
        return json_decode($content, true);
    }
    
  
    protected function jsonMock(string $mockName): string
    {
        return file_get_contents(__DIR__ . "/Mocks/$mockName.json");
    }
    
  
}