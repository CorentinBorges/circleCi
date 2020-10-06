<?php


namespace App\Validator\Constraints\Phones;


use App\DTO\Phone\UpdatePhone\UpdatePhoneFromRequestInput;
use App\Repository\PhoneRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class isUniqueUpdatePhoneValidator extends ConstraintValidator
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
        /**
         * @var UpdatePhoneFromRequestInput $object
         */
        $object = $this->context->getObject();
        if ($phoneInDb=$this->phoneRepository->findOneBy([$property=>$value])) {
            if ($phoneInDb->getId()!==$object->getId()) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ property }}', $property )
                    ->setParameter('{{ value }}', $value)
                    ->addViolation();
            }

        }

    }

}