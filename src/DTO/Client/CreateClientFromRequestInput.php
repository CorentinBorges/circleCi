<?php


namespace App\DTO\Client;


class CreateClientFromRequestInput
{
    /**
     * @var string
     *
     */
    public $name;

    /**
     *
     */
    public $roles;

    /**
     * @var string
     *
     */
    public $password;

    public function __construct()
    {
        $this->roles = ['ROLE_CLIENT'];
    }


}