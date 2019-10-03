<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A request (or verzoek in dutch) to an organisations (usually govenmental) to do 'something' on behave of a citicen
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\RequestRepository")
 * @ORM\HasLifecycleCallbacks 
 */
class Request
{
	/**
	 * @var \Ramsey\Uuid\UuidInterface $id The UUID identifier of this object
	 * @example e2984465-190a-4562-829e-a8cca81aa35d
	 *
	 * @ApiProperty(
	 * 	   identifier=true,
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The UUID identifier of this object",
	 *             "type"="string",
	 *             "format"="uuid",
	 *             "example"="e2984465-190a-4562-829e-a8cca81aa35d"
	 *         }
	 *     }
	 * )
	 *
	 * @Assert\Uuid
	 * @ORM\Id
	 * @ORM\Column(type="uuid", unique=true)
	 * @ORM\GeneratedValue(strategy="CUSTOM")
	 * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
	 */
	private $id;
	
	/**
	 * @param string $reference The human readable reference for this request, build as {gemeentecode}-{year}-{referenceId}. Where gemeentecode is a four digit number for gemeenten and a four letter abriviation for other organisations 
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The human readable reference for this request",
	 *             "type"="string",
	 *             "example"="6666-2019-0000000012",
	 *             "maxLength"="255"
	 *         }
	 *     }
	 * )	 
	 *
	 * @Groups({"read"})
	 * @ORM\Column(type="string", length=255, nullable=true) //, unique=true
	 * @ApiFilter(SearchFilter::class, strategy="exact")
	 */
	private $reference;
	
	/**
	 * @param string $referenceId The autoincrementing id part of the reference, unique on a organisation-year-id basis
	 *	 
	 * @ORM\Column(type="integer", length=11, nullable=true)
	 */
	private $referenceId;
	
	/**
	 * @param string $status The status of this request. e.g submitted
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The status of this request.",
	 *             "type"="string",
	 *             "example"="submitted",
	 *             "maxLength"="255",
	 *             "enum"={"incomplete", "complete", "submitted", "processed"}
	 *         }
	 *     }
	 * )	 *
	 *
     * @Assert\Choice({"incomplete", "complete", "submitted", "processed"})
	 * @Assert\NotNull
	 * @Assert\Length(
	 *      max = 255
	 * )
	 * @Groups({"read"})
	 * @ORM\Column(type="string", length=255)
	 * @ApiFilter(SearchFilter::class, strategy="exact")
	 */
	private $status = "submitted";
	
	/**
	 * @var string $requestType The request type agains wich this request should be validated
	 * @example http://vtc.zaakonline.nl/9bd169ef-bc8c-4422-86ce-a0e7679ab67a
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The request type agains wich this request should be validated",
	 *             "type"="string",
	 *             "format"="uri",
	 *             "example"="http://vtc.zaakonline.nl/9bd169ef-bc8c-4422-86ce-a0e7679ab67a",
	 *             "maxLength"="255",
	 *             "required"=true
	 *         }
	 *     }
	 * )
	 *
	 * @Assert\NotNull
	 * //@Assert\Url
	 * @Assert\Length(
	 *      max = 255
	 * )
	 * @Groups({"read","write"})
	 * @ORM\Column(type="string", length=255)
	 * @ApiFilter(SearchFilter::class, strategy="exact")
	 */
	private $requestType;
	
	/**
	 * @var string $rsin The RSIN of the organisation that ownes this proces
	 * @example 002851234
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The RSIN of the organisation that ownes this proces",
	 *             "type"="string",
	 *             "example"="002851234",
	 *              "maxLength"="255",
	 *             "required"=true
	 *         }
	 *     }
	 * )
	 *
	 * @Assert\NotNull
	 * @Groups({"read", "write"})
	 * @ORM\Column(type="string", length=255)
	 * @ApiFilter(SearchFilter::class, strategy="exact")
	 */
	private $rsin;
	
	/**
	 * @var string $submitter The BSN (if person) or RSIN (if organisation) that submited this request
	 * @example 002851234
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The BSN (if person) or RSIN (if organisation) that submited this request",
	 *             "type"="string",
	 *             "example"="002851234",
	 *             "maxLength"="255",
	 *             "required"=true
	 *         }
	 *     }
	 * )
	 *
	 * @Assert\NotNull
	 * @Groups({"read", "write"})
	 * @ORM\Column(type="string", length=255)
	 * @ApiFilter(SearchFilter::class, strategy="exact")
	 */
	private $submitter;
	
