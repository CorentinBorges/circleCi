<?php


namespace App\DTO\Client\CreateClient;


use Symfony\Component\Validator\Constraints as Assert;

class CreateClientFromRequestInput
{
    /**
     * @var string
     * @Assert\NotBlank(message="Client must have a name")
     * @Assert\Type(type="string")
     * @Assert\Length(max="64",maxMessage="Name can't exceed 64 characters")
     */
    public $name;

    public $roles;

    /**
     * @var string
     * @Assert\NotBlank(message="Client must have a password")
     * @Assert\Type(type="string")
     * @Assert\Length (
     *     max="50",
     *     maxMessage="password can't exceed 50 words",
     *     min="8",
     *     minMessage="password must contain at least 8 characters")
     *
     * @Assert\Regex(
     *     pattern="#[A-Z]+#",
     *     message="Password must have at least one Uppercase"
     * )
     * @Assert\Regex(
     *     pattern="/\b[0-9]+\b/",
     *     message="Password must have at least one number"
     * )
     *
     * @Assert\Regex(
     *     pattern="/\b[a-z]+\b/",
     *     message="Password must have at least one lowercase"
     * )
     */
    public $password;

    public function __construct()
    {
        $this->roles = ['ROLE_CLIENT'];
    }

}