<?php

namespace Eltharin\ReloadableFieldBundle\Controller;

use App\Controller\PlantController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Router;
use Twig\Environment;
use Doctrine\Common\Util\ClassUtils;
use Symfony\Component\DomCrawler\Crawler;

class ReloadFieldController extends AbstractController
{
	public function reload($uuid, $fieldName, Request $request)
	{
		$requestFrom = $request->getSession()->get('formRelaodableRequests')[$uuid];

		$request = Request::create($requestFrom['url'], $requestFrom['method']);


		$route = $this->container->get('router')->matchRequest($request);

		$response = $this->forward($route['_controller'], [
			'_route' => $route['_route'],
			'_route_params' => [],

		], $requestFrom['query'] ?: $requestFrom['request']);

		$crawler = new Crawler($response->getContent());

		return new Response($crawler->filter('#' . $fieldName)->eq(0)->outerHtml());
	}
}