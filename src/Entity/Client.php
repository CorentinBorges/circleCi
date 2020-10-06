<?php


namespace App\Entity;



use App\DTO\Client\CreateClient\CreateClientFromRequestInput;
use App\DTO\Client\UpdateClient\UpdateClientFromRequestInput;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

/**
 * Class Client
 * @package App\Entity
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 */
class Client
{
    /**
     * @var string
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     * @Groups({"list_all", "details"})
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
     * @ORM\Column(type="json")
     */
    private $roles;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="client")
     * @Groups({"details"})
     */
    private $users;

    public function __construct(string $name,string $mail,string $phoneNumber, string $password, array $roles)
    {
        $this->id = Uuid::v4()->__toString();
        $this->createdAt=time();
        $this->name = $name;
        $this->mail=$mail;
        $this->phoneNumber=$phoneNumber;
        $this->password=$password;
        $this->roles = $roles;

    }

    
    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
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

    public static function createClientFromRequest(CreateClientFromRequestInput $clientDTO)
    {
        return new self(
            $clientDTO->name,
            $clientDTO->mail,
            $clientDTO->phoneNumber,
            $clientDTO->password,
            $clientDTO->roles
        );
    }

    public function updateClientFromRequest(UpdateClientFromRequestInput $clientDTO)
    {
        $this->name = $clientDTO->name;
        $this->phoneNumber=$clientDTO->phoneNumber;
        $this->mail = $clientDTO->mail;
        $this->password=$clientDTO->password;
        $this->roles = $clientDTO->roles;
    }

}