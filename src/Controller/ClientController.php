<?php


namespace App\Controller;

use App\DTO\Client\CreateClient\CreateClientFromRequestInput;
use App\DTO\Client\UpdateClient\UpdateClientFromRequestInput;
use App\Entity\Client;
use App\Helper\ViolationBuilder;
use App\Repository\ClientRepository;
use App\Responder\JsonResponder;
use App\Validator\ExceptionHandler\AccessDeniedHandler;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security as SecureSwag;
use OpenApi\Annotations as OA;

/**
 * Class ClientController
 * @package App\Controller
 */
class ClientController extends BaseEntityController
{

    /**
     * @var ClientRepository
     */
    private $clientRepository;


    /**
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     * @param ClientRepository $clientRepository
     * @param Security $security
     */
    public function __construct(
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        ClientRepository $clientRepository,
        Security $security
    )
    {
        parent::__construct($serializer,$em,$validator,$security);
        $this->clientRepository = $clientRepository;
    }

    /**
     * Create a client
     *
     * <h1>Admin access only</h1>
     * @Route("/clients",name="create_client",methods={"POST"})
     * @param Request $request
     * @param EncoderFactoryInterface $encoderFactory
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     *
     * @OA\Response(
     *     response=201,
     *     description="CREATED",
     *     @OA\Header(header="Location", description="Link to client",
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
     *     name = "JSON",
     *     in = "query",
     *     required = true,
     *     description="Client object that will be created",
     *     @OA\JsonContent(ref=@Model(type=Client::class, groups={"create_client"}))
     * )
     *
     * @OA\Tag(name="Client")
     * @SecureSwag(name="Bearer")
     */
    public function createClient(Request $request, EncoderFactoryInterface $encoderFactory)
    {
        /**
         * @var CreateClientFromRequestInput $clientDTO
         */
        $clientDTO = $this->serializer->deserialize(
            $request->getContent(),
            CreateClientFromRequestInput::class,
            'json'
        );

        $errors = $this->validator->validate($clientDTO);
        if ($errors->count() > 0) {
            $errorList = ViolationBuilder::build($errors);
            return JsonResponder::responder(json_encode($errorList),Response::HTTP_BAD_REQUEST);
        }

        $client = Client::createClientFromRequest($clientDTO,$encoderFactory);

        $this->em->persist($client);
        $this->em->flush();

        return JsonResponder::responder(
            null,
            Response::HTTP_CREATED,
            ['Location'=>'/api/clients/'.$client->getId()]
        );

    }

    /**
     * Update a client
     *
     * <h1>Admin access only</h1>
     *
     * @Route ("/clients/{id}",name="update_client",methods={"PUT"})
     * @param Client $client
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     *
     * @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\Header(header="Location", description="Link to this client",@OA\Schema (type="string"))
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
     *     name = "JSON",
     *     in = "query",
     *     required = true,
     *     description="Client object that we will update",
     *     @OA\JsonContent(ref=@Model(type=Client::class, groups={"create_client"}))
     * )
     *
     * @OA\Tag(name="Client")
     * @SecureSwag(name="Bearer")
     */
    public function updateClient(Client $client, Request $request)
    {
        $clientDTO = new UpdateClientFromRequestInput();
        $clientDTO->setId($client->getId());
        $newClient = $this->serializer->deserialize(
            $request->getContent(),
            UpdateClientFromRequestInput::class,
            'json',
            [AbstractNormalizer::IGNORED_ATTRIBUTES=>['roles','id','password'],
            AbstractNormalizer::OBJECT_TO_POPULATE=>$clientDTO]);

        $errors = $this->validator->validate($newClient);
        if ($errors->count() > 0) {
            $errorsList=ViolationBuilder::build($errors);
            return JsonResponder::responder(json_encode($errorsList), Response::HTTP_BAD_REQUEST);
        }

        $client->updateClientFromRequest($clientDTO);
        $this->em->flush();

        return JsonResponder::responder(null,Response::HTTP_NO_CONTENT);
    }

    /**
     * Get client list
     *
     * <h1> Admin access only </h1>
     * @Route("/clients",name="client_list",methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     *
     * @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *          type="object",
     *          @OA\Property (property="Client",ref=@Model(type=Client::class,groups={"list_client"})),
     *          @OA\Property (property="links",type="string")
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
     * @OA\Tag(name="Client")
     * @SecureSwag(name="Bearer")
     */
    public function clientList()
    {
        $all = $this->clientRepository->findAll();
        $data = $this->serializer->serialize($all, 'json',['groups'=>'list_client']);
        return JsonResponder::responder($data);
    }

    /**
     * Details about one client
     *
     * <h1> Access for admin and client's concerned</h1>
     * @Route("/clients/{id}",name="client_details", methods={"GET"})
     * @param Client $client
     * @return Response
     * @IsGranted("ROLE_CLIENT")
     *
     * @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *         @OA\Property (ref=@Model(type=Client::class, groups={"client_details"})),
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
     * @OA\Tag(name="Client")
     * @SecureSwag(name="Bearer")
     */
    public function clientDetails(Client $client)
    {
        AccessDeniedHandler::build(
            $this->security,
            'showClientDetail',
            $client,
            "You can not see this client's details"
        );
        $clientDetails = $this->serializer->serialize($client, 'json',['groups'=>'client_details']);
        return JsonResponder::responder($clientDetails);
    }

    /**
     * Delete a client
     *
     * <h1> Admin access only </h1>
     * @Route("/clients/{id}",name="delete_client",methods={"DELETE"})
     * @param Client $client
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     *
     * @OA\Response(
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
     * @OA\Tag(name="Client")
     * @SecureSwag(name="Bearer")
     */
    public function clientDelete(Client $client)
    {
        $this->em->remove($client);
        $this->em->flush();
        return JsonResponder::responder(null,Response::HTTP_NO_CONTENT);
    }
}