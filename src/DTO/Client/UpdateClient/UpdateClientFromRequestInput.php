<?php


namespace App\DTO\Client\UpdateClient;


use App\DTO\Client\ClientFromRequestInput;

class UpdateClientFromRequestInput extends ClientFromRequestInput
{
    public $id;


    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}