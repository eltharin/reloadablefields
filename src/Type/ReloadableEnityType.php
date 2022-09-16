<?php

namespace Eltharin\ReloadableFieldBundle\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Form\AbstractType;

use Eltharin\TwigFilesGetterBundle\Service\FileManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Markup;


class ReloadableEnityType extends AbstractType
{
	public function __construct(private UrlGeneratorInterface $router, private ContainerBagInterface $containerBag)
	{
	}

	public function getParent()
	{
		return EntityType::class;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'reloadbtn' => '<span class="btn success reloader" data-target="{{ id }}" />reload</span>',
			'endpoint' => 'eltharin_reloadablefields_endpoint',
		]);
	}

	public function buildView(FormView $view, FormInterface $form, array $options)
	{
		if(!$this->containerBag->get('eltharin_reloadable_field__useownjsfile') && class_exists(FileManager::class ))
		{
			FileManager::registerJsFile('/bundles/eltharinreloadablefield/js/reloader.js');
		}

		parent::buildView($view, $form, $options);

		$view->vars['attr']['class'] = ($options['attr']['class']??'') . ' reloadable';
		$view->vars['attr']['data-reload-url'] = $options['attr']['data-reload-url'] ?? $this->router->generate($options['endpoint'], [
			'type' => base64_encode(get_class($form->getParent()->getRoot()->getConfig()->getType()->getInnerType())),
			'entitytype' => base64_encode(get_class($form->getParent()->getRoot()->getViewData())),
			'field' => $view->vars['name']
		]);

		$view->vars['params']['after'][] = $options['reloadbtn'];

	}
}