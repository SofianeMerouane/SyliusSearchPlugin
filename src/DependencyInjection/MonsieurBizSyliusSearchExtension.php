<?php

/*
 * This file is part of Monsieur Biz' Search plugin for Sylius.
 *
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusSearchPlugin\DependencyInjection;

use MonsieurBiz\SyliusSearchPlugin\Search\RequestInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class MonsieurBizSyliusSearchExtension extends Extension
{
    public const EXTENSION_CONFIG_NAME = 'monsieurbiz.search.config';

    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        foreach ($config as $name => $value) {
            $container->setParameter(self::EXTENSION_CONFIG_NAME . '.' . $name, $value);
        }

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $container->registerForAutoconfiguration(RequestInterface::class)
            ->addTag('monsieurbiz.search.request')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return str_replace(['monsieur_biz'], ['monsieurbiz'], parent::getAlias());
    }
}