	/**
	 * @var boolean $submitterPerson True if the submitters is a person
	 * @example true
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "True if the submitters is a person",
	 *             "type"="boolean",
	 *             "example"=true,
	 *             "default"=true
	 *         }
	 *     }
	 * )
	 *
	 * @Groups({"read", "write"})
	 * @ORM\Column(type="boolean")
	 */
	private $submitterPerson = true;
	
	/**
	 * @var array $properties The actual properties of the request
	 * @example {}
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The actual properties of the request",
	 *             "type"="array",
	 *             "format"="json",
	 *             "example"={}
	 *         }
	 *     }
	 * )
	 *
	 * @Groups({"read", "write"})
	 * @ORM\Column(type="json_array")
	 */
	private $properties;
	
	/**
	 * @var array $cases cases from ZGW that are atached to this request
	 * @example []
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "cases from ZGW that are atached to this request",
	 *             "type"="array",
	 *             "example"="[]"
	 *         }
	 *     }
	 * )
	 * @Groups({"read"})
	 * @ORM\Column(type="array", nullable=true)
	 */
	private $cases = [];
	
	
	/**
	 * @var string $proces The proces type that made this request
	 * @example http://ptc.zaakonline.nl/9bd169ef-bc8c-4422-86ce-a0e7679ab67a
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The proces type that made this reques",
	 *             "type"="string",
	 *             "format"="uri",
	 *             "example"="http://ptc.zaakonline.nl/9bd169ef-bc8c-4422-86ce-a0e7679ab67a",
	 *             "maxLength"="255"
	 *         }
	 *     }
	 * )
	 *
	 * @Assert\Url
	 * @Assert\Length(
	 *      max = 255
	 * )
	 * @Groups({"read","write"})
	 * @ORM\Column(type="string", length=255, nullable=true)
	 * @ApiFilter(SearchFilter::class, strategy="exact")
	 */
	private $process;
	
	/**
	 * @var Datetime $createdAt The moment this request was created by the submitter
	 * 
	 * 
	 * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $createdAt;
	
	/**
	 * @var Datetime $submittedAt The moment this request was submitted by the submitter
	 * 
	 * @Groups({"read"})
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $submittedAt;
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getReference(): ?string
	{
		return $this->reference;
	}
	
	public function setReference(string $reference): self
	{
		$this->reference = $reference;
		
		return $this;
	}
	
	public function getReferenceId(): ?int
	{
		return $this->reference;
	}
	
	public function setReferenceId(int $referenceId): self
	{
		$this->referenceId = $referenceId;
		
		return $this;
	}
	
	public function getStatus(): ?string
	{
		return $this->status;
	}
	
	public function setStatus(string $status): self
	{
		$this->status = $status;
		
		return $this;
	}
	
	public function getRequestType(): ?string
	{
		return $this->requestType;
	}
	
	public function setRequestType(string $requestType): self
	{
		$this->requestType = $requestType;
		
		return $this;
	}
	
	public function getRsin(): ?string
	{
		return $this->rsin;
	}
	
	public function setRsin(string $rsin): self
	{
		$this->rsin = $rsin;
		
		return $this;
	}
	
	public function getSubmitter(): ?string
	{
		return $this->submitter;
	}
	
	public function setSubmitter(string $submitter): self
	{
		$this->submitter = $submitter;
		
		return $this;
	}
	
	public function getSubmitterPerson(): ?bool
	{
		return $this->submitterPerson;
	}
	
	public function setSubmitterPerson(bool $submitterPerson): self
	{
		$this->submitterPerson = $submitterPerson;
		
		return $this;
	}
	
	public function getProperties()
	{
		return $this->properties;
	}
	
	public function setProperties($properties): self
	{
		$this->properties = $properties;
		
		return $this;
	}
	
	public function getCases(): ?array
	{
		return $this->cases;
	}
	
	public function setCases(?array $cases): self
	{
		$this->cases = $cases;
		
		return $this;
	}
	
	public function getProcess(): ?string
	{
		return $this->process;
	}
	
	public function setProcess(?string $process): self
	{
		$this->process = $process;
		
		return $this;
	}
	
	public function getCreatedAt(): ?\DateTimeInterface
	{
		return $this->createdAt;
	}
	
	public function setCreatedAt(\DateTimeInterface $createdAt): self
	{
		$this->createdAt = $createdAt;
		
		return $this;
	}
	
	public function getSubmittedAt(): ?\DateTimeInterface
	{
		return $this->submittedAt;
	}
	
	public function setSubmittedAt(\DateTimeInterface $submittedAt): self
	{
		$this->submittedAt = $submittedAt;
		
		return $this;
	}
}
