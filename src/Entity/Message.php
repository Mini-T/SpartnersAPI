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
use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ApiResource(
    operations: [
    new Get(),
    new GetCollection(),
    new Post(),
    new Put(),
    new Patch(),
    new Delete()
],
    normalizationContext: ['groups' => ['read:message:item']],
    denormalizationContext: ['groups' => ['write:message:item']]
)]
class Message {
    #[ApiProperty(identifier: true)]
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Groups(['read:message:item', 'read:user:item', 'write:message:item', 'read:chat:item'])]
    #[ApiProperty(writable: true)]
    private string $content = "test";


    #[ORM\Column]
    #[Assert\NotBlank]
    #[Groups(['read:message:item', 'write:message:item'])]
    private DateTime $datetime;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    #[ApiProperty(writable: true)]
    #[Groups(['read:message:item'])]
    private User $sender;

    #[Assert\NotNull]
    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[Groups(['read:message:item', 'write:message:item'])]
    private ?Chat $chat = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return DateTime
     */
    public function getDatetime(): DateTime
    {
        return $this->datetime;
    }

    /**
     * @param DateTime $datetime
     */
    public function setDatetime(DateTime $datetime): void
    {
        $this->datetime = $datetime;
    }

    public function getSender(): User
    {
        return $this->sender;
    }

    public function setSender(User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getChat(): ?Chat
    {
        return $this->chat;
    }

    public function setChat(?Chat $chat): self
    {
        $this->chat = $chat;

        return $this;
    }

}