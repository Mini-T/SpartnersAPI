<?php
namespace App\Entity;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    operations: [
    new Get(),
    new GetCollection(),
    new Post(),
    new Put(),
    new Patch(),
    new Delete()
],
    normalizationContext: ['groups' => ['read:sportshall:item']],
    denormalizationContext: ['groups' => ['write:sportshall:item']]
)]

class SportsHall {
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Groups(['read:sportshall:item', 'write:sportshall:item', 'read:user:item'])]
    public string $name;

    #[ORM\Column]
    #[Groups(['read:sportshall:item', 'write:sportshall:item', 'read:user:item'])]
    public ?float $latitude;

    #[ORM\Column]
    #[Groups(['read:sportshall:item', 'write:sportshall:item', 'read:user:item'])]
    public ?float $longitude;

    #[ORM\Column]
    #[Groups(['read:sportshall:item', 'write:sportshall:item', 'read:user:item'])]
    public ?string $image;

    #[ORM\Column]
    #[Groups(['read:sportshall:item', 'write:sportshall:item', 'read:user:item'])]
    public string $city;

    #[ORM\OneToMany(mappedBy: 'sportsHallId', targetEntity: User::class)]
    #[Groups(['read:sportshall:item', 'write:sportshall:item'])]
    private Collection $subscribers;

    public function __construct()
    {
        $this->subscribers = new ArrayCollection();
    }

    /******** METHODS ********/

    public function getId()
    {
        return $this->id;
    }

    /**
     * Prepersist gets triggered on Insert
     * @ORM\PrePersist
     */


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return float|null
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * @param float|null $latitude
     */
    public function setLatitude(?float $latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return float|null
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    /**
     * @param float|null $longitude
     */
    public function setLongitude(?float $longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     */
    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function updatedTimestamps()
    {
        if ($this->created_at == null) {
            $this->created_at = new \DateTime('now');
        }
    }


    public function __toString()
    {
        return $this->name;
    }

    public function addSubscriber(User $subscriber): self
    {
        if (!$this->subscribers->contains($subscriber)) {
            $this->subscribers->add($subscriber);
            $subscriber->setSportshall($this);
        }

        return $this;
    }

    public function removeSubscriber(User $subscriber): self
    {
        if ($this->subscribers->removeElement($subscriber)) {
            // set the owning side to null (unless already changed)
            if ($subscriber->getSportshall() === $this) {
                $subscriber->setSportshall(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getSubscribers(): Collection
    {
        return $this->subscribers;
    }

    public function addUser(User $user): self
    {
        if (!$this->subscribers->contains($user)) {
            $this->subscribers->add($user);
            $user->setSportsHallId($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->subscribers->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getSportsHallId() === $this) {
                $user->setSportsHallId(null);
            }
        }

        return $this;
    }
}