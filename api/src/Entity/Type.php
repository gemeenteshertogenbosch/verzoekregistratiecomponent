<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\TypeRepository")
 */
class Type
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Verzoek", mappedBy="type", orphanRemoval=true)
     */
    private $verzoeken;

    public function __construct()
    {
        $this->verzoeken = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Verzoek[]
     */
    public function getVerzoeken(): Collection
    {
        return $this->verzoeken;
    }

    public function addVerzoeken(Verzoek $verzoeken): self
    {
        if (!$this->verzoeken->contains($verzoeken)) {
            $this->verzoeken[] = $verzoeken;
            $verzoeken->setType($this);
        }

        return $this;
    }

    public function removeVerzoeken(Verzoek $verzoeken): self
    {
        if ($this->verzoeken->contains($verzoeken)) {
            $this->verzoeken->removeElement($verzoeken);
            // set the owning side to null (unless already changed)
            if ($verzoeken->getType() === $this) {
                $verzoeken->setType(null);
            }
        }

        return $this;
    }
}
