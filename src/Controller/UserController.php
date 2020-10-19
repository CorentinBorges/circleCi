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
use App\Validator\ExceptionHandler\AccessDeniedHandler;
use App\Validator\ExceptionHandler\EntityNotFoundHandler;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security as SecureSwag;
use OpenApi\Annotations as OA;

/**
 * Class UserController
 * @package App\Controller
 * @IsGranted("ROLE_CLIENT")
 */
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



    /**
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     * @param ClientRepository $clientRepository
     * @param UserRepository $userRepository
     * @param Security $security
     */
    public function __construct(
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        ClientRepository $clientRepository,
        UserRepository $userRepository,
        Security $security
    )
    {
        parent::__construct($serializer, $em, $validator,$security);
        $this->clientRepository = $clientRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * User's list for one client
     *
     * <h1>Access for user's owner only</h1>
     * @Route("/clients/{id}/users",name="show_users",methods={"GET"})
     * @param Client $client
     * @return Response
     *
     * @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *          type="object",
     *          @OA\Property (property="Users",ref=@Model(type=User::class,groups={"list_users"})),
     *          @OA\Property (
     *              property="_links",
     *              type="array",
     *              items=@OA\Items (type="string"),
     *              example={"self": "string"})
     *          )
     *     )
     *  )
     * @OA\Response(
     *     response=401,
     *     description="UNAUTHORIZED - JWT token not found || JWT token expired || Invalid JWT token",
     *
     *  )
     *
     * @OA\Parameter(
     *     name="HTTP_Authorization",
     *     in="header",
     *     description="Bearer {Token}",
     *     required= true
     * )
     *
     * @OA\Parameter (
     *     name="id",
     *     in="path",
     *     description="Client's id",
     *     required=true
     * )
     *
     * @OA\Tag(name="User")
     * @SecureSwag(name="Bearer")
     */
    public function usersListForOneClient(Client $client)
    {
        AccessDeniedHandler::build(
            $this->security,
            'showUsersList',
            $client,
            "Those users are not yours, you can not access to them"
        );

        $usersList = $this->userRepository->findBy(['client' => $client]);
        $listJson = $this->serializer->serialize($usersList, 'json',['groups'=>'list_users']);
        return JsonResponder::responder($listJson);
    }

    /**
     * Detail one User
     *
     * <h1>Access for user's owner only</h1>
     * @Route("/clients/{id}/users/{userId}",name="show_user_details",methods={"GET"})
     * @param Client $client
     * @param string $userId
     * @return Response
     *
     * @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *         @OA\Property ( property="User detail",ref=@Model(type=User::class, groups={"user_details"})),
     *         @OA\Property (
     *              property="_links",
     *              type="array",
     *              items=@OA\Items (type="string"),
     *              example={"update": "string", "delete": "string"})
     *      )
     *  )
     *
     * @OA\Response(
     *     response=401,
     *     description="UNAUTHORIZED - JWT token not found || JWT token expired || Invalid JWT token",
     *  )
     *
     * @OA\Response(
     *     response=404,
     *     description="NOT FOUND"
     *  )
     *
     * @OA\Parameter(
     *     name="HTTP_Authorization",
     *     in="header",
     *     description="Bearer {Token}",
     *     required= true,
     * )
     *
     * @OA\Parameter (
     *     name="id",
     *     in="path",
     *     description="Client's id",
     *     required=true
     * )
     *
     * @OA\Parameter (
     *     name="userId",
     *     in="path",
     *     description="User's id",
     *     required=true
     * )
     *
     * @OA\Tag(name="User")
     * @SecureSwag(name="Bearer")
     */
    public function userDetails(Client $client, string $userId)
    {
        /**
         * @var User $user
         */
        $user = $this->userRepository->findOneBy(['id' => $userId]);
        EntityNotFoundHandler::build($user,'User not found');
        AccessDeniedHandler::build(
            $this->security,
            'show',
            $user,
            "You can not see this user's details"
        );
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
     * Create a user
     *
     * <h1> Client access only </h1>
     * @Route ("/clients/{id}/users",name="create_user", methods={"POST"})
     * @param Client $client
     * @param Request $request
     * @return Response
     *
    @OA\Response(
     *     response=201,
     *     description="CREATED",
     *     @OA\Header(header="Location", description="Link to new user",
     *          @OA\Schema (
     *              type="string"
     *          )
     *      )
     *  )
     *
     * @OA\Response(
     *     response=400,
     *     description="BAD REQUEST"
     *  )
     *
     * @OA\Response(
     *     response=401,
     *     description="UNAUTHORIZED - JWT token not found || JWT token expired || Invalid JWT token"
     *  )
     *
     * @OA\Parameter(
     *     name="HTTP_Authorization",
     *     in = "header",
     *     description = "Bearer {Token}",
     *     required = true
     * )
     *
     * @OA\Parameter (
     *     name="id",
     *     in="path",
     *     description="Client's id",
     *     required=true
     * )
     *
     * @OA\RequestBody  (
     *     required = true,
     *     description="User object that will be created",
     *     @OA\JsonContent(ref=@Model(type=User::class, groups={"user_details"}))
     * )
     *
     * @OA\Tag(name="User")
     * @SecureSwag(name="Bearer")
     *
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
     * Update a user
     *
     * <h1> User's owner  access only </h1>
     * @Route("/clients/{id}/users/{userId}",name="update_user",methods={"PUT"})
     * @param Client $client
     * @param string $userId
     * @param Request $request
     * @return Response
     *
     * @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\Header(header="Location", description="Link to user",@OA\Schema (type="string"))
     *  )
     *
     * @OA\Response(
     *     response=400,
     *     description="BAD REQUEST"
     *  )
     *
     * @OA\Response(
     *     response=401,
     *     description="UNAUTHORIZED - JWT token not found || JWT token expired || Invalid JWT token"
     *  )
     *
     * @OA\Response(
     *     response=404,
     *     description="NOT FOUND"
     *  )
     *
     * @OA\Parameter(
     *     name="HTTP_Authorization",
     *     in = "header",
     *     description = "Bearer {Token}",
     *     required = true
     * )
     *
     * @OA\Parameter (
     *    name = "id",
     *    in = "path",
     *    required = true,
     *    description="Client's id"
     * )
     *
     * @OA\Parameter (
     *    name = "userId",
     *    in = "path",
     *    required = true,
     *    description="User's id"
     * )
     *
     * @OA\RequestBody  (
     *     required = true,
     *     description="Client object that we will update",
     *     @OA\JsonContent(ref=@Model(type=User::class, groups={"user_details"}))
     * )
     *
     * @OA\Tag(name="User")
     * @SecureSwag(name="Bearer")
     *
     */
    public function updateUser(Client $client, string $userId, Request $request)
    {
        /**
         * @var User $user
         */
        $user = $this->userRepository->findOneBy(['id' => $userId]);
        EntityNotFoundHandler::build($user,'User not found');
        AccessDeniedHandler::build(
            $this->security,
            'edit',
            $user,
            "You can not edit this user"
        );
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
     * Delete user
     *
     * <h1>User's owner only</h1>
     * @Route("/clients/{id}/users/{userId}",name="delete_user",methods={"DELETE"})
     * @param Client $client
     * @param string $userId
     * @return Response
     *
    @OA\Response(
     *     response=204,
     *     description="NO CONTENT",
     *  )
     *
     * @OA\Response(
     *     response=401,
     *     description="UNAUTHORIZED - JWT token not found || JWT token expired || Invalid JWT token"
     *  )
     *
     * @OA\Response(
     *     response=404,
     *     description="NOT FOUND"
     *  )
     *
     * @OA\Parameter(
     *     name="HTTP_Authorization",
     *     in = "header",
     *     description = "Bearer {Token}",
     *     required = true
     * )
     *
     * @OA\Parameter (
     *    name = "id",
     *    in = "path",
     *    required = true,
     *    description="Client's id"
     * )
     *
     * @OA\Parameter (
     *    name = "userId",
     *    in = "path",
     *    required = true,
     *    description="User's id"
     * )
     *
     * @OA\Tag(name="User")
     * @SecureSwag(name="Bearer")
     *
     */
    public function deleteUser(Client $client,string $userId)
    {
        /**
         * @var User $user
         */
        $user = $this->userRepository->findOneBy(['id' => $userId]);
        EntityNotFoundHandler::build($user,'User not found');
        AccessDeniedHandler::build(
            $this->security,
            'delete',
            $user,
            "You can not delete this user"
        );
        $this->em->remove($user);
        $this->em->flush();
        return JsonResponder::responder(null,Response::HTTP_NO_CONTENT);
    }



}