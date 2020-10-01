<?php


namespace App\Controller;


use App\Responder\JsonResponder;
use App\Services\ResponseFactory;
use App\Services\SerializerFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class PhoneController
{

    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
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