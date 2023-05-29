<?php
namespace App\Entity;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\SportsHallRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SportsHallRepository::class)]
#[ApiResource(
    operations: [
    new Get(),
    new GetCollection(),
],
    normalizationContext: ['groups' => ['read:sportshall:item']],
    denormalizationContext: ['groups' => ['write:sportshall:item']]
)]

class SportsHall {
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    #[Groups('read:sportshall:item')]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Groups(['read:sportshall:item', 'write:sportshall:item', 'read:user:collection'])]
    public string $name;

    #[ORM\Column]
    #[Groups(['read:sportshall:item', 'write:sportshall:item'])]
    public ?float $latitude;

    #[ORM\Column]
    #[Groups(['read:sportshall:item', 'write:sportshall:item'])]
    public ?float $longitude;

    #[ORM\Column]
    #[Groups(['read:sportshall:item', 'write:sportshall:item'])]
    public string $city;

    #[Groups(['read:sportshall:item'])]
    #[ApiProperty(readableLink: true)]
    #[ORM\OneToMany(mappedBy: 'sportsHall', targetEntity: User::class)]
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
}