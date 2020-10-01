<?php


namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
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
     * @ORM\Column(type="string")
     */
    private $brand;

    /**
     * @var integer
     *
     * @ORM\Column(type="decimal",precision= 2, scale = 4)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column (type="string")
     */
    private $system;

    private $screenSize;

    private $storage;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
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
    private $releaseDate;

    public function __construct(
        string $brand,
        int $price,
        string $system,
        int $screenSize,
        int $storage,
        string $color,
        string $description,
        int $releaseDate
    )
    {
        $this->id= Uuid::v4()->__toString();
        $this->brand = $brand;
        $this->price = $price;
        $this->system= $system;
        $this->screenSize = $screenSize;
        $this->storage = $storage;
        $this->color = $color;
        $this->description = $description;
        $this->releaseDate = $releaseDate;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBrand(): string
    {
        return $this->brand;
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

    public function getReleaseDate(): int
    {
        return $this->releaseDate;
    }

}