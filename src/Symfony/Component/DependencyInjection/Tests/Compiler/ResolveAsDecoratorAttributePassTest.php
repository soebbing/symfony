<?php

namespace Symfony\Component\DependencyInjection\Tests\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Compiler\DecoratorServicePass;
use Symfony\Component\DependencyInjection\Compiler\ResolveAsDecoratorAttributePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ResolveAsDecoratorAttributePassTest extends TestCase
{
    public function testAttributesApplyDecorations()
    {
        $container = new ContainerBuilder();
        $container
            ->register(Foo::class)
            ->setAutoconfigured(true)
            ->setPublic(true);

        $container
            ->register(Bar::class)
            ->setAutoconfigured(true)
            ->setPublic(true);

        $container
            ->register(FooUsingClass::class)
            ->setAutoconfigured(true)
            ->setPublic(true);

        #(new ResolveAsDecoratorAttributePass())->process($container);
        #(new DecoratorServicePass)->process($container);

        $container->compile();

        #dd($container);
        $this->assertEquals(Bar::class, $container->get(Bar::class));
        $this->assertEquals(Bar::class, $container->get(Foo::class));
    }
}

class Foo {
    public string $value = 'foo';
}

#[AsDecorator(Foo::class, 0, ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE)]
class Bar extends Foo {
    public string $value = 'bar';
    public function __construct(public Foo $foo) {}
}

class FooUsingClass
{
    public function __construct(
        public Foo $foo
    )
    {
    }
}
