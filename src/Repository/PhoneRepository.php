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

    public function findWith(string $attr, string $value)
    {
        return $this->findBy([$attr => $value], array('createdAt' => 'ASC'));
    }
}