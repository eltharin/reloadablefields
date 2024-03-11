<?php

namespace Eltharin\ReloadableFieldBundle\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Form\AbstractType;

use Eltharin\TwigFilesGetterBundle\Service\FileManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReloadableEntityType extends AbstractType
{
	public function __construct( private ContainerBagInterface $containerBag )
	{
	}

	public function getParent() : ?string
	{
		return EntityType::class;
	}

	public function configureOptions(OptionsResolver $resolver) : void
	{
		$resolver->setDefaults([
            'reloadbtn' => '',
			'params' => [],
		]);
	}

	public function buildView(FormView $view, FormInterface $form, array $options) : void
	{
        if(!$this->containerBag->get('eltharin_reloadable_field__useownjsfile') && class_exists(FileManager::class ))
		{
			FileManager::registerJsFile('/bundles/eltharinreloadablefield/js/reloader.js');
		}

        $view->vars['params'] = $options['params'];
		$view->vars['attr']['class'] = ($options['attr']['class']??'') . ' reloadable';
		$view->vars['params']['after'][] = [$options['reloadbtn'] ?: 'reloadablefield_reload_button', 1];
	}

}