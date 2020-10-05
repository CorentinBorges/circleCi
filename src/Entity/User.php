<?php


namespace App\Entity;


use App\DTO\Users\CreateUser\CreateUserFromRequestInput;
use App\Repository\ClientRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

/**
 * Class Users
 * @package App\Entity
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
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
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     * @Groups({"list_users"})
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Groups({"list_users"})
     */
    private $email;

    /**
     *
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    public function __construct(string $fullName, string $username, string $email, Client $client)
    {
        $this->id = Uuid::v4()->__toString();
        $this->fullName= $fullName;
        $this->username = $username;
        $this->email = $email;
        $this->client = $client;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    //todo: Client can search just his own users without clientName
    public function getClientName()
    {
        return $this->getClient()->getName();
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public static function createUserFromRequest(CreateUserFromRequestInput $requestInput, ClientRepository $clientRepository)
    {
        if (! $clientRepository->findOneBy(['name' => $requestInput->clientName])){
            throw new EntityNotFoundException(
                'Client with the name: ' . $requestInput->clientName . ' not found'
            );
        }
        /**
         * @var Client $client
         */
        $client = $clientRepository->findOneBy(['name' => $requestInput->clientName]);
        return new self(
            $requestInput->fullName,
            $requestInput->username,
            $requestInput->email,
            $client
        );
    }
}