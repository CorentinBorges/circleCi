<?php


namespace App\Validator\Constraints\Phones;


use App\Repository\PhoneRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class isUniquePhonePropertyValidator extends ConstraintValidator
{
    /**
     * @var PhoneRepository
     */
    private $phoneRepository;

    public function __construct(PhoneRepository $phoneRepository)
    {
        $this->phoneRepository = $phoneRepository;
    }
    public function validate($value, Constraint $constraint)
    {
        $property=$this->context->getPropertyName();
        if ($this->phoneRepository->findOneBy([$property=>$value])) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ property }}', $property )
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }

    }

}