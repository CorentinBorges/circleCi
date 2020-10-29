<?php


namespace App\Cache;


use App\Entity\Client;
use App\Repository\UserRepository;
use Symfony\Component\Serializer\SerializerInterface;

class UserCache
{

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(UserRepository $userRepository,SerializerInterface $serializer)
    {
        $this->userRepository = $userRepository;
        $this->serializer = $serializer;
    }

    public function buildAllUsersCache($itemName,$expiredAfter,$client)
    {
        return CacheBuilder::build($itemName, $this->allUserData($client), $expiredAfter);
    }

    private function allUserData(Client $client)
    {
        $usersList = $this->userRepository->findBy(['client' => $client]);
        return $this->serializer->serialize($usersList, 'json',['groups'=>'list_users']);
    }
}