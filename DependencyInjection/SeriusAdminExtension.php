<?php
/**
 * @author      Dirk Luijk <dirk@luijkwebcreations.nl>
 * @copyright   Copyright (c) 2014 Dirk Luijk
 * @license     MIT
 */

namespace Serius\Bundle\AdminBundle\DependencyInjection;


use Serius\Bundle\AdminBundle\Routing\EntityRoute;
use Serius\Bundle\AdminBundle\Routing\EntityRouteCollection;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class SeriusAdminExtension extends Extension
{
    /**
     * @inheritdoc
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $loader->load('services.yml');
        $loader->load('menus.yml');
    }
}