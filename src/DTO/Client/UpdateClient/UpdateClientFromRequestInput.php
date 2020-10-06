<?php


namespace App\DTO\Client\UpdateClient;


use App\DTO\Client\ClientFromRequestInput;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AcmeAssert;

class UpdateClientFromRequestInput extends ClientFromRequestInput
{
    public $id;


    /**
     * @var string
     * @Assert\NotBlank(message="Client must have a name")
     * @Assert\Type(type="string")
     * @AcmeAssert\Client\isUniqueUpdateClient
     * @Assert\Length(max="64",maxMessage="Name can't exceed 64 characters")
     */
    public $name;

    /**
     * @var string
     * @AcmeAssert\Client\isUniqueUpdateClient
     * @Assert\NotBlank(message="Client must have a mail")
     * @Assert\Email(message="mail not valid")
     */
    public $mail;

    /**
     * @var string
     * @AcmeAssert\Client\isUniqueUpdateClient
     * @Assert\NotBlank(message="Client must have a phoneNumber")
     * @Assert\Type(type="string", message="Phone number has to be string type")
     * @Assert\Regex (pattern="#[0-9]+#")
     */
    public $phoneNumber;


    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}