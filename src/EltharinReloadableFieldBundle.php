<?php

namespace Eltharin\ReloadableFieldBundle;

use Doctrine\ORM\EntityManagerInterface;
use Eltharin\ReloadableFieldBundle\Command\CopyRouteFileCommand;
use Eltharin\ReloadableFieldBundle\Controller\ReloadFieldController;
use Eltharin\ReloadableFieldBundle\Type\ReloadableEnityType;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

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
		$container->services()
			->set(CopyRouteFileCommand::class)
			->args(['%kernel.project_dir%',])
			->tag('console.command')
		;

		$container->services()
			->set(ReloadableEnityType::class)
			->args([
				service(UrlGeneratorInterface::class),
				service(ContainerBagInterface::class),
				service(RequestStack::class),
			])
			->tag('form.type')
		;

		$container->services()
			->set(ReloadFieldController::class)
			->args([
				service(RequestStack::class),
			])
			->call('setContainer')
			->tag('controller.service_arguments')
			->tag('container.service_subscriber')
		;
	}
}
