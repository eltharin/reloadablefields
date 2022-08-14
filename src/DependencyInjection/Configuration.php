<?php

namespace Eltharin\ReloadableFieldBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
	public function getConfigTreeBuilder()
	{
		$treeBuilder = new TreeBuilder('eltharin_reloadable_field');

		$treeBuilder->getRootNode()
			->children()
				->scalarNode('endpoint')
					->defaultValue('eltharin_reloadablefields_endpoint')
				->end()
			->end()
			->children()
				->scalarNode('showbtn')
					->defaultValue( \Eltharin\ReloadableFieldBundle\Service\ButtonPrinter::class . '::showButton')
				->end()
			->end()
		;

		return $treeBuilder;
	}
}