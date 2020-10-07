<?php


namespace App\Controller;


use App\DTO\User\CreateUser\CreateUserFromRequestInput;
use App\DTO\User\UpdateUser\UpdateUserFromRequestInput;
use App\Entity\Client;
use App\Entity\User;
use App\Helper\ViolationBuilder;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use App\Responder\JsonResponder;
use App\Validator\Authorizer\ClientAuthorizer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
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
        parent::__construct($serializer, $em, $validator);
        $this->clientRepository = $clientRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/clients/{id}/users",name="show_users",methods={"GET"})
     * @param Client $client
     * @return Response
     */
    public function usersListForOneClient(Client $client)
    {
        $usersList = $this->userRepository->findBy(['client' => $client]);
        $listJson = $this->serializer->serialize($usersList, 'json',['groups'=>'list_users']);
        return JsonResponder::responder($listJson);
    }

    /**
     * @Route("/clients/{id}/users/{userId}",name="show_user_details",methods={"GET"})
     * @param Client $client
     * @param string $userId
     * @param ClientAuthorizer $clientAuthorizer
     * @return Response
     * @throws Exception
     */
    public function userDetails(Client $client, string $userId, ClientAuthorizer $clientAuthorizer)
    {
        /**
         * @var User $user
         */
        $user = $this->userRepository->findOneBy(['id' => $userId]);
        if (!$clientAuthorizer->isUserOfClient($client,$user)) {
            throw new Exception(json_encode("Access denied for those information, this client is not yours"));
        }
        $userJson = $this->serializer->serialize(
            $user,
            'json',
            [
                'groups'=>'user_details',
                AbstractNormalizer::IGNORED_ATTRIBUTES=>['client'],
            ]
        );
        return JsonResponder::responder($userJson);
    }

    /**
     * @Route ("/clients/{id}/users",name="create_user", methods={"POST"})
     * @param Client $client
     * @param Request $request
     * @return Response
     */
    public function createUser(Client $client,Request $request)
    {
        $userDTO = new CreateUserFromRequestInput();
        $userDTO->setClientId($client->getId());
        /**
         * @var CreateUserFromRequestInput $userObject
         */
        $userObject=$this->serializer->deserialize(
            $request->getContent(),
            CreateUserFromRequestInput::class,
            'json',[AbstractNormalizer::OBJECT_TO_POPULATE=>$userDTO]);

        $errors = $this->validator->validate($userDTO);

        if ($errors->count() > 0) {
            $listErrors = ViolationBuilder::build($errors);
            return JsonResponder::responder(json_encode($listErrors),Response::HTTP_BAD_REQUEST);
        }

        $user = User::createUserFromRequest($userObject,$this->clientRepository);
        $this->em->persist($user);
        $this->em->flush();

        return JsonResponder::responder(null, Response::HTTP_CREATED,['Location'=>'/api/users/'.$user->getId()]);
    }

    /**
     * @Route("/users/{id}",name="update_user",methods={"PUT"})
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function updateUser(User $user,Request $request)
    {
        $userDTO = new UpdateUserFromRequestInput();
        $userDTO->setId($user->getId());
        $newUser = $this->serializer->deserialize(
            $request->getContent(),
            UpdateUserFromRequestInput::class,
            'json',[AbstractNormalizer::OBJECT_TO_POPULATE=>$userDTO]
        );

        $errors = $this->validator->validate($newUser);
        if ($errors->count() > 0) {
            $errorList = ViolationBuilder::build($errors);
            return JsonResponder::responder(json_encode($errorList), Response::HTTP_BAD_REQUEST);
        }

        $user->updateUserFromRequest($userDTO);
        $this->em->flush();

        return JsonResponder::responder(null,
            Response::HTTP_NO_CONTENT,
            ['Location'=>'/api/users/'.$user->getId()]
        );
    }

    /**
     * @Route("/users/{id}",name="delete_user",methods={"DELETE"})
     * @param User $user
     * @return Response
     */
    public function deleteUser(User $user)
    {
        $this->em->remove($user);
        $this->em->flush();
        return JsonResponder::responder(null,Response::HTTP_NO_CONTENT);
    }



}