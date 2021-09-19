<?php

namespace Symfony\Component\DependencyInjection\Attribute;

use Symfony\Component\DependencyInjection\ContainerInterface;

#[\Attribute(\Attribute::TARGET_CLASS)]
class AsDecorator
{
    public function __construct(
        public string $serviceId,
        public int $priority = 0,
        public int $invalidBehavior = ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE
    )
    {
    }
}
