<?php


namespace App\DTO\Phones\CreatePhones;

use Symfony\Component\Validator\Constraints as Assert;

class CreatePhoneFromRequestInput
{
    /**
     * @var string
     *
     * @Assert\Length(max=50, maxMessage="The brand can't be a brand with more than 50 characters")
     */
    public $brand;

    /**
     * @var string
     *
     * @Assert\Length(max=60, maxMessage="The brand can't be a brand with more than 60 characters")
     */
    public $model;

    /**
     * @var float
     */
    public $price;

    /**
     * @var string
     */
    public $system;

    /**
     * @var int
     */
    public $screenSize;

    /**
     * @var int
     */
    public $storage;

    /**
     * @var string
     *
     */
    public $color;

    /**
     * @var string
     */
    public $description;

}