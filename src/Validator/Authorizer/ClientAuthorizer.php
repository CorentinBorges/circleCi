<?php


namespace App\Validator\Authorizer;


use App\Entity\Client;
use App\Entity\User;
use App\Repository\UserRepository;

class ClientAuthorizer
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function isUserOfClient(Client $client, User $user)
    {
        return $user->getClient()->getId() == $client->getId();
    }
}