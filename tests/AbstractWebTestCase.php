<?php


namespace App\Tests;


use App\DTO\Phone\CreatePhone\CreatePhoneFromRequestInput;
use App\Entity\Client;
use App\Entity\Phone;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Faker\Factory;
use Faker\Generator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

abstract class AbstractWebTestCase extends WebTestCase
{
    /**
     * @var ContainerInterface
     */
    protected $containerService;

    /**
     * @var KernelBrowser
     */
    protected $apiClient;
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;
    /**
     * @var EncoderFactory
     */
    protected $encoderFactory;
    /**
     * @var JWTManager
     */
    protected $jwtManager;
    /**
     * @var Generator
     */
    protected $faker;
    /**
     * @var Client
     */
    protected $client;
    /**
     * @var Client
     */
    private $adminClient;

    protected function setUp()
    {
        $this->apiClient = static::createClient();

        $this->containerService = self::$container;
        $this->entityManager = $this->containerService->get('doctrine.orm.entity_manager');
        $this->encoderFactory = $this->containerService->get('security.encoder_factory');

        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());

        $this->faker = Factory::create();
        $this->jwtManager = $this->containerService->get('lexik_jwt_authentication.jwt_manager');

        $this->loadClientFixture();
    }

    protected function request(string $methode, string $uri,? Client $client = null, $contentBody = null) :Response
    {
        if (isset($client)) {
            $this->apiClient->setServerParameter('HTTP_Authorization', 'Bearer '.$this->jwtManager->create($client));
        }
        $this->apiClient->request(
            $methode,
            $uri,
            [],
            [],
            [
                'CONTENT-TYPE' => 'application/json'
            ],
            $contentBody
        );
        return $this->apiClient->getResponse();
    }

    protected function loadPhoneFixtures(){

        for ($i=0; $i < 10; $i++) {
            $phoneDTO = new CreatePhoneFromRequestInput();
            $phoneDTO->description = $this->faker->text(500);
            $phoneDTO->screenSize = $this->faker->randomFloat(2, 1.00, 7.00);
            if ($i<=5) {
                $phoneDTO->system = "android";
                $phoneDTO->model = "galaxy S" . ($i + 1);
                $phoneDTO->brand = "samsung";
            }
            elseif( $i<10 && $i>5){
                $phoneDTO->system = "iOS";
                $phoneDTO->model = "iphone" . ($i - 10 + 1);
                $phoneDTO->brand = "apple";
            }
            else{
                $phoneDTO->system = "android";
                $phoneDTO->model = "P" . ($i + 1);
                $phoneDTO->brand = "Huawei";
            }

            $phoneDTO->price = $this->faker->randomFloat(2, 10.00, 500.00);
            $phoneDTO->color = $this->faker->safeColorName;
            $phoneDTO->storage = 8 * $this->faker->numberBetween(1, 16);
            $phone = Phone::createFromRequest($phoneDTO);

            $this->entityManager->persist($phone);
        }
        $this->entityManager->flush();
    }

    protected function loadClientFixture()
    {
        $this->client = new Client(
            'Jean',
            'testUsername95',
            'testmail@gmail.com',
            '0145354686',
            '123456',
            $this->encoderFactory);
        $this->entityManager->persist($this->client);
        $this->entityManager->flush();
    }
}