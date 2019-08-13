<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\Exception\InvalidArgumentException;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use App\Service\RequestTypeService;

class RequestTypeSubscriber implements EventSubscriberInterface
{
	private $params;
	private $requestTypeService;
	private $serializer;
	
	public function __construct(ParameterBagInterface $params, RequestTypeService $requestTypeService, SerializerInterface $serializer)
	{
		$this->params = $params;
		$this->requestTypeService= $requestTypeService;
		$this->serializer= $serializer;
	}
	
	public static function getSubscribedEvents()
	{
		return [
				KernelEvents::VIEW => ['getRequestType', EventPriorities::PRE_VALIDATE],
		];
		
	}
	
	public function getRequestType(GetResponseForControllerResultEvent $event)
	{
		$requestType = $event->getControllerResult();
		$route =  $event->getRequest()->get('_route');
		$method = $event->getRequest()->getMethod();
		$extend = $event->getRequest()->query->get('extend');
		
		//!$requestType instanceof RequestType || Request::METHOD_GET !== $method ||
		if ( $extend != "true" || $route !='api_request_types_get_item') {
			return;
		}
		
		//var_dump($method);
		
		$requestType = $this->requestTypeService->extendRequestType($requestType);
		
		return $requestType;
	}	
}
