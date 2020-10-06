<?php


namespace App\DTO\Users\UpdateUser;


use App\DTO\Users\UserFromRequestInput;


class UpdateUserFromRequestInput extends UserFromRequestInput
{
    public $id;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }



}