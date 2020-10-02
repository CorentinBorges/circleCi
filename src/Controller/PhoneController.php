<?php


namespace App\Controller;


use App\DTO\Phones\CreatePhones\CreatePhoneFromRequestInput;
use App\Entity\Phone;
use App\Helper\ViolationBuilder;
use App\Responder\JsonResponder;
use App\Services\ResponseFactory;
use App\Services\SerializerFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->em = $em;
        $this->validator = $validator;
    }

    /**
     * @Route("/phones", name="list_phones", methods={"GET"})
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

    /**
     * @Route ("/phones", name="create_phone", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function createPhone(Request $request)
    {

        $phoneObject = $this->serializer->deserialize(
            $request->getContent(),
            CreatePhoneFromRequestInput::class,
            'json'
        );

        $errors=$this->validator->validate($phoneObject);
        if($errors->count() > 0 ){
            $listErrors = ViolationBuilder::build($errors);
            return JsonResponder::responder(json_encode($listErrors),Response::HTTP_BAD_REQUEST);
        }

        $phone = Phone::createFromRequest($phoneObject);
        $this->em->persist($phone);
        $this->em->flush();
        return JsonResponder::responder(null, Response::HTTP_CREATED, ['Location' => "phones" . $phone->getId()]);
    }
}