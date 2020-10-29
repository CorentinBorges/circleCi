<?php


namespace App\Repository;


use App\Entity\Phone;
use App\Handlers\PhoneHandler;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class PhoneRepository extends ServiceEntityRepository
{
    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(ManagerRegistry $registry, CacheInterface $cache)
    {
        parent::__construct($registry, Phone::class);
        $this->cache = $cache;
    }

    public function findAll()
    {
        return $this->findBy(array(),array('createdAt'=>'ASC'));
    }

    public function findWithQuery(int $page=null,string $attr=null, string $value=null)
    {
        /*if ($page === null) {
            if ($attr === null) {
                return $this->findAll();
            }
            else{
                return $this->findBy([$attr=>$value],array('createdAt'=>'ASC'));
            }
        }
        if (isset($page) && ($attr === null || $value===null)){
            return $this->findBy(array(),array('createdAt'=>'ASC'),10,$page*10);
        }
        else{
            return $this->findBy([$attr=>$value],array('createdAt'=>'ASC'),10,$page*10);
        }*/


        if ($attr === null) {
            if ($page === null) {
                return $this->findAll();
            } else {
                return $this->findBy(array(),array('createdAt'=>'ASC'),10,$page*10);
            }
        } else {
            return $this->findBy([$attr=>$value],array('createdAt'=>'ASC'));
        }
    }
}