<?php


namespace App\DTO\Client\CreateClient;


use App\DTO\Client\ClientFromRequestInput;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AcmeAssert;

class CreateClientFromRequestInput extends ClientFromRequestInput
{
    /**
     * @var string
     * @Assert\NotBlank(message="Client must have a name")
     * @Assert\Type(type="string")
     * @AcmeAssert\Client\isUniqueClient
     * @Assert\Length(max="64",maxMessage="Name can't exceed 64 characters")
     */
    public $name;

    /**
     * @var string
     * @AcmeAssert\Client\isUniqueClient
     * @Assert\NotBlank(message="Client must have a mail")
     * @Assert\Email(message="mail not valid")
     */
    public $mail;

    /**
     * @var string
     * @AcmeAssert\Client\isUniqueClient
     * @Assert\NotBlank(message="Client must have a phoneNumber")
     * @Assert\Type(type="string", message="Phone number has to be string type")
     * @Assert\Regex (pattern="#[0-9]+#")
     */
    public $phoneNumber;

}