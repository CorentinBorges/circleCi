<?php


namespace App\Cache;


use App\Handlers\PhoneHandler;
use App\Repository\PhoneRepository;
use Psr\Cache\CacheItemInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class PhoneCache
{

    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var PhoneRepository
     */
    private $phoneRepository;
    /**
     * @var FilesystemAdapter
     */
    private $cache;

    public function __construct(
        SerializerInterface $serializer,
        PhoneRepository $phoneRepository)
    {
        $this->serializer = $serializer;
        $this->phoneRepository = $phoneRepository;
        $this->cache = new FilesystemAdapter();
    }
    public function buildAllPhones(string $itemName, int $expiredAfter, Request $request)
    {
        /**
         * @var CacheItemInterface $listJson
         */
        $listJson = $this->cache->getItem($itemName);

        if (!$listJson->isHit()) {
            $listPhone = PhoneHandler::build($request, $this->phoneRepository);
            $listJson->set($this->serializer->serialize($listPhone, 'json', ['groups' => 'list_phone']));
            $listJson->expiresAfter($expiredAfter);
            $this->cache->save($listJson);
        }
        return $listJson;
    }

}