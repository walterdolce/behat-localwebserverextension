<?php

namespace WalterDolce\Behat\SeleniumServerExtension\ServiceContainer;

use Behat\Testwork\EventDispatcher\ServiceContainer\EventDispatcherExtension;
use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class SeleniumServerExtension implements Extension
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasParameter('mink.base_url')) {
            $definition = new Definition('WalterDolce\Behat\SeleniumServerExtension\Server\MinkConfiguration', array(
                '%walterdolce.seleniumserver.configuration.host%',
                '%walterdolce.seleniumserver.configuration.port%',
                '%walterdolce.seleniumserver.configuration.docroot%',
                '%mink.base_url%',
                '%walterdolce.seleniumserver.configuration.router%'
            ));
            $container->setDefinition('walterdolce.seleniumserver.configuration.mink', $definition);

            $container->setAlias('walterdolce.seleniumserver.configuration.inner', new Alias('walterdolce.seleniumserver.configuration.mink'));
        }
        else {
            $container->setAlias('walterdolce.seleniumserver.configuration.inner', new Alias('walterdolce.seleniumserver.configuration.basic'));
        }
    }

    /**
     * Returns the extension config key.
     *
     * @return string
     */
    public function getConfigKey()
    {
        return 'seleniumserver';
    }

    /**
     * Initializes other extensions.
     *
     * This method is called immediately after all extensions are activated but
     * before any extension `configure()` method is called. This allows extensions
     * to hook into the configuration of other extensions providing such an
     * extension point.
     *
     * @param ExtensionManager $extensionManager
     */
    public function initialize(ExtensionManager $extensionManager)
    {
    }

    /**
     * Setups configuration for the extension.
     *
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
            ->children()
                ->scalarNode('host')
                    ->defaultNull()
                ->end()
                ->scalarNode('port')
                    ->defaultNull()
                ->end()
                ->scalarNode('docroot')
                    ->defaultNull()
                ->end()
                ->arrayNode('suites')
                    ->prototype('scalar')->end()
                ->end()
                ->scalarNode('router')
                    ->defaultNull()
                ->end()
            ->end()
        ->end();
    }

    /**
     * Loads extension services into temporary container.
     *
     * @param ContainerBuilder $container
     * @param array $config
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $container->setParameter('walterdolce.seleniumserver.configuration.host', $config['host']);
        $container->setParameter('walterdolce.seleniumserver.configuration.port', $config['port']);
        $container->setParameter('walterdolce.seleniumserver.configuration.docroot', $config['docroot']);
        $container->setParameter('walterdolce.seleniumserver.configuration.suites', $config['suites']);
        $container->setParameter('walterdolce.seleniumserver.configuration.router', $config['router']);

        $this->loadEventSubscribers($container);
        $this->loadServerController($container);
        $this->loadServerConfiguration($container, $config);
    }

    private function loadEventSubscribers(ContainerBuilder $container)
    {
        $definition = new Definition('WalterDolce\Behat\SeleniumServerExtension\EventDispatcher\ServerSubscriber', array(
            new Reference('walterdolce.seleniumserver.server_controller.built_in'),
            new Reference('walterdolce.seleniumserver.suite.suite_identifier')
        ));
        $definition->addTag(EventDispatcherExtension::SUBSCRIBER_TAG);
        $container->setDefinition('walterdolce.seleniumserver.suite_listener', $definition);

        $definition = new Definition('WalterDolce\Behat\SeleniumServerExtension\Suite\SuiteIdentifier', array(
            '%walterdolce.seleniumserver.configuration.suites%'
        ));
        $container->setDefinition('walterdolce.seleniumserver.suite.suite_identifier', $definition);
    }

    private function loadServerController(ContainerBuilder $container)
    {
        $definition = new Definition('WalterDolce\Behat\SeleniumServerExtension\Server\SeleniumServerController', array(
            new Reference('walterdolce.seleniumserver.configuration')
        ));
        $container->setDefinition('walterdolce.seleniumserver.server_controller.built_in', $definition);
    }

    private function loadServerConfiguration(ContainerBuilder $container)
    {
        $definition = new Definition('WalterDolce\Behat\SeleniumServerExtension\Server\BasicConfiguration', array(
            '%walterdolce.seleniumserver.configuration.host%',
            '%walterdolce.seleniumserver.configuration.port%',
            '%walterdolce.seleniumserver.configuration.docroot%',
            '%walterdolce.seleniumserver.configuration.router%'
        ));
        $container->setDefinition('walterdolce.seleniumserver.configuration.basic', $definition);

        $definition = new Definition('WalterDolce\Behat\SeleniumServerExtension\Server\DefaultConfiguration', array(
            new Reference('walterdolce.seleniumserver.configuration.inner'),
            '%paths.base%'
        ));
        $container->setDefinition('walterdolce.seleniumserver.configuration', $definition);
    }
}
