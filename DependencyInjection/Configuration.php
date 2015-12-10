<?php

namespace Zanshee\TelegramBDKBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package Zanshee\TelegramBDKBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('telegram_bdk');

        $rootNode->useAttributeAsKey('name')->prototype('array')->children()
            ->scalarNode('api_key')->isRequired()->cannotBeEmpty()->info('api key for bot')->end()
            ->booleanNode('webhook')->defaultFalse()->info('use webhook (default: false)')->end()
            ->end();

        return $treeBuilder;
    }
}
