<?php


namespace App\Validator\Constraints\Phones;

use Symfony\Component\Validator\Constraint;

/**
 * Class isUniqueUpdatePhone
 * @package App\Validator\Constraints\Phones
 * @Annotation
 */
class isUniqueUpdatePhone extends Constraint
{
    public $message = 'The {{ property }} "{{ value }}" already exist';

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }

}