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


use App\Entity\RequestType;

/**
 * This property follows the following shemes (in order of impotance)
 * https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.0.md#specificationExtensions 
 * https://tools.ietf.org/html/draft-wright-json-schema-validation-00
 * http://json-schema.org/
 * 
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}},
 *     denormalizationContext={"groups"={"write"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\PropertyRepository")
 */
class Property
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
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity="App\Entity\RequestType", inversedBy="properties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $requestType;    
    
    /**
     * @ApiProperty(
     * 		iri="http://schema.org/name"
     * )
     * @Assert\NotBlank
     * @Assert\Length(min = 15, max = 255)
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     */
    private $title;
    
    /**
     * Not yet supported by business logic
     * 
     * @ApiProperty(
     * 		iri="http://schema.org/name",
     * 		swaggerContext={"enum"={"string", "integer", "boolean", "number","array"}}
     * )
     *
     * @Assert\NotBlank
     * @Assert\Length(max = 255)
     * @Assert\Choice({"string", "integer", "boolean", "number","array"})
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     */
    private $type;    
    
    /**
     * Not yet supported by business logic
     *
     * @ApiProperty(
     * 		iri="http://schema.org/name",
     * 		swaggerContext={"enum"={"int32","int64","float","double","byte","binary","date","duration","date-time","password","boolean","string","uuid","uri","email","rsin","bag","bsn","iban"}}
     * )
     * 
     * @Assert\NotBlank
     * @Assert\Length(max = 255)
     * @Assert\Choice({"int32","int64","float","double","byte","binary","date","date-time","duration","password","boolean","string","uuid","uri","email","rsin","bag","bsn","iban"})
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     */
    private $format;

    /**
     * 
     * @Assert\Type("integer")
     * @Groups({"read", "write"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private $multipleOf;

    /**
     * Not yet supported by business logic
     * 
     * @Assert\Type("integer")
     * @Groups({"read", "write"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maximum;

    /**
     * Not yet supported by business logic
     * 
     * @Assert\Type("bool")
     * @Groups({"read", "write"})
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $exclusiveMaximum;

    /**
     * Not yet supported by business logic
     * 
     * @Assert\Type("integer")
     * @Groups({"read", "write"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private $minimum;

    /**
     * Not yet supported by business logic
     * 
     * @Assert\Type("bool")
     * @Groups({"read", "write"})
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $exclusiveMinimum;

    /**
     * Suported
     * 
     * @Assert\Type("integer")
     * @Groups({"read", "write"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxLength;

    /**
     * Suported
     * 
     * @Assert\Type("integer")
     * @Groups({"read", "write"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private $minLength;

    /**
     * Not yet supported by business logic
     * 
     * @Assert\Length(max = 255)
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pattern;

    /**
     * Not yet supported by business logic
     * 
     * @Groups({"read", "write"})
     * @ORM\ManyToMany(targetEntity="App\Entity\Property")
     */
    private $items;

    /**
     * Not yet supported by business logic
     * 
     * @Assert\Type("bool")
     * @Groups({"read", "write"})
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $additionalItems;

    /**
     * Not yet supported by business logic
     * 
     * @Groups({"read", "write"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxItems;

    /**
     * Not yet supported by business logic
     * 
     * @Assert\Type("bool")
     * @Groups({"read", "write"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private $minItems;

    /**
     * Not yet supported by business logic
     * 
     * @Assert\Type("bool")
     * @Groups({"read", "write"})
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $uniqueItems;

    /**
     * Not yet supported by business logic
     * 
     * @Groups({"read", "write"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxProperties;

    /**
     * Not yet supported by business logic
     * 
     * @Groups({"read", "write"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private $minProperties;

    /**
     * Suported
     *      * 
     * @Assert\Type("bool")
     * @Groups({"read", "write"})
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $required;

    /**
     * Not yet supported by business logic
     * 
     * @Groups({"read", "write"})
     * @ORM\Column(type="object", nullable=true)
     */
    private $properties;

    /**
     * Not yet supported by business logic
     * 
     * @Groups({"read", "write"})
     * @ORM\Column(type="object", nullable=true)
     */
    private $additionalProperties;

    /**
     * Not yet supported by business logic
     * 
     * @Groups({"read", "write"})
     * @ORM\Column(type="object", nullable=true)
     */
    private $object;

    /**
     * Supported 
     * 
     * @Groups({"read", "write"})
     * @ORM\Column(type="array", nullable=true)
     */
    private $enum = [];

    /**
     * Not yet supported by business logic
     * 
     * @ORM\Column(type="array", nullable=true)
     */
    private $allOf = [];

    /**
     * Not yet supported by business logic
     * 
     * @ORM\Column(type="array", nullable=true)
     */
    private $anyOf = [];

    /**
     * Not yet supported by business logic
     * 
     * @ORM\Column(type="array", nullable=true)
     */
    private $oneOf = [];

    /**
     * Not yet supported by business logic
     * 
     * @ORM\Column(type="object", nullable=true)
     */
    private $definitions;

    /**
     * Not yet supported by business logic
     * 
     * @Assert\Length(max = 255)
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * Not yet supported by business logic
     * 
     * @Assert\Length(max = 255)
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $defaultValue;


    /**
     * Not yet supported by business logic
     * 
     * @Assert\Type("bool")
     * @Groups({"read", "write"})
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $nullable;

    /**
     * Not yet supported by business logic
     * 
     * @ORM\Column(type="object", nullable=true)
     */
    private $discriminator;

    /**
     * Not yet supported by business logic
     * 
     * @Assert\Type("bool")
     * @Groups({"read", "write"})
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $readOnly;

    /**
     * Not yet supported by business logic
     * 
     * @Assert\Type("bool")
     * @Groups({"read", "write"})
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $writeOnly;

    /**
     * Not yet supported by business logic
     * 
     * @ORM\Column(type="object", nullable=true)
     */
    private $xml;

    /**
     * Not yet supported by business logic
     * 
     * @Groups({"read", "write"})
     * @ORM\Column(type="object", nullable=true)
     */
    private $externalDoc;

    /**
     * Not yet supported by business logic
     * 
     * @Assert\Length(max = 255)
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $example;

    /**
     * Not yet supported by business logic
     * 
     * @Assert\Type("bool")
     * @Groups({"read", "write"})
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $deprecated;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $availableFrom;

    /**
     * 
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $availableUntil;

    /**
     * Either a date, datetime or duration (ISO_8601)
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $minDate;

    /**
     * Either a date, datetime or duration (ISO_8601)
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $maxDate;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getMultipleOf(): ?int
    {
        return $this->multipleOf;
    }

    public function setMultipleOf(?int $multipleOf): self
    {
        $this->multipleOf = $multipleOf;

        return $this;
    }

    public function getMaximum(): ?int
    {
        return $this->maximum;
    }

    public function setMaximum(?int $maximum): self
    {
        $this->maximum = $maximum;

        return $this;
    }

    public function getExclusiveMaximum(): ?bool
    {
        return $this->exclusiveMaximum;
    }

    public function setExclusiveMaximum(?bool $exclusiveMaximum): self
    {
        $this->exclusiveMaximum = $exclusiveMaximum;

        return $this;
    }

    public function getMinimum(): ?int
    {
        return $this->minimum;
    }

    public function setMinimum(?int $minimum): self
    {
        $this->minimum = $minimum;

        return $this;
    }

    public function getExclusiveMinimum(): ?bool
    {
        return $this->exclusiveMinimum;
    }

    public function setExclusiveMinimum(?bool $exclusiveMinimum): self
    {
        $this->exclusiveMinimum = $exclusiveMinimum;

        return $this;
    }

    public function getMaxLength(): ?int
    {
        return $this->maxLength;
    }

    public function setMaxLength(?int $maxLength): self
    {
        $this->maxLength = $maxLength;

        return $this;
    }

    public function getMinLength(): ?int
    {
        return $this->minLength;
    }

    public function setMinLength(?int $minLength): self
    {
        $this->minLength = $minLength;

        return $this;
    }

    public function getPattern(): ?string
    {
        return $this->pattern;
    }

    public function setPattern(?string $pattern): self
    {
        $this->pattern = $pattern;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(self $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
        }

        return $this;
    }

    public function removeItem(self $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
        }

        return $this;
    }

    public function getAdditionalItems(): ?bool
    {
        return $this->additionalItems;
    }

    public function setAdditionalItems(?bool $additionalItems): self
    {
        $this->additionalItems = $additionalItems;

        return $this;
    }

    public function getMaxItems(): ?int
    {
        return $this->maxItems;
    }

    public function setMaxItems(?int $maxItems): self
    {
        $this->maxItems = $maxItems;

        return $this;
    }

    public function getMinItems(): ?int
    {
        return $this->minItems;
    }

    public function setMinItems(int $minItems): self
    {
        $this->minItems = $minItems;

        return $this;
    }

    public function getUniqueItems(): ?bool
    {
        return $this->uniqueItems;
    }

    public function setUniqueItems(?bool $uniqueItems): self
    {
        $this->uniqueItems = $uniqueItems;

        return $this;
    }

    public function getMaxProperties(): ?int
    {
        return $this->maxProperties;
    }

    public function setMaxProperties(?int $maxProperties): self
    {
        $this->maxProperties = $maxProperties;

        return $this;
    }

    public function getMinProperties(): ?int
    {
        return $this->minProperties;
    }

    public function setMinProperties(?int $minProperties): self
    {
        $this->minProperties = $minProperties;

        return $this;
    }

    public function getRequired(): ?bool
    {
        return $this->required;
    }

    public function setRequired(?bool $required): self
    {
        $this->required = $required;

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

    public function getAdditionalProperties()
    {
        return $this->additionalProperties;
    }

    public function setAdditionalProperties($additionalProperties): self
    {
        $this->additionalProperties = $additionalProperties;

        return $this;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function setObject($object): self
    {
        $this->object = $object;

        return $this;
    }

    public function getEnum(): ?array
    {
        return $this->enum;
    }

    public function setEnum(?array $enum): self
    {
        $this->enum = $enum;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAllOf(): ?array
    {
        return $this->allOf;
    }

    public function setAllOf(?array $allOf): self
    {
        $this->allOf = $allOf;

        return $this;
    }

    public function getAnyOf(): ?array
    {
        return $this->anyOf;
    }

    public function setAnyOf(?array $anyOf): self
    {
        $this->anyOf = $anyOf;

        return $this;
    }

    public function getOneOf(): ?array
    {
        return $this->oneOf;
    }

    public function setOneOf(?array $oneOf): self
    {
        $this->oneOf = $oneOf;

        return $this;
    }

    public function getDefinitions()
    {
        return $this->definitions;
    }

    public function setDefinitions($definitions): self
    {
        $this->definitions = $definitions;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDefaultValue(): ?string
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(?string $defaultValue): self
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(?string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getNullable(): ?bool
    {
        return $this->nullable;
    }

    public function setNullable(bool $nullable): self
    {
        $this->nullable = $nullable;

        return $this;
    }

    public function getDiscriminator()
    {
        return $this->discriminator;
    }

    public function setDiscriminator($discriminator): self
    {
        $this->discriminator = $discriminator;

        return $this;
    }

    public function getReadOnly(): ?bool
    {
        return $this->readOnly;
    }

    public function setReadOnly(?bool $readOnly): self
    {
        $this->readOnly = $readOnly;

        return $this;
    }

    public function getWriteOnly(): ?bool
    {
        return $this->writeOnly;
    }

    public function setWriteOnly(?bool $writeOnly): self
    {
        $this->writeOnly = $writeOnly;

        return $this;
    }

    public function getXml()
    {
        return $this->xml;
    }

    public function setXml($xml): self
    {
        $this->xml = $xml;

        return $this;
    }

    public function getExternalDoc()
    {
        return $this->externalDoc;
    }

    public function setExternalDoc($externalDoc): self
    {
        $this->externalDoc = $externalDoc;

        return $this;
    }

    public function getExample(): ?string
    {
        return $this->example;
    }

    public function setExample(?string $example): self
    {
        $this->example = $example;

        return $this;
    }

    public function getDeprecated(): ?bool
    {
        return $this->deprecated;
    }

    public function setDeprecated(?bool $deprecated): self
    {
        $this->deprecated = $deprecated;

        return $this;
    }

    public function getAvailableFrom(): ?\DateTimeInterface
    {
        return $this->availableFrom;
    }

    public function setAvailableFrom(\DateTimeInterface $availableFrom): self
    {
        $this->availableFrom = $availableFrom;

        return $this;
    }

    public function getAvailableUntil(): ?\DateTimeInterface
    {
        return $this->availableUntil;
    }

    public function setAvailableUntil(?\DateTimeInterface $availableUntil): self
    {
        $this->availableUntil = $availableUntil;

        return $this;
    }

    public function getMinDate(): ?string
    {
        return $this->minDate;
    }

    public function setMinDate(?string $minDate): self
    {
        $this->minDate = $minDate;

        return $this;
    }

    public function getMaxDate(): ?string
    {
        return $this->maxDate;
    }

    public function setMaxDate(?string $maxDate): self
    {
        $this->maxDate = $maxDate;

        return $this;
    }
}
