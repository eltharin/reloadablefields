<?php

namespace Eltharin\ReloadableFieldBundle;

use Doctrine\ORM\EntityManagerInterface;
use Eltharin\ReloadableFieldBundle\Command\CopyRouteFileCommand;
use Eltharin\ReloadableFieldBundle\Controller\ReloadFieldController;
use Eltharin\ReloadableFieldBundle\Form\BlockReloadSubmitFormExtension;
use Eltharin\ReloadableFieldBundle\Type\ReloadableEntityType;
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
	}

	public function configure(DefinitionConfigurator $definition): void
	{
		$definition->rootNode()
						->children()
							->scalarNode('useOwnJsFile')->defaultValue( false)->end()
						->end()
		;
	}

	public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
	{
        $container->extension('twig', [
            'form_themes' => ['@EltharinReloadableField/reload_btn_block.html.twig']
        ]);

		$container->services()
			->set(ReloadableEntityType::class)
			->args([
				service(ContainerBagInterface::class),
			])
			->tag('form.type')
		;

		$container->services()
			->set(BlockReloadSubmitFormExtension::class)
			->args([
				service(RequestStack::class),
			])
			->tag('form.type_extension')
		;
	}
}
