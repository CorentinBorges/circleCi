<?php


namespace App\Controller;


use App\DTO\Users\CreateUser\CreateUserFromRequestInput;
use App\Entity\User;
use App\Helper\ViolationBuilder;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use App\Responder\JsonResponder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController Extends BaseEntityController
{
    /**
     * @var ClientRepository
     */
    private $clientRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        ClientRepository $clientRepository,
        UserRepository $userRepository
    )
    {
        parent::__construct($serializer,$em,$validator);
        $this->clientRepository = $clientRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/users",name="show_users",methods={"GET"})
     */
    public function usersList()
    {
        $usersList = $this->userRepository->findAll();
        $listJson = $this->serializer->serialize($usersList, 'json',['groups'=>'list_users']);
        return JsonResponder::responder($listJson);
    }

    /**
     * @Route("/users/{id}",name="show_user_details",methods={"GET"})
     * @param User $user
     * @return Response
     */
    public function userDetails(User $user)
    {
        /*$defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getName();
            },
        ];
        $normalizer = new ObjectNormalizer(
            null,
            null,
            null,
            null,
            null,
            null,
            $defaultContext);
        $serializer = new Serializer([$normalizer], [new JsonEncoder()]);*/
        $userJson = $this->serializer->serialize($user, 'json',[AbstractNormalizer::IGNORED_ATTRIBUTES=>['client']]);
        return JsonResponder::responder($userJson);
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