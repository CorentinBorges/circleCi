<?php


namespace App\Controller;


use App\DTO\Users\CreateUser\CreateUserFromRequestInput;
use App\Entity\User;
use App\Helper\ViolationBuilder;
use App\Repository\ClientRepository;
use App\Responder\JsonResponder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController Extends BaseEntityController
{
    /**
     * @var ClientRepository
     */
    private $clientRepository;

    public function __construct(
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        ClientRepository $clientRepository
    )
    {
        parent::__construct($serializer,$em,$validator);
        $this->clientRepository = $clientRepository;
    }

    /**
     * @Route ("/users",name="create_user", methods={"POST"})
     * @param Request $request
     * @return Response
     * @throws EntityNotFoundException
     */
    public function createUser(Request $request)
    {
        /**
         * @var CreateUserFromRequestInput $userObject
         */
        $userObject=$this->serializer->deserialize(
            $request->getContent(),
            CreateUserFromRequestInput::class,
            'json');

        $errors = $this->validator->validate($userObject);

        if ($errors->count() > 0) {
            $listErrors = ViolationBuilder::build($errors);
            return JsonResponder::responder(json_encode($listErrors),Response::HTTP_BAD_REQUEST);
        }

        $user = User::createUserFromRequest($userObject,$this->clientRepository);
        $this->em->persist($user);
        $this->em->flush();

        return JsonResponder::responder(null, Response::HTTP_CREATED,['Location'=>'api/users/'.$user->getId()]);
    }
}