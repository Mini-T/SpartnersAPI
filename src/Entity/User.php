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
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Put(),
        new Patch(),
        new Delete()
    ],
    normalizationContext: ['groups' => ['read:user:item']],
    denormalizationContext: ['groups' => ['write:user:item']]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: true)]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['read:user:item'])]
    private ?string $email = null;

    #[Groups(['read:user:item', 'write:user:item'])]
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[Groups(['read:user:item', 'write:user:item'])]
    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[Groups(['read:user:item', 'write:user:item'])]
    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[Groups(['read:user:item', 'write:user:item'])]
    #[ORM\Column(length: 255)]
    private ?string $sex = null;

    #[Groups(['read:user:item', 'write:user:item'])]
    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[Groups(['read:user:item', 'write:user:item'])]
    #[ORM\Column]
    private ?int $age = null;

    #[Groups(['read:user:item', 'write:user:item'])]
    #[ORM\Column(length: 255)]
    private ?string $level = null;

    #[Groups(['read:user:item', 'write:user:item'])]
    #[ORM\Column(length: 255)]
    private ?string $objective = null;

    #[Groups(['read:user:item', 'write:user:item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[Groups(['read:user:item', 'write:user:item'])]
    #[ORM\Column]
    private bool $allowLocation = false;

    #[Groups(['read:user:item', 'write:user:item'])]
    #[ORM\Column]
    private bool $premium = false;

    #[Groups(['read:user:item', 'write:user:item'])]
    #[ORM\Column]
    private bool $registeredToAGym = false;

    #[Groups(['read:user:item', 'write:user:item'])]
    #[ORM\Column(nullable: true)]
    private ?float $latitude = null;

    #[Groups(['read:user:item', 'write:user:item'])]
    #[ORM\Column(nullable: true)]
    private ?float $longitude = null;

    #[Groups(['read:user:item', 'write:user:item'])]
    #[ORM\OneToMany(mappedBy: 'sender', targetEntity: Message::class, orphanRemoval: true)]
    private ?Collection $messages;

    #[Groups(['read:user:item', 'write:user:item'])]
    #[ORM\ManyToOne(inversedBy: 'subscribers')]
    private ?SportsHall $sportsHallId = null;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getObjective(): ?string
    {
        return $this->objective;
    }

    public function setObjective(string $objective): self
    {
        $this->objective = $objective;

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

    public function isAllowLocation(): ?bool
    {
        return $this->allowLocation;
    }

    public function setAllowLocation(bool $allowLocation): self
    {
        $this->allowLocation = $allowLocation;

        return $this;
    }

    public function isPremium(): ?bool
    {
        return $this->premium;
    }

    public function setPremium(bool $premium): self
    {
        $this->premium = $premium;

        return $this;
    }

    public function isRegisteredToAGym(): ?bool
    {
        return $this->registeredToAGym;
    }

    public function setRegisteredToAGym(bool $registeredToAGym): self
    {
        $this->registeredToAGym = $registeredToAGym;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setSender($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getSender() === $this) {
                $message->setSender(null);
            }
        }

        return $this;
    }

    public function getSportsHallId(): ?SportsHall
    {
        return $this->sportsHallId;
    }

    public function setSportsHallId(?SportsHall $sportsHallId): self
    {
        $this->sportsHallId = $sportsHallId;

        return $this;
    }
}
