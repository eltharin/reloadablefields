<?php

namespace Eltharin\ReloadableFieldBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ReloadFieldController extends AbstractController
{
	public function __construct(protected RequestStack $request)
	{
	}

	public function reload($uuid, $fieldName)
	{
		$requestFrom = $this->request->getSession()->get('formRelaodableRequests')[$uuid];

		$request = Request::create($requestFrom['url'], $requestFrom['method']);

		$route = $this->container->get('router')->matchRequest($request);
		$route['_route_params'] = $route; //-- on mets aussi les parametres de la route ici pour l'autowiring

		$response = $this->forward($route['_controller'], $route, $requestFrom['query'] ?: $requestFrom['request']);

		$crawler = new Crawler($response->getContent());
		return new Response($crawler->filter('#' . $fieldName)->eq(0)->outerHtml());
	}
}