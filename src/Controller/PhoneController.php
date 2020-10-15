<?php


namespace App\Controller;


use App\DTO\Phone\CreatePhone\CreatePhoneFromRequestInput;
use App\DTO\Phone\UpdatePhone\UpdatePhoneFromRequestInput;
use App\Entity\Phone;
use App\Helper\ViolationBuilder;
use App\Repository\PhoneRepository;
use App\Responder\JsonResponder;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
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
        PhoneRepository $phoneRepository,
        Security $security
    )
    {
        parent::__construct($serializer,$em,$validator,$security);
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

    /**
     * @Route("/phones/{id}",name="detail_phone",methods={"GET"})
     * @param Phone $phone
     * @return Response
     */
    public function detailOnePhone(Phone $phone)
    {
        $phoneJson = $this->serializer->serialize($phone, 'json',['groups'=>"detail_phone"]);
        return JsonResponder::responder($phoneJson);
    }

    /**
     * @Route ("/phones", name="create_phone", methods={"POST"})
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function createPhone(Request $request)
    {
        /**
         * @var CreatePhoneFromRequestInput $phoneObject
         */
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
        return JsonResponder::responder(null, Response::HTTP_CREATED, ['Location' => "/api/phones/" . $phone->getId()]);
    }

    /**
     * @Route ("/phones/{id}",name="update_phone",methods={"PUT"})
     * @param Phone $phone
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function updatePhone(Phone $phone, Request $request)
    {
        $phoneDTO = new UpdatePhoneFromRequestInput();
        $phoneDTO->setId($phone->getId());
        $newPhone = $this->serializer->deserialize(
            $request->getContent(),
            UpdatePhoneFromRequestInput::class,
            'json',[AbstractNormalizer::OBJECT_TO_POPULATE=>$phoneDTO]
        );

        $errors = $this->validator->validate($newPhone);
        if ($errors->count() > 0) {
            $listErrors=ViolationBuilder::build($errors);
            return JsonResponder::responder(json_encode($listErrors), Response::HTTP_BAD_REQUEST);
        }

        $phone->updateFromRequest($phoneDTO);
        $this->em->flush();

        return JsonResponder::responder(null, Response::HTTP_NO_CONTENT, ['Location' => '/api/phones/' . $phone->getId()]);
    }

    /**
     * @Route("/phones/{id}",name="delete_phone",methods={"DELETE"})
     * @param Phone $phone
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function deletePhone(Phone $phone)
    {
        $this->em->remove($phone);
        $this->em->flush();
        return JsonResponder::responder(null,Response::HTTP_NO_CONTENT);
    }
}