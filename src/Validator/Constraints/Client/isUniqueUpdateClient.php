<?php


namespace App\Validator\Constraints\Client;

use Symfony\Component\Validator\Constraint;

/**
 * Class isUniqueUpdateClient
 * @package App\Validator\Constraints\Client
 * @Annotation
 */
class isUniqueUpdateClient extends Constraint
{
    public $message='Client with the name "{{ value }}", already exist ';

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }
}