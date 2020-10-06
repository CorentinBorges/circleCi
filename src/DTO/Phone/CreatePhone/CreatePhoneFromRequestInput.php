<?php


namespace App\DTO\Phone\CreatePhone;


use App\DTO\Phone\PhoneFromRequestInput;
use App\Validator\Constraints as AcmeAssert;
use Symfony\Component\Validator\Constraints as Assert;

class CreatePhoneFromRequestInput extends PhoneFromRequestInput
{
    /**
     * @var string
     * @AcmeAssert\Phones\isUniquePhoneProperty()
     * @Assert\Length(max="60", maxMessage="The model can't exceed 60 characters")
     * @Assert\Type(type="string", message="Model has to be string type")
     * @Assert\NotBlank (message="You have to enter a model")
     */
    public $model;
}