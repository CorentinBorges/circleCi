<?php


namespace App\DTO\Phone\CreatePhone;


use Symfony\Component\Validator\Constraints as Assert;

class CreatePhoneFromRequestInput
{
    /**
     * @var string
     *
     * @Assert\Length(max="50", maxMessage="The brand can't exceed 50 characters")
     * @Assert\Type(type="string", message="Brand has to be string type")
     * @Assert\NotBlank(message="You have to enter a brand")
     */
    public $brand;

    /**
     * @var string
     *
     * @Assert\Length(max="60", maxMessage="The model can't exceed 60 characters")
     * @Assert\Type(type="string", message="Model has to be string type")
     * @Assert\NotBlank (message="You have to enter a model")
     */
    public $model;

    /**
     * @var float
     *@Assert\Range(
     *     min="10.00",
     *     minMessage="The price can't be under 10",
     *     max="5000",
     *     maxMessage="The price can't exceed 5000"))
     * @Assert\Type (type="float", message="Price has to be float type")
     * @Assert\NotBlank (message="You have to enter a price")
     */
    public $price;

    /**
     * @var string
     * @Assert\Type(type="string", message="System has to be string type")
     * @Assert\NotBlank (message="You have to enter a system name")
     */
    public $system;

    /**
     * @var int
     * @Assert\NotBlank (message="You have to enter a screenSize")
     */
    public $screenSize;

    /**
     * @var int
     *
     * @Assert\NotBlank (message="You have to enter a storage value")
     */
    public $storage;

    /**
     * @var string
     *
     * @Assert\NotBlank (message="You have to enter a color")
     */
    public $color;

    /**
     * @var string
     *
     * @Assert\NotBlank (message="You have to enter a description")
     */
    public $description;

    public function __construct()
    {
        $this->price = round($this->price, 2);
    }
}