<?php
// Conduction/CommonGroundBundle/Service/RequestTypeService.php

/*
 * This file is part of the Conduction Common Ground Bundle
 *
 * (c) Conduction <info@conduction.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\RequestType;

class RequestTypeService
{
	private $em;
	
	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}
	
	public function extendRequestType(RequestType $requestType)
	{
		$requestTypesProcessed = [(string) $requestType->getId()];
		$extendedRequest = $requestType->getExtends();
		$propertiesTitles = [];
		// Let loop this for as long as we can extend requests
		while($extendedRequest){
			// But kill it the moment we spot an invinate loop
			if(in_array((string) $extendedRequest->getId(), $requestTypesProcessed)){
				throw new \Exception('Request type '.$extendedRequest->getName().'(id:'.(string) $extendedRequest->getId().') has been referenced more then once in this extention, posible loop detected');
			}
			
			// lets add the id to the check array, so that we can prefend loops
			$requestTypesProcessed[(string) $extendedRequest->getId()] = true;
			
			// Then we need to do the actual extending
			foreach ($extendedRequest->getProperties() as $property){
				
				/* @todo we should als check on dubble property titles
				if(in_array($property->getTitle(), $propertiesTitles)){
					throw new \Exception('There is more then one property titled '.$property->getTitle().' in this extention');
				}*/
				
				$propertiesTitles[$property->getTitle()] = true;
				$requestType->extendProperty($property);
			}
			
			$extendedRequest = $extendedRequest->getExtends();
		}
		
		return $requestType;
	}
	
}
