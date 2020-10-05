<?php


namespace App\Entity;


use App\DTO\Phone\CreatePhone\CreatePhoneFromRequestInput;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

/**
 * Class Phone
 * @package App\Entity
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\Repository\PhoneRepository")
 */
class Phone
{
    /**
     * @var string
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     * @Groups({"list_phone"})
     */
    private $brand;

    /**
     * @var string
     *
     * @ORM\Column (type="string", length=60)
     * @Groups({"list_phone"})
     */
    private $model;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal",precision= 6, scale = 2)
     * @Groups({"list_phone"})
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column (type="string", length=50)
     */
    private $system;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $screenSize;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $storage;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=16)
     *
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column (type="text")
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $createdAt;

    public function __construct(
        string $brand,
        string $model,
        int $price,
        string $system,
        int $screenSize,
        int $storage,
        string $color,
        string $description
    )
    {
        $this->id= Uuid::v4()->__toString();
        $this->brand = $brand;
        $this->model = $model;
        $this->price = $price;
        $this->system= $system;
        $this->screenSize = $screenSize;
        $this->storage = $storage;
        $this->color = $color;
        $this->description = $description;
        $this->createdAt = time();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getSystem(): string
    {
        return $this->system;
    }

    public function getScreenSize(): int
    {
        return $this->screenSize;
    }

    public function getStorage(): int
    {
        return $this->storage;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public static function createFromRequest(CreatePhoneFromRequestInput $object)
    {
        return new self(
            $object->brand,
            $object->model,
            $object->price,
            $object->system,
            $object->screenSize,
            $object->storage,
            $object->color,
            $object->description

        );
    }
}