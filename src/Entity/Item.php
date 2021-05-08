<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     denormalizationContext={"groups"={"post"}},
 *     normalizationContext={"groups"={"read"}}
 * )
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)*
     * @Groups({"post","read"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post","read"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Item::class, inversedBy="items")
     * @Groups({"post","read"})
     */
    private $parent;

    /**
     * @var Item
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="parent", cascade={"persist"})
     * @Groups({"post","read"})
     */
    private $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

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
            $item->setParent($this);
        }

        return $this;
    }

    public function removeItem(self $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getParent() === $this) {
                $item->setParent(null);
            }
        }

        return $this;
    }
}
