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

use App\Service\RequestService;

class RequestSubscriber implements EventSubscriberInterface
{
	private $params;
	private $requestService;
	private $serializer;
	
	public function __construct(ParameterBagInterface $params, RequestService $requestService, SerializerInterface $serializer)
	{
		$this->params = $params;
		$this->requestService = $requestService;
		$this->serializer= $serializer;
	}
	
	public static function getSubscribedEvents()
	{
		return [
				KernelEvents::VIEW => ['postRequest', EventPriorities::PRE_VALIDATE],
		];
		
	}	
	
	public function postRequest(GetResponseForControllerResultEvent $event)
	{
		$request = $event->getControllerResult();
		$route =  $event->getRequest()->get('_route');
		$method = $event->getRequest()->getMethod();
		
		
		if ($route !='api_requests_post_collection') {
			return;
		}
		
		if($errors = $this->requestService->getRequestErrors($request)){
			var_dump($errors) ;
			return $errors;
		}
		
		return $request;
	}	
}
