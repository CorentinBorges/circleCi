<?php


namespace App\Entity;

use App\DTO\Client\CreateClient\CreateClientFromRequestInput;
use App\DTO\Client\UpdateClient\UpdateClientFromRequestInput;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

/**
 * Class Client
 * @package App\Entity
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 */
class Client implements UserInterface
{
    /**
     * @var string
     * @ORM\Id()
     * @ORM\Column(type="string")
     * @Groups({"list_all", "on_connect"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     * @Groups({"details","on_connect"})
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column (type="string", length=64)
     * @Groups({"list_all"})
     */
    private $name;


    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     * @Groups({"list_all", "details"})
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Groups({"details"})
     */
    private $phoneNumber;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @Groups({"details"})
     */
    private $createdAt;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="client")
     */
    private $users;

    public function __construct(
        string $name,
        string $username,
        string $mail,
        string $phoneNumber,
        string $password,
        EncoderFactoryInterface $encoderFactory)
    {
        $this->id = Uuid::v4()->__toString();
        $this->createdAt = time();
        $this->name = $name;
        $this->username = $username;
        $this->mail = $mail;
        $this->phoneNumber = $phoneNumber;
        $this->password = $encoderFactory->getEncoder(Client::class)->encodePassword($password,'');
        $this->roles = ['ROLE_CLIENT'];
    }


    public function getName()
    {
        return $this->name;
    }
    public function getId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function getMail(): string
    {
        return $this->mail;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    public function getRoles():array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public static function createClientFromRequest(CreateClientFromRequestInput $clientDTO, EncoderFactoryInterface $encoderFactory)
    {
        return new self(
            $clientDTO->name,
            $clientDTO->username,
            $clientDTO->mail,
            $clientDTO->phoneNumber,
            $clientDTO->password,
            $encoderFactory
        );
    }

    public function updateClientFromRequest(UpdateClientFromRequestInput $clientDTO)
    {
        $this->name = $clientDTO->name;
        $this->username = $clientDTO->username;
        $this->phoneNumber=$clientDTO->phoneNumber;
        $this->mail = $clientDTO->mail;
        $this->password=$clientDTO->password;
    }

    public function getSalt()
    {
        return '';
    }

    public function eraseCredentials()
    {
        return;
    }

    public function isAdmin()
    {
        return in_array("ROLE_ADMIN", $this->getRoles());
    }
}