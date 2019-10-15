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
 * An opencase atached to a request
 * 
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\OpenCaseRepository")
 */
class OpenCase
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
	 * @var string $openCase The OpenCase that is handling or supposed to handle this request
	 * @example zrc.gemeente.nl/case/e2984465-190a-4562-829e-a8cca81aa35d
	 *
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *         	   "description" = "The OpenCase that is handling or supposed to handle this request",
	 *             "type"="string",
	 *             "format"="url",
	 *             "example"="zrc.gemeente.nl/case/e2984465-190a-4562-829e-a8cca81aa35d",
	 *             "maxLength"="255",
	 *             "required"=true
	 *         }
	 *     }
	 * )
	 * 
	 * @Assert\Url
	 * @Assert\Length(
	 *      max = 255
	 * )
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255)
     */
    private $openCase;

    /**
     * @var Object $request The request that this case is handling
     * 
     * @MaxDepth(1)
     * @Groups({"read","write"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Request", inversedBy="openCases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $request;
    
    /**
     * @var Datetime $createdAt  The moment this opencase was added to the request
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    public function getId()
    {
        return $this->id;
    }

    public function getOpenCase(): ?string
    {
        return $this->openCase;
    }

    public function setOpenCase(string $openCase): self
    {
        $this->openCase = $openCase;

        return $this;
    }

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    public function setRequest(?Request $request): self
    {
        $this->request = $request;

        return $this;
    }
}
