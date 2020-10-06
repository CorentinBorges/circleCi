<?php


namespace App\Validator\Constraints\Client;

use App\Repository\ClientRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class isUniqueClientValidator extends ConstraintValidator
{
    /**
     * @var ClientRepository
     */
    private $ClientRepository;

    public function __construct(ClientRepository $ClientRepository)
    {
        $this->ClientRepository = $ClientRepository;
    }
    public function validate($value, Constraint $constraint)
    {
        $property=$this->context->getPropertyName();
        $object = $this->context->getObject();
        if ($userInDb=$this->ClientRepository->findOneBy([$property=>$value])) {
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