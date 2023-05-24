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
use App\Controller\UserController;
use App\Repository\UserRepository;
use App\State\UserProcessor;
use App\Validators\Constraint\Birthdate;
use App\Validators\Constraint\Level;
use App\Validators\Constraint\Name;
use App\Validators\Constraint\Objective;
use App\Validators\Constraint\Sex;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Email;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource]
#[Post(exceptionToStatus: [UniqueConstraintViolationException::class => 409], processor: UserProcessor::class, denormalizationContext:  ['groups' => ['write:user:item']])]
#[GetCollection(normalizationContext: ['groups' => ['read:user:collection']])]
#[Get(normalizationContext: ['groups' => ['read:user:item']])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: true)]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Email]
    #[Groups(['write:user:item', 'read:chat:item', 'read:user:collection'])]
    private ?string $email = null;


    #[ORM\Column]
    #[ApiProperty(writable: false)]
    private array $roles = ['ROLE_USER'];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['write:user:item'])]
    #[ApiProperty(writableLink: false, security: true)]
    private ?string $password = null;

    #[Groups(['write:user:item', 'read:user:collection'])]
    #[Name]
    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[Groups(['write:user:item', 'read:user:collection'])]
    #[Name]
    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[Groups([ 'write:user:item', 'read:user:collection'])]
    #[Sex]
    #[ORM\Column(length: 255)]
    private ?string $sex = null;

    #[Groups([ 'write:user:item'])]
    #[Name]
    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[Groups([ 'write:user:item'])]
    #[Birthdate]
    #[ORM\Column]
    private string $birthDate;

    #[Groups([ 'write:user:item', 'read:user:collection'])]
    #[Level]
    #[ORM\Column(length: 255)]
    private ?string $level = null;

    #[Groups(['write:user:item'])]
    #[Objective]
    #[ORM\Column(length: 255)]
    private ?string $objective = null;

    #[Groups(['write:user:item', 'read:user:collection'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = '';

    #[Groups(['write:user:item'])]
    #[ORM\Column]
    private bool $premium = false;

    #[Groups(['write:user:item', 'read:user:collection'])]
    #[ORM\Column(nullable: true)]
    private ?float $latitude = null;

    #[Groups(['write:user:item', 'read:user:collection'])]
    #[ORM\Column(nullable: true)]
    private ?float $longitude = null;

    #[ORM\OneToMany(mappedBy: 'sender', targetEntity: Message::class, orphanRemoval: true)]
    private ?Collection $messages;

    #[Groups([ 'write:user:item', 'read:user:collection'])]
    #[ORM\ManyToOne(inversedBy: 'subscribers')]
    private ?SportsHall $sportsHall = null;

    #[ORM\ManyToMany(targetEntity: Chat::class, mappedBy: 'users')]
    private Collection $inChats;

    #[ORM\OneToMany(mappedBy: 'admin', targetEntity: Chat::class, orphanRemoval: true)]
    private Collection $ownedChats;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->inChats = new ArrayCollection();
        $this->ownedChats = new ArrayCollection();
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

    public function getPlainPassword(): string
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
        $this->plainPassword = null;
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

    public function getBirthdate(): string
    {
        return $this->birthDate;
    }

    public function setBirthDate(string $birthDate): self
    {
        $this->birthDate = $birthDate;

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

    public function isPremium(): ?bool
    {
        return $this->premium;
    }

    public function setPremium(bool $premium): self
    {
        $this->premium = $premium;

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

    public function getSportsHall(): ?SportsHall
    {
        return $this->sportsHall;
    }

    public function setSportsHall(?SportsHall $sportsHall): self
    {
        $this->sportsHall = $sportsHall;

        return $this;
    }

    /**
     * @return Collection<int, Chat>
     */
    public function getInChats(): Collection
    {
        return $this->inChats;
    }

    public function addInChat(Chat $inChat): self
    {
        if (!$this->inChats->contains($inChat)) {
            $this->inChats->add($inChat);
            $inChat->addUser($this);
        }

        return $this;
    }

    public function removeInChat(Chat $inChat): self
    {
        if ($this->inChats->removeElement($inChat)) {
            $inChat->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Chat>
     */
    public function getOwnedChats(): Collection
    {
        return $this->ownedChats;
    }

    public function addOwnedChat(Chat $ownedChat): self
    {
        if (!$this->ownedChats->contains($ownedChat)) {
            $this->ownedChats->add($ownedChat);
            $ownedChat->setAdmin($this);
        }

        return $this;
    }

    public function removeOwnedChat(Chat $ownedChat): self
    {
        if ($this->ownedChats->removeElement($ownedChat)) {
            // set the owning side to null (unless already changed)
            if ($ownedChat->getAdmin() === $this) {
                $ownedChat->setAdmin(null);
            }
        }

        return $this;
    }
}
