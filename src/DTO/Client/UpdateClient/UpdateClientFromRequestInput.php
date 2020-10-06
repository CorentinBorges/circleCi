<?php


namespace App\DTO\Client\UpdateClient;


use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AcmeAssert;

class UpdateClientFromRequestInput
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

    public $roles;

    /**
     * @var string
     * @Assert\NotBlank (message="Client must have a password")
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

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}