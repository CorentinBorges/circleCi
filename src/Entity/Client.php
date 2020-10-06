<?php


namespace App\Entity;



use App\DTO\Client\CreateClient\CreateClientFromRequestInput;
use App\DTO\Client\UpdateClient\UpdateClientFromRequestInput;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;

/**
 * Class Client
 * @package App\Entity
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 * @UniqueEntity(
 *     fields={"email"},
 *     message="A user is already logs with this email",
 * )
 * @UniqueEntity(
 *     fields={"username"},
 *     message="This username already exist"
 * )
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
     */
    private $name;

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
     */
    private $users;

    public function __construct(string $name, string $password, array $roles)
    {
        $this->id = Uuid::v4()->__toString();
        $this->name = $name;
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
            $clientDTO->password,
            $clientDTO->roles
        );
    }

    public function updateClientFromRequest(UpdateClientFromRequestInput $clientDTO)
    {
        $this->name = $clientDTO->name;
        $this->password=$clientDTO->password;
        $this->roles = $clientDTO->roles;
    }

}