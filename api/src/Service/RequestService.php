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
use GuzzleHttp\Client;

use App\Entity\Request;

class RequestService
{
	private $em;
	
	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}
		
	/*
	 * Validates a given request conform its request type, returns an array of validation errors or false if no errors could be found
	 * 
	 * 
	 */	
	public function getRequestErrors(Request $request)
	{
		
		
		$properties = [];
		$validation = [];
		
		// Lets get the multidiensional out of it
		$responces = $this->getValidationForRequest($request);
		foreach($responces as $responce){
			$validation[$responce['title']] = $responce;
		}
		
		// Lets get the multidiensional out of it
		foreach($request->getProperties() as $property){
			$properties[array_key_first($property)] = $property[array_key_first($property)];
		}
		
		$errors= [];
		
		//var_dump($validation);
		//var_dump(in_array('Adress', $properties));
		
		// First lets check if we have al te requered properties
		foreach($validation as $rules ){
			if(in_array('required', $rules) && $rules['required'] == true && !array_key_exists($rules['title'], $properties)){
				$errors[] = ["property"=>$rules['title'], "error"=>"This property is required but not present in your request"];/* @todo translate */
			}
		}
					
		// Then Lets validate for the remaining properties for the remaining rules
		foreach($properties as $property => $value){
			// lets first check if the property exisits for this request type
			if(!array_key_exists($property, $validation)){
				$errors[] = ["property"=>$property, "error"=>"This property does not exist for the given request type"];/* @todo translate */
				// there is no further point in validation
				continue;
			}			
			/* @todo lets check for double properties */
			
			// Then the atual valdiation
			$errors = array_merge ($errors, $this->validateProperty($value, $validation[$property]));			
		}
		
		//array_search($rules['title'], array_column($request->getProperties(), 'title'));
				
		if($errors){
			return $errors;
		}
		
		return false;
	}
	
	/*
	 * We need to turn a request type into a set of rules that we can test against
	 */
	public function getValidationForRequest($request)
	{		
		$requestType = $this->getRequestType($request);
		
		/*
		$client = new Client();
		$responce= $client->request('GET', 'http://localhost/request_types/2?extend=true');
		$requestType = json_decode ( $responce->getBody(), true);
		*/
		
		$requestType = json_decode('{
    "_links": {
        "self": {
            "href": "/request_types/2"
        },
        "properties": [
            {
                "href": "/properties/4"
            },
            {
                "href": "/properties/3"
            }
        ]
    },
    "_embedded": {
        "properties": [
            {
                "_links": {
                    "self": {
                        "href": "/properties/4"
                    }
                },
                "id": 4,
                "title": "Adress",
                "type": "string",
                "format": "uri",
                "multiple_of": null,
                "maximum": null,
                "exclusive_maximum": null,
                "minimum": null,
                "exclusive_minimum": null,
                "max_length": null,
                "min_length": null,
                "pattern": null,
                "additional_items": null,
                "max_items": null,
                "min_items": null,
                "unique_items": null,
                "max_properties": null,
                "min_properties": null,
                "required": true,
                "properties": null,
                "additional_properties": null,
                "object": null,
                "enum": [],
                "description": "De bag nummer aanduiding waar een persoon heen haat verhuizen",
                "default_value": null,
                "nullable": null,
                "read_only": null,
                "write_only": null,
                "external_doc": null,
                "example": null,
                "deprecated": null
            },
            {
                "_links": {
                    "self": {
                        "href": "/properties/3"
                    }
                },
                "id": 3,
                "title": "Persoon",
                "type": "integer",
                "format": "bsn",
                "multiple_of": null,
                "maximum": null,
                "exclusive_maximum": null,
                "minimum": null,
                "exclusive_minimum": null,
                "max_length": null,
                "min_length": null,
                "pattern": null,
                "additional_items": null,
                "max_items": null,
                "min_items": null,
                "unique_items": null,
                "max_properties": null,
                "min_properties": null,
                "required": true,
                "properties": null,
                "additional_properties": null,
                "object": null,
                "enum": [],
                "description": "Persoon dat gaat verhuizen",
                "default_value": null,
                "nullable": null,
                "read_only": null,
                "write_only": null,
                "external_doc": null,
                "example": null,
                "deprecated": null
            }
        ]
    },
    "rsin": "den bosh",
    "name": "verhuizen"
}',true);
		
		$validation = [];
		// Lets rebuild te request type into an validation array key on property name
		foreach ($requestType['_embedded']['properties'] as $property){
			$validation[$property['title']] = $property;
		}
		
		return $validation;
	}
	
	
	public function getRequestType(Request $request)
	{
		
		
		// @todo lets get this trough guzzle, it might be an external request type
		return $request->getRequestType();
	}
	
	/*
	 * The actual validation
	 */
	public function validateProperty($value, $rules)
	{		
		$errors= [];
					
		// Since we get the OAS3 property settings as a $rules array
		
		// minLength
		if(array_key_exists('minLength', $rules) && $length = strlen($value) < (int) $rules['minLength']){
			$errors[] = ["property"=>$rules['title'], "error"=>"This property should be less then ".$rules['minLength']." characters,  but you provided ".$length];/* @todo translate */			
			
		}		
		// maxLength
		if(array_key_exists('maxLength', $rules) && $length =  strlen($value) > (int) $rules['maxLength']){
			$errors[] = ["property"=>$rules['title'], "error"=>"This property should be more then ".$rules['maxLength']." characters,  but you provided ".$length];/* @todo translate */	
			
		}
		// enum
		/*
		if(array_key_exists('enum', $rules) && !in_array($value, $rules['enum'])){
			$errors[] = ["property"=>$rules['title'], "error"=>"This property should be one of ".implode (',',$rules['enum'])];/
		}
		*/
		return $errors;
	}
	
	
}
