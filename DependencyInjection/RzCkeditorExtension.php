<?php

namespace Rz\CkeditorBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Rz\CkeditorBundle\Exception\DependencyInjectionException;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class RzCkeditorExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('ckeditor.xml');
        $this->registerCkeditor($config, $container);
    }
    /**
     * Registers ckeditor widget.
     *
     */
    protected function registerCkeditor(array $config, ContainerBuilder $container)
    {
        // load CkeditorType Template
        $container->setParameter('twig.form.resources', array_merge(
                                                          $container->getParameter('twig.form.resources'),
                                                          array('RzCkeditorBundle:Form:ckeditor.html.twig')
                                                      ));

        if (!empty($config['configs'])) {
            $config = $this->mergeCkeditorToolbars($config);
            $definition = $container->getDefinition('rz_ckeditor.config_manager');
            foreach ($config['configs'] as $name => $configuration) {
                $definition->addMethodCall('setConfig', array($name, $configuration));
            }
        }
    }

    /**
     * Merges the toolbars into the CKEditor configurations.
     *
     * @info Fork from Ivory CKEditor package.
     * @author Eric GELOEN <geloen.eric@gmail.com>
     *
     * @param array $config The CKEditor configuration.
     *
     * @throws \Rz\Exception\DependencyInjectionException
     * @throws \Rz\Exception\DependencyInjectionException
     * @return array                                                                The CKEditor configuration with merged toolbars.
     */
    protected function mergeCkeditorToolbars(array $config)
    {
        $toolbars = array();
        foreach ($config['toolbars']['configs'] as $name => $toolbar) {
            $toolbars[$name] = array();
            foreach ($toolbar as $item) {
                if (is_string($item) && ($item[0] === '@')) {
                    $itemName = substr($item, 1);

                    if (!isset($config['toolbars']['items'][$itemName])) {
                        throw DependencyInjectionException::invalidToolbarItem($itemName);
                    }

                    $item = $config['toolbars']['items'][$itemName];
                }

                $toolbars[$name][] = $item;
            }
        }

        foreach ($config['configs'] as $name => $configuration) {
            if (isset($configuration['toolbar']) && is_string($configuration['toolbar'])) {
                if (!isset($toolbars[$configuration['toolbar']])) {
                    throw DependencyInjectionException::invalidToolbar($configuration['toolbar']);
                }

                $config['configs'][$name]['toolbar'] = $toolbars[$configuration['toolbar']];
            }
        }

        unset($config['toolbars']);

        return $config;
    }
}
