<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * A request (or verzoek in dutch) to an organizations (usually govenmental) to do 'something' on behave of a citicen or other organisation
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\RequestRepository")
 * @ORM\HasLifecycleCallbacks 
 * @ApiFilter(SearchFilter::class, properties={
 * 		"submitter":"exact",
 * 		"reference":"exact",
 * 		"reference":"exact",
 * 		"status":"exact",
 * 		"requestType":"exact",
 * 		"processType":"exact",
 * 		"organisations.rsin": "exact",
 * 		"organisations.status": "exact",
 * 		"submitters.organisation": "exact", 
 * 		"submitters.person": "exact", 
 * 		"submitters.contact": "exact", 
 * 		"opencase.open_case": "exact"
 * })

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
	 * @Groups({"read"})
	 * @ORM\Id
	 * @ORM\Column(type="uuid", unique=true)
	 * @ORM\GeneratedValue(strategy="CUSTOM")
	 * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
	 */
	private $id;
	
	/**
	 * @param string $reference The human readable reference for this request, build as {gemeentecode}-{year}-{referenceId}. Where gemeentecode is a four digit number for gemeenten and a four letter abriviation for other organizations 
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
	 * @Assert\Length(
	 *      max = 255
	 * )
	 * @Groups({"read"})
	 * @ORM\Column(type="string", length=255, nullable=true) //, unique=true
	 */
	private $reference;
	
	/**
	 * @param string $referenceId The autoincrementing id part of the reference, unique on a organization-year-id basis
	 *	 
	 * @Assert\Positive
	 * @Assert\Length(
	 *      max = 11
	 * )
	 * @ORM\Column(type="integer", length=11, nullable=true)
	 */
	private $referenceId;
	
	/**
	 * @param string $status The status of this request. e.g submitted
	 * @example incomplete
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The status of this request.",
	 *             "type"="string",
	 *             "example"="incomplete",
	 *             "maxLength"="255",
	 *             "enum"={"incomplete", "complete", "submitted", "processed"},
	 *             "default"="incomplete"
	 *         }
	 *     }
	 * )	 
	 *
     * @Assert\Choice({"incomplete", "complete", "submitted", "processed"})
	 * @Assert\Length(
	 *      max = 255
	 * )
	 * 
	 * @Groups({"read","write"})
	 * @ORM\Column(type="string", length=255)
	 */
	private $status = "incomplete";
	
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
	 * @Assert\Url
	 * @Assert\Length(
	 *      max = 255
	 * )
	 * @Groups({"read","write"})
	 * @ORM\Column(type="string", length=255)
	 */
	private $requestType;
	
	/**
	 * @var string $targetOrganization The RSIN of the organization that should handle this request
	 * @example 002851234
	 * @deprecated
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The RSIN of the organization that should handle this request",
	 *             "type"="string",
	 *             "example"="002851234",
	 *              "maxLength"="255",
	 *             "deprecated"=true
	 *         }
	 *     }
	 * )
	 *
	 * @Groups({"read", "write"})
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $targetOrganization;
	
	/**
	 * @var string $submitter The BSN (if person) or RSIN (if organization) that is the primary submiter this request
	 * @example 002851234
	 * @deprecated
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The BSN (if person) or RSIN (if organization) that is the primary submiter this request",
	 *             "type"="string",
	 *             "example"="002851234",
	 *             "maxLength"="255",
	 *             "deprecated"=true
	 *         }
	 *     }
	 * )
	 *
	 * @Groups({"read", "write"})
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $submitter;
	
	/**
	 * @var string $submitters The submitters of this request
	 * 
     * @MaxDepth(1)
	 * @Groups({"read", "write"})
	 * @ORM\OneToMany(targetEntity="App\Entity\Submitter", mappedBy="request", orphanRemoval=true, fetch="EAGER", cascade={"persist"})
	 */
	private $submitters;
	
	/**
	 * @var boolean $submitterPerson True if the submitters is a person
	 * @example true
	 * @deprecated
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "True if the submitters is a person",
	 *             "type"="boolean",
	 *             "example"=true,
	 *             "default"=true,
	 *             "deprecated"=true
	 *         }
	 *     }
	 * )
	 *
	 * @Groups({"read", "write"})
	 * @ORM\Column(type="boolean", nullable=true)
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
	 * @var string $processType The processType type that made this request
	 * @example http://ptc.zaakonline.nl/9bd169ef-bc8c-4422-86ce-a0e7679ab67a
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The processType type that made this reques",
	 *             "type"="string",
	 *             "format"="url",
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
	 */
	private $processType;
	
	/**
	 * @var Datetime $createdAt The moment this request was created by the submitter
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

    /**
	 * @var ArrayCollection $organisations Organisations that are handling this request
	 * 
     * @MaxDepth(1)
	 * @Groups({"read","write"})
     * @ORM\OneToMany(targetEntity="App\Entity\Organisation", mappedBy="request", orphanRemoval=true, fetch="EAGER", cascade={"persist"})
     */
    private $organisations;

    /**
	 * @var ArrayCollection $openCases Any open cases currently atached to this request
	 * 
     * @MaxDepth(1)
	 * @Groups({"read","write"})
     * @ORM\OneToMany(targetEntity="App\Entity\OpenCase", mappedBy="request", orphanRemoval=true, fetch="EAGER", cascade={"persist"})
     */
    private $openCases;

    public function __construct()
    {
        $this->submitters = new ArrayCollection();
        $this->organisations = new ArrayCollection();
        $this->openCases = new ArrayCollection();
    }
	
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
	
	public function getTargetOrganization(): ?string
                                                      	{
                                                      		return $this->targetOrganization;
                                                      	}
	
	public function setTargetOrganization(string $targetOrganization): self
                                                      	{
                                                      		$this->targetOrganization= $targetOrganization;
                                                      		
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

    /**
     * @return Collection|Submitter[]
     */
    public function getSubmitters(): Collection
    {
        return $this->submitters;
    }

    public function addSubmitter(Submitter $submitter): self
    {
        if (!$this->submitters->contains($submitter)) {
            $this->submitters[] = $submitter;
            $submitter->setRequest($this);
        }

        return $this;
    }

    public function removeSubmitter(Submitter $submitter): self
    {
        if ($this->submitters->contains($submitter)) {
            $this->submitters->removeElement($submitter);
            // set the owning side to null (unless already changed)
            if ($submitter->getRequest() === $this) {
                $submitter->setRequest(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Organisation[]
     */
    public function getOrganisations(): Collection
    {
        return $this->organisations;
    }

    public function addOrganisation(Organisation $organisation): self
    {
        if (!$this->organisations->contains($organisation)) {
            $this->organisations[] = $organisation;
            $organisation->setRequest($this);
        }

        return $this;
    }

    public function removeOrganisation(Organisation $organisation): self
    {
        if ($this->organisations->contains($organisation)) {
            $this->organisations->removeElement($organisation);
            // set the owning side to null (unless already changed)
            if ($organisation->getRequest() === $this) {
                $organisation->setRequest(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OpenCase[]
     */
    public function getOpenCases(): Collection
    {
        return $this->openCases;
    }

    public function addOpenCase(OpenCase $openCase): self
    {
        if (!$this->openCases->contains($openCase)) {
            $this->openCases[] = $openCase;
            $openCase->setRequest($this);
        }

        return $this;
    }

    public function removeOpenCase(OpenCase $openCase): self
    {
        if ($this->openCases->contains($openCase)) {
            $this->openCases->removeElement($openCase);
            // set the owning side to null (unless already changed)
            if ($openCase->getRequest() === $this) {
                $openCase->setRequest(null);
            }
        }

        return $this;
    }
}
