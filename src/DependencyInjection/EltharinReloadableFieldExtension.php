<?php

namespace Eltharin\ReloadableFieldBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Parser;

class EltharinReloadableFieldExtension extends Extension
{
	public function load(array $configs, ContainerBuilder $container)
	{
		$loader = new YamlFileLoader(
			$container,
			new FileLocator(__DIR__.'/../Resources/config')
		);
		$loader->load('services.yaml');

		$configuration = new Configuration();

		$config = $this->processConfiguration($configuration, $configs);

		$container->setParameter('eltharin_reloadable_field__endpoint', $config['endpoint']);
		$container->setParameter('eltharin_reloadable_field__showbtn', $config['showbtn']);
	}
}