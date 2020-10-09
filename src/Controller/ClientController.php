<?php


namespace App\Controller;

use App\DTO\Client\CreateClient\CreateClientFromRequestInput;
use App\DTO\Client\UpdateClient\UpdateClientFromRequestInput;
use App\Entity\Client;
use App\Helper\ViolationBuilder;
use App\Repository\ClientRepository;
use App\Responder\JsonResponder;
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
     * @Route("/clients",name="create_client",methods={"POST"})
     * @param Request $request
     * @param EncoderFactoryInterface $encoderFactory
     * @return Response
     * @IsGranted("ROLE_ADMIN")
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
     * @Route ("/clients/{id}",name="update_client",methods={"PUT"})
     * @param Client $client
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_ADMIN")
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
     * @Route("/clients",name="client_list",methods={"GET"})
     * @return Response
     * @IsGranted("ROLE_CLIENT")
     */
    public function clientList()
    {
        /**
         * @var Client $client
         */
        $client = $this->security->getUser();
        if ($client->isAdmin()) {
            $all = $this->clientRepository->findAll();
            $data = $this->serializer->serialize($all, 'json',['groups'=>'list_all']);
        }
        else{
            $clientDatas = $this->clientRepository->findOneBy(['id' => $client->getId()]);
            $data = $this->serializer->serialize($clientDatas, 'json', ['groups' => 'list_all']);
        }

        return JsonResponder::responder($data);
    }

    /**
     * @Route("/clients/{id}",name="client_details", methods={"GET"})
     * @param Client $client
     * @return Response
     * @IsGranted("ROLE_CLIENT")
     */
    public function clientDetails(Client $client)
    {
        $this->security->isGranted('showClientDetail',$client);
        $clientDetails = $this->serializer->serialize($client, 'json',['groups'=>'details']);
        return JsonResponder::responder($clientDetails);
    }

    /**
     * @Route("/clients/{id}",name="delete_client",methods={"DELETE"})
     * @param Client $client
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function clientDelete(Client $client)
    {
        $this->em->remove($client);
        $this->em->flush();
        return JsonResponder::responder(null,Response::HTTP_NO_CONTENT);
    }
}