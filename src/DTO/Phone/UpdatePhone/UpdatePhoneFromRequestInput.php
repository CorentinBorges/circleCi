<?php


namespace App\DTO\Phone\UpdatePhone;


use App\DTO\Phone\PhoneFromRequestInput;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AcmeAssert;


class UpdatePhoneFromRequestInput extends PhoneFromRequestInput
{

    public $id;

    /**
     * @var string
     * @AcmeAssert\Phones\isUniqueUpdatePhone
     * @Assert\Length(max="60", maxMessage="The model can't exceed 60 characters")
     * @Assert\Type(type="string", message="Model has to be string type")
     * @Assert\NotBlank (message="You have to enter a model")
     */
    public $model;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }
}

