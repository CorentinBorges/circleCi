<?php


namespace App\Validator\Authorizer;


use App\Entity\Client;
use App\Entity\User;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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

    /**
     * @param Client $client
     * @param User $user
     * @param string $path
     */
    public static function verifyIsUsersClient(Client $client, User $user)
    {
        if ($user->getClient()->getId() !== $client->getId()) {

            throw new AccessDeniedHttpException("You can not access to this user's informations");
        }
    }
}