<?php

namespace Kynno\SmartBotsBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class SmartBotsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $configuration = $this->getConfiguration($configs, $container);
        $config        = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition('kynno.smartbots');
        $definition->setArgument(0, $config['api_url']);
        $definition->setArgument(1, $config['api_key']);

        if (null !== $config['bots']) {
            $definition->setArgument(2, $config['bots']);
        }
    }

    public function getAlias()
    {
        return 'kynno_smartbots';
    }
}
