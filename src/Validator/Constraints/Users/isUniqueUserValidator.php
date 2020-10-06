<?php


namespace App\Validator\Constraints\Users;


use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class isUniqueUserValidator extends ConstraintValidator
{
    /**
     * @var UserRepository
     */
    private $UserRepository;

    private $testRepo;

    public function __construct(UserRepository $UserRepository)
    {
        $this->UserRepository = $UserRepository;
    }
    public function validate($value, Constraint $constraint)
    {
        $property=$this->context->getPropertyName();
        $object = $this->context->getObject();
        if ($userInDb=$this->UserRepository->findOneBy([$property=>$value])) {
            if ((method_exists($object,'getId') && $object->getId()!==$userInDb->getId()) ||
                !method_exists($object,'getId')) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ property }}', $property )
                    ->setParameter('{{ value }}', $value)
                    ->addViolation();
            }
        }
    }

}