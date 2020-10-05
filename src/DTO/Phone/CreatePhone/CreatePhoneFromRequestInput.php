<?php


namespace App\DTO\Phone\CreatePhone;


class CreatePhoneFromRequestInput
{
    /**
     * @var string
     */
    public $brand;

    /**
     * @var string
     *
     */
    public $model;

    /**
     * @var float
     */
    public $price;

    /**
     * @var string
     *
     */
    public $system;

    /**
     * @var int
     *
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