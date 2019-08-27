<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A request (or verzoek in dutch) to an organisations (usually govenmental) to do 'something' on behave of a citicen
 * 
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}},
 *     denormalizationContext={"groups"={"write"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\RequestRepository")
 */
class Request
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
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
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @Groups({"read", "write"})
     * @ORM\ManyToOne(targetEntity="App\Entity\RequestType", inversedBy="requests")
     * @ORM\JoinColumn(nullable=false)
     * @ApiFilter(SearchFilter::class, properties={"requestType.id": "exact"})
     */
    private $requestType;

    /**
     * @param string The RSIN of the organisation that ownes this proces
     * 
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
 	 *         	   "description" = "The RSIN of the organisation that ownes this proces",
     *             "type"="string",
     *             "example"="002851234",
 	*              "maxLength"="255"
     *         }
     *     }
     * )
     * 
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     * @ApiFilter(SearchFilter::class, strategy="exact")
     */
    private $rsin;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     */
    private $submitter;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="boolean")
     */
    private $submitterPerson;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="json_array")
     */
    private $properties;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="array", nullable=true)
     */
    private $cases = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $proces;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $submittedAt;

    public function getId()
    {
        return $this->id;
    }

    public function getRequestType(): ?RequestType
    {
        return $this->requestType;
    }

    public function setRequestType(?RequestType $requestType): self
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

    public function getProces(): ?string
    {
        return $this->proces;
    }

    public function setProces(?string $proces): self
    {
        $this->proces = $proces;

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
