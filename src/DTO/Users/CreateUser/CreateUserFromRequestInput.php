<?php


namespace App\DTO\Users\CreateUser;




use Symfony\Component\Validator\Constraints as Assert;

class CreateUserFromRequestInput
{

    /**
     * @var string
     *
     * @Assert\Length(max="64", maxMessage="the full name can't exceed 64 characters")
     * @Assert\NotBlank(message="You have to enter youre full name")
     */
    public $fullName;

    /**
     * @var string
     *
     * @Assert\Length (max="50", maxMessage="the full username can't exceed 50 characters")
     * @Assert\NotBlank(message="You have to enter a userame")
     */
    public $username;

    /**
     * @var string
     *
     * @Assert\Email(message="Email not valid")
     * @Assert\NotBlank(message="You have to enter an email")
     */
    public $email;

    /**
     * @var string
     *
     * @Assert\Length (max=64, maxMessage="The client name can't exceed 64 characters")
     * @Assert\NotBlank(message="You have to enter a client Name")
     */
    public $clientName;

}