<?php


namespace App\Controller;


use App\Responder\JsonResponder;
use App\Services\ResponseFactory;
use App\Services\SerializerFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class PhoneController
{

    /**
     * @var Serializer
     */
    private $serializer;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $this->serializer = $serializer;
        $this->em = $em;
    }
    /**
     * @Route("/phones", name="app_phones", methods={"GET"})
     * @param JsonResponder $jsonResponder
     * @return Response
     */
    public function allPhones( JsonResponder $jsonResponder)
    {
        $list = ['test', 'test2', 'test3'];
        $listJson = $this->serializer->serialize($list, 'json');
        echo $listJson;
        return $jsonResponder::responder($listJson);

    }
}