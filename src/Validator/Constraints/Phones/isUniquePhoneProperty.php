<?php


namespace App\Validator\Constraints\Phones;

use Symfony\Component\Validator\Constraint;

/**
 * Class isUniquePhoneProperty
 * @package App\Validator\Constraints
 * @Annotation
 */
class isUniquePhoneProperty extends Constraint
{
    public $message = 'The {{ property }} "{{ value }}" already exist';

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
   }
}