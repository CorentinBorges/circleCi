<?php


namespace App\Validator\Constraints\Phones;


use App\Repository\PhoneRepository;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class isUniquePhoneValidator extends ConstraintValidator
{
    /**
     * @var PhoneRepository
     */
    private $PhoneRepository;

    public function __construct(PhoneRepository $PhoneRepository)
    {
        $this->PhoneRepository = $PhoneRepository;
    }
    public function validate($value, Constraint $constraint)
    {
        $property=$this->context->getPropertyName();
        $object = $this->context->getObject();
        if ($userInDb=$this->PhoneRepository->findOneBy([$property=>$value])) {
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