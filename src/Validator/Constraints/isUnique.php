<?php


namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class isUnique
 * @package App\Validator\Constraints
 * @Annotation
 */
class isUnique extends Constraint
{
    public $message = 'The {{ property }} "{{ value }}" already exist';

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }

}