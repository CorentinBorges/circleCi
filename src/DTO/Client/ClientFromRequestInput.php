<?php


namespace App\DTO\Client;


use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AcmeAssert;


abstract class ClientFromRequestInput
{
    /**
     * @var string
     * @Assert\NotNull()
     * @AcmeAssert\Client\isUniqueClient
     * @Assert\NotBlank(message="Client must have a name")
     * @Assert\Type(type="string", message="Name has to be string type")
     * @Assert\Length(max="64",maxMessage="Name can't exceed 64 characters")
     */
    public $name;

    /**
     * @var string
     * @AcmeAssert\Client\isUniqueClient
     * @Assert\NotNull()
     * @Assert\Email(message="mail not valid")
     * @Assert\NotBlank(message="Client must have a mail")
     */
    public $mail;

    /**
     * @var string
     * @AcmeAssert\Client\isUniqueClient
     * @Assert\NotNull()
     * @Assert\Type(type="string", message="Phone number has to be string type")
     * @Assert\NotBlank(message="Client must have a phoneNumber")
     * @Assert\Regex (pattern="[^0-9]", message="Phone number can just have...numbers!")
     */
    public $phoneNumber;

    public $roles;

    /**
     * @var string
     * @Assert\NotNull()
     * @Assert\NotBlank(message="Client must have a password")
     * @Assert\Type(type="string")
     * @Assert\Length (
     *     max="50",
     *     maxMessage="password can't exceed 50 words",
     *     min="8",
     *     minMessage="password must contain at least 8 characters")
     * @Assert\Regex(
     *     pattern="#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).+#",
     *     message="Password must have at least one Uppercase, one lowercase and one number")
     */
    public $pass;

    public $password;

    public function __construct()
    {
        $this->password = password_hash($this->pass, PASSWORD_BCRYPT, ['cost' => 8]);
        $this->roles = ['ROLE_CLIENT'];
    }
}