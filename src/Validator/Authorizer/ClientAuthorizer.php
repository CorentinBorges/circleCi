<?php


namespace App\Validator\Authorizer;


use App\Entity\Client;
use App\Entity\User;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ClientAuthorizer
{

    /**
     * @param Client $client
     * @param User $user
     */
    public static function verifyIsUsersClient(Client $client, User $user)
    {
        if ($user->getClient()->getId() !== $client->getId()) {

            throw new AccessDeniedHttpException("You can not access to this user's informations");
        }
    }
}