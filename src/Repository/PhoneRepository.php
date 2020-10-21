<?php


namespace App\Repository;


use App\Entity\Phone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PhoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Phone::class);
    }

    public function findAll()
    {
        return $this->findBy(array(),array('createdAt'=>'ASC'));
    }

    public function findWithQuery(int $page=null,string $attr=null, string $value=null)
    {
        if ($page === null) {
            return $this->findBy([$attr=>$value],array('createdAt'=>'ASC'));
        }
        if ($attr === null || $value===null){
            return $this->findBy(array(),array('createdAt'=>'ASC'),10,$page*10);
        }
        else{
            return $this->findBy([$attr=>$value],array('createdAt'=>'ASC'),10,$page*10);
        }
    }
}