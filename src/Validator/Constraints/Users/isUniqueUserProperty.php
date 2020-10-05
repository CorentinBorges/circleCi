<?php


namespace App\Validator\Constraints\Users;

use Symfony\Component\Validator\Constraint;

/**
 * Class isUniqueUserProperty
 * @package App\Validator\Constraints
 * @Annotation
 */
class isUniqueUserProperty extends Constraint
{
    public $message = 'The {{ property }} "{{ value }}" already exist';

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }
}