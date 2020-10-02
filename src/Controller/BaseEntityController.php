<?php


namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseEntityController
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->em = $em;
        $this->validator = $validator;
    }

}