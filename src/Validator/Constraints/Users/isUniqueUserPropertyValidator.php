<?php


namespace App\Validator\Constraints\Users;


use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class isUniqueUserPropertyValidator extends ConstraintValidator
{
    /**
     * @var UserRepository
     */
    private $UserRepository;

    public function __construct(UserRepository $UserRepository)
    {
        $this->UserRepository = $UserRepository;
    }
    public function validate($value, Constraint $constraint)
    {
        $property=$this->context->getPropertyName();
        if ($this->UserRepository->findOneBy([$property=>$value])) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ property }}', $property )
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }

    }

}