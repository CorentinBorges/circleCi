<?php


namespace App\Validator\Constraints\Client;
//todo: delete
use Symfony\Component\Validator\Constraint;

/**
 * Class isUniqueClient
 * @package App\Validator\Constraints\Client\Password
 * @Annotation
 */
class isUniqueClient extends Constraint
{
    public $message='Client with the name "{{ value }}", already exist ';

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }
}

