<?php


namespace App\Validator\Constraints\Users;

use Symfony\Component\Validator\Constraint;

/**
 * Class isUniqueUser
 * @package App\Validator\Constraints
 * @Annotation
 */
class isUniqueUser extends Constraint
{
    public $message = 'The {{ property }} "{{ value }}" already exist';

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }
}