<?php


namespace App\Entity;


use App\DTO\Users\CreateUser\CreateUserFromRequestInput;
use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * Class User
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
     * @ORM\Column(type="string")
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
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

    public function getClient(): Client
    {
        return $this->client;
    }

    public static function createUserFromRequest(CreateUserFromRequestInput $requestInput, ClientRepository $clientRepository)
    {
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