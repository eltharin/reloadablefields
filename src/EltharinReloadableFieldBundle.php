<?php

namespace Eltharin\ReloadableFieldBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\Yaml\Parser;
use Eltharin\ReloadableFieldBundle\DependencyInjection\Configuration;

class EltharinReloadableFieldBundle extends AbstractBundle
{
	public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
	{
		$container->parameters()->set('eltharin_reloadable_field__useownjsfile', $config['useOwnJsFile']);
		$container->parameters()->set('eltharin_reloadable_field__reloadButtonHtml', $config['reloadButtonHtml']);
	}

	public function configure(DefinitionConfigurator $definition): void
	{
		$definition->rootNode()
						->children()
							->scalarNode('useOwnJsFile')->defaultValue( false)->end()
							->scalarNode('reloadButtonHtml')->defaultValue( '<i class="fa-solid fa-rotate fa-2x reloader" data-target="{{ id }}"></i>')->end()
						->end()
		;
	}

	public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
	{
		$container->import(__DIR__.'/../config/services.yaml');
	}
}
