<?php

namespace Eltharin\ReloadableFieldBundle\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Form\AbstractType;

use Eltharin\TwigFilesGetterBundle\Service\FileManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ReloadableEnityType extends AbstractType
{
	public function __construct(private UrlGeneratorInterface $router, private ContainerBagInterface $containerBag, private RequestStack $requestStack )
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
			'endpoint' => 'eltharin_reloadablefields_endpoint',
			'params' => [],
		]);
	}

	public function buildView(FormView $view, FormInterface $form, array $options) : void
	{

		if($this->requestStack->getMainRequest()->getSession()->has('formRelaodableRequests'))
		{
			$avaiableRequests = $this->requestStack->getMainRequest()->getSession()->get('formRelaodableRequests');
		}
		else
		{
			$avaiableRequests = [];
		}

		$requestToSearch =
			[
				'method' => $this->requestStack->getMainRequest()->getMethod(),
				'url' => $this->requestStack->getMainRequest()->getPathInfo(),
				'query' => $this->requestStack->getMainRequest()->query->all(),
				'request' => $this->requestStack->getMainRequest()->request->all(),
				//'attributes' => $this->requestStack->getMainRequest()->attributes->all(),
			];

		if(($key = array_search($requestToSearch, $avaiableRequests)) === false)
		{
			$uid = sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
			$avaiableRequests[$uid] = $requestToSearch;
			$this->requestStack->getMainRequest()->getSession()->set('formRelaodableRequests', $avaiableRequests);
		}
		else
		{
			$uid = $key;
		}

		if(!$this->containerBag->get('eltharin_reloadable_field__useownjsfile') && class_exists(FileManager::class ))
		{
			FileManager::registerJsFile('/bundles/eltharinreloadablefield/js/reloader.js');
		}


        $view->vars['params'] = $options['params'];

		$view->vars['attr']['class'] = ($options['attr']['class']??'') . ' reloadable';;

        $view->vars['attr']['data-reload-url'] = $options['attr']['data-reload-url'] ?? $this->router->generate($options['endpoint'], [
			'uuid' => $uid,//base64_encode(serialize($modifiedOptions) ),
			'fieldName' => $view->vars['id']
		]);

		$view->vars['params']['after'][] = $options['reloadbtn'] ?: $this->containerBag->get('eltharin_reloadable_field__reloadButtonHtml');
	}

}