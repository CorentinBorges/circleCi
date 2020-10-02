<?php


namespace App\Controller;


use App\Responder\JsonResponder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhoneController extends BaseEntityController
{

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