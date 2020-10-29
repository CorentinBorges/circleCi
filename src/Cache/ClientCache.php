<?php


namespace App\Cache;


use App\Repository\ClientRepository;
use Symfony\Component\Serializer\SerializerInterface;

class ClientCache
{

    /**
     * @var ClientRepository
     */
    private $clientRepository;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(ClientRepository $clientRepository,SerializerInterface $serializer)
    {
        $this->clientRepository = $clientRepository;
        $this->serializer = $serializer;
    }

    public function buildAllClientsCache(string $itemName,int $expiredAfter)
    {
        return CacheBuilder::build($itemName, $this->allClientsData(), $expiredAfter);
    }

    private function allClientsData()
    {
        $all = $this->clientRepository->findAll();
        return $this->serializer->serialize($all, 'json',['groups'=>'list_client']);
    }
}