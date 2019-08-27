<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * 
 * @ApiResource( 
 *  normalizationContext={"groups"={"read"}},
 *  denormalizationContext={"groups"={"write"}},
 *  collectionOperations={
 *  	"get",
 *  	"post"
 *  },
 * 	itemOperations={
 *     "get"={
 *  		"swagger_context" = {
 *                  "parameters" = {
 *                      {
 *                          "name" = "extend",
 *                          "in" = "query",
 *                          "description" = "Add the properties of the requestType that this requetType extends",
 *                          "required" = false,
 *                          "type" : "boolean"
 *                      }
 *                  }
 *          }        
 *  	},
 *     "put",
 *     "delete"
 *  }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\RequestTypeRepository")
 */
class RequestType
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
    private $name;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @Groups({"read", "write"})
     * @ORM\OneToMany(targetEntity="App\Entity\Property", mappedBy="requestType", orphanRemoval=true, fetch="EAGER", cascade={"persist"})
     */
    private $properties;

    /**
     * @Groups({"write"})
     * @ORM\ManyToOne(targetEntity="App\Entity\RequestType", inversedBy="extendedBy", fetch="EAGER")
     */
    private $extends;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RequestType", mappedBy="extends")
     */
    private $extendedBy;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Request", mappedBy="requestType")
     */
    private $requests;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $availableFrom;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $availableUntil;


    public function __construct()
    {
    	$this->properties= new ArrayCollection();
     	$this->extendedBy = new ArrayCollection();
     	$this->requests = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Properties[]
     */
    public function getProperties(): Collection
    {
    	return $this->properties;
    }

    public function addProperty(Property $property): self
    {
    	if (!$this->properties->contains($property)) {
    		$this->properties[] = $property;
    		$property->setRequestType($this);
        }

        return $this;
    }
        
    /*
     * Used for soft adding properties for the extention functionality
     */
    public function extendProperty(Property $property): self
    {
    	if (!$this->properties->contains($property)) {
    		$this->properties[] = $property;
    	}
    	
    	return $this;
    }

    public function removeProperty(Property $property): self
    {
    	if ($this->properties->contains($property)) {
    		$this->properties->removeElement($property);
            // set the owning side to null (unless already changed)
    		if ($property->getRequestType() === $this) {
    			$property->setRequestType(null);
            }
        }

        return $this;
    }

    public function getExtends(): ?self
    {
        return $this->extends;
    }

    public function setExtends(?self $extends): self
    {
        $this->extends = $extends;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getExtendedBy(): Collection
    {
        return $this->extendedBy;
    }

    public function addExtendedBy(self $extendedBy): self
    {
        if (!$this->extendedBy->contains($extendedBy)) {
            $this->extendedBy[] = $extendedBy;
            $extendedBy->setExtends($this);
        }

        return $this;
    }

    public function removeExtendedBy(self $extendedBy): self
    {
        if ($this->extendedBy->contains($extendedBy)) {
            $this->extendedBy->removeElement($extendedBy);
            // set the owning side to null (unless already changed)
            if ($extendedBy->getExtends() === $this) {
                $extendedBy->setExtends(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Request[]
     */
    public function getRequests(): Collection
    {
        return $this->requests;
    }

    public function addRequest(Request $request): self
    {
        if (!$this->requests->contains($request)) {
            $this->requests[] = $request;
            $request->setRequestType($this);
        }

        return $this;
    }

    public function removeRequest(Request $request): self
    {
        if ($this->requests->contains($request)) {
            $this->requests->removeElement($request);
            // set the owning side to null (unless already changed)
            if ($request->getRequestType() === $this) {
                $request->setRequestType(null);
            }
        }

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

}
