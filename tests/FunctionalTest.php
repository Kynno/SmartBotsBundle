<?php

namespace Kynno\SmartBotsBundle\Tests;

use Kynno\SmartBotsBundle\Service\SmartBots;
use Kynno\SmartBotsBundle\SmartBotsBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class FunctionalTest extends TestCase
{
    public function testServiceWiring()
    {
        $kernel = new KynnoSmartBotsBundleTestingKernel('test', true);
        $kernel->boot();
        $container = $kernel->getContainer();

        $smartbots = $container->get('kynno.smartbots');
        $this->assertInstanceOf(SmartBots::class, $smartbots);
    }
}

class KynnoSmartBotsBundleTestingKernel extends Kernel
{
    public function registerBundles()
    {
        return [
            new SmartBotsBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
    }
}
