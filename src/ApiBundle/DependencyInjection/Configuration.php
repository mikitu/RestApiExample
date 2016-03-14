<?php
/**
 * Created by PhpStorm.
 * User: mbucse
 * Date: 13/03/2016
 * Time: 20:19
 */

namespace ApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('api');
        return $treeBuilder;
    }
}