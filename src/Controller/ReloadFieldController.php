<?php

namespace Eltharin\ReloadableFieldBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Twig\Environment;
use Doctrine\Common\Util\ClassUtils;


class ReloadFieldController extends AbstractController
{
	public function reload($type, $options, $field, Request $request, FormFactoryInterface $formFactory, Environment $twig, EntityManagerInterface $entityManager)
	{
		$type = base64_decode($type);
		$attributes = unserialize(base64_decode($options));

        $entitiesManaged = array_map(function($a) {return $a->getName();}, $entityManager->getMetadataFactory()->getAllMetadata());
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        //-- recursive function for persist all entity in options passed to the type, clear it after for not save in DB
        //-- TODO:check if better solution
        $fct = function ($element) use (&$fct, $entityManager,$entitiesManaged,$propertyAccessor) {
            if (is_array($element))
            {
                foreach($element as $subElement)
                {
                  $fct($subElement);
                }
            }
            elseif(is_object($element) && in_array(ClassUtils::getClass($element), $entitiesManaged))
            {
                $entityManager->persist($element);

                foreach($entityManager->getMetadataFactory()->getMetadataFor(ClassUtils::getClass($element))->getAssociationMappings() as $assoc)
                {
                    $prop = $propertyAccessor->getValue($element, $assoc['fieldName']);
                    $fct($prop);
                }
            }
        };

        $fct($attributes);

		$form = $formFactory->create($type, null, $attributes);

		if (!$form->has($field))
		{
			return new Response(null, 204);
		}
        $entityManager->clear(); // for not save persist

		$content = $twig->render('@EltharinReloadableField/__specific_field.html.twig', [
			'myForm' => $form->createView(),
			'field' => $field
		]);

		$response = new Response();

		$response->setContent($content);

		return $response;
	}
}