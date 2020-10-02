<?php


namespace App\DTO\Users\CreateUser;


use App\Entity\Client;
use App\Repository\ClientRepository;

class CreateUserFromRequestInput
{

    /**
     * @var string
     */
    public $fullName;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $clientName;

}