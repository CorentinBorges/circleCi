<?php


namespace App\Controller;


use App\Repository\ClientRepository;
use App\Repository\PhoneRepository;
use App\Responder\JsonResponder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PhoneController extends BaseEntityController
{
    /**
     * @var PhoneRepository
     */
    private $phoneRepository;

    public function __construct(
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        PhoneRepository $phoneRepository
    )
    {
        parent::__construct($serializer,$em,$validator);
        $this->phoneRepository = $phoneRepository;
    }

    /**
     * @Route("/phones", name="list_phones", methods={"GET"})
     * @param JsonResponder $jsonResponder
     * @return Response
     */
    public function allPhones( JsonResponder $jsonResponder)
    {
        $listPhone = $this->phoneRepository->findAll();
        $listJson = $this->serializer->serialize($listPhone, 'json',['groups'=>'list_phone']);
        return $jsonResponder::responder($listJson);
    }

//    /**
//     * @Route ("/phones", name="create_phone", methods={"POST"})
//     * @param Request $request
//     * @return Response
//     */
    /*public function createPhone(Request $request)
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
    }*/
}