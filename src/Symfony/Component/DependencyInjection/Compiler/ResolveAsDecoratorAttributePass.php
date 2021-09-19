<?php

namespace Symfony\Component\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ResolveAsDecoratorAttributePass extends AbstractRecursivePass
{
    public function process(ContainerBuilder $container)
    {
        if (\PHP_VERSION_ID < 80000) {
            return;
        }

        foreach ($container->getDefinitions() as $class => $definition) {
            $attributeReflector = $container->getReflectionClass($definition->getClass(), false);

            if(null === $attributeReflector) {
                continue;
            }

            /** @var \ReflectionAttribute[] $reflectionAttributes */
            $reflectionAttributes = $attributeReflector->getAttributes(AsDecorator::class);

            if (0 === count($reflectionAttributes)) {
                continue;
            }

            $attribute = array_shift($reflectionAttributes)->newInstance();

            $definition->setDecoratedService(
                $attribute->serviceId,
                null,
                $attribute->priority,
                $attribute->invalidBehavior
            );
        }
    }
}
