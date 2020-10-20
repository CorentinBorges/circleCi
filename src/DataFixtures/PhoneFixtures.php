<?php


namespace App\DataFixtures;


use App\DTO\Phone\CreatePhone\CreatePhoneFromRequestInput;
use App\Entity\Phone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class PhoneFixtures extends Fixture
{

    /**
     * @var Generator $faker
     */
    protected $faker;

    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create();
        for ($i=0; $i < 10; $i++) {
            $phoneDTO = new CreatePhoneFromRequestInput();
            $phoneDTO->description = $this->faker->text(500);
            $phoneDTO->screenSize = $this->faker->randomFloat(2, 1.00, 7.00);
            if ($i%2 == 1) {
                $phoneDTO->system = "Android";
                $phoneDTO->model = "Galaxy S" . ($i + 1);
                $phoneDTO->brand = "Samsung";
            }
            else{
                $phoneDTO->system = "iOS";
                $phoneDTO->model = "Iphone" . ($i + 1);
                $phoneDTO->brand = "Apple";
            }

            $phoneDTO->price = $this->faker->randomFloat(2, 10.00, 500.00);
            $phoneDTO->color = $this->faker->colorName;
            $phoneDTO->storage = 8 * $this->faker->numberBetween(1, 16);
            $phone = Phone::createFromRequest($phoneDTO);

            $manager->persist($phone);
        }
        $manager->flush();
    }
}