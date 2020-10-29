<?php


namespace App\Cache;


use App\Handlers\PhoneHandler;
use App\Repository\PhoneRepository;
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

    public function buildAllPhonesCache(string $itemName,int $expiredAfter,Request $request)
    {
        return CacheBuilder::build(
            $itemName,
            $this->phoneDataBuilder($request),
            $expiredAfter
        );
    }

    public function phoneDataBuilder(Request $request)
    {
        $listPhone= PhoneHandler::build($request, $this->phoneRepository);
        return $this->serializer->serialize($listPhone, 'json', ['groups' => 'list_phone']);
    }

}