<?php

namespace Kunstmaan\SearchBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class KunstmaanSearchExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if(count($config['analyzer_languages']) <= 0) {
            $config['analyzer_languages'] = $this->getDefaultAnalyzerLanguages();
        }

        $container->setParameter('analyzer_languages', $config['analyzer_languages']);
        $container->setParameter('stopwords_nl', Yaml::parse(file_get_contents(__DIR__ . '/../Resources/config/stopwords_nl.yml')));

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    public function getDefaultAnalyzerLanguages()
    {
        return Yaml::parse(file_get_contents(__DIR__ . '/../Resources/config/analyzer_languages.yml'));
    }
}
