<?php

namespace Eltharin\ReloadableFieldBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class ReloadFieldController extends AbstractController
{
	public function reload($type, $entitytype, $field, Request $request, FormFactoryInterface $formFactory, Environment $twig)
	{
		$type = base64_decode($type);
		$entityType = base64_decode($entitytype);

		$entity = new $entityType();
		$form = $formFactory->create($type, $entity);

		if (!$form->has($field))
		{
			return new Response(null, 204);
		}

		$content = $twig->render('@EltharinReloadableField/__specific_field.html.twig', [
			'myForm' => $form->createView(),
			'field' => $field
		]);

		$response = new Response();

		$response->setContent($content);

		return $response;
	}
}