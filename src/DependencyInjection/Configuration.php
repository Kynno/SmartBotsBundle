<?php

namespace Kynno\SmartBotsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('kynno_smartbots');

        if (\method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('maker');
        }

        $rootNode
            ->children()
                ->scalarNode('api_key')->defaultNull()->info('Get your API Key here: https://www.mysmartbots.com/process/adminbot.html')->end()
                ->scalarNode('api_url')->defaultValue('https://api.mysmartbots.com/api/bot.html')->info('Use the Bot API URL there: https://www.mysmartbots.com/dev/docs/HTTP_API/Doing_HTTP_API_Calls')->end()
                ->arrayNode('bots')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('name')->end()
                            ->scalarNode('botSecret')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
